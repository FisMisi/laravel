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

	protected $primaryKey = 'user_id';
	
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	public static function getUsersToDatas($adminList = null, $active = null, $confirmed = null, $sa = null, $limit = 20, $page = 1) {
		if ($page > 0) {
			$page--;
		}
	
		if (is_null($adminList)) $adminList = 0;
		
		if (is_null($sa) || $adminList == 0) {#ekkor hozza mindent
			$query = self::where('admin', $adminList);
			$query2 = self::where('admin', $adminList);
		} else if ($sa) {#ekkor superadmin
			$query = self::join('users_rights', 'users_rights.user_id', '=', 'users.user_id');
			$query2 = self::join('users_rights', 'users_rights.user_id', '=', 'users.user_id');
			$query->join('rights', 'users_rights.right_id', '=', 'rights.right_id')->where("rights.right_name", "like", "superadmin");
			$query2->join('rights', 'users_rights.right_id', '=', 'rights.right_id')->where("rights.right_name", "like", "superadmin");
		} else {#ekkor csak azt, ha nem super admin
			#$query = self::leftJoin('users_rights', 'users_rights.user_id', '=', 'users.user_id')->where();
			$query = self::leftJoin('users_rights', function($join) {
				$join->on('users_rights.user_id', '=', 'users.user_id')->where('users_rights.right_id', '=', 1);
			})->whereNull('users_rights.right_id');
			$query2 = self::leftJoin('users_rights', function($join) {
				$join->on('users_rights.user_id', '=', 'users.user_id')->where('users_rights.right_id', '=', 1);
			})->whereNull('users_rights.right_id');
		}
		
		if (!is_null($active)){
			$query->where('active', $active);
			$query2->where('active', $active);
		} 
		if (!is_null($confirmed)){
			$query->where('confirmed', $confirmed);
			$query2->where('confirmed', $confirmed);
		} 
		$superAdminUsers = RightUser::getUserIdsToRight('superadmin');
		$query->take($limit);
		if ($page>0) {
			$query->skip($page*$limit);
		}
		$ret = array();
		$ret['users'] = $query->get(array('users.user_id', 'first_name', 'last_name', 'nick', 'email', 'active', 'admin', 'confirmed'))->toArray();
		$ret['count'] = $query2->count();
		#$queries = DB::getQueryLog();
		#$last_query = end($queries);
		return $ret;
	}
	
	public static function is_a_SA($user_id) {
		$right = Right::where('right_name', 'like', 'superadmin')->first();
		$ru = RightUser::where('user_id', $user_id)->where('right_id', $right->right_id)->first();
		if (!is_null($ru) && $ru) return true;
		return false;
	}
	
	public function softDeletes($reason = null, $formUser = false) {
		if (isset($_SESSION['userId'])) {
			$processUser = self::find(  $_SESSION['userId'] );
		}
		
		if (!isset($processUser) || !($processUser->admin || $formUser) || !$processUser->active || $processUser->user_id) {
			return false;
		}
	
		$this->active = 0;
		$this->inactive_time = date('Y-m-d h:i:s');
		$this->inactive_user = $processUser->user_id;
		$this->save();
		return $this->id;
	}
	public function scopeConfirmed($query) {
		return $query->where('confirmed',  1);
	}
	
	public function scopeNotConfirmed($query) {
		return $query->where('confirmed',  0);
	}
	
	public static function confirmAccount($vertifyKey) {
		$user = self::where('confirm_link', $vertifyKey)->first();
		
		if ( !$user || !$user->user_id ) {
			return array('Error' => 'Invalid Link', 'do' => '404');
		}
		
		if ( $user->confirmed ) {
			return array('do' => 'normalLogin', 'Error' => null);
		}
		
		if ( time($user->confirm_valid_time) >= time() ) {
			return array('do' => 'generateNewLink', 'userId' => $user->user_id, 'Error' => 'Link Overtime');
		}
		
		$user->confirmed = 1;
		$user->save();
		return array('do' => 'firstLogin', 'userId' => $user->user_id, 'meddage' => 'Confirm Success');	
	}
	
	public static function isNewPasswdLink($vertifyKey) {
		$user = self::where('new_pasword_link', $vertifyKey)->first();
		
		if ( !$user || !$user->user_id ) {
			return array('Error' => 'Invalid Link', 'do' => '404');
		}
		
		if ( time($user->new_password_valid_time) >= time() ) {
			return array('do' => 'generateNewLink', 'userId' => $user->user_id, 'Error' => 'Link Overtime');
		}
		
		return array('do' => 'addFormNewPasswd', 'user_id' => $user->user_id);
	}
	
	public static function hashedPasswd($passwd) {
		return Hash::make($passwd);
	}
	
	public static function isSamePasswd($hashed, $passwd) {
		return Hash::check($passwd, $hashed);
	}
	
	private function refleshPasswd($passwd) {
		if (Hash::needsRehash($this->password)) {
			$this->password = Hash::make($passwd);
			$this->save();
		}
	}
	
        /**
        * Rendelés leadásához szükséges adatok vizsgálata profilból
        *
        * @param  array modell id
        * @return array
        */ 
	public static function canOrder($id)
        {
            $required = [
                'first_name' => 'First name',
                'last_name' => 'Last name'
            ];
            
            $query = self::where('user_id', '=', $id)->first()->toArray();
            
            $ret = [];
            
            foreach ($required as $col => $val){
                if(is_null($query[$col])){
                   $ret[] = $val;
                }
            }
            return $ret;
        }
	
}
