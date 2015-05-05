<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Orders extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'orders';
	protected $primaryKey = 'id';
        
        public static $rules = [
            'model_id'            => 'required|integer',
            'user_id'             => 'required|integer', //megrendelő
         //   'send_date'           => 'required',  //mikor kell kiküldeni
            'message1'            => 'string',  //üzenet a célszemélynek, akinek rendeltük
            'message2'            => 'string',  //üzenet a modelnek, instrukciók
         //   'storaged_video_type' => 'required|integre',
         //   'storaged_video_id'   => 'required|integre',
         //   'is_said_back'      => 'required|integer', //vissza mondta-e a megrendelést a user
            'said_back_time'    => 'date',      //ennek ideje
        //    'is_rejected'       => 'required', //visszautasította a megrendelést a modell
            'rejected_time'     => 'date',     //ennek ideje
            'rejected_reason'   => 'string',   //visszautasítás indoka
        //    'is_inactive'       => 'required', //admin felületről a megrendelést valamiért megszüntetjük, pl.: kiderül a modelről, hogy nincs 18
            'inactive_reason'   => 'string',   //indok
            'inactive_user'     => 'integer',  //aki vissza mondta admin, annak a user_id-je
            'inactive_time'     => 'date',     //ennek ideje
        //    'status'            => 'required|integer', // státusz kódok, kezdésnél:0
            'from_name'         => 'required', //ha akar más néven küldi
            'from_email'        => 'required', //ha akar más email címről
            'to_name'           => 'required', //kinek
            'to_email'          => 'required', //ki emailjére   
            'gs_video_category_id' => 'required', //videó kategória id amit kiválaszt
        ];
        
        //admin valid szabályok
         public static $adminRules = [
            'is_inactive'       => 'required', //admin felületről a megrendelést valamiért megszüntetjük, pl.: kiderül a modelről, hogy nincs 18
          //  'inactive_reason'   => 'string:300',   //indok
         ];
        
        //megkjelenítendő mezők összegyűjtése
        public static $getArray = array(
                          'orders.id as orderId',
                          'orders.send_date as sendDate',
                          'orders.model_id as modelId',
                          'orders.message1 as message1',
                          'orders.message2 as message2',
                          'orders.is_said_back',
                          'orders.said_back_time as backTime',
                          'orders.is_rejected',
                          'orders.rejected_time as rejectedTime',
                          'orders.rejected_reason as reason',
                          'orders.is_inactive',
                          'orders.inactive_reason',
                          'orders.status as orderStatusz',
                          'orders.from_name as fromName',
                          'orders.from_email as fromEmail',
                          'orders.to_name as toName',
                          'orders.to_email as toEmail',
                          'orders.storaged_video_id as videoId',
                          'orders.created_at as created',
                          'orders.updated_at as updated',
                          
                          'models.artist_name as artistName',
                          'users.email as userEmail',
                          'gs_video_categories.title as categoryTitle',
                        );
        
        public static $getExportArray = array(
                          'transaction_items.base_total_price', 
                          'transaction_items.total_tax', 
                          'transactions.trid', 
                          'transactions.payment_system', 
                          'transactions.currency', 
                          'transactions.status as transactionStatus', 
                          'transactions.anum', 
                          'transactions.result_code', 
                          'billing_addresses.country', 
                          'billing_addresses.zip_code', 
                          'billing_addresses.state',  
                          'billing_addresses.city', 
                          'billing_addresses.address', 
                          'orders.id as orderId', 
                          'orders.is_said_back', 
                          'orders.is_rejected', 
                          'orders.is_inactive', 
                          'orders.send_date as sendDate',
                          'orders.status as orderStatusz', 
                          'orders.storaged_video_id as videoId', 
                          'orders.created_at as created', 
                          'orders.updated_at as updated', 
                          'users.email as userEmail', 
                          'users.first_name', 
                          'users.last_name',  
                          'models.artist_name as artistName', 
                          'storaged_video_types.title as typeTitle',
                          'gs_video_categories.title as categoryTitle', 
                          
                        );
        
   /**
    * Admin oldalon a rendelés/ek megjelenítéséhez szükséges szűrési feltételek összegyűjtése.
    * 
    * @param  int  $limit, $page
    * @return array $ret
    */   
    public static function getOrderToAdminList($orderId = null,$storaged_video_id,
                                               $is_rejected, $is_said_back, $is_inactive, $send_date,
                                               $limit,$order,$ordered,$is_export = false) 
    {
       $ret = []; 
        
       $query = self::join('models', 'models.id', '=', 'orders.model_id')
                     ->join('users', 'users.user_id', '=', 'orders.user_id')
                     ->join('storaged_video_types', 'storaged_video_types.id', '=', 'orders.storaged_video_type')
                     ->leftJoin('storaged_videos', 'storaged_videos.id', '=', 'orders.storaged_video_id')
                     ->leftJoin('gs_video_categories', 'storaged_videos.gs_vc_id', '=', 'gs_video_categories.id');
       
       if($is_export == true){
          $query->leftJoin('transaction_items', 'transaction_items.type_element_id', '=', 'orders.id');
          $query->leftJoin('transactions', 'transactions.id', '=', 'transaction_items.transaction_id');
          $query->leftJoin('billing_addresses', 'billing_addresses.id', '=', 'transactions.billing_address_id');
          $query->whereRaw("transaction_items.item_type = 'orders' OR transaction_items.item_type is NULL");
       }
       
        //View-nál
        if($orderId != null){
          $query->where('orders.id', '=', $orderId);
          
          $order = $query->get(self::$getArray);
          
          return $order;
        }
       
        //elkészült-e
        if ($storaged_video_id != 2) {
             if($storaged_video_id == 1){
                $query->whereNotNull('orders.storaged_video_id');  
             }else{
                $query->whereNull('orders.storaged_video_id');
             }        
        }
        
        //visszavonva modell által?
        if ($is_rejected != 2) {
            $query->where('orders.is_rejected', '=', $is_rejected);
        }
        
         //visszavonva user által?
        if ($is_said_back != 2) {
           
            $query->where('orders.is_said_back', '=', $is_said_back);
        }
        
         //visszavonva admin által?
        if ($is_inactive != 2) {
            $query->where('orders.is_inactive', '=', $is_inactive);
        }
        
        //beküldési hatridőre szűrés  0=élő 1 lejárt
        if ($send_date != 2) {
            $dt = new DateTime;
            $timestamp = $dt->format('Y-m-d H:i:s');
            
            if($send_date == 1){   //lejárt
              $query->where('orders.send_date', '<', $timestamp);
            }else{                          //nem járt le
              $query->where('orders.send_date', '>', $timestamp);
            }
                
        }
        
        // mi alapján rendezzük  0=rendelés dátuma, 1=elkészítés dátuma
        if ($order != 0) {
            $order = 'orders.send_date';
        }else{
            $order = 'orders.created_at';
        }
        
        //csökkenő vagy növekvő
        if ($ordered != 0) {
            $ordered = 'desc';
        }else{
            $ordered = 'asc';
        }
        
       
        if($is_export == true){    //exporthoz
          $ret['count'] = $query->count(); 
          $retArray = self::$getExportArray; 
        }else{
          $retArray = self::$getArray;  
        }
       
        $ret['orders'] = $query->orderBy($order, $ordered)->paginate($limit,$retArray);
        return $ret;
    }      
	
}