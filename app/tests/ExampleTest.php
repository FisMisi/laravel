<?php

class ExampleTest extends TestCase 
{

  
    
    
     public function UpdateModelStep2($id)
    {   
        $data = Input::all();
        $rules = Model::$step2_rules;
        
       
        
        
        
        
        
        $valid = Validator::make($data, $rules);
        
        if ($valid->passes() && $valid_categories->passes()) {
            
            $model = Model::findOrFail($id);
            $model->introducte = Input::get('introducte');
            
             //személyi okmányok feltöltése
            if(Input::file('documents'))
            {   
                $files = Input::file('documents');
                $this->uploadPersonalDocuments($files,$model->id);
            }
            
            $model->update();
            
            //kategóriák mentése a modelhez
            foreach($types as $type)
            {   
                if(is_array(Input::get($type['id'])))       //select, azaz tömb
                {  
                    $query = ModelModelCategory::where('type_id', '=', $type['id']);
                    $dbCateg = $query->where('model_id', '=', $id)->get(array('category_id'))->toArray();
                    $dbCategory = array();
                    
                    foreach($dbCateg as $tmp) {
                        $dbCategory[] = $tmp['category_id'];
                    }
                    
                    $newValue = Input::get($type['id']);
                    $merge = array_merge($dbCategory, $newValue);
                     
                    $needDelete = array_diff($merge,$newValue);
                    foreach($needDelete as $item){
                        ModelModelCategory::where('category_id', '=', $item)->delete();
                    }
                    
                    $needInsert = array_diff($merge, $dbCategory);
                    foreach($needInsert as $item){
                        ModelModelCategory::insert(
                                array('model_id' => $id, 'type_id' => $type['id'], 'category_id'=>$item)
                        );
                    }
                }else{                                       //radio 
                       $categ = ModelModelCategory::where('model_id', '=', $id)
                                ->where('type_id', '=', $type['id'])->first();
                       
                       if(!is_null($categ))
                       {
                            $categ->category_id = Input::get($type['id']);

                            $categ->update();
                       }else{
                           ModelModelCategory::insert(
                                array('model_id' => $id, 'type_id' => $type['id'], 'category_id'=>Input::get($type['id']))
                        );
                       }
                }  
            }
            
            return Redirect::to('/');    
        }
       
       
    }
    
    
     /**
     * Új Model regisztrációja step2. 
     *(Egy már meglévő rekord fríssítése a hiányzó adatokkal)
     * 
     * @return Response
     */
    public function CreateModelStep2()
    {  
        $types = ModelCategoryType::getCategoryTypes();
        
        //lekérem a rullokat és hibaüzeneteket
        $categ = ModelModelCategory::getCategoryRules($types);
        
        $valid_categories = Validator::make(Input::all(),$categ['newRules'],$categ['newMessages']);
        
        $valid = Validator::make(Input::all(),Model::$step2_rules);
        
        if($valid->passes() && $valid_categories->passes())
        {
            
            
            //kategóriák mentése a modelhez
            foreach($types as $type)
            {
                if(is_array(Input::get($type['id']))){  //select, azaz tömb
                   foreach(Input::get($type['id']) as $item)
                    {
                       $model = new ModelModelCategory();

                       $model->model_id = Input::get('model_id');
                       $model->type_id = $type['id'];
                       $model->category_id = $item;

                       $model->save();

                    }
                }else{               //radio
                       $model = new ModelModelCategory();

                       $model->model_id = Input::get('model_id');
                       $model->type_id = $type['id'];
                       $model->category_id = Input::get($type['id']);

                       $model->save();
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

}
