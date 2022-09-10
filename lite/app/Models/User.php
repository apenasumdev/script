<?php

namespace App\Models;

use Lite\Database\Eloquent;


/**
 * Class User
 * @package App\Models
 * @property string $email
 * @property string password
 * @property string role
 * @property string admin
 * @property string token
 */
class User extends Eloquent {

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = ['password'];

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = ['admin'];

	/**
	 * Get the administrator flag for the user.
	 *
	 * @return bool
	 */
	public function getAdminAttribute()
	{
		if(!isset($this->attributes['role'])) return false;
		return $this->attributes['role'] === 'admin';
	}

	public static function username(){
		return 'email';
	}

	public function isAdmin(){
	    return $this->role === 'admin';
    }
}