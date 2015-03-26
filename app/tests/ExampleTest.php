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

public static function downloadvideos() 
    {
         $query = self::join('storaged_video_types', 'storaged_video_types.id', '=', 'storaged_videos.type_id')
                     ->leftJoin('models', 'storaged_videos.model_id', '=', 'models.id');
        
         $getArray = array("videos.video_id",
                          "base_video_id",
                          "length",
                          "active",
                          "active2",
                          "created_at",
                          "rating",
                          "rating_l",
                          "sum_rating",
                          "sum_rating_l",
                          "rating_number",
                          "rating_number_l",
                          "see_count",
                          "see_count_l");

        $count = $query->count();
        $page = ceil($count/200);
        $basePath = storage_path();
        if (!file_exists($basePath.'/videoexport')) {
                mkdir($basePath.'/videoexport', 0770, true);
        }
        $path = $basePath.'/videoexport/';
        $file = "videos".date("Y_m_d_h_i_s").".csv";
        $del = ',';
        $newRow = "\n";	
        $exportData = '';
        $exportData.= 'video id'.$del.'base id'.$del.'video length'.$del.'active by tags'.$del.'active'.$del.'created'.$del.'rating all'.$del.'local rating'.$del.'sum rating all'.$del.'rating number all'.$del.'sum rating local'.$del.'rating number local'.$del.'see video all'.$del.'see video local'.$newRow;
        for($p = 0;$p < $page; $p++) {
                file_put_contents($path."rows.txt", $p);
                $idDatas = $query->take(200)->skip($p*200)->get($getArray)->toArray();
                foreach($idDatas as $row) {
                        $exportData.= $row['video_id'].$del
                                     .$row['base_video_id'].$del
                                     .$row['length'].$del
                                     .$row['active'].$del
                                     .$row['active2'].$del
                                     .$row['created_at'].$del
                                     .$row['rating'].$del
                                     .$row['rating_l'].$del
                                     .$row['sum_rating'].$del
                                     .$row['rating_number'].$del
                                     .$row['sum_rating_l'].$del
                                     .$row['rating_number_l'].$del
                                     .$row['see_count'].$del
                                     .$row['see_count_l']
                                     .$newRow;
                }
                file_put_contents($path.$file, $exportData, FILE_APPEND | LOCK_EX);
                $exportData = '';
        }

        return Response::download($path.$file, $file);
    }
