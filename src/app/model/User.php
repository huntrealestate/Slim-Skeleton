<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * An alias of 'whereToken'
     * @see whereToken
     */
    public function byToken($token, $type = 'google')
    {
        return $this->whereToken($token, $type);
    }

    /**
     * Get the user by their assosiated oauth token.
     */
    public function whereToken($token, $type = 'google')
    {
        switch($type){
            case'google': {
                return $this->google_token()->where('access_token', '=', $token)->user();
            }
            default:{
                return $this->where('1', '=', '0');
            }
        }
        return $this->hasOne('App\Model\GoogleToken');
    }

    /**
     * Get the google token associated with the user.
     */
    public function google_token()
    {
        return $this->hasOne('App\Model\GoogleToken');
    }

    public static function getCurrentSessionUser()
    {
        $identifier = \Hybrid_Auth::storage()->get('user');
        return User::where('identifier','=', $identifier)->first();
    }
}
