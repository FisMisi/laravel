<?php

/**
 * (bemutatkozó videó, megköszönő videó,rendelésre készülő videó,előre gyártott videó,vásárolt videó(saját),oktató videó(saját))
 * 
 */

class VideoStorageHelper extends BaseController 
{
    
    //videó exportálás
    public static function downloadvideos() 
    { 
         $params = self::getParams(Input::all(),$datas=null);
            
         $query  = StoragedVideo::getVideoToAdminList(null,
                        $params["activated_user"],$params["activated_admin"],$params["videoType"],
                        $params["published_and_date"],$params["in_storage"],$params["over_trans_code"],
                        $params["limit"], $params["order"], $params["ordered"], $params["page"]
                  ); 

        $count = $query['count'];
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
        //első sor
        
        $exportData.= 'VIDEO ID'.$del.
                      'VIDEO NAME'.$del.
                      'VIDEO TITLE'.$del.
                      'VIDEO TYPE'.$del.
                      'ARTIST NAME'.$del.
                      'ACTIVATED BY USER'.$del.
                      'ACTIVATED BY ADMIN'.$del.
                      'PUBLISHED AND DATE'.$del.
                      'IN STORAGE'.$del.
                      'RATING'.$del.
                      'CREATED'.$del.
                      'UPDATED'.$del.
                      $newRow;
        
        for($p = 0;$p < $page; $p++) {
                file_put_contents($path."rows.txt", $p);
                $idDatas = $query['videos'];
                foreach($idDatas as $row) {
                        $exportData.= $row['id'].$del
                                     .$row['videoName'].$del
                                     .$row['videoTitle'].$del
                                     .$row['videoTypeTitle'].$del
                                     .$row['artist_name'].$del
                                     .$row['active_user'].$del
                                     .$row['active_admin'].$del
                                     .$row['published_and_date'].$del
                                     .$row['in_storage'].$del
                                     .$row['rating'].$del
                                     .$row['created_at'].$del
                                     .$row['updated_at'].$del
                                     .$newRow;
                }
                file_put_contents($path.$file, $exportData, FILE_APPEND | LOCK_EX);
                $exportData = '';
        }

        return Response::download($path.$file, $file);
    }
    
    /**
    * GET paraméterek alapján az export szűréshez és az adatbázis szűréshez szükséges paraméterek elő 
    * állítása
    *
    * @param  array  $get 
    * @return array
    */
    protected static function getParams($get=null,$datas)
    {
        //videó Típusokra való szűrés
        $videoType = 0; //összes
        if (isset($get['video_type'])) {
                $videoType = $get['video_type'];
                $datas['helperData']['video_type'] = $get['video_type'];
        } else {
                $datas['helperData']['video_type'] = 0;
        }
     
        //user által aktivált
        
        $activated_user = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($get['activated_user'])) {
                $activated_user = $get['activated_user'];
                $datas['helperData']['activated_user'] = $get['activated_user'];
        } else {
                $datas['helperData']['activated_user'] = 2;
        }
        
        //admin által aktivált
        
