<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * gs_video_categories és a models közti kapcsoló
 */

class ModelGsVc extends Eloquent
{
	protected $table = 'model_gs_vc';
	protected $primaryKey = 'id';
	public    $timestamps = false;
	 
        public static $rules = array(
            'gs_vc_id'     => 'required|integer',
            'model_id'     => 'required|integer',
           // 'gs_vc_price'  => 'required|integer',
            'ex_vc_price'  => 'required|integer',
            'active'       => 'required|integer'
            );
        
    /**
     * Public categories prices rules és messages.
     * @param int modellLevelId modell besorolt kategóriája (gold,silver)
     * @param array input kategória checkboxok 
     * @return array(), newMessages, newRules
     */
    public static function getPriceRules($input, $modellLevelId)
    {
        $newMessages = [];
        $newRules    = [];
        
        $categs = GsVideoCategory::orderBy('id','desc')
                                 ->get(array('id', 'title'))
                                 ->toArray();
  
        $prices = GsMlsGsVc::getVideoCategoryPrice($modellLevelId);
        
        foreach ($categs as $categ)
        {
          // csak azokat a input mezőket validáljuk amely kategóriák ki is vannak választva
          if(in_array($categ['id'], $input))
           {   
                //egyedi szabályok generálása
                $rule = [
                    'ex_vc_price__'.$categ['id'] => 'required|integer|max:'.$prices[1][$categ['id']]['max'].'|min:'.$prices[1][$categ['id']]['min']
                ];

                //egyedi üzenetek generálása
                $message = [
                    'ex_vc_price__'.$categ['id'].".required" => 'The '.$categ['title'] .' exclusive price field is required!',
                    'ex_vc_price__'.$categ['id'].".max"      => 'The '.$categ['title'] .' exclusive price field value may not be greater than maximum!',  
                    'ex_vc_price__'.$categ['id'].".min"      => 'The '.$categ['title'] .' exclusive price field value may not be shorter than minimum!',
                ];

                $newMessages = $newMessages + $message;

                $newRules = $newRules + $rule; 
            }    
        }
        return [
                'newRules'    => $newRules,
                'newMessages' => $newMessages
              ];
    }
    
    /**
    * Public oldalon (step2) vállalt videó kategóriákhoz tartozó árak behúzása
    * @param int modelId szint id
    * @return array() $query
    */ 
    public static function getModellVideoCategoryPrice($modelId) 
    {
        $query = self::where('model_id', '=', $modelId)->get()->toArray();
        
        $ret = array();
        
        foreach($query as $data) {
            $ret[$data['gs_vc_id']]['ex_vc_price'] = $data['ex_vc_price'];
        }
        
        return $ret;
        
    }
        
}