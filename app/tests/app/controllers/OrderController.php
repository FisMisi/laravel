<?php

/**
 * Rendelések
 * 
 */

class OrderController extends BaseController 
{
    
    /**
    * vezérlő public statikus metódus
    *
    * @param  array  datas modell id
    * @return array  datas
    */ 
    public static function publicIndex($datas) 
    {
        $datas['view'] = 'helper.orders.create';
        $datas['styleCss'] = array();
        $datas['jsLinks'] = array();
        $datas['helperDataJson'] = 'helperDataJson';
        
        //modell
        $modell = Model::where('id', '=', $datas['id'])->first();
        
        //ha nincs bejelentkezve a User, vagy nem érkezik model id, vagy nem
        // egy létező modell id érkezik
        if(!Auth::check() || !isset($datas['id']) || empty($modell)){
           return false;
        }
        
        //modell adatok
        $datas['helperData']['modell'] = $modell;
        
        // bizonyos adatok megléte kötelező a user táblában
        $errors = User::canOrder(Auth::user()->user_id);
        
        // ha nincs meg minden szükséges adat, akkor profil
        if(!empty($errors)){
            $datas['view'] = 'helper.orders.pleaseFill';
            $datas['helperData']['errors'] = $errors;
            
            return $datas;
        }
        
        // pl.: http://mediastream-test.ibase/models?gs=WzRd
        $datas['helperData']['categId'] = null;  //videó kategória id
         if (isset($_GET['gs'])) {
                $datas['helperData']['categId'] = $_GET['gs'];
                
        }
        
        //rendelő user 
        $datas['helperData']['user'] = User::where('user_id', '=', Auth::user()->user_id)->first();
        
        
        //modell által vállalt videó kategóriák
        $categs = GsVideoCategory::getVideoCategoriesPublic($datas['id']);
        $datas['helperData']['gsVideoCategories'] = $categs;
        
        return $datas;
    }
    
//    public function getModelCategoryToList() {
//        $data = Input::all();
//        $gs = $data['gs'];
//        $ms = id_null($data = Input::all()) ? null : json_decode(base64_decode($data['ms']));
//        
//        
//    }
    
     /**
    * Public - rendelés mentése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function save()
    {  
        
        $data = Input::all();
        $rules = Orders::$rules;
        $valid = Validator::make($data, $rules);
        
       
        if ($valid->passes()) {
            dd($data);
            $newOrder = new Orders();
            
            $newOrder->model_id = $data['model_id'];
            
            return Redirect::to('/models');    
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
    /**************/
    
    //rendelések exportálása
    public static function export() 
    { 
        $params = self::getParams(Input::all(),$datas=null);
        $is_export = true;    
        $query = Orders::getOrderToAdminList(null,$params["storaged_video_id"],$params["is_rejected"],
                            $params["is_said_back"], $params["is_inactive"], 
                            $params["send_date"],$params["limit"],
                            $params["order"],$params["ordered"],$is_export);
        
        #dd($query['orders'][0]);
        
        $count = $query['count'];
        $page = ceil($count/200);
        $basePath = storage_path();
        if (!file_exists($basePath.'/orderexport')) {
                mkdir($basePath.'/orderexport', 0770, true);
        }
        $path = $basePath.'/orderexport/';
        $file = "orders".date("Y_m_d_h_i_s").".csv";
        $del = ',';
        $newRow = "\n";
        
        $exportData = '';
        //első sor
        
        $exportData.= 'ORDER ID'.$del.
                      'CUSTOMER NAME'.$del.
                      'CUSTOMER EMAIL'.$del.
                      'ARTIST NAME'.$del.
                      'VIDEO TYPE'.$del.
                      'VIDEO CATEGORY'.$del.
                      'REJECTED USER'.$del.
                      'REJECTED MODELL'.$del.
                      'REJECTED ADMIN'.$del.
                      'SEND DATE'.$del.
                      'VIDEO STATUSZ'.$del.
                      'STATUSZ'.$del.
                      'NETTO'.$del.
                      'BRUTTO'.$del.
                      'TOTAL TAX'.$del.
                      'COUNTRY'.$del.
                      'ZIP CODE'.$del.
                      'STATE'.$del.
                      'CITY'.$del.
                      'ADDRESS'.$del.
                      'CONNECT TO SERVER CODE'.$del.
                      'PAYMENT SYSTEM'.$del.
                      'CURRENCY'.$del.
                      'TRANSACTION STATUS'.$del.
                      'ANUM CODE'.$del.
                      'TRANSACTION RESULT CODE'.$del.
                      'CREATED'.$del.
                      'UPDATED'.$del.
                      $newRow;
        
        for($p = 0;$p < $page; $p++) {
                #file_put_contents($basePath.'/orderexport/rows.txt', $p);
                $idDatas = $query['orders'];
                foreach($idDatas as $row) {
                        $exportData.= $row['orderId'].$del
                                     .$row['first_name'].' '.$row['last_name'].$del
                                     .$row['userEmail'].$del
                                     .$row['artistName'].$del
                                     .$row['typeTitle'].$del
                                     .$row['categoryTitle'].$del
                                     .($row['is_said_back'] == 1 ? 'Yes' : 'No').$del
                                     .($row['is_rejected'] == 1 ? 'Yes' : 'No').$del
                                     .($row['is_inactive'] == 1 ? 'Yes' : 'No').$del
                                     .$row['sendDate'].$del
                                     .(!is_null($row['videoId']) ? 'Complated' : 'Not Finished').$del
                                     .$row['orderStatusz'].$del
                                     .$row['base_total_price'].$del
                                     .($row['base_total_price']+$row['total_tax']).$del
                                     .$row['total_tax'].$del
                                     .$row['country'].$del
                                     .$row['zip_code'].$del
                                     .$row['state'].$del
                                     .$row['city'].$del
                                     .$row['address'].$del
                                     .$row['trid'].$del
                                     .$row['payment_system'].$del
                                     .$row['currency'].$del
                                     .$row['transactionStatus'].$del
                                     .$row['anum'].$del
                                     .$row['result_code'].$del
                                     .$row['created'].$del
                                     .$row['updated'].$del
                                     .$newRow;
                }
                file_put_contents($path.$file, $exportData, FILE_APPEND | LOCK_EX);
                $exportData = '';
        }
        if (file_exists($basePath.'/orderexport/'.$file)) {
            return Response::download($path.$file, $file);
        } else {
            return Redirect::to('/administrator/orders');
        }
        
    }
    
