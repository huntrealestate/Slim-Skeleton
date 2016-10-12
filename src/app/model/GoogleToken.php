<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoogleToken extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_tokens';
    
    /**
     * Get the user that owns the google_token.
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}