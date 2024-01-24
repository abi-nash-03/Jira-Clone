<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //relating User to Task
    public function tasks(){
        return $this->belongsToMany(Task::class,'task_user','user_id','task_id')
        ->withTimestamps()
        ->wherePivot('deleted_at', null);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }

    public static function getUserByEmail($email){
        // $user = User::where('email','=', $email)->first();
        return self::where('email', $email)->first();
    }

    public static function getUserEmailById($id){
        return self::where('id', $id)->first();
    }

    public static function setAccessToken($token, $id){
        $user = User::find($id);
        $user->api_token = $token;
        $user->save();
    }
    
    public static function getAccessToken($id){
        // dd();
        $user = User::find($id);
        return $user->api_token;
    }

}
