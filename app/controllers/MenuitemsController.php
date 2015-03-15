<?php

class MenuitemsController extends \BaseController 
{

    public function index() {
        $menuitems = Menuitem::where('availability', '=', 1)->paginate(5);
        $categories = [];

        foreach (Category::all() as $categ) {
            $categories[$categ->id] = $categ->name;
        }
        return View::make('menuitems.index')
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
            return Redirect::route('menuitems.index')
                            ->with('message', 'Sikeresen törölve lett a termék');
        }

        return Redirect::back()
                        ->with('message', 'Nincs ilyen termék');
    }

    public function setStatusz($id) {
        $item = Menuitem::find($id);

        if ($item) {
            $item->availability = Input::get('availability');
            $item->save();

            return Redirect::route('menuitems.index')
                            ->with('message', 'Sikeres frissítés');
        }

        return Redirect::back()->with('message', 'Nincs ilyen termék');
    }

}
