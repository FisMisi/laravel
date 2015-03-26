<?php

class AdminUserController extends \BaseController 
{

    /**
     * Felhasználók/alkalmazottak megjelenítése.
     *
     * @return Response
     */
    public function index() 
    {
        $admin = 2;
        
        if(!empty($post = Input::get('admin')))
        {
          switch($post)
          {
            case 'user':
                $admin = 0;
                break;
            case 'admin': 
                $admin = 1;
                break;
            default: 
                $admin = 2;
                break;
          }   
        }
   
        $users = User::getResult($admin);

        return View::make('admin.users.index', compact('users'));
    }
    
    public function exportUsers() 
    {
         $query = User::where('admin','=',0);
        
         $getArray = array("users.id",
                          "users.first_name",
                          "users.last_name",
                          );

        $count = $query->count();
        $page = ceil($count/4);
        $basePath = public_path();
        if (!file_exists($basePath.'/userexport')) {
                mkdir($basePath.'/userexport', 0770, true);
        }
        $path = $basePath.'/userexport/';
        $file = "users".date("Y_m_d_h_i_s").".csv";
        $del = ',';
        $newRow = "\n";	
        $exportData = '';
        $exportData .= 'USER ID'.$del.'FIRST NAME'.$del.'LAST NAME'.$del.$newRow;
        for($p = 0;$p < $page; $p++) {
                file_put_contents($path."rows.txt", $p);
                $idDatas = $query->skip($p*4)->take(4)->get($getArray)->toArray();
                
                foreach($idDatas as $row) {
                        $exportData.= $row['id'].$del
                                     .$row['first_name'].$del
                                     .$row['last_name'].$newRow;
                }
                file_put_contents($path.$file, $exportData, FILE_APPEND | LOCK_EX);
                $exportData = '';
        }

        return Response::download($path.$file, $file);
    }
    

    /**
     * Új User Form megjelenítése.
     *
     * @return Response
     */
    public function create() {
        return View::make('users.create');
    }

    /**
     * Új user adatainak ellenőrzése, majd mentése
     *
     * @return Response
     */
    public function store() 
    {
        //dd(Input::all());
        
        $valid = Validator::make($data = Input::all(), User::$rules);

        if ($valid->fails()) {
            return Redirect::back()
                    ->withErrors($valid)
                    ->withInput(); //old inputok miatt
        }
        
        $pwd = Input::get('password'); 
        $hashed_pwd = Hash::make($pwd);
        $data['password'] = $hashed_pwd;
         
        $destinationPath = public_path().'/img/profil'; // upload path
        
        $extension = Input::file('img_path')->getClientOriginalName(); // getting image extension
        $fileName = time().'.'.$extension; // renameing image
        Input::file('img_path')->move($destinationPath, $fileName); //
        
        $data['img_path'] = 'img/profil/' . $fileName;
        User::create($data);
        
        return Redirect::route('login')
                ->with('message','Köszönjük a regisztrációt');
    }
    
    public function update($id)
    {
       $model = User::find($id);
       $user = $model->getFullName();
       
       if(!$model){
           return Redirect::back();
       }
       
       if(Input::get('delete')==1){
           $this->userDestroy($id);
           
           return Redirect::route('admin.users.index')
                ->with('message', $user.' sikeresen törölve lett!');
       }
       
       $data = Input::all();
      
       $rules = User::$admin_rules;
       $rules['username']['unique'] = 'unique:users,username,' . $id;
       
       $valid = Validator::make($data,$rules);
        
        if ($valid->fails()) {
            return Redirect::back()
                    ->withErrors($valid)
                    ->withInput(); //old inputok miatt
        }

        $model->update($data);
        
        return Redirect::route('admin.users.index')
                ->with('message', $user. 'adatai frissítve lettek!');
    }
    
    
    public function edit($id)
    {
        $user = User::where('id','=',$id)->first();
        
        return View::make('admin.users.edit', compact('user'));
    }
   
    
    public function getLogin()
    {
        return View::make('admin.login');
    }
    
    public function postLogin()
    {
        
        if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')),(Input::get('remember')==1 ? true : false))){
            return Redirect::intended('/');
        }
        
        return Redirect::route('login')
                ->with('message','Felhasználónév vagy a jelszó nem megfelelő!');
    }
    
    public function getLogout()
    {
        Auth::logout();
        return View::make('admin.login');
    }
    
    /**
     * Alkalmazott törlése.
     *
     * @param  int  $id
     * @return Response
     */
    public function userDestroy($id) 
    {
        $model = User::find($id);
        $model->delete();
    }

}
