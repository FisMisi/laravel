<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
        protected $fillable = ['first_name','password','last_name', 'username'];
        public    $timestamps = false;
        public static $rules = [
            'first_name'           => 'required|min:2|alpha_dash',
            'last_name'            => 'required|min:2|alpha_dash',
            'username'             =>  array('required','min:6','unique'=>'unique:users,username'),
            'password'             => 'required|alpha_num|between:4,12|confirmed',
            'password_confirmation'=> 'required|alpha_num|between:4,12',
            'admin'                => 'integer'
        ];
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

}
