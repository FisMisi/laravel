<?php

class ProductsController extends \BaseController
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

        return View::make('products.index')
                ->with('categoryTypes',$categoryTypes);
    }
    
    public function lists()
    {
     $datas = Input::all();
    
     $users = User::getUserList($datas);
     
     return View::make('products.lists')
             ->with('users',$users);
    }
}
