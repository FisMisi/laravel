<?php

class UserController extends \BaseController 
{
    /**
     * Felhasználók/aaaaaalkalmazottak megjelenítése.
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
        
        $pwd = Input::get('password'); 
        $hashed_pwd = Hash::make($pwd);
        $data['password'] = $hashed_pwd;
        
        $user->password = $data['password'];
        
        $user->save();
        
        return Redirect::route('/users/create/step2/{id}',$user->id);
    }
    
    
    public function store2()
    {
        //KATEGÓRIÁK MENTÉSÉNEK ELŐKÉSZÍTÉSE
        
        $types = CategoryType::getCategoryTypes();
        
        //lekérem a rullokat és hibaüzeneteket
        $categ = UserCategory::getCategoryRules($types);
        
        $valid_categories = Validator::make(Input::all(),$categ['newRules'],$categ['newMessages']);
        
        $valid = Validator::make($data = Input::all(), User::$rules_step2);

        if ($valid->passes() && $valid_categories->passes()) {
           
           //lekérjük a modell már meglévő adatait, hogy tudjuk bővíteni
           
           $model = User::where('id','=',Input::get('user_id'))->first(); 
            
           $model->os_v_vallalkozas = Input::get('os_v_vallalkozas');
           $model->vallalkozas_nev  = (Input::get('vallalkozas_nev')) ? Input::get('vallalkozas_nev') : null;
           $model->update();
           
            //kategóriák mentése a modelhez
            foreach($types as $type)
            {
                if(is_array(Input::get($type['id']))){  //select, azaz tömb
                   foreach(Input::get($type['id']) as $item)
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
                       $model->category_id = Input::get($type['id']);

                       $model->save();
                }  
            }
        
           return Redirect::route('login')
                ->with('message','Sikeresen regisztrált');   
        }
        
        
        //user step 2 form validáló és a kategória validáló egybe vonása 
        $errors = $valid_categories->messages()->merge($valid->messages());
        
        return Redirect::back()
                ->withInput()
                ->withErrors($errors);
  
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
   
    
    //step1 update/mentés
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
       
        //Kép mentése
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
         $model->img_path = $data['img_path'];
        }
        
        $pwd = Input::get('password'); 
        $hashed_pwd = Hash::make($pwd);
        $data['password'] = $hashed_pwd;
        $model->password  = $data['password'];
        $model->first_name = $data['first_name'];
        $model->last_name  = $data['last_name'];
        $model->username   = $data['username'];
    
        $model->update();
        
        return Redirect::route('users.edit2');
    }
    
    
     //step2 update,mentés
    
     public function update2()
    {
       $model = User::find(Auth::user()->id);
      
       if(!$model){
           return Redirect::back();
       }
       
       $id = Input::get('user_id');
      
       $data = Input::all();
       // dd($data);
       $rules = User::$rules_step2;
       
       //Kategóriák mentése
        
       $types = CategoryType::getCategoryTypes();
        
       //lekérem a rullokat és hibaüzeneteket
       $categ = UserCategory::getCategoryRules($types);
        
       //validálom a típusokat
       $valid_categories = Validator::make($data,$categ['newRules'],$categ['newMessages']);
       
       //validálom az alap, rögzített adatokat
       $valid = Validator::make($data,$rules);
   
        if ($valid->passes() && $valid_categories->passes()) {    //nincs hiba, akkor mentés
  
            $model->os_v_vallalkozas = Input::get('os_v_vallalkozas');
            $model->vallalkozas_nev  = (Input::get('vallalkozas_nev')) ? Input::get('vallalkozas_nev') : null;
           
            $model->update();
            
            //kategóriák mentése a modelhez
            foreach($types as $type)
            {   
                if(is_array(Input::get($type['id'])))       //select, azaz tömb
                {  
                    $query = UserCategory::where('type_id', '=', $type['id']);
                    $dbCateg = $query->where('user_id', '=', $id)->get(array('category_id'))->toArray();
                    $dbCategory = array();
                    
                    foreach($dbCateg as $tmp) {
                        $dbCategory[] = $tmp['category_id'];
                    }
                    
                    $newValue = Input::get($type['id']);
                    $merge = array_merge($dbCategory, $newValue);
                     
                    $needDelete = array_diff($merge,$newValue);
                    foreach($needDelete as $item){
                        UserCategory::where('category_id', '=', $item)->delete();
                    }
                    
                    $needInsert = array_diff($merge, $dbCategory);
                    foreach($needInsert as $item){
                        UserCategory::insert(
                                array('user_id' => $id, 'type_id' => $type['id'], 'category_id'=>$item)
                        );
                    }
                }else{                                       //radio 
                       $categ = UserCategory::where('user_id', '=', $id)
                                ->where('type_id', '=', $type['id'])->first();
                       
                       if(!is_null($categ))
                       {
                            $categ->category_id = Input::get($type['id']);

                            $categ->update();
                       }else{
                           UserCategory::insert(
                                array('user_id' => $id, 'type_id' => $type['id'], 'category_id'=>Input::get($type['id']))
                        );
                       }
                }  
            }
            return Redirect::to('/');   
        }
        
         //user step 2 form validáló és a kategória validáló egybe vonása 
            $errors = $valid_categories->messages()->merge($valid->messages());

            return Redirect::back()
                    ->withInput()
                    ->withErrors($errors);   
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
