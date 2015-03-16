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
	protected $table      = 'users';
        protected $fillable   = ['first_name','password','last_name', 'username', 'img_path', 'admin'];
        public    $timestamps = false;
        
        public static $rules_step1 = [
            'first_name'           => 'required|min:2|alpha',
            'last_name'            => 'required|min:2|alpha',
            'username'             =>  array('required','min:6','unique'=>'unique:users,username'),
        ];
        
         public static $rules_step2 = [
            'password'             => 'required|alpha_num|between:4,12|confirmed',
            'password_confirmation'=> 'required|alpha_num|between:4,12',
            'admin'                => 'integer'
        ];
        
         public static $admin_rules = [
            'first_name'           => 'required|min:2|alpha',
            'last_name'            => 'required|min:2|alpha',
            'username'             =>  array('required','min:6','unique'=>'unique:users,username'),
            'admin'                => 'integer'
        ];
        
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
        
        public function getFullName()
        {
            return $this->first_name.' '.$this->last_name;
        }
        
        public static function getResult($admin)
        {
            if($admin != 2){
              $query = self::where('admin', '=', $admin)->paginate(5);  
            }else{
              $query = self::paginate(5);  
            }
            
            return $query;
        }
}
