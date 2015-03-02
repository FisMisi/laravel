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
        return View::make('users.create');
    }

    /**
     * Új user adatainak ellenőrzése, majd mentése
     *
     * @return Response
     */
    public function store() 
    {
        $valid = Validator::make($data = Input::all(), User::$rules);

        if ($valid->fails()) {
            return Redirect::back()
                    ->withErrors($valid)
                    ->withInput(); //old inputok miatt
        }
        
        $pwd = Input::get('password'); 
        $hashed_pwd = Hash::make($pwd);
        $data['password'] = $hashed_pwd;
        User::create($data);
        
        return Redirect::route('login')
                ->with('message','Köszönjük a regisztrációt');
    }
    
    public function update($id)
    {
        
        
        dd($id);
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