     /**
     * Admin orders oldal vezérlője 
     */
   
    public static function getOrderDatas($datas) 
    {
        $datas['helperDataJson'] = 'helperDataJson';
        $datas['view'] = 'helper.admin.order.list';  

        $datas['styleCss'] = array();

        $datas['jsLinks'] = array();
        
        $params = self::getParams(Input::all(),$datas);
        $datas = $params['datas'];
        
        // Update ezen keresztül megy
        if (isset($datas['id'])) {
            return self::editOrder($datas);
        }
        
        $orders = Orders::getOrderToAdminList(null,$params["storaged_video_id"],$params["is_rejected"],
                            $params["is_said_back"], $params["is_inactive"], 
                            $params["send_date"],$params["limit"],
                            $params["order"],$params["ordered"]);
       
        $datas['helperData']['orders'] = $orders['orders'];
        
        return $datas;
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
        //rendelés teljesítve
        
        $storaged_video_id = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($get['storaged_video_id'])) {
                $storaged_video_id = $get['storaged_video_id'];
                $datas['helperData']['storaged_video_id'] = $get['storaged_video_id'];
        } else {
                $datas['helperData']['storaged_video_id'] = 2;
        }
        
        //modell által visszamondott
        $is_rejected = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($get['is_rejected'])) {
                $is_rejected = $get['is_rejected'];
                $datas['helperData']['is_rejected'] = $get['is_rejected'];
        } else {
                $datas['helperData']['is_rejected'] = 2;
        }
        
        //user által visszamondott
        $is_said_back = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($get['is_said_back'])) {
                $is_said_back = $get['is_said_back'];
                $datas['helperData']['is_said_back'] = $get['is_said_back'];
        } else {
                $datas['helperData']['is_said_back'] = 2;
        }
        
        //admin által visszamondott
        $is_inactive = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($get['is_inactive'])) {
                $is_inactive = $get['is_inactive'];
                $datas['helperData']['is_inactive'] = $get['is_inactive'];
        } else {
                $datas['helperData']['is_inactive'] = 2;
        }
        
        //lejárati dátum szerinti szűrés
        
        $send_date = 2;  //0 = nem, 1=igen, 2=egyik sem
        if (isset($get['send_date'])) {
                $send_date = $get['send_date'];
                $datas['helperData']['send_date'] = $get['send_date'];
        } else {
                $datas['helperData']['send_date'] = 2;
        }

        //lapozó limit
        $limit = 10;
        if (isset($get['limit'])) {
                $limit = $get['limit'];
                $datas['helperData']['limit'] = $get['limit'];
        } else {
                $datas['helperData']['limit'] = 10;
        }
        
        //rendezés
        $order = 0;  // 0=rendelés dátuma, 1=elkészítés dátuma
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
            "storaged_video_id"  => $storaged_video_id,
            "is_rejected"        => $is_rejected,
            "is_said_back"       => $is_said_back,
            "is_inactive"        => $is_inactive,
            "send_date"          => $send_date,
            "limit"              => $limit,
            "order"              => $order,
            "ordered"            => $ordered,
        ];
    }
    
    /**
     * View 
     * @param type $datas
     * @return type obj
     */
    protected static function editOrder($datas) 
    {          
	
        if ($datas['id'] != 0){       //UPDATE
            $datas['view'] = 'helper.admin.order.edit';
            $order = Orders::getOrderToAdminList($datas['id']);
            $datas['helperData']['order'] = $order[0];    
        } 
        
        return $datas;              
    }
    
    
    /**
    * Admin - rendelés adatok frissítése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function update($id)
    {  
        $data = Input::all();
        $rules = Orders::$adminRules;
        $valid = Validator::make($data, $rules);
        $model = Orders::find($id);
       
        if ($valid->passes()) {
            $model->is_inactive  = (Input::get('is_inactive')== 1) ? 1 : 0;
            $model->inactive_reason  = (Input::get('inactive_reason')!='') ? Input::get('inactive_reason') : null;
            
            $model->update();
            
            return Redirect::to('/administrator/orders');    
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
    
    
     
}
