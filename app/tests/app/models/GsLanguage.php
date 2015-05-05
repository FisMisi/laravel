<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * modellek szánmára elérhető beszélt nyelvek
 */

class GsLanguage extends Eloquent
{   
    public $timestamps = false;
    protected $table = 'gs_languages';
	protected $primaryKey = 'id';
	
	 
        public static $rules = array(
            'sort'   => 'required|alpha',
            'name'   => 'required',
            'active' => 'integer',
            );
        
    /**
    * public oldalon megjelenítendő nyelvek, ha van id, akkor update esetén hozzá is rendeljük a modelt    
    * 
    * @param  int  $modelId
    * @return array() - languages
    */    
    public static function getLanguages($modelId)
    {   
        $query = self::leftJoin('model_language', function($join) use($modelId)
            {
            $join->on('model_language.gs_language_id', '=', 'gs_languages.id')
                ->where('model_language.model_id', '=', $modelId);
            })
        ->where('gs_languages.active', '=', 1);
            
        $ret = $query->orderBy('gs_languages.id', 'desc')->get(array(
                                            'gs_languages.id',
                                            'gs_languages.name',
                                            'model_language.model_id'
                                            ))->toArray();     
        
        return $ret;
    } 
    
    
    /**
    * admin oldalon a modelhez tartozó nyelveket megjelenítéséhez
    * 
    * @param  int  $modelId
    * @return array() - languages
    */    
    public static function getLanguagesAdmin($modelId)
    {   
        $query = self::join('model_language','gs_languages.id','=','model_language.gs_language_id')
                     ->where('model_language.model_id','=',$modelId);
           
                
        $ret = $query->orderBy('gs_languages.id', 'desc')->get(array(
                                            'gs_languages.name'
                                            ))->toArray();     
        
        return $ret;
    }   
}