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
            'password'             => 'required|alpha_num|between:4,12|confirmed',
            'password_confirmation'=> 'required|alpha_num|between:4,12',
        ];
        
         public static $rules_step2 = [
            'os_v_vallalkozas'    => 'required|integer',
            'vallalkozas_nev'     => 'alpha|min:2'
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
        
        public static function getUserList($datas)
        {
            $query = self::join('user_category','user_category.user_id', '=', 'users.id')
                    ->join('categories','user_category.category_id', '=', 'categories.id');
            
            unset($datas['_token']); 
            $datas = array_values($datas); // 'reindex' array
            
            if(!empty($datas)){
                foreach($datas as $data){

                    if(is_array($data)){        //checkbox
                        foreach($data as $id){
                          $query->where('categories.id', '=', $id);  
                        }
                    }else{                      //radio
                        $query->where('categories.id', '=', $data);
                    }
                }     
            }
          
            $ret = $query->groupBy('users.id')->get(array(
                        'users.id as userId',
                        'users.username as userName',
                        'users.img_path as image',
                        'categories.title as categoryName'
                        ))->toArray();
            return $ret;
            
        }
}
