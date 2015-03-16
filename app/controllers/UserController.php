<?php

class UserController extends \BaseController 
{
    /**
     * Felhasználók/alkalmazottak megjelenítése.
     *
     * @return Response
     */
    public function index() {
        $users = User::all();

        return View::make('user.index', compact('users'));
    }

    /**
     * Új User Form1 (step1) megjelenítése.
     *
     * @return Response
     */
    public function create() 
    {
        return View::make('users.step1.create');
    }
    
    /**
     * Új User Form2 (step2) megjelenítése.
     *
     * @return Response
     */
    public function create2($id) 
    {
        $user = User::find($id);
        
        $categoryTypes = CategoryType::getTypes();
       
        return View::make('users.step2.create')
                ->with('user', $user)
                ->with('categoryTypes', $categoryTypes);
    }

    /**
     * Új user adatainak ellenőrzése,mentése majd tovább step2
     *
     * @return Response
     */
    public function store() 
    { 
        $valid = Validator::make($data = Input::all(), User::$rules_step1);

        if ($valid->fails()) {
            return Redirect::back()
                    ->withErrors($valid)
                    ->withInput(); //old inputok miatt
        }
         
        $destinationPath = public_path().'/img/profil'; // upload path
        
        $extension = Input::file('img_path')->getClientOriginalName(); // getting image extension
        $fileName = time().'.'.$extension; // renameing image
        Input::file('img_path')->move($destinationPath, $fileName); //
        
        $data['img_path'] = 'img/profil/' . $fileName;
        
        $user = new User();
        
        $user->first_name = $data['first_name'];
        $user->last_name  = $data['last_name'];
        $user->username   = $data['username'];
        $user->img_path   = $data['img_path'];
        
        $user->save();
        
        return Redirect::route('/users/create/step2/{id}',$user->id);
    }
    
    
    public function store2()
    {
        $types = CategoryType::select('id','name')->get()->toArray();
        
        foreach($types as $type)
        {
            if(is_array(Input::get($type['name']))){  //select, azaz tömb
               foreach(Input::get($type['name']) as $item)
                {
                   $model = new UserCategory();
                   
                   $model->user_id = Input::get('user_id');
                   $model->type_id = $type['id'];
                   $model->category_id = $item;
                   
                   $model->save();
                   
                }
            }else{               //radio
                   $model = new UserCategory();
                   
                   $model->user_id = Input::get('user_id');
                   $model->type_id = $type['id'];
                   $model->category_id = Input::get($type['name']);
                   
                   $model->save();
            }  
        }
        
        $valid = Validator::make($data = Input::all(), User::$rules_step2);

        if ($valid->fails()) {
            return Redirect::back()
                    ->withErrors($valid)
                    ->withInput(); //old inputok miatt
        }
        
        $pwd = Input::get('password'); 
        $hashed_pwd = Hash::make($pwd);
        $data['password'] = $hashed_pwd;
        
        $user = User::find(Input::get('user_id'));
        $user->password = $data['password'];
        
        $user->update();
        
         return Redirect::route('login')
                ->with('message','Sikeresen regisztrált');
    }

    
    //step1 edit form
    public function edit()
    {
        $user = User::where('id','=',Auth::user()->id)->firstOrFail();
        
        return View::make('users.step1.edit', compact('user'));
    }
    
    //step2 edit form
    public function edit2()
    {
        $user = User::where('id','=',Auth::user()->id)->firstOrFail();
        $categoryTypes = CategoryType::getTypes();
        
        return View::make('users.step2.edit', compact(array('user','categoryTypes')));
    }
   
    

    public function update($id)
    {
       $model = User::find(Auth::user()->id);
       
       if(!$model){
           return Redirect::back();
       }
       
       $data = Input::all();
       $rules = User::$rules_step1;
       
       $rules['username']['unique'] = 'unique:users,username,' . $id;
       
       $valid = Validator::make($data,$rules);

        if ($valid->fails()) {
            return Redirect::back()
                    ->withErrors($valid)
                    ->withInput(); //old inputok miatt
        }
      
        if(Input::file('img_path'))
        {
            
         $filename = substr(strrchr($model->img_path, "/"), 1);  //kep nevének kinyerése    
         $fileLocation = $model->img_path;   //file neve útvonallal
         $target = public_path().'/img/old_profil/'.$filename; //cél
         
         File::copy($fileLocation, $target);
         File::delete(public_path().'/img/profil/'.$filename);
         
         $destinationPath = public_path().'/img/profil'; // upload path
         $extension = Input::file('img_path')->getClientOriginalName(); // getting image extension
         $fileName = time().'.'.$extension; // renameing image
         Input::file('img_path')->move($destinationPath, $fileName);
        
         $data['img_path'] = 'img/profil/' . $fileName;
        }
        
        $pwd = Input::get('password'); 
        $hashed_pwd = Hash::make($pwd);
        $data['password'] = $hashed_pwd;
         
        
        $model->update($data);
        
        return Redirect::route('users.edit2');
    }
    
    
    
     public function update2()
    {
       $model = User::find(Auth::user()->id);
       
       if(!$model){
           return Redirect::back();
       }
       
       $data = Input::all();
       $rules = User::$rules_step2;
       
       
       $valid = Validator::make($data,$rules);
       dd($valid->fails());
        if ($valid->fails()) {
            return Redirect::back()
                    ->withErrors($valid)
                    ->withInput(); //old inputok miatt
        }
      
        
        $pwd = Input::get('password'); 
        $hashed_pwd = Hash::make($pwd);
        $data['password'] = $hashed_pwd;
         
        
        $model->update($data);
        
        return Redirect::route('login')
                ->with('message','Adatok frissítve lettek');
    }
    
    public function getLogin()
    {
        return View::make('users.login');
    }
    
    public function postLogin()
    {
//        $data = Input::all();
//        $valid = Validator::make($data,User::$auth_rules);
//        
//        if ($valid->fails()) {
//            return Redirect::back()->withErrors($valid)->withInput();
//        }
        
        if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')),(Input::get('remember')==1 ? true : false))){
            return Redirect::intended('/');
        }
        
        return Redirect::route('login')
                ->with('message','Felhasználónév vagy a jelszó nem megfelelő!');
    }
    
    public function getLogout()
    {
        Auth::logout();
        return View::make('users.login');
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

        return Redirect::route('user.index');
    }

}
