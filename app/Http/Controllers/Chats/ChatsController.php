<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Models\Rooms;
use App\Models\UserInRooms;
use App\User;
use Auth;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Log;

class ChatsController extends Controller
{
    public function showChatsEnv()
    {	
    	$onlUsersInRoomsColl = UserInRooms::onlRoomsCount([
    		'userID' => Auth::id(),
    	])->get();

    	foreach ($onlUsersInRoomsColl as $key => $user) {
    		$onlUsersInRooms[$key] = $user->toArray();
    		$onlUsersInRooms[$key]['chatID'] = $user->chatID;

    		unset(
    			$onlUsersInRooms[$key]['id'], 
    			$onlUsersInRooms[$key]['user_id']
    		);
    	}

        $emptyRooms = $this->getEmptyRooms();

        $onlRoomsCount = array_column($onlUsersInRooms, 'total', 'room_name');
        $onlRoomsCount = array_merge($emptyRooms, $onlRoomsCount);
        $onlRoomsCount = $this->fistChrConvertToUtf8AndSort($onlRoomsCount);

        $authUser = array_shift($onlUsersInRooms);
        $onlRoomsCount[$authUser['room_name']]--;

        Redis::hset(
        	'chat_unique_id:'.$authUser['chatID'], 
        	'rooms', 
        	implode(',', [$authUser['room_name']])
        );
        
    	return view('chats/index', [
    		'authUser' => $authUser,
    		'onlRoomsCount' => $onlRoomsCount,
    		'onlUsersInRooms' => $onlUsersInRooms,
    	]);
    }

    private function getEmptyRooms()
    {
    	$emptyRooms = Cache::store('redis')->remember('empty_rooms', 1440, function () {
			return Rooms::emptyRooms()->get()->toArray();
		});

        $additionalRooms = [];
        foreach ($emptyRooms as $roomName) {
        	$additionalRooms[$roomName['roomName']] = 0;
        }

        return $additionalRooms;
    }

    protected function fistChrConvertToUtf8AndSort(Array $arr, $convertType = 'lower')
    {
		$keys = $this->fisrtLetterConvert($arr, $convertType);
		$usersInRm = array_combine($keys, $arr);
		ksort($usersInRm);
		
		return $usersInRm;
    }

    private function fisrtLetterConvert($arr, $convertType)
    {
    	array_walk($arr, function(&$item, $key) use ($convertType){
			$firstLetter = mb_substr($key, 0, 1);
			$otherLetter = mb_substr($key, 1);

			switch ($convertType) {
				case 'upper':
					$firstLetter = mb_strtoupper($firstLetter);
					break;
				
				case 'lower':
					$firstLetter = mb_strtolower($firstLetter);
					break;
			}

			$item = $firstLetter.$otherLetter;
    	});

    	return array_values($arr);
    }
}