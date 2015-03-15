<?php

class AdminCategoryController extends \BaseController
{
    public function index($id=null)
    {
        $categoryTypes = CategoryType::paginate(10);
        
        if(!is_null($id))
        {
          $categories = Category::getQuery($id);
          
          return View::make('admin.categories.categories.index')
                ->with('categories',$categories)
                ->with('category_id',$id)
                ->with('categoryTypes',$categoryTypes);
        }
        

        return View::make('admin.categories.categoryTypes.index')
                ->with('categoryTypes',$categoryTypes);
    }
    
    public function editCategoryType($id)
    {
        $categoryType = CategoryType::where('id','=',$id)->first();
        
        return View::make('admin.categories.categoryTypes.edit')
                ->with('categoryType', $categoryType);
    }
    
   
    
    //főkategóriákhoz tartozó alkategóriák listázása
    public function lists($id)
    {
        $categoryType = 0;
        if($id) {
          $categoryType = $id;  
        }
       
//        if(!empty($_GET['type_id'])) {
//          $categoryType = $_GET['type_id'];  
//        }
        
        $categories = Category::getQuery($categoryType);
        $categoryTypes = CategoryType::all();
        
        return View::make('admin.categories.categories.index')
                ->with('categories',$categories)
                ->with('categoryTypeId',$id)
                ->with('categoryTypes',$categoryTypes);
    }

    public function createCategory($id)
    {
        $categoryType = CategoryType::where('id','=',$id)->first();
        return View::make('admin.categories.categories.create')
               ->with('categoryType',$categoryType);
    }
    
    public function saveCategory()
    {
        $valid = Validator::make(Input::all(),  Category::$rules);
        
        if($valid->passes()){
            $categ = new Category();
            $categ->name = Input::get('name');
            $categ->title = Input::get('title');
            $categ->active = (Input::get('active')) ? Input::get('active') : 0;
            $categ->type_id = Input::get('type_id');
            
            $categ->save();
            
            return Redirect::route('admin.categories.index')
                    ->with('message', 'Sikeresen hozzá lett adva az új kategória');  
        }
        
        return Redirect::back()
                ->with('message', 'Hiha!')
                ->withInput()
                ->withErrors($valid);
    }
    
     public function editCategory($type_id,$id)
    {
        $category = Category::where('id','=',$id)->first();
   
        $categoryType = CategoryType::where('id','=',$type_id)->first();
        
        return View::make('admin.categories.categories.edit')
                ->with('category', $category)
                ->with('categoryType',$categoryType);
    }
    
    
    public function updateCategory($id)
    {
        $valid = Validator::make(Input::all(),  Category::$rules);
        
        if($valid->passes()){
            $categ = Category::find($id);
            $categ->name = Input::get('name');
            $categ->title = Input::get('title');
            $categ->active = (Input::get('active')!=null) ? Input::get('active') : 0;
            $categ->type_id = Input::get('type_id');
            
            $categ->update();
            return Redirect::route('admin.categories.index')
                    ->with('message', 'Sikeresen hozzá lett adva az új kategória');  
        }
        
        return Redirect::back()
                ->with('message', 'Hiha!')
                ->withInput()
                ->withErrors($valid);
    }
    
    
    public function updateCategoryType($id)
    {
        $valid = Validator::make(Input::all(), CategoryType::$rules);
        
        if($valid->passes()){
            $categ = CategoryType::find($id);
            $categ->name  = Input::get('name');
            $categ->title = Input::get('title');
            $categ->active = (Input::get('active')) ? Input::get('active') : 0;
            $categ->multi = (Input::get('multi')) ? Input::get('multi') : 0;
            
            $categ->update();
            
            return Redirect::route('admin.categories.index')
                    ->with('message', 'Sikeresen hozzá lett adva az új kategória');  
        }
        
        return Redirect::back()
                ->with('message', 'Hiha!')
                ->withInput()
                ->withErrors($valid);
    }
    
    
    public function createType()
    {
        
        return View::make('admin.categories.categoryTypes.create');
    }
    
    public function saveCategoryType()
    {
        $valid = Validator::make(Input::all(), CategoryType::$rules);
        
        if($valid->passes()){
            $categ = new CategoryType();
            $categ->name = Input::get('name');
            $categ->title = Input::get('title');
            $categ->active = (Input::get('active')) ? Input::get('active') : 0;
            $categ->multi = (Input::get('multi')) ? Input::get('multi') : 0;
            
            $categ->save();
            
            return Redirect::route('admin.categories.cat',array('id'=>$categ->category_id))
                    ->with('message', 'Sikeresen hozzá lett adva az új kategória típus');  
        }
        
        return Redirect::back()
                ->with('message', 'Hiha!')
                ->withInput()
                ->withErrors($valid);
    }
    
    
    public function editStatusz($id)
    {
        $model = CategoryType::find($id);
        
        if ($model->active == 0){
            $model->active = 1;
        }else{
            $model->active = 0;
        }
        $model->update();
        
        return Redirect::route('admin.categories.index');
    }
    
    public function editCatStatusz($id)
    {
        $model = Category::find($id);
        
        if ($model->active == 0){
            $model->active = 1;
        }else{
            $model->active = 0;
        }
        $model->update();
        
        return Redirect::route('admin.categories.index');
    }
}

