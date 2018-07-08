<?php

namespace App\Models;

use App\ModelTraits\ModelSnippets;
use Illuminate\Database\Eloquent\Model;

class UserInRooms extends Model
{
    use ModelSnippets;

    protected $fillable = [
        'user_id', 'rooms_id'
    ];

    public function getChatIDAttribute(){
        return $this->convertCryptID($this->user_id);
    }

    public function scopeOnlRoomsCount($query, $params){
 
        $query->from('user_in_rooms as uir')
        ->selectRaw('
        	us.*, imgs.path, 
            uir.user_id user_id, 
            LCASE(rm.room_name) room_name, 
            (
                SELECT rm.room_name
                FROM rooms rm
                WHERE rm.active = 1
            ) as activeRoom,
            (
                SELECT COUNT(uir2.id)
                FROM user_in_rooms as uir2 
                INNER JOIN users as us2 ON us2.id = uir2.user_id
                WHERE uir2.rooms_id = rm.id 
                AND us2.online > 0
            ) as total
		')
        ->join('rooms as rm', 'uir.rooms_id', '=', 'rm.id')
        ->join('users as us', 'uir.user_id', '=', 'us.id')
        ->join('images as imgs', 'us.id', '=', 'imgs.owner_id')
        ->whereRaw('imgs.owner_type = "App\\\User"')
        ->whereRaw('us.online > 0');
        
        $userID = $params['userID'] ?? null;
        
        if (isset($userID)){
            $query->orderByRaw(
                'us.id = :userID DESC', 
                ['userID' => $userID]
            );
        }
        
        $query->orderByRaw('rm.room_name, us.name ASC');

        if(!empty($params['whereArr'])){

            $delimeter = $params['delimeter'] ?? '=';
            $tblAs = [
                'user_in_rooms' => 'uir',
                'users' => 'us',
                'rooms' => 'rm',
                'images' => 'imgs',
            ];

    		$where = '';
        	foreach ($params['whereArr'] as $key => $val) {

                if ($where !== '')
        			$where .= ' AND ';

                $key = explode('.', $key);
                $col = "{$tblAs[$key[0]]}.{$key[1]}";

        		$where .= "{$col} {$delimeter} ?";
        	}	

        	$query->whereRaw($where, array_values($params['whereArr']));
        }
        	
        return $query;
    }
}