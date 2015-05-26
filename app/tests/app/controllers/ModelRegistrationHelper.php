<?php

class ModelRegistrationHelper extends BaseController 
{ 
    
    /**
     * Új Model regisztrációja step1 form megjelenítése. 
     */
    public static function getViewDatas1($datas) 
    { 
        if(!Auth::check()){
           return false;   
        }
        
        $userModel = Model::join('countries', 'models.country_id', '=', 'countries.country_id')
            ->where('models.user_id','=',Auth::user()->user_id)->first();
        
        if($userModel){
           $datas['view'] = 'helper.modelregistration.step1.update';
           $datas['helperData']['userModel'] = $userModel;
        }else{
           $datas['view'] = 'helper.modelregistration.step1.create';
        }
        
        $datas['styleCss'] = array();
        $datas['jsLinks'] = array();
        
        $datas['helperDataJson'] = 'helperDataJson';#
        if (!isset($datas['pagetitle'])) {
            $datas['pagetitle'] = "Model Registration";#
        }
      
        return $datas;
    }

     /**
     * Új Model regisztrációja step1.
     *
     * @return Response
     */
    public function CreateModelStep1()
    { 
      ///validáció, majd ugrás a step 2 ha nincs hiba
      $valid = Validator::make($data = Input::all(), Model::$step1_rules);
      $baseLevelId = GsModellLevel::where('title','=','Base')
                                    ->first(array('id'))
                                    ->toArray();
      if($valid->passes()){
            $model = new Model();
            $model->user_id          = Auth::user()->user_id;
            $model->artist_name      = Input::get('artist_name');
            $model->fullname         = Input::get('fullname');
            $model->payout_system_id = Input::get('payout_system_id');
            $model->country_id       = Input::get('country_id');
            $model->city             = Input::get('city');
            $model->address          = Input::get('address');
            $model->accept_tor       = Input::get('accept_tor');
            $model->model_level_id   = $baseLevelId['id'];
            //képfeltöltés
            
            $image = $this->moveImageToDirectory(Input::file('img_path'));
            
            $model->img_path = $image;                //adatbázisba mentés
            
            $model->save();
            
            return Redirect::to('/model-registration/step2');        
        }
        
        return Redirect::back()
                ->withInput()
                ->withErrors($valid);
    }
    
    
   /**
    * Kép áthelyezése.
    *
    * @param  int  input::file(image)
    * @return string útvonal és file név
    */
    
    public function moveImageToDirectory($inputImage)
    {
        $getSavePath = $this->savePath('/img/models/');
        $savePath = public_path().$getSavePath['savePath'];
        $name = $getSavePath['base'];
            
        $fileExtension = $inputImage->getClientOriginalExtension(); //file kiterjesztésének kinyerése
        $fileName = $name . '.' . $fileExtension;                //file átnevezése 
        $inputImage->move($savePath, $fileName);                    //file áthelyezése
        
        return $getSavePath['savePath'] . $fileName;                //vissza adom az adatbázisba menteni valót
    }

    /**
    * Bináris mappa szerkezet.
    * @param string menteni kívánt könyvtár szerkezet pl.: '/img/models/'
    * @return array user id 0-al feltöltve, valamint az feldarabolva '/'-el
    */
    
    public function savePath($path)
    {
        $basePath = public_path();
        $base = str_pad((string)Auth::user()->user_id, 9, '0', STR_PAD_LEFT);
        $b1 = substr($base, 0, 3);
        $b2 = substr($base, 3, 3);
        $b3 = substr($base, 6, 3);

        if (!file_exists($basePath.'/'.$b1)) {
         mkdir($basePath.'/'.$b1, 0770, true);
        } 
        if (!file_exists($basePath.'/'.$b1."/".$b2)) {
         mkdir($basePath.'/'.$b1."/".$b2, 0770, true);
        }
        if (!file_exists($basePath.$path.$b1."/".$b2."/".$b3)) {
	 mkdir($basePath.$path.$b1."/".$b2."/".$b3, 0770, true);
        }
        
        $savePath = $path.$b1."/".$b2."/".$b3."/";
        
        return [
                 "savePath" => $savePath,       //útvonal mentéshez
                 "base" => $base               //bináris szám (file névhez) user id feltölétve 0-al
                ];
    }
    
