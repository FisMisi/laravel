<?php

/**
 * Admin oldalon Config menüpont alatt levő beállítások kontrollerje 
 * 
 */

class AdminConfigController extends BaseController 
{
   
    public static function getConfigDatas($datas) 
    {
        $datas['helperDataJson'] = 'helperDataJson';
        $datas['view'] = 'helper.admin.configs.list';  

        $datas['styleCss'] = array();

        $datas['jsLinks'] = array();
 
        
        $datas['helperData']['configs'] = GsConfig::orderBy('id')->paginate(10);
        
        return $datas;
    }
    
    
   /**
     * Edit/Create 
     * @param type $datas
     * @return type obj
     */
//    protected static function editCreate($datas) 
//    {
//        if ($datas['id'] != 0){       //UPDATE
//            $datas['view'] = 'helper.admin.configs.edit';
//            $config = GsConfig::find($datas['id']);
//            $datas['helperData']['config'] = $config[0];    
//        }
//        else {                      //CREATE
//            $datas['view'] = 'helper.admin.configs.create';   
//        } 
        
  //      return $datas;              
  //  }
    
      /**
     * Új Kategória mentése.
     *
     * @return Response
     */
//    public function save()
//    {  
//      $valid = Validator::make($data = Input::all(), GsConfig::$rules);
//      
//      if($valid->passes()){
//           $model = new GsConfig;
//           $model->title    = Input::get('title');
//           $model->name     = Input::get('name');
//           $model->value    = Input::get('value');
//           
//           $model->save();
//
//           return Redirect::route('/administrator/configs');   
//        }
//       
//        return Redirect::back()
//                ->withInput()
//                ->withErrors($valid); 
//    }
    
    
    /**
    * Admin - config adatok frissítése.
    * 
    * @param  int  $id
    * @return Response
    */
    public function update($id)
    {  
        $data = Input::all();
        $rules = GsConfig::$rules;
        $valid = Validator::make($data, $rules);
        $model = GsConfig::find($id);
       
        if ($valid->passes()) {
            
            $model->value    = Input::get('value');
            
            $model->update();
            
            return Redirect::to('/administrator/configs')
                            ->withMessage('Sikeresen módosítva lett a #'.$model->id.' azonosítójú rekord értéke.');    
        }
       
        return Redirect::back()
                ->withInput()
                ->withErrors($valid); 
    }
      
}