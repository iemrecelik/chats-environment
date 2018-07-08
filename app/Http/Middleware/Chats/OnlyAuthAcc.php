<?php

namespace App\Http\Middleware\Chats;

use Closure;
use Illuminate\Support\Facades\Redis;
use Session;

class OnlyAuthAcc
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        $chatID = $user->chatID;
        $ltime = Redis::hget('chat:online', $chatID);

        if(( $ltime && ($ltime != session('loginTime')) ) && !auth()->guest()){
            $request->session()->flush();
            \Auth::logout();
            return redirect('login')->with('existAuth', 'This authentication already exist.');
        }

        $chatID = $user->chatID;
        $userArr = $user->toArray();
        $userArr['chatID'] = $chatID;
        $userArr['path'] = $user->images[0]->path;

        $time = strtotime('now') * 1000;
        session(['loginTime' => $time]);

        Redis::hmset('chat_unique_id:'.$chatID, [
            'userID' => $user->id,
            'user' => json_encode($userArr),
            'sessionID' => Session::getId(),
            'loginTime' => $time,
        ]);

        Redis::hset('chat:online', $chatID, $time);

        return $next($request);
    }
}
