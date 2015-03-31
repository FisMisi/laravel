<?php

class BorController extends \BaseController
{
    public function index()
    {
        $user = Auth::user()->id;
        
        $borok = Bor::getMyWines($user);
       
        return View::make('borok.index', compact('borok'));
    }
    
      //ajax hívás ami lekéri a modelhez tartozó videó active_user értékét
    public function setBorStatusz() 
    {	
        $data = Input::all();
        //$modelId = $data['modelId'];
        $borId = $data['borId'];
        $bor = Bor::find($borId);

         if($bor->active_user == 1){
            $bor->active_user = 0;  
          }else{
            $bor->active_user = 1;  
          }

        $bor->update();

        $fleg = $bor->active_user;

        return $fleg;             
    }
    
//    public function create()
//    {
//        return View::make('categories.create');
//    }
//    
//    public function store()
//    {
//        $valid = Validator::make(Input::all(),  Category::$rules);
//        
//        if($valid->passes()){
//            $categ = new Category();
//            $categ->name = Input::get('name');
//            $categ->save();
//            
//            return Redirect::route('categories.index')
//                    ->with('message', 'Sikeresen hozzá lett adva az új kategória');  
//        }
//        
//        return Redirect::back()
//                ->with('message', 'Hiha!')
//                ->withInput()
//                ->withErrors($valid);
//    }
//    
//    public function delCateg($id)
//    {
//        $model = Category::find($id);
//        
//        if ($model){
//            $model->delete();
//            return Redirect::route('categories.index')
//                    ->with('message', 'Sikeresen törölve lett a kategória');
//        }
//        
//        return Redirect::back()
//                    ->with('message', 'Nincs ilyen kategória');
//    }
}

