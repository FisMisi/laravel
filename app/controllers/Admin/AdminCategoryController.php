<?php

class AdminCategoryController extends \BaseController
{
    public function index()
    {        
        $categories = Category::paginate(10);

        return View::make('admin.categories.categories.index')
                ->with('categories',$categories);
    }
    
    public function lists($id)
    {
        $category = 0;
        if(!empty($id)) {
          $category = $id;  
        }
        
        $categoryType = 0;
//        if(!empty($_GET['type_id'])) {
//          $categoryType = $_GET['type_id'];  
//        }
        
        $categoryTypes = Category::getQuery($category, $categoryType);
        $categories = Category::all();
        
        return View::make('admin.categories.categories.index')
                ->with('categories',$categories)
                ->with('categoriy_id',$id)
                ->with('categoryTypes',$categoryTypes);
    }

    public function createCategory()
    {
        return View::make('admin.categories.categories.create');
    }
    
    public function saveCategory()
    {
        $valid = Validator::make(Input::all(),  Category::$rules);
        
        if($valid->passes()){
            $categ = new Category();
            $categ->name = Input::get('name');
            $categ->save();
            
            return Redirect::route('admin.categories.index')
                    ->with('message', 'Sikeresen hozzá lett adva az új kategória');  
        }
        
        return Redirect::back()
                ->with('message', 'Hiha!')
                ->withInput()
                ->withErrors($valid);
    }
    
    public function delCategory($id)
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
    
    
    public function createType($id)
    {
        $category = Category::where('id','=',$id)->first();
        
        return View::make('admin.categories.categoryTypes.create')
                ->with('category', $category);
    }
    
    public function saveCategoryType()
    {
        $valid = Validator::make(Input::all(), CategoryType::$rules);
        
        if($valid->passes()){
            $categ = new CategoryType();
            $categ->name = Input::get('name');
            $categ->title = Input::get('title');
            $categ->category_id = Input::get('category_id');
            $categ->save();
            
            return Redirect::route('admin.categories.cat',array('id'=>$categ->category_id))
                    ->with('message', 'Sikeresen hozzá lett adva az új kategória típus');  
        }
        
        return Redirect::back()
                ->with('message', 'Hiha!')
                ->withInput()
                ->withErrors($valid);
    }
    
    public function delCategoryType($id)
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

