<?php

namespace App\Listeners\Chats;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

use Session;

class RedisRegisteredUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $chatID = $event->user->chatID;
        $user = $event->user->toArray();
        $user['chatID'] = $chatID;
        $user['path'] = $event->user->images[0]->path;

        $time = strtotime('now') * 1000;
        Redis::hmset('chat_unique_id:'.$chatID, [
            'userID' => $event->user->id,
            'user' => json_encode($user),
            'sessionID' => Session::getId(),
            'loginTime' => $time,
        ]);

        Redis::hset('chat:online', $chatID, $time);
    }
}