        $activated_admin = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($get['activated_admin'])) {
                $activated_admin = $get['activated_admin'];
                $datas['helperData']['activated_admin'] = $get['activated_admin'];
        } else {
                $datas['helperData']['activated_admin'] = 2;
        }
        
        //lejárati dátum szerinti szűrés
        
        $published_and_date = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($get['published_and_date'])) {
                $published_and_date = $get['published_and_date'];
                $datas['helperData']['published_and_date'] = $get['published_and_date'];
        } else {
                $datas['helperData']['published_and_date'] = 2;
        }
        
        //storage-ban van e
        
        $in_storage = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($get['in_storage'])) {
                $in_storage = $get['in_storage'];
                $datas['helperData']['in_storage'] = $get['in_storage'];
        } else {
                $datas['helperData']['in_storage'] = 2;
        }
        
        //átesett minden transzformáláson
        
        $over_trans_code = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($get['over_trans_code'])) {
                $over_trans_code = $get['over_trans_code'];
                $datas['helperData']['over_trans_code'] = $get['over_trans_code'];
        } else {
                $datas['helperData']['over_trans_code'] = 2;
        }
        
        //lapozó
        $limit = 20;
        if (isset($get['limit'])) {
                $limit = $get['limit'];
                $datas['helperData']['limit'] = $get['limit'];
        } else {
                $datas['helperData']['limit'] = 20;
        }
        
        //rendezés
        $order = 0;
        if (isset($get['order'])) {
                $order = $get['order'];
                $datas['helperData']['order'] = $get['order'];
        }
        
        //csökkenő=0, növekvő=1
        $ordered = 0;
        if (isset($get['ordered'])) {
                $ordered = $get['ordered'];
                $datas['helperData']['ordered'] = $get['ordered'];
        } 

        $page = 1;
        if (isset($get['page'])) {
                $page = $get['page'];
                $datas['helperData']['page'] = $get['page'];
        } else {
                $datas['helperData']['page'] = 1;
        }
        
        return [
            "datas"              => $datas,
            "videoType"          => $videoType,
            "activated_user"     => $activated_user,
            "activated_admin"    => $activated_admin,
            "published_and_date" => $published_and_date,
            "in_storage"         => $in_storage,
            "over_trans_code"    => $over_trans_code,
            "limit"              => $limit,
            "order"              => $order,
            "ordered"            => $ordered,
            "page"               => $page
        ];
    }

     /**
     * Admin oldal
     */
   
    public static function getAdminDatas($datas) 
    {
        $datas['helperDataJson'] = 'helperDataJson';
        $datas['view'] = 'helper.admin.videostorage.list';  

        $datas['styleCss'] = array();

        $datas['jsLinks'] = array();
 
        $datas['helperData']['videoTypeList'] = StoragedVideoType::get(array('id', 'title'))->toArray();
        //dd($_GET);
        $params = self::getParams(Input::all(),$datas);
        $datas = $params['datas'];
        
        // Update ezen keresztül megy
         $datas['helperData']['videoId'] = null;
          if (isset($datas['id'])) {
            $datas['view'] = 'helper.admin.videostorage.edit';
            $datas['helperData']['videoId'] = $datas['id'];  
            //$datas = self::edit($datas);
          }
          
          $idDatas = StoragedVideo::getVideoToAdminList($datas['helperData']['videoId'],
                        $params["activated_user"],$params["activated_admin"],$params["videoType"],
                        $params["published_and_date"],$params["in_storage"],$params["over_trans_code"],
                        $params["limit"],$params["order"], $params["ordered"], $params["page"]
                  ); 
         
        $datas['helperData']['videos'] = $idDatas['videos'];
          //dd($idDatas['videos']);
        $datas['helperData']['needPager']    = $idDatas['count']/$params["limit"] > 1 ? 1 : 0;
        $datas['helperData']['pagerOptions'] = ceil($idDatas['count']/$params["limit"]);
        $datas['helperData']['modelsCount']  = $idDatas['count'];

        return $datas;
    }
    
    
    //módosító lapot hozza be
    protected static function edit($datas) 
    {          
		$datas['view'] = 'helper.admin.videostorage.edit';
                $datas['helperData']['videoId'] = $datas['id'];

		return $datas;
                
    }
        
    
    /**
    * Admin - model adatok frissítése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function update($id)
    {
        $data = Input::all();
        $rules = StoragedVideo::$rules;
        $valid = Validator::make($data, $rules);
        $model = StoragedVideo::find($id);
       
        if ($valid->passes()) {
            $model->active_admin  = (Input::get('active_admin')== 1) ? 1 : 0;
            $model->type_id       =  Input::get('type_id');
            $model->title         =  Input::get('videoTitle');
            $model->inactivated_desctription  = (Input::get('inactivated_desctription')) ? Input::get('inactivated_desctription') : '';
            
            $model->update();
            
            return Redirect::to('/administrator/video_storage');    
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
      
   /**************************************************
    * Admin - video categories adatok kezelése.
    * 
    * @param  int  $datas
    * @return datas
    */
    public static function videoCategoryIndex($datas) 
    {
        $datas['helperDataJson'] = 'helperDataJson';
        $datas['view'] = 'helper.admin.videostorage.categories.list';  

        $datas['styleCss'] = array();

        $datas['jsLinks'] = array();

        // Update ezen keresztül megy
        if (isset($datas['id'])) {
            return self::editCategory($datas);
        }
        
        $categories = GsVideoCategory::orderBy('id', 'DESC')->paginate(7);
        $datas['helperData']['categories'] = $categories;
        
        return $datas;
    }
    
    //létrehozó, módosító lapokat hozza be
    protected static function editCategory($datas) 
    {          
	 
        //kategória típusok összegyűjtése
        $modellLevels = GsModellLevel::getModellLevels();
        $datas['helperData']['modellLevels'] = $modellLevels;
        
        // CREATE CATEGORY FORM HA A KAPOTT ID 0
        if ($datas['id'] != 0){       //UPDATE
            $datas['view'] = 'helper.admin.videostorage.categories.edit';
            $category = GsVideoCategory::find($datas['id']);
            $datas['helperData']['category'] = $category;
            $datas['helperData']['prices'] = $category->getGsMlsGsVc();
        } else {                      //CREATE
            $datas['view'] = 'helper.admin.videostorage.categories.create';   
        }
        
        return $datas;              
    }
   
     /**
     * Új Kategória mentése.
     *
     * @return Response
     */
    public function saveCategory()
    { 
      //dd(Input::all());
      //lekérem a modell szinteket
      $levels = GsModellLevel::getModellLevels();
      
      //lekérem a rullokat és hibaüzeneteket
      $rules = GsMlsGsVc::getRules($levels);
        
      $valid_prices = Validator::make(Input::all(),$rules['newRules'],$rules['newMessages']);
      
      $valid = Validator::make($data = Input::all(), GsVideoCategory::$rules);
      
      if($valid->passes() && $valid_prices->passes())
        {
           //alap adatok mentése
           $model = new GsVideoCategory;
           $model->title    = Input::get('title');
           $model->name    = Input::get('name');
           $model->active  = (Input::get('active')== 1) ? 1 : 0;
           
           $model->save();
           
           //kapcsolótáblába mentés
           $cucc = array();
            foreach (Input::all() as $key => $input){
        
                if(strpos($key,"__") !== false){
                    list($title, $isexclusive, $levelId) = explode("__", $key);
                    
                    $cucc[$isexclusive][$levelId][$title] = $input;
                }
            }
           
           foreach($cucc as $isexclusive => $value1) {
               foreach($value1 as $levelId => $values) {
                   $gsMlsGsVc = new GsMlsGsVc();
                   $gsMlsGsVc->min = $values['min'];
                   $gsMlsGsVc->max = $values['max'];
                   $gsMlsGsVc->referenced_price = $values['referenced_price'];
                   $gsMlsGsVc->is_exclusive = $isexclusive;
                   $gsMlsGsVc->gs_model_level_id = $levelId;
                   $gsMlsGsVc->gs_video_category_id = $model->id;
                   $gsMlsGsVc->save();
               }    
           }
           
           return Redirect::route('/administrator/video_storaged_categories');   
        }
        
        $errors = $valid_prices->messages()->merge($valid->messages());
        
        return Redirect::back()
                ->withInput()
                ->withErrors($errors); 
    }
    
   /**
    * Admin - videó kategória frissítése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function updateCategory($id)
    {
      //lekérem a modell szinteket
      $levels = GsModellLevel::getModellLevels();

      //lekérem a rullokat és hibaüzeneteket
      $rules = GsMlsGsVc::getRules($levels);

      $valid_prices = Validator::make(Input::all(),$rules['newRules'],$rules['newMessages']);

      $valid = Validator::make($data = Input::all(), GsVideoCategory::$rules);
      
        
        if ($valid->passes() && $valid_prices->passes()) {
            $model = GsVideoCategory::find($id);
            $model->title    = Input::get('title');
            $model->name     = Input::get('name');
            $model->active   = (Input::get('active')== 1) ? 1 : 0;
            
            $model->update();
            
            //kapcsolótáblába update
            $cucc = array();
            foreach (Input::all() as $key => $input){
        
                if(strpos($key,"__") !== false){
                    list($title, $isexclusive, $levelId) = explode("__", $key);
                    
                    $cucc[$isexclusive][$levelId][$title] = $input;
                }
            }
            
            foreach($cucc as $isexclusive => $value1) {
               foreach($value1 as $levelId => $values) {
                   
                   $gsMlsGsVc = GsMlsGsVc::where('gs_model_level_id','=',$levelId)
                                          ->where('gs_video_category_id','=',$model->id)
                                          ->where('is_exclusive','=',$isexclusive)->first();
                   
                   if(is_null($gsMlsGsVc)){    //ha még nincs mentve modell szinthez értékek
                        
                        $gsMlsGsVc = new GsMlsGsVc();
                        $gsMlsGsVc->min = $values['min'];
                        $gsMlsGsVc->max = $values['max'];
                        $gsMlsGsVc->referenced_price = $values['referenced_price'];
                        $gsMlsGsVc->is_exclusive = $isexclusive;
                        $gsMlsGsVc->gs_model_level_id = $levelId;
                        $gsMlsGsVc->gs_video_category_id = $model->id;
                        $gsMlsGsVc->save();   
                   }else{       
                        $gsMlsGsVc->min              = $values['min'];
                        $gsMlsGsVc->max              = $values['max'];
                        $gsMlsGsVc->referenced_price = $values['referenced_price'];

                        $gsMlsGsVc->update();
                   }
               }    
            }
            
            return Redirect::route('/administrator/video_storaged_categories');    
        }
       
        $errors = $valid_prices->messages()->merge($valid->messages());
        
        return Redirect::back()
                ->withInput()
                ->withErrors($errors); 
    }

    /**
     * Státusz módosítására szolgáló metódus.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateStatusz($id) 
    {
        $model = GsVideoCategory::find($id);
        
        if($model->active == 1){
          $model->active = 0;  
        }else{
          $model->active = 1;  
        }
        
        $model->update();

        return Redirect::route('/administrator/video_storaged_categories');
    }
      
}