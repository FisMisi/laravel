<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/



#osszeszedem az aktiv oldalakat
$routings = Routing::where('active', 1)->orderBy('id')->get();
#$routings = array();

/*
 * majd beallitom hozzajauk a meghivando contrclass="btn btn-primary btn-sm"ollert
 *
 * Minden Controller 1 layout megjeleniteseert felel
 */
 

$isLocalhost = false; 
 
if ($isLocalhost) {
Route::any('logout', array('uses' => 'SourceController@logout', 'as' => 'logout'));
Route::any('postreg', array('uses' => 'RegistrationHelper@postreg', 'as' => 'postreg'));
Route::any('postlogin', 'SourceController@postLogin');
Route::any('postlogin', 'SourceController@postLogin');
Route::any('postadminlogin', 'SourceController@postAdminLogin');
Route::any('administrator/postadminlogin', 'SourceController@postAdminLogin');
Route::any('adminLogin', 'SourceController@postLogin');
Route::any('adminlogout', array('uses' => 'SourceController@logout', 'as' => 'adminlogout'));
Route::any('setover18', array('uses' => 'SourceController@setOver18', 'as' => 'setover18' ));

Route::any('saveinttag', array('uses' => 'TagHelper@saveinttag', 'as' => 'saveinttag' ));
Route::any('deleteexternalfromint', array('uses' => 'TagHelper@deleteexternalfromint', 'as' => 'deleteexternalfromint' ));
Route::any('addexternaltoint', array('uses' => 'TagHelper@addexternaltoint', 'as' => 'addexternaltoint' ));

Route::any('postroutingmodify', array('uses' => 'OldalkezeloHelper@modify', 'as' => 'postroutingmodify'));
Route::any('administrator/oldalkezelo/postroutingmodify', array('uses' => 'OldalkezeloHelper@modify', 'as' => 'postroutingmodify'));

Route::any('administrator/commentmodify', array("uses" => "CommentHelper@modify", "as" => "commentmodify"));


Route::any('postroutingac', array('uses' => 'OldalkezeloHelper@addcontainer', 'as' => 'postroutingac'));
Route::any('administrator/oldalkezelo/postroutingac', array('uses' => 'OldalkezeloHelper@addcontainer', 'as' => 'postroutingac'));

Route::any('saveuser', array('uses' => 'FelhasznalokezeloHelper@modifyUser', 'as' => 'saveuser'));
Route::any('administrator/felhasznalokezelo/saveuser', array('uses' => 'FelhasznalokezeloHelper@modifyUser', 'as' => 'saveuser'));

Route::any('postroutingmc', array('uses' => 'OldalkezeloHelper@modcontainer', 'as' => 'postroutingmc'));
Route::any('administrator/oldalkezelo/postroutingmc', array('uses' => 'OldalkezeloHelper@modcontainer', 'as' => 'postroutingmc'));

Route::any('postscmodify', array('uses' => 'StaticContentHelper@modify', 'as' => 'postscmodify'));
Route::any('administrator/postscmodify', array('uses' => 'StaticContentHelper@modify', 'as' => 'postscmodify'));

Route::any('administrator/bannermodify', array('uses' => 'BannerHelper@modify', 'as' => 'bannermodify'));

Route::any('changelanguage', array('uses' => 'LanguageHelper@changeLanguage', 'as' => 'changelanguage'));
Route::any('en/changelanguage', array('uses' => 'LanguageHelper@changeLanguage', 'as' => 'changelanguage'));
Route::any('hu/changelanguage', array('uses' => 'LanguageHelper@changeLanguage', 'as' => 'changelanguage'));

Route::any('login/{vertify}', array('uses' => 'RegistrationHelper@validreg', 'as' => 'login/{vertify}'));

Route::any('comment/getpage', array('uses' => "CommentHelper@getPage", 'as' => 'comment/getpage'));
Route::any('videos/commentteszt/comment/getpage', array('uses' => "CommentHelper@getPage", 'as' => 'comment/getpage'));
Route::any('savevideo', array('uses' => "VideoHelper@savevideo", 'as' => 'savevideo'));
} else {
Route::any('/administrator/postscmodify', array('uses' => 'StaticContentHelper@modify', 'as' => '/postscmodify'));
Route::any('/postroutingmodify', array('uses' => 'OldalkezeloHelper@modify', 'as' => '/postroutingmodify'));
Route::any('/postroutingac', array('uses' => 'OldalkezeloHelper@addcontainer', 'as' => '/postroutingac'));
Route::any('/postroutingmc', array('uses' => 'OldalkezeloHelper@modcontainer', 'as' => '/postroutingmc'));

Route::any('/administrator/bannermodify', array('uses' => 'BannerHelper@modify', 'as' => 'bannermodify'));
Route::any('/administrator/commentmodify', array("uses" => "CommentHelper@modify", "as" => "commentmodify"));
Route::any('/logout', array('uses' => 'SourceController@logout', 'as' => '/logout'));
Route::any('/postreg', array('uses' => 'RegistrationHelper@postreg', 'as' => '/postreg'));
Route::any('/postlogin', 'SourceController@postLogin');
Route::any('/postadminlogin', 'SourceController@postAdminLogin');
Route::any('/adminLogin', 'SourceController@postLogin');
Route::any('/adminlogout', array('uses' => 'SourceController@logout', 'as' => '/adminlogout'));
Route::any('/setover18', array('uses' => 'SourceController@setOver18', 'as' => '/setover18' ));
Route::any('/saveuser', array('uses' => 'FelhasznalokezeloHelper@modifyUser', 'as' => '/saveuser'));
Route::any('/login/{vertify}', array('uses' => 'RegistrationHelper@validreg', 'as' => '/login/{vertify}'));

Route::any('/changelanguage', array('uses' => 'LanguageHelper@changeLanguage', 'as' => '/changelanguage'));
Route::any('/saveinttag', array('uses' => 'TagHelper@saveinttag', 'as' => '/saveinttag' ));
Route::any('/savetaggroup', array('uses' => 'TagHelper@savetaggroup', 'as' => '/savetaggroup' ));


Route::any('/deleteexternalfromint', array('uses' => 'TagHelper@deleteexternalfromint', 'as' => '/deleteexternalfromint' ));
Route::any('/addexternaltoint', array('uses' => 'TagHelper@addexternaltoint', 'as' => '/addexternaltoint' ));

Route::any('/redtubeinterface', array('uses' => 'RedtubeInterface@fullrun', 'as' => '/redtubeinterface' ));
Route::any('/savevideo', array('uses' => "VideoHelper@savevideo", 'as' => '/savevideo'));
Route::any('/proposersave', array('uses' => "ProposerHelper@proposersave", 'as' => '/proposersave'));
Route::any('/comment/getpage', array('uses' => "CommentHelper@getPage", 'as' => '/comment/getpage'));
Route::any('/comment/savecomment', array('uses' => "CommentHelper@savecomment", 'as' => '/comment/savecomment'));

Route::any('/getsearch/gettag', array('uses' => "SearchHelper@gettag", 'as' => '/getsearch/gettag'));
Route::any('/getsearch/getstars', array('uses' => "SearchHelper@getstars", 'as' => '/getsearch/getstars'));

Route::any('/getsearch/getdatas', array('uses' => "SearchHelper@getdatatosearch", 'as' => '/getsearch/getdatas'));


Route::any('/getld', array('uses' => "RegistrationHelper@getviewtologin", 'as' => '/getld'));
Route::any('/getrd', array('uses' => "RegistrationHelper@getviewtoreg", 'as' => '/getrd'));

Route::any('/administrator/videodownload', array('uses' => "VideoHelper@downloadvideos", 'as' => '/administrator/videodownload'));


Route::any('/modpasswd', array('uses' => "FelhasznalokezeloHelper@modpasswd", 'as' => '/modpasswd'));
Route::any('/moduser', array('uses' => "FelhasznalokezeloHelper@moduser", 'as' => '/moduser'));


Route::any('/administrator/savemainmenu', array('uses' => "MainmenuHelper@savemenu", 'as' => '/administrator/savemainmenu'));
Route::any('/ratingvideos', array('uses' => "VideoHelper@setRating", 'as' => '/ratingvideos'));

Route::any('/administrator/regentag', array('uses' => "TagHelper@regentag", 'as' => '/administrator/regentag'));

Route::any('/youporninterface', array('uses' => 'InterfaceHelper@youpornInterface', 'as' => '/youporninterface' ));
Route::any('/pornhubinterface', array('uses' => 'InterfaceHelper@pornhubeInterface', 'as' => '/pornhubinterface' ));
Route::any('/redtubeinterface', array('uses' => 'InterfaceHelper@redtubeInterface', 'as' => '/redtubeinterface' ));

Route::any('/regredthu', array('uses' => 'InterfaceHelper@regenerateRedtubeThumbs', 'as' => '/regredthu' ));
Route::any('/getregdatart', array('uses' => 'InterfaceHelper@getDataToRegenerate', 'as' => '/getregdatart' ));

Route::any('/getregdatart0', array('uses' => 'InterfaceHelper@saveRegenerateVideoDatas0', 'as' => '/getregdatart0' ));
Route::any('/getregdatart1', array('uses' => 'InterfaceHelper@regenerateThumbsTeszt', 'as' => '/getregdatart1' ));

Route::any('/postdelvid', array('uses' => 'InterfaceHelper@postDeletedVideoIds', 'as' => '/postdelvid' ));
Route::any('/getdelvid', array('uses' => 'InterfaceHelper@getDeletedVideoIds', 'as' => '/getdelvid' ));

Route::any('/postrefvid', array('uses' => 'InterfaceHelper@postRefaktVideoIds', 'as' => '/postrefvid' ));
Route::any('/getrefvid', array('uses' => 'InterfaceHelper@getRefaktVideoIds', 'as' => '/getrefvid' ));

Route::any('/nartd', array('uses' => 'InterfaceHelper@newApiRedtubeRemoveVideo', 'as' => '/nartd' ));
Route::any('/naphd', array('uses' => 'InterfaceHelper@newApiPornhubRemoveVideo', 'as' => '/naphd' ));
Route::any('/naypd', array('uses' => 'InterfaceHelper@newApiYoupornRemoveVideo', 'as' => '/naypd' ));

Route::any('/ga2vff', array('uses' => 'InterfaceHelper@getActive2VideosFromFront', 'as' => '/ga2vff' ));
Route::any('/srd', array('uses' => 'InterfaceHelper@sendRemovedDatas', 'as' => '/srd' ));

Route::any('/getFrontDatasCount', array('uses' => 'InterfaceHelper@getFrontDatasCount', 'as' => '/getFrontDatasCount' ));
Route::any('/getFrontDatas', array('uses' => 'InterfaceHelper@getFrontDatas', 'as' => '/getFrontDatas' ));
Route::any('/removeFrontNewApi', array('uses' => 'InterfaceHelper@removeFrontNewApi', 'as' => '/removeFrontNewApi' ));

Route::any('/regvidthumbs', array('uses' => 'VideoHelper@regvidthumbs', 'as' => '/regvidthumbs' ));

Route::any('/adpersvid', array('uses' => 'PrivateVideoHelper@adpersvid', 'as' => '/adpersvid' ));
Route::any('/vidtoseo', array('uses' => 'VideoHelper@getvidseo', 'as' => '/vidtoseo' ));
//model regisztráció step1
Route::any('/postmodelregistraton/step1/createModelStep1', array('as' => '/postmodelregistraton/step1/createModelStep1', 'uses' => 'ModelRegistrationHelper@CreateModelStep1'));
Route::any('/postmodelregistraton/step1/updateModelStep1/{id}', array('as' => '/postmodelregistraton/step1/updateModelStep1', 'uses' => 'ModelRegistrationHelper@UpdateModelStep1'));
//model regisztráció step2
Route::any('/postmodelregistraton/step2/createModelStep2', array('as' => '/postmodelregistraton/step2/createModelStep2', 'uses' => 'ModelRegistrationHelper@CreateModelStep2'));
Route::any('/postmodelregistraton/step2/updateModelStep2/{id}', array('as' => '/postmodelregistraton/step2/updateModelStep2', 'uses' => 'ModelRegistrationHelper@UpdateModelStep2'));
Route::any('/postmodelregistraton/step2/video_statusz', array('as' => '/postmodelregistraton/step2/video_statusz', 'uses' => 'ModelRegistrationHelper@getVideoStatusz'));

#paypall

// Add this route for checkout or submit form to pass the item into paypal
Route::post('/postmodelregistraton/step2/payment', array(
        'as' => '/postmodelregistraton/step2/payment',
        'uses' => 'ModelRegistrationHelper@postPayment',
));
// this is after make the payment, PayPal redirect back to your site
Route::get('/postmodelregistraton/step2/payment_status', array(
    'as' => '/postmodelregistraton/step2/payment_status',
    'uses' => 'ModelRegistrationHelper@getPaymentStatus',
));
// payout
Route::get('/postmodelregistraton/step2/payout', array(
    'as' => '/postmodelregistraton/step2/payout',
    'uses' => 'ModelRegistrationHelper@singlePayout',
));

//admin model manage
Route::any('/administrator/models/modifyModel/{id}', array('as' => '/administrator/models/modifyModel', 'uses' => 'ModelRegistrationHelper@adminUpdateModel'));

//languages
Route::any('/administrator/modelslanguages/update_statusz/{id}', array('as' => '/administrator/modelslanguages/update_statusz/{id}', 'uses' => 'ModelRegistrationHelper@updateStatusz'));
Route::any('/administrator/modelslanguages/update/{id}', array('as' => '/administrator/modelslanguages/update/{id}', 'uses' => 'ModelRegistrationHelper@updateLanguage'));
Route::any('/administrator/modelslanguages/save', array('as' => '/administrator/modelslanguages/save', 'uses' => 'ModelRegistrationHelper@saveLanguage'));
//admin - model categories
Route::any('/administrator/model_categories/type/update_statusz/{id}', array('as' => '/administrator/model_categories/type/update_statusz/{id}', 'uses' => 'ModelCategoryHelper@updateStatusz'));
Route::any('/administrator/model_categories/type/update/{id}', array('as' => '/administrator/model_categories/type/update/{id}', 'uses' => 'ModelCategoryHelper@updateCategoryType'));
Route::any('/administrator/model_categories/save', array('as' => '/administrator/model_categories/save', 'uses' => 'ModelCategoryHelper@saveCategoryType'));

//model allkategória
Route::any('/administrator/model_categories/cat/update_statusz/{id}', array('as' => '/administrator/model_categories/cat/update_statusz/{id}', 'uses' => 'ModelCategoryHelper@updateCatStatusz'));
Route::any('/administrator/model_categories/cat/update/{id}', array('as' => '/administrator/model_categories/cat/update/{id}', 'uses' => 'ModelCategoryHelper@updateCategory'));
Route::any('/administrator/model_categories/cat/save', array('as' => '/administrator/model_categories/cat/save', 'uses' => 'ModelCategoryHelper@saveCategory'));

//vide storage routok
Route::any('/administrator/video_storage/videodownload', array('as' => '/administrator/video_storage/videodownload', 'uses' => "VideoStorageHelper@downloadvideos"));
Route::any('/administrator/video_storage/update/{id}', array('as' => '/administrator/video_storage/update', 'uses' => 'VideoStorageHelper@update'));

//video categories
Route::any('/administrator/video_storaged_categories/update_statusz/{id}', array('as' => '/administrator/video_storaged_categories/update_statusz/{id}', 'uses' => 'VideoStorageHelper@updateStatusz'));
Route::any('/administrator/video_storaged_categories/update/{id}', array('as' => '/administrator/video_storaged_categories/update/{id}', 'uses' => 'VideoStorageHelper@updateCategory'));
Route::any('/administrator/video_storaged_categories/save', array('as' => '/administrator/video_storaged_categories/save', 'uses' => 'VideoStorageHelper@saveCategory'));

//order admin
Route::any('/administrator/orders/update/{id}', array('as' => '/administrator/orders/update', 'uses' => 'OrderController@update'));
Route::any('/administrator/orders/orderdownload', array('as' => '/administrator/orders/export', 'uses' => "OrderController@export"));

//order public
Route::any('/order/save', array('as' => '/order/save', 'uses' => 'OrderController@save'));

//admin config

//Route::any('/administrator/configs/{id}', array('as' => '/administrator/configs/{id}', 'uses' => 'AdminConfigController@editCreate'));
Route::any('/administrator/configs/update/{id}', array('as' => '/administrator/configs/update', 'uses' => 'AdminConfigController@update'));
//Route::any('/administrator/configs/save', array('as' => '/administrator/configs/save', 'uses' => 'AdminConfigController@save'));

//admin model levels
Route::any('/administrator/model_levels/update/{id}', array('as' => '/administrator/model_levels/update', 'uses' => 'ModelRegistrationHelper@updateLevel'));
Route::any('/administrator/model_levels/save', array('as' => '/administrator/model_levels/save', 'uses' => 'ModelRegistrationHelper@saveLevel'));


}

$languages = Language::getLangList(false);
$_SESSION['routing'] = array();
foreach($routings as $routing) {
	Route::any($routing->routing_path, array('uses' => "SourceController"."@showDefault", 'as' => $routing->routing_path));
	$_SESSION['routing'][] = $routing->routing_path;
	if ($routing->system_route == 0) {
		if ($routing->routing_path == '/') {
			Route::any('/{lang}', array('uses' => "SourceController"."@showDefault", 'as' => '/{lang}'))->where('lang', '[a-z][a-z]');
			$_SESSION['routing'][] = '/{lang}';
		} else {
			Route::any('/{lang}'.$routing->routing_path, array('uses' => "SourceController"."@showDefault", 'as' => '{lang}/'.$routing->routing_path))->where('lang', '[a-z][a-z]');
			$_SESSION['routing'][] = '{lang}/'.$routing->routing_path;
		}
	}
}
