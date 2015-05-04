<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Tárolt videók (model videok és belső videók)
 */

class StoragedVideo extends Eloquent
{
	protected $table = 'storaged_videos';
	protected $primaryKey = 'id';
	
	 
        public static $rules = array(
//            'model_id'          => 'integer',   
//            'user_id'           => 'required|integer',
//              'name'            => 'alpha',
//              'title'           => 'alpha',
              'type_id'           => 'required|integer',      
//            'active_user'       => 'required|integer',    //user által engedélyezett  
              'active_admin'      => 'integer',    //admin által engedélyezett
              'inactivated_desctription' => 'min:10', //miért nem aktiválható  
//            'published_and_date'=> 'date',               //meddíg jeleníthető meg egy videó
//            'in_storage'        => 'required|integer',   //stroga serveren fent van-e már a videó
//            'over_trans_code'   => 'required|integer',  //transzformálás után kapott kód
//            'local_store_path'  => 'string:32',
//            'storage_reference' => 'string:64',
//            'sum_rating'        => 'integer',  // összeadott szavazatok pl.: 5+3 = 8
//            'rating_number'     => 'integer',  // előzőből kiindulva 2 (2 db szavazat érkezett)
//            'rating'            => 'integer'   // átlagolt raiting
            );
        
        //megkjelenítendő mezők összegyűjtése
        public static $getArray = array('storaged_videos.id',
                          'storaged_videos.name as videoName',
                          'storaged_videos.title as videoTitle',
                          'storaged_videos.active_user',
                          'storaged_videos.active_admin',
                          'storaged_videos.inactivated_desctription',
                          'storaged_videos.in_storage',
                          'storaged_videos.published_and_date',
                          'storaged_videos.over_trans_code',
                          'storaged_videos.type_id',
                          'storaged_videos.rating_number',
                          'storaged_videos.rating',
                          'storaged_videos.created_at',
                          'storaged_videos.updated_at',
                          'models.artist_name',
                          'storaged_video_types.title as videoTypeTitle'
                        );

    /**
    * Admin oldalon a videók megjelenítéséhez szükséges szűrési feltételek összegyűjtése.
    * 
    * @param  int  $limit, $page
    * @return array $ret
    */ 
     
    public static function getVideoToAdminList($videoId,$active_user,$active_admin,$videoType,$published_and_date,$in_storage,$over_trans_code,$limit,$order, $ordered, $page) 
    {
        
        if ($page != 0) {
                $page--;
        }

       $query = self::join('storaged_video_types', 'storaged_video_types.id', '=', 'storaged_videos.type_id')
                     ->leftJoin('models', 'storaged_videos.model_id', '=', 'models.id');
       $query2 = self::join('storaged_video_types', 'storaged_video_types.id', '=', 'storaged_videos.type_id')
                     ->leftJoin('models', 'storaged_videos.model_id', '=', 'models.id');
       
       $ret = [];
       
       
       if($videoId != null){
          $query->where('storaged_videos.id', '=', $videoId);
          $query2->where('storaged_videos.id', '=', $videoId);
          
          $ret['videos'] = $query->orderBy('storaged_videos.id', 'desc')->get(self::$getArray);
       }

        if ($active_user != 2) {
                $query->where('storaged_videos.active_user', '=', $active_user);
                $query2->where('storaged_videos.active_user', '=', $active_user);
        }
        
        if ($active_admin != 2) {
                $query->where('storaged_videos.active_admin', '=', $active_admin);
                $query2->where('storaged_videos.active_admin', '=', $active_admin);
        }
        
        if ($published_and_date != 2) {
            $dt = new DateTime;
            $timestamp = $dt->format('Y-m-d H:i:s');
            
            $query->whereNotNull('storaged_videos.published_and_date');  
            $query2->whereNotNull('storaged_videos.published_and_date');
            
            if($published_and_date == 1){   //lejárt
              $query->where('storaged_videos.published_and_date', '<', $timestamp);
              $query2->where('storaged_videos.published_and_date', '<', $timestamp);  
            }else{                          //nem járt le
              $query->where('storaged_videos.published_and_date', '>', $timestamp);
              $query2->where('storaged_videos.published_and_date', '>', $timestamp);  
            }
                
        }
        
        if ($in_storage != 2) {
                $query->where('storaged_videos.in_storage', '=', $in_storage);
                $query2->where('storaged_videos.in_storage', '=', $in_storage);
        }
        
        if ($over_trans_code != 2) {
                $query->where('storaged_videos.over_trans_code', '=', $over_trans_code);
                $query2->where('storaged_videos.over_trans_code', '=', $over_trans_code);
        }
        
        if ($videoType != 0) {
                $query->where('storaged_videos.type_id', '=', $videoType);
                $query2->where('storaged_videos.type_id', '=', $videoType);
        }
        
        // mi alapján rendezzük
        if ($order != 0) {
            $order = 'storaged_videos.published_and_date';
        }else{
            $order = 'models.artist_name';
        }
        
        //csökkenő vagy növekvő
        if ($ordered != 0) {
            $ordered = 'desc';
        }else{
            $ordered = 'asc';
        }

        $query->take($limit);
        if ($page > 0) {
                $query->skip($limit*$page);
        }
        
        $ret['videos'] = $query->orderBy($order, $ordered)->get(self::$getArray);
        $ret['count']  = $query2->count();
        
        return $ret;
    } 
    
    /**
    * Hölgy-höz tartozó videók megjelenítéséhez
    * 
    * @param  int  $modelId
    * @return array categories and types
    */ 
    
    public static function getGiftSHowTypeId() {
        $obj = StoragedVideoType::where('name', '=', 'gift_show')->first();
        return $obj->id;
    }
    
    
    public static function getModelVideos($modelId) 
    {   
       $getArray = array(
                    'storaged_video_types.title as type',
                    'storaged_videos.title as name',
                    'storaged_videos.storage_reference',
                    'storaged_videos.local_store_path',
                    'storaged_videos.active_user',
                    'storaged_videos.inactivated_desctription',
                    'gs_video_categories.title as categName',
                    'storaged_videos.id as id'
                  );
       
       $query = self::join('storaged_video_types', 'storaged_video_types.id', '=', 'storaged_videos.type_id')
                     ->join('models', 'storaged_videos.model_id', '=', 'models.id')
                     ->join('gs_video_categories', 'gs_video_categories.id', '=', 'storaged_videos.gs_vc_id')
                     ;
       
       $query->where('models.id','=',$modelId);
       $query->where('storaged_videos.type_id', '=', self::getGiftSHowTypeId());
       $ret = $query->get($getArray);
 
       return $ret;
    }   
        
}