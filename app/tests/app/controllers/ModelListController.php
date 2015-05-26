<?php


/**
 * Modellek listázásához használt kontroller
 *
 * @author Markó Mihály
 */
class ModelListController extends BaseController 
{
    
    /**
    * vezérlő statikus metódus
    *
    * @param  array  datas
    * @return array  datas
    */ 
    public static function getModelList($datas) 
    {
        if(!Auth::check()){
           return false;   
        }
        $datas['view'] = 'helper.modellist.list';
        $datas['styleCss'] = array();
        $datas['jsLinks'] = array();
        $datas['helperDataJson'] = 'helperDataJson';
        
        
        $modelLimitPerPage = isset($datas['model_num']) ? $datas['model_num'] : 27;
        $needPager         = isset($datas['need_pager']) ? $datas['need_pager'] : 0;
        $page              = isset($_GET['page']) ? $_GET['page'] : 1;
        $advPos            = isset($datas['adv_pos']) ? $datas['adv_pos'] : 1;
        
        $gs = array();
        if (isset($_GET['gs'])) {
            $gs = json_decode(base64_decode($_GET['gs']));
        } 
        
        
        if (isset($_GET['ms'])) {
            $ms = json_decode(base64_decode($_GET['ms']));
        }else{
            #teszt adat amíg nincs bekötve a kereső
            //$ms = array(2);
            $ms = array();
        } 
          
        $result = Model::getModelListByMsGs($ms, $gs, $needPager, $modelLimitPerPage, $page);
        
        $datas['helperData']['models'] = $result['models'];
        
        return $datas;
    }
    
    
    public static function generateLinkByMsGs($ms, $gs) {
        $ret = '';
        $sep = '?';
        if(!is_null($gs)) {
            $ret.= $sep."gs=".$gs;
            $sep = '&';
        }
        if (!is_null($ms) && $ms != array() ) {
            $ret.= $sep.base64_encode(json_encode($ms));
        }
        return $ret;
    }
    
    
    public static function getPublicListLink($ms, $gs, $catId) {
        $linkType = 0;
        
        if (in_array($catId, $ms)) {
            $linkType = 1;
            $ms = array_diff($ms, array($catId));
        } else {
            $ms[] = $catId;
        }
        $link = self::generateLinkByMsGs($ms, $gs);
        return array('link' => $link, 'linkType' => $linkType);
    }
    
    public static function getVideoCategoryToList() {
        $data = Input::all();
        $gs = is_null($data['gs']) ? null : json_decode(base64_decode($data['gs']));
        $ms = data['ms'];
    }
    
    public function getModelCategoryToList() {
        $data = Input::all();
        $gs = $data['gs'];
        $ms = is_null($data['ms']) ? null : json_decode(base64_decode($data['ms']));
        $datas = ModelCategory::getOrderedDatas();
        $typeArray = array();
        $catArray = array();
        foreach($datas as $data) {
            if(!in_array($data['type_id'], $typeArray)) {
                $typeArray[$data['typepos']]['id'] = $data['type_id'];
                $typeArray[$data['typepos']]['title'] = $data['type_title'];
            }
            $catArray[$data['type_id']][$data['catpos']]['id'] = $data['id'];
            $catArray[$data['type_id']][$data['catpos']]['title'] = $data['title'];
            $linkTmp = self::getPublicListLink($ms, $gs, $data['id']);
            $catArray[$data['type_id']][$data['catpos']]['link'] = $linkTmp['link'];
            $catArray[$data['type_id']][$data['catpos']]['linkType'] = $linkTmp['linkType'];
        }      
    }
    
}
