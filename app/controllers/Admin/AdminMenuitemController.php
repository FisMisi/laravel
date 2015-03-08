<?php

class AdminMenuitemController extends \BaseController {

    public function index() 
    {
        $availability = 2;
        if(!empty($avai=Input::get('availability'))){
           switch($avai)
           {
              case 'yes' :
                $availability = 1;
                break;
              case 'no' :
                $availability = 0;
                break;
              default:
                $availability = 2;
                break;
           }
        }
        
        $type = 0;
        if(!empty($type_=Input::get('type'))){ 
            if($type_ != 'all'){
               $type = (int)$type_; 
            }
        }
       
        $menuitems = Menuitem::getQuery($availability, $type);
        
        $categories = [];
        foreach (Category::all() as $categ) {
            $categories[$categ->id] = $categ->name;
        }
        return View::make('admin.menuitems.index')
                        ->with('menuitems', $menuitems)
                        ->with('categories', $categories);
    }

    public function create() {
        return View::make('menuitems.create');
    }
    
    public function getShow($id)
    {
        $item = Menuitem::find($id);
        
        return View::make('menuitems.show')->with('item', $item);
    }
    
    public function getSearch()
    {
        $keyword = Input::get('keyword');
        $menuitems = Menuitem::where('name', 'LIKE', '%'.$keyword.'%')->get();
        
        return View::make('menuitems.search')
                ->with('key', $keyword)
                ->with('menuitems', $menuitems);
    }

    public function store() {
        $valid = Validator::make(Input::all(), Menuitem::$rules);

        if ($valid->passes()) {
            $item = new Menuitem();
            $item->category_id = Input::get('category_id');
            $item->name = Input::get('name');
            $item->price = Input::get('price');
            //kep
            
            $image = Input::file('image');
            $filename = time() . '.' . $image->getClientOriginalName();
            $path = public_path('img/products/' . $filename);
            Image::make($image->getRealPath())->resize('200','200')->save($path);
            $item->image = 'img/products/' . $filename;
            $item->save();

            return Redirect::route('menuitems.index')
                            ->with('message', 'Sikeresen hozzá lett adva az új termék');
        }

        return Redirect::back()
                        ->with('message', 'Hiha!')
                        ->withInput()
                        ->withErrors($valid);
    }
    
    public function delProd($id) {
        $model = Menuitem::find($id);

        if ($model) {
            File::delete('products/' . $model->image);
            $model->delete();
        }

        return Redirect::back()
                        ->with('message', 'Nincs ilyen termék');
    }
    
    public function edit($id)
    {
        $menuitem = Menuitem::where('id','=',$id)->first();
        
        return View::make('admin.menuitems.edit', compact('menuitem'));
    }
    
    
     public function update($id)
    {
      $model = Menuitem::find($id);
      $name = $model->name;
       
       if(!$model){
           return Redirect::back();
       }
       
       if(Input::get('delete')==1){
           $this->delProd($id);
           
           return Redirect::route('admin.menuitems.index')
                  ->with('message', $name. ' sikeresen törölve lett a termék');
       }   
       
       $data = Input::all();
      
       $rules = Menuitem::$update_rules;
       
       $valid = Validator::make($data,$rules);
       
       if ($valid->passes()) {
            
            $model->category_id = Input::get('category_id');
            $model->name = Input::get('name');
            $model->price = Input::get('price');
            $model->availability = (Input::get('availability')) ? Input::get('availability') : 0 ;
            //kep
            if(Input::file('image'))
            {
                $image = $data['image'];
                $filename = time() . '.' . $image->getClientOriginalName();
                $path = public_path('img/products/' . $filename);
                Image::make($image->getRealPath())->resize('200','200')->save($path);
                $model->image = 'img/products/' . $filename;
            }
            
            $model->update();
        
            return Redirect::route('admin.menuitems.index')
                ->with('message', $name. ' adatai frissítve lettek!');
        }

        return Redirect::back()
                        ->with('message', 'Hiha!')
                        ->withInput()
                        ->withErrors($valid);     
    }

}