    /**
    * Model reg. Step1 form frissítése.
    *
    * @param  int  $id
    * @return Response
    */
    
    public function UpdateModelStep1($id)
    {
        $model = Model::find($id);
        
        $data = Input::all();
        //update esetén más validálási szabály rendszer
        $rules = Model::$step1_update_rules;
        $rules['artist_name']['unique'] = 'unique:models,artist_name,' . $id;
  
        $valid = Validator::make($data, $rules);
        
        if ($valid->passes()) {
            $model->user_id          = Auth::user()->user_id;
            $model->artist_name      = Input::get('artist_name');
            $model->fullname         = Input::get('fullname');
            $model->payout_system_id = Input::get('payout_system_id');
            $model->country_id       = Input::get('country_id');
            $model->city             = Input::get('city');
            $model->address          = Input::get('address');
             
        if(Input::file('img_path'))
        { 
         $filename = substr(strrchr($model->img_path, "/"), 1);  //kep nevének kinyerése   
          
         $fileLocation = $model->img_path;   //file neve útvonallal
         $target = public_path().'/img/model_old/'.$filename; //cél

         File::copy($fileLocation, $target);
         File::delete(public_path().'/img/models/'.$filename);
         
         $image = $this->moveImageToDirectory(Input::file('img_path'));
            
         $model->img_path = $image;      
        }  
            $model->update();
            
            return Redirect::to('/model-registration/step2');  
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
    
   //PAYPAL 
    public $paypalapi;
    
    public function __construct() {
        $this->paypalapi = new PayPalController();
    }
    
    public function postPayment()
    {
       return $this->paypalapi->postPayment(); 
    }
    
    public function getPaymentStatus()
    {
       return $this->paypalapi->getPaymentStatus(); 
    }

    //payout
     public function singlePayout()
    {
       return $this->paypalapi->singlePayout(); 
    }
    
    public function getPayoutStatus()
    {
       return $this->paypalapi->getPayoutStatus(); 
    }
    
    
    /****************************************************************************
     * Új Model regisztrációja step2 form megjelenítése. (index) 
     */
    
    
    public static function getViewDatas2($datas) 
    {
        
        if(!Auth::check()){
           return false;
        }
        
        //$this->paypalapi = new PayPalController();
        
        //amennyiben még mem töltötte ki a step1-et, akkor oda irányítjuk
        $girl = Model::where('user_id','=',Auth::user()->user_id)->first();
        if(is_null($girl)) {
                dd('asa');
                return Redirect::to('/model-registration/step1'); 
        }
        
        $model = Model::join('countries', 'models.country_id', '=', 'countries.country_id')
            ->where('models.user_id','=',Auth::user()->user_id)
            ->whereNotNull('models.introducte')
            ->first();
        
        $datas['helperData']['prices'] = GsMlsGsVc::getVideoCategoryPrice($girl->model_level_id);
       
        if($model){  //  UPDATE
           $datas['view'] = 'helper.modelregistration.step2.update';
           $datas['helperData']['modellVideoPrices'] = ModelGsVc::getModellVideoCategoryPrice($model->id);
        }else{      //  CREATE
            $datas['view'] = 'helper.modelregistration.step2.create';
           
                // lekérdezzük a Model adatait
             $model = Model::join('countries', 'models.country_id', '=', 'countries.country_id')
                 ->where('models.user_id','=',Auth::user()->user_id)->first();
             
        }
        
        //kategória típusok összegyűjtése
        $categoryTypes = ModelCategoryType::getCategoryTypes();
        $datas['helperData']['categoryTypes'] = $categoryTypes; 
        
        //beszélhető nyelvek összegyűjtése
        $languages = GsLanguage::getLanguages($model->id);
        $datas['helperData']['languages'] = $languages; 
        
        //bevállalt videó kategóriák összegyűjtése
        $showCategories = GsVideoCategory::getShowCategories($model->id);
        $datas['helperData']['showCategories'] = $showCategories; 
        
        $datas['helperData']['userModel'] = $model;
        
        $datas['styleCss'] = array();
        $datas['jsLinks'] = array();
        
        $datas['helperDataJson'] = 'helperDataJson';#
        if (!isset($datas['pagetitle'])) {
            $datas['pagetitle'] = "Model Registration";#
        }
      
        return $datas;
    }
    
    
    //ajax hívás ami lekéri a modelhez tartozó videó active_user értékét
    public static function getVideoStatusz() 
    {	
                $data = Input::all();
		//$modelId = $data['modelId'];
		$videoId = $data['videoid'];
                $video = StoragedVideo::find($videoId);
                
                 if($video->active_user == 1){
                    $video->active_user = 0;  
                  }else{
                    $video->active_user = 1;  
                  }
        
                $video->update();
		
		$fleg = $video->active_user;
               
		return $fleg;             
    }
    
     /**
     * Új Model regisztrációja step2. 
     *(Egy már meglévő rekord fríssítése a hiányzó adatokkal)
     * 
     * @return Response
     */
    public function CreateModelStep2()
    {  
        //frissítendő modell rekord lekérése
        $model = Model::where('user_id','=',Auth::user()->user_id)->first();
            
        //típusok lekérése
        $types = ModelCategoryType::getCategoryTypes();
        
        //lekérem a rullokat és hibaüzeneteket
            $categ = ModelModelCategory::getCategoryRules($types); //modell testalkat kategóriák
            $rules = ModelGsVc::getPriceRules(Input::get('gs_video_categories'), $model->model_level_id); // modell videó árak
        
        //validálások
        $valid_categories = Validator::make(Input::all(),$categ['newRules'],$categ['newMessages']);
        $valid_prices     = Validator::make(Input::all(),$rules['newRules'],$rules['newMessages']);
        $valid            = Validator::make(Input::all(),Model::$step2_rules);
        
        if($valid->passes() && $valid_categories->passes() && $valid_prices->passes())
        {
            $model->introducte = Input::get('introducte');
       
             //személyi okmányok feltöltése
            if(Input::file('documents'))
            {   
                $files = Input::file('documents');
                $this->uploadPersonalDocuments($files,$model->id);
            }
            
            $myPublicFolder = public_path();
            
            //introduction video feltöltése
           
            if(Input::file('introduction_video'))
            {    
                $savePath = $myPublicFolder.'/model_videos/model_introduction/';
                $introName = Input::file('introduction_video')->getClientOriginalName(); 
                $introname = Auth::user()->user_id.'.'.$introName; // renameing image
                Input::file('introduction_video')->move($savePath, $introname);
            }
            
            //thank you video feltöltése
            
            if(Input::file('thanks_video'))
            {
                $savePath   = $myPublicFolder.'/model_videos/model_thanks/';
                $thanksName = Input::file('thanks_video')->getClientOriginalName(); 
                $thanksname = Auth::user()->user_id.'.'.$thanksName; // renameing image
                Input::file('thanks_video')->move($savePath, $thanksname);
            }
            
            //step2 save
            $model->update();
            
            //nyelvek mentése modellekhez
                foreach(Input::get('gs_languages') as $id)
                    {
                       $model = new ModelLanguage();

                       $model->model_id = Input::get('model_id');
                       $model->gs_language_id = $id;

                       $model->save();
                    }
                    
            //VIDEÓ kategóriák mentése modellekhez (kapcsolótáblába)
                foreach(Input::get('gs_video_categories') as $id)
                    {
                       $model = new ModelGsVc();

                       $model->model_id = Input::get('model_id');
                       $model->gs_vc_id = $id;
                       
                       $cucc = array();
                       foreach (Input::all() as $key => $input){
                            if(strpos($key,"__") !== false){
                                list($title,$categId) = explode("__", $key); //

                                $cucc[$categId][$title] = $input; //
                            }
                        }

                       foreach($cucc as $categId => $title) {
                           $model->ex_vc_price = $title['ex_vc_price'];
                       }    
                       
                       $model->save();
                    }        
            
            //testalkat kategóriák mentése a modelhez
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
        $errors = $valid_categories->messages()
                    ->merge($valid->messages())
                    ->merge($valid_prices->messages());
        
        return Redirect::back()
                ->withInput()
                ->withErrors($errors);
    }
    
    
    /**
     * Modellek személyes okmányainak feltöltése. 
     *
     */
    public function uploadPersonalDocuments($files,$id)
    {
        $i=1;
        $rules = array(
                  'file' => 'required',
                            'mimes:jpeg,jpg,bmp,png,gif'   
                 );
        
        foreach($files as $file) {
          
          $validator = Validator::make(array('file'=> $file), $rules);
          if($validator->passes()){
           $getSavePath = $this->savePath('/img/models/personal_documents/');
           $savePath = public_path().$getSavePath['savePath'];
           $name = $getSavePath['base'] . '_' . $i;
            
           $fileExtension = $file->getClientOriginalExtension(); //file kiterjesztésének kinyerése
           $fileName = $name . '.' . $fileExtension;                //file átnevezése 
           $file->move($savePath, $fileName);                    //file áthelyezése
         
           $model = new Personaldocument;                      
           $model->model_id = $id;
           $model->path    =  $getSavePath['savePath'] . $fileName;
           $model->save();
           $i++;
          } 
          else {
            return Redirect::back()->withInput()->withErrors($validator);
          }
        } 
    }


    /**
    * Model reg. Step2 form frissítése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function UpdateModelStep2($id)
    {
        //frissítendő modell rekord lekérése
        $model = Model::where('user_id','=',Auth::user()->user_id)->first();
            
        //típusok lekérése
        $types = ModelCategoryType::getCategoryTypes();
        
        //lekérem a rullokat és hibaüzeneteket
            $categ = ModelModelCategory::getCategoryRules($types); //modell testalkat kategóriák
            $rules = ModelGsVc::getPriceRules(Input::get('gs_video_categories'), $model->model_level_id); // modell videó árak
        
        //validálások
        $valid_categories = Validator::make(Input::all(),$categ['newRules'],$categ['newMessages']);
        $valid_prices     = Validator::make(Input::all(),$rules['newRules'],$rules['newMessages']);
        $valid            = Validator::make(Input::all(),Model::$step2_rules);
        
        if ($valid->passes() && $valid_categories->passes() && $valid_prices->passes()) {
            
            $model = Model::findOrFail($id);
            $model->introducte = Input::get('introducte');
            
             //személyi okmányok feltöltése
            if(Input::file('documents'))
            {   
                $files = Input::file('documents');
                $this->uploadPersonalDocuments($files,$model->id);
            }
            
            $model->update();
            
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
                
                //videó kategóriák és hozzájuk az összegek frissítése modelhez
                $old = ModelGsVc::where('model_id', '=', $id)
                        ->get(array('gs_vc_id'))
                        ->toArray();

                $dbCategories = array();

                foreach($old as $tmp) {
                    $dbCategories[] = $tmp['gs_vc_id'];
                }

                foreach($dbCategories as $item){
                    ModelGsVc::where('gs_vc_id', '=', $item)->delete();
                }
                
                //szétszedem a checkboxokhoz tartozó input tartalmakat
                 $cucc = array();
                   foreach (Input::all() as $key => $input){
                        if(strpos($key,"__") !== false){
                            list($title, $categId) = explode("__", $key); //

                            $cucc[$categId][$title] = $input; //
                        }
                    }    
                    
                foreach(Input::get('gs_video_categories') as $inputId)
                    {
                       $model = new ModelGsVc();

                       $model->model_id = Input::get('model_id');
                       $model->gs_vc_id = $inputId;
                       
                       foreach($cucc as $categId => $title) {
                           if($categId == $inputId){
                                $model->ex_vc_price = $title['ex_vc_price'];
                           }
                       }    
                       
                       $model->save();
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
            
            return Redirect::to('/');    
        }
       
        //user step 2 form validáló és a kategória validáló egybe vonása 
        $errors = $valid_categories->messages()
                    ->merge($valid->messages())
                    ->merge($valid_prices->messages());
        
        return Redirect::back()
                ->withInput()
                ->withErrors($errors);
    }
    
    /***************************************************************************
     * Admin oldal
     */
   
    public static function getAdminDatas($datas) 
    {
        $datas['helperDataJson'] = 'helperDataJson';
        $datas['view'] = 'helper.admin.'.'models'.'.list';  

        $datas['styleCss'] = array();

        $datas['jsLinks'] = array();

        // Update ezen keresztül megy
        if (isset($datas['id'])) {
            return self::subFunc($datas);
        }
        
        //aktív modellek
        $active = 2;  //0 = inaktv, 1=aktív,2=all
        if (isset($_GET['active'])) {
                $active = $_GET['active'];
                $datas['helperData']['active'] = $_GET['active'];
        } else {
                $datas['helperData']['active'] = 2;
        }
        
        //validált modellek (adatai átnézve)
        $validated = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($_GET['validated'])) {
                $validated = $_GET['validated'];
                $datas['helperData']['validated'] = $_GET['validated'];
        } else {
                $datas['helperData']['validated'] = 2;
        }
        
        //országra való szűrés
        $country = 0; //összes
        if (isset($_GET['country'])) {
                $country = $_GET['country'];
                $datas['helperData']['country'] = $_GET['country'];
        } else {
                $datas['helperData']['country'] = 0;
        }
        $datas['helperData']['countryList'] = Country::get(array('country_id', 'country_name'))->toArray();
        
        //modell szintekre (level) szűrés
        $level = 0; //összes
        if (isset($_GET['level'])) {
                $level = $_GET['level'];
                $datas['helperData']['level'] = $_GET['level'];
        } else {
                $datas['helperData']['level'] = 0;
        }
        $datas['helperData']['levelList'] = GsModellLevel::get(array('id', 'title'))->toArray();
        
        //manuálisan lett - e beállítva a modell szint 
        $autoLevel = 2;  //0 = nem, 1=igen, 2=all
        if (isset($_GET['auto_level'])) {
                $autoLevel = $_GET['auto_level'];
                $datas['helperData']['autoLevel'] = $_GET['auto_level'];
        } else {
                $datas['helperData']['autoLevel'] = 2;
        }
        
        //fizetési módra való szűrés
        $payout = 0; //összes
        if (isset($_GET['payout'])) {
                $payout = $_GET['payout'];
                $datas['helperData']['payout'] = $_GET['payout'];
        } else {
                $datas['helperData']['payout'] = 0;
        }
        $datas['helperData']['payoutList'] = PayPutSystem::get(array('pos_id', 'pos_title'))->toArray();
      
        //szerződési feltételeket elfogadta e 
        $accept_tor = 2;  //0 = nem, 1=igen, 2=all
        if (isset($_GET['accept_tor'])) {
                $accept_tor = $_GET['accept_tor'];
                $datas['helperData']['accept_tor'] = $_GET['accept_tor'];
        } else {
                $datas['helperData']['accept_tor'] = 2;
        }

        //lapozó
        $limit = 20;
        if (isset($_GET['limit'])) {
                $limit = $_GET['limit'];
                $datas['helperData']['limit'] = $_GET['limit'];
        } else {
                $datas['helperData']['limit'] = 20;
        }

        $page = 1;
        if (isset($_GET['page'])) {
                $page = $_GET['page'];
                $datas['helperData']['page'] = $_GET['page'];
        } else {
                $datas['helperData']['page'] = 1;
        }
        //lekérdezés az összegyűjtött adatokból
        $idDatas = Model::getModelToAdminList($active, $validated, $country, $payout, $autoLevel, $level, $accept_tor, $limit, $page);
        $datas['helperData']['models'] = $idDatas['models'];
        $datas['helperData']['needPager'] = $idDatas['count']/$limit > 1 ? 1 : 0;
        $datas['helperData']['pagerOptions'] = ceil($idDatas['count']/$limit);
        $datas['helperData']['modelsCount'] = $idDatas['count'];

        return $datas;
    }
    
    
    //létrehozó, módosító lapokat hozza be
    protected static function subFunc($datas) 
    {          
		$datas['view'] = 'helper.admin.models.modify';
		$model = Model::find($datas['id']);
                $model_documents = Personaldocument::where('model_id','=',$datas['id'])->get();
		$datas['helperData']['model'] = $model;
                $datas['helperData']['model_documents'] = $model_documents;
                
		return $datas;
                
    }
        
    
    /**
    * Admin - model adatok frissítése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function adminUpdateModel($id)
    {
          $model = Model::find($id);
          $data = Input::all();
          $rules = Model::$admin_rules;
          $rules['artist_name']['unique'] = 'unique:models,artist_name,' . $id;
          $valid = Validator::make($data, $rules);
        
        if ($valid->passes()) {
            
            $model->user_id          = $model->user_id;
            $model->artist_name      = Input::get('artist_name');
            $model->fullname         = Input::get('fullname');
            $model->payout_system_id = Input::get('payout_system_id');
            $model->country_id       = Input::get('country_id');
            $model->city             = Input::get('city');
            $model->address          = Input::get('address');
            $model->introducte       = Input::get('introducte');
            $model->active           = (Input::get('active')== 1) ? 1 : 0;
            $model->validated        = (Input::get('validated')== 1) ? 1 : 0;
            $model->accept_tor       = (Input::get('accept_tor')== 1) ? 1 : 0;
//            $model->model_level_id   = (Input::get('model_level_id') != 0) ? Input::get('model_level_id') : 0;
//            $model->is_manual        = (Input::get('model_level_id') != 0) ? Input::get('model_level_id') : 0;
              //személyi okmányok feltöltése
//            if(Input::file('documents'))
//            {   
//                $files = Input::file('documents');
//                $this->uploadPersonalDocuments($files);
//            }
            
            $model->update();
            if(Input::get('model_level_id')!=0){
                $model->setManualLevel(TRUE, Input::get('model_level_id'));    
            }else{
                $model->setManualLevel();    
            }
            
            return Redirect::to('/administrator');    
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
   /**
    * Admin - languages adatok kezelése.
    * 
    * @param  int  $datas
    * @return datas
    */
    public static function languagesIndex($datas) 
    {
        $datas['helperDataJson'] = 'helperDataJson';
        $datas['view'] = 'helper.admin.models.languages.list';  

        $datas['styleCss'] = array();

        $datas['jsLinks'] = array();

        // Update ezen keresztül megy
        if (isset($datas['id'])) {
            return self::editLanguage($datas);
        }
        
        $languages = GsLanguage::orderBy('id', 'DESC')->paginate(3);
        $datas['helperData']['languages'] = $languages;
        
        return $datas;
    }
    
    //létrehozó, módosító lapokat hozza be
    protected static function editLanguage($datas) 
    {          
	 // CREATE CATEGORY FORM HA A KAPOTT ID 0
        
        if ($datas['id'] != 0){       //UPDATE
            $datas['view'] = 'helper.admin.models.languages.edit';
            $language = GsLanguage::find($datas['id']);
            $datas['helperData']['language'] = $language;
        } else {                      //CREATE
            $datas['view'] = 'helper.admin.models.languages.create';   
        }
        
        return $datas;              
    }
   
     /**
     * Új Nyelv mentése.
     *
     * @return Response
     */
    public function saveLanguage()
    {  
      $valid = Validator::make($data = Input::all(), GsLanguage::$rules);
      
      if($valid->passes()){
           $model = new GsLanguage;
           $model->sort    = Input::get('sort');
           $model->name    = Input::get('name');
           $model->active  = (Input::get('active')== 1) ? 1 : 0;
           
           $model->save();

           return Redirect::route('/administrator/modelslanguages');   
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
   /**
    * Admin - nyelv frissítése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function updateLanguage($id)
    {
      
      $data = Input::all();
      $rules = GsLanguage::$rules;
      $valid = Validator::make($data, $rules);
        
        if ($valid->passes()) {
            $model = GsLanguage::find($id);
            $model->sort    = Input::get('sort');
            $model->name    = Input::get('name');
            $model->active  = (Input::get('active')== 1) ? 1 : 0;
            
            $model->update();

            return Redirect::route('/administrator/modelslanguages');    
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }

    /**
     * Státusz módosítására szolgáló metódus.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateStatusz($id) 
    {
        $model = GsLanguage::find($id);
        
        if($model->active == 1){
          $model->active = 0;  
        }else{
          $model->active = 1;  
        }
        
        $model->update();

        return Redirect::route('/administrator/modelslanguages');
    }
    
    
    
   /************************************Model Levels**********************/ 
   
    
    
  /**
    * Admin - modell szintek adatok kezelése.
    * 
    * @param  int  $datas
    * @return datas
    */
    public static function levelsIndex($datas) 
    {
        $datas['helperDataJson'] = 'helperDataJson';
        $datas['view'] = 'helper.admin.models.levels.list';  

        $datas['styleCss'] = array();

        $datas['jsLinks'] = array();

        // Update ezen keresztül megy
        if (isset($datas['id'])) {
            return self::editCreateLevels($datas);
        }
        
        $levels = GsModellLevel::orderBy('id', 'DESC')->paginate(3);
        $datas['helperData']['levels'] = $levels;
        
        return $datas;
    }
    
    //létrehozó, módosító lapokat hozza be
    protected static function editCreateLevels($datas) 
    {          
	 // CREATE LEVEL FORM HA A KAPOTT ID 0
        
        if ($datas['id'] != 0){       //UPDATE
            $datas['view'] = 'helper.admin.models.levels.edit';
            $level = GsModellLevel::find($datas['id']);
            $datas['helperData']['level'] = $level;
        } else {                      //CREATE
            $datas['view'] = 'helper.admin.models.levels.create';   
        }
        
        return $datas;              
    }
   
     /**
     * Új Szint mentése.
     *
     * @return Response
     */
    public function saveLevel()
    {  
      $valid = Validator::make($data = Input::all(), GsModellLevel::$rules);
      
      if($valid->passes()){
           $model                     = new GsModellLevel();
           $model->title              = Input::get('title');
           $model->min_view           = Input::get('min_view');
           $model->min_view_p_week    = Input::get('min_view_p_week');
           $model->min_rating         = Input::get('min_rating');
           $model->max_video_p_day    = Input::get('max_video_p_day');
           $model->pos                = Input::get('pos');
           
           $model->save();

           return Redirect::route('/administrator/model_levels');   
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
   /**
    * Admin - szint frissítése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function updateLevel($id)
    {
      
      $data = Input::all();
      $rules = GsModellLevel::$rules;
      $valid = Validator::make($data, $rules);
        
        if ($valid->passes()) {
            $model = GsModellLevel::find($id);
            $model->title              = Input::get('title');
            $model->min_view           = Input::get('min_view');
            $model->min_view_p_week    = Input::get('min_view_p_week');
            $model->min_rating         = Input::get('min_rating');
            $model->max_video_p_day    = Input::get('max_video_p_day');
            $model->pos                = Input::get('pos');
            
            $model->update();

            return Redirect::route('/administrator/model_levels');    
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
}