<?php

class ExampleTest extends TestCase 
{
//nyelvek frissítése modelhez
                $query = ModelLanguage::where('model_id', '=', $id)
                        ->get(array('gs_language_id'))
                        ->toArray();

                $dbLanguage = array();

                foreach($query as $tmp) {
                    $dbLanguage[] = $tmp['gs_language_id'];
                }

                $newValue = Input::get('gs_languages');
                $merge = array_merge($dbLanguage, $newValue);

                $needDelete = array_diff($merge,$newValue);
                foreach($needDelete as $item){
                    ModelLanguage::where('gs_language_id', '=', $item)->delete();
                }

                $needInsert = array_diff($merge, $dbLanguage);
                foreach($needInsert as $item){
                    ModelLanguage::insert(
                            array('model_id' => $id,'gs_language_id' => $item)
                    );
                }
            
            
            //kategóriák mentése a modelhez
                foreach($types as $type)
                {   
                    if(is_array(Input::get($type['id'])))       //select, azaz tömb
                    {  
                        $query = ModelModelCategory::where('type_id', '=', $type['id']);
                        $dbCateg = $query->where('model_id', '=', $id)->get(array('category_id'))->toArray();
                        $dbLanguage = array();

                        foreach($dbCateg as $tmp) {
                            $dbLanguage[] = $tmp['category_id'];
                        }

                        $newValue = Input::get($type['id']);
                        $merge = array_merge($dbLanguage, $newValue);

                        $needDelete = array_diff($merge,$newValue);
                        foreach($needDelete as $item){
                            ModelModelCategory::where('category_id', '=', $item)->delete();
                        }

                        $needInsert = array_diff($merge, $dbLanguage);
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

}
