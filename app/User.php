<?php

namespace App;

use App\ModelTraits\ModelSnippets;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Session;


class User extends Authenticatable
{
    use Notifiable;
    use ModelSnippets;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'nickname', 
        'bio', 'email', 'mobile',
        'hide_account', 'password', 'gender',
        'language', 'date_of_birth', 'country',
        'province', 'brief', 'online'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getChatIDAttribute(){
        return $this->convertCryptID($this->id);
    }

    public function images()
    {
        return $this->morphMany('App\Models\Images', 'owner');
    }

    public function rooms()
    {
        return $this->belongsToMany('App\Models\Rooms', 'user_in_rooms');
    }

    public function scopeGetUsersWithImg($query){
        
        return $query->from('users as us')
        ->select('us.*', 'img.*')
        ->leftJoin(
            'images as img', 'us.id', '=', 'img.owner_id'
        )
        ->whereRaw(
            'img.owner_type = :user', 
            ['user' => 'App\User']
        );
    }
}
