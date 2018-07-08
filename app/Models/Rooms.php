<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_in_rooms');
    }

    public function scopeEmptyRooms($query){
		
		$query->from('rooms as rm')
		->selectRaw(
			'LCASE(rm.room_name) roomName'
		)
		->whereRaw('
			rm.id NOT IN (
				SELECT uir.rooms_id 
				FROM user_in_rooms uir
			)
		');

		return $query;
	}
}