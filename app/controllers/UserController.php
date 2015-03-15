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
     * Új User Form megjelenítése.
     *
     * @return Response
     */
    public function create() {
        
        $categoryTypes = CategoryType::getTypes();
       
        return View::make('users.create')
                ->with('categoryTypes', $categoryTypes);
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
       
       
       if(!$model){
           return Redirect::back();
       }
       
       $data = Input::all();
       $rules = User::$rules;
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
        
        return Redirect::route('login')
                ->with('message','Adatok frissítve lettek');
    }
    
    public function editUser()
    {
        $user = User::where('id','=',Auth::user()->id)->firstOrFail();
        
        return View::make('users.edit', compact('user'));
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
