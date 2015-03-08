<?php

class AdminCategoryController extends \BaseController
{
    public function index()
    {
        $categories = Category::paginate(10);
        return View::make('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return View::make('categories.create');
    }
    
    public function store()
    {
        $valid = Validator::make(Input::all(),  Category::$rules);
        
        if($valid->passes()){
            $categ = new Category();
            $categ->name = Input::get('name');
            $categ->save();
            
            return Redirect::route('categories.index')
                    ->with('message', 'Sikeresen hozzá lett adva az új kategória');  
        }
        
        return Redirect::back()
                ->with('message', 'Hiha!')
                ->withInput()
                ->withErrors($valid);
    }
    
    public function delCateg($id)
    {
        $model = Category::find($id);
        
        if ($model){
            $model->delete();
            return Redirect::route('categories.index')
                    ->with('message', 'Sikeresen törölve lett a kategória');
        }
        
        return Redirect::back()
                    ->with('message', 'Nincs ilyen kategória');
    }
}

