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

//CONTROLLER

//videó exportálás
    public static function downloadvideos() 
    { 
         $params = self::getParams(Input::all(),$datas=null);
            
         $query  = StoragedVideo::getVideoToAdminList(null,
                        $params["activated_user"],$params["activated_admin"],$params["videoType"],
                        $params["published_and_date"],$params["in_storage"],$params["over_trans_code"],
                        $params["limit"], $params["page"]
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
            "page"               => $page
        ];
    }
    
    // View::
    
     <a class="btn btn-primary btn-sm" @if (!count($helperDataJson['videos']))  disabled="disabled" @endif   href={{ route('/administrator/video_storage/videodownload',$_GET) }}>Export Videos</a>
            
    //AJAX
     
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
    
    
    //View
        <script>
    
function setModelVideoState(vid) {
    var datastring = 'videoid='+vid;
    
    $.ajax({
            type: "POST",
            url: "/postmodelregistraton/step2/video_statusz",
            data: datastring,
            success: function(data){
                if(data == 1){
                    $("#statusz").html("Inactivated");
                }else{
                    $("#statusz").html("Activated");
                } 				
            }
    }, 'json');
    
}    
    
$("document").ready(function()
{
	$("#statusz").click(function(event) {
            
            var videoId    = $("#video_id").val();
            setModelVideoState(videoId);
	});
        
});
</script>


<a name='statusz' id="statusz">
                     
                       @if($video->active_user == 1)
                           Inactivated
                       @else
                           Activated
                       @endif
                     </a>