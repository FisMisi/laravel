<?php

class ModelCategoryHelper extends BaseController 
{ 

      /**
     * Admin oldalon - Modellek (fő)kategóriáit megjelenítendő metódus
     */
   
    public static function getAdminDatas($datas) 
    {
        $datas['helperDataJson'] = 'helperDataJson';  
        
        $datas['styleCss'] = array();

        $datas['jsLinks'] = array();
        
         //lapozó
        $active = 2;
        if (isset($_GET['active'])) {
                $active = $_GET['active'];
                $datas['helperData']['active'] = $_GET['active'];
        } else {
                $datas['helperData']['active'] = 2;
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
        
        if (isset($datas['id']) && !isset($datas['type_id'])){       //főkategóriához tartozó alkategóriák listázása
            $datas['helperData']['categoryTypeId'] = $datas['id'];
            $datas['view'] = 'helper.admin.modelcategories.categories.list'; 
            $categoryType = $datas['id'];
            
            $idDatas = ModelCategory::getCategoriesToList($active, $categoryType,$limit, $page);
            $datas['helperData']['categoriesList'] =  $idDatas['categories'];
        }else
        {                               //főkategóriák listázása
            $datas['view'] = 'helper.admin.modelcategories.categoryTypes.list';
            $idDatas =  ModelCategoryType::getCategoryTypeList($active, $limit, $page);
            $datas['helperData']['categoriesList'] =  $idDatas['categories'];
        }
        
        //alkategória create, update
        
        if (isset($datas['type_id'])) 
        {       
           return self::editCategory($datas);  
        }
 
        $categoryTypes = ModelCategoryType::all();
        $datas['helperData']['categoryTypes'] = $categoryTypes;
        //Lapozás
        $datas['helperData']['needPager'] = $idDatas['count']/$limit > 1 ? 1 : 0;
        $datas['helperData']['pagerOptions'] = ceil($idDatas['count']/$limit);
        $datas['helperData']['categoriesCount'] = $idDatas['count'];

        return $datas;

    }
    
     /**
     * Új Kategória mentése.
     *
     * @return Response
     */
    public function saveCategory()
    { 
      
      $valid = Validator::make($data = Input::all(), ModelCategory::$rules);
      
      if($valid->passes()){
           $model = new ModelCategory;
           $model->name    = Input::get('name');
           $model->title   = Input::get('title');
           $model->active  = (Input::get('active')== 1) ? 1 : 0;
           $model->type_id = Input::get('type_id');
           $model->pos     = (Input::get('pos')) ? Input::get('pos') : null;
           
           $model->save();

           return Redirect::route('/administrator/model_categories/{id}',array('id'=>$model->type_id));   
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
    
    //létrehozó, módosító lapokat hozza be
    protected static function editCategory($datas) 
    {          
	 // CREATE CATEGORY FORM HA A KAPOTT ID 0
        
        if ($datas['id'] != 0){       //UPDATE
            $datas['view'] = 'helper.admin.modelcategories.categories.edit';
            $datas['helperData']['category'] = ModelCategory::find($datas['id']);
        } else {                      //CREATE
            $datas['view'] = 'helper.admin.modelcategories.categories.create';   
        }
        
        $datas['helperData']['categoryType'] = ModelCategoryType::find($datas['type_id']);
        
        return $datas;              
    }
    
    /**
    * Admin - model (al)kategória típus frissítése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function updateCategory($id)
    {
      $model = ModelCategory::find($id);
      $data = Input::all();
      $rules = ModelCategory::$rules;
      $rules['name']['unique'] = 'unique:model_categories,name,' . $id;
      $valid = Validator::make($data, $rules);
        
        if ($valid->passes()) {
            $model->name          = Input::get('name');
            $model->title         = Input::get('title');
            $model->active        = (Input::get('active')== 1) ? 1 : 0;
            $model->pos           = (Input::get('pos')) ? Input::get('pos') : $model->pos;
            
            $model->update();

            return Redirect::route('/administrator/model_categories/{id}',array('id'=>$model->type_id));    
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
     /**
     * Catgeoria Státusz módosítására szolgáló metódus.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateCatStatusz($id) 
    {
        $model = ModelCategory::find($id);
        
        if($model->active == 1){
          $model->active = 0;  
        }else{
          $model->active = 1;  
        }
        
        $model->update();

        return Redirect::route('/administrator/model_categories/{id}',array('id'=>$model->type_id));
    }
    
    
    
    ##############  Modell (allkategória)kategóriákat kezelő metódusok  ############### 
    
    
     /**
     * Admin oldalon Modellek kategória típusait megjelenítendő metódus
     */
   
    public static function getAdminDatasType($datas) 
    {
        $datas['helperDataJson'] = 'helperDataJson';
       
        $datas['styleCss'] = array();

        $datas['jsLinks'] = array();
        
//         Update ezen keresztül megy
        if (isset($datas['id'])) {
            $datas['helperData']['categoryTypeId'] = $datas['id'];
            return self::editCategoryType($datas);
        }
        
        //kategória típusra való szűrés
        $categoryType = 0; //összes
        if (isset($_GET['id'])) {
           $categoryType = $_GET['id'];
           $datas['helperData']['categoryType'] = $_GET['id'];
        } else {
           $datas['helperData']['categoryType'] = 0;
        }
        
          //lapozó
        $active = 2;
        if (isset($_GET['active'])) {
                $active = $_GET['active'];
                $datas['helperData']['active'] = $_GET['active'];
        } else {
                $datas['helperData']['active'] = 2;
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
        
        
        $categoryTypes = ModelCategoryType::all();
        $datas['helperData']['categoryTypes'] = $categoryTypes;
        
        $idDatas =  ModelCategoryType::getCategoryTypeList($active, $limit, $page);
        $datas['helperData']['categories'] =  $idDatas['categories'];
        
        //Lapozás
        $datas['helperData']['needPager'] = $idDatas['count']/$limit > 1 ? 1 : 0;
        $datas['helperData']['pagerOptions'] = ceil($idDatas['count']/$limit);
        $datas['helperData']['categoriesCount'] = $idDatas['count'];

        return $datas;
    }
  
    
     //módosító lapot hozza be
    protected static function editCategoryType($datas) 
    {       
        // CREATE CATEGORY TYPE FORM HA A KAPOTT ID 0
        if ($datas['id'] != 0){
            $datas['view'] = 'helper.admin.modelcategories.categoryTypes.edit';
            $category = ModelCategoryType::find($datas['id']);
     
            $datas['helperData']['categoryType'] = $category;
        } else {
            
            $datas['view'] = 'helper.admin.modelcategories.categoryTypes.create';   
        }

        return $datas;
                
    }
   
    
     /**
     * Új Kategória Típus mentése.
     *
     * @return Response
     */
    public function saveCategoryType()
    { 
      
      $valid = Validator::make($data = Input::all(), ModelCategoryType::$rules);
      
      if($valid->passes()){
           $model = new ModelCategoryType;
           $model->name    = Input::get('name');
           $model->title   = Input::get('title');
           $model->active  = (Input::get('active')== 1) ? 1 : 0;
           $model->pos     = (Input::get('pos')) ? Input::get('pos') : null;
           $model->multi   = (Input::get('multi')== 1) ? 1 : 0;
           
           $model->save();

            return Redirect::to('/administrator/model_categories');    
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
    /**
    * Admin - model kategória (főtípus) típus frissítése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function updateCategoryType($id)
    {
      $model = ModelCategoryType::find($id);
      $data = Input::all();
      $rules = ModelCategoryType::$rules;
      $rules['name']['unique'] = 'unique:model_category_types,name,' . $id;
      $valid = Validator::make($data, $rules);
        
        if ($valid->passes()) {
            $model->name          = Input::get('name');
            $model->title         = Input::get('title');
            $model->active        = (Input::get('active')== 1) ? 1 : 0;
            $model->pos           = (Input::get('pos')) ? Input::get('pos') : $model->pos;
            $model->multi         = (Input::get('active')== 1) ? 1 : 0;
            
            $model->update();

            return Redirect::to('/administrator/model_categories');    
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
    /**
     * Főkategóra Státusz módosítására szolgáló metódus.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateStatusz($id) 
    {
        $model = ModelCategoryType::find($id);
        
        if($model->active == 1){
          $model->active = 0;  
        }else{
          $model->active = 1;  
        }
        
        $model->update();

        return Redirect::route('/administrator/model_categories');
    }
   
}
