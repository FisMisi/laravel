<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageModelVideos extends Migration 
{
        //videos
	private $routingIds = array();
        
        //categories
        private $routingIds2 = array();
	
	private function modules() {
		DB::table('modules')->insert(
			array(
				'modul_name' => 'VideoStorage',
				'modul_title' => 'Video Storage',
				'helper' => 'VideoStorageHelper',
				'admin_route' => '/administrator/video_storage',
				'has_public' => 0,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	}
	
	private function routings() { 
		$this->routingIds[0] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator/video_storage',
				'layout_name' => 'leftright',
				'active' => 1,
				'needover18' => 0,
				'need_auth' => 0,
				'need_admin_auth' => 1,
				'system_route' => 1,
				'adminheader' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		$this->routingIds[1] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator/video_storage/{id}',
				'layout_name' => 'leftright',
				'active' => 1,
				'needover18' => 0,
				'need_auth' => 0,
				'need_admin_auth' => 1,
				'system_route' => 1,
				'adminheader' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	}
        //categories
         private function routings2() {  
		$this->routingIds2[0] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator/video_storaged_categories', //
				'layout_name' => 'leftright',
				'active' => 1,
				'needover18' => 0,
				'need_auth' => 0,
				'need_admin_auth' => 1,
				'system_route' => 1,
				'adminheader' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		$this->routingIds2[1] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator/video_storaged_categories/{id}', //id 
				'layout_name' => 'leftright',
				'active' => 1,
				'needover18' => 0,
				'need_auth' => 0,
				'need_admin_auth' => 1,
				'system_route' => 1,
				'adminheader' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	}
	
	private function contents() {
		foreach($this->routingIds as $routeId) {
			DB::table('contents')->insert(
				array(
					'routing_id' => $routeId,
					'container_name' => 'left',
					'pos' => 1,
					'modul' => 'Custom',
					'helper_function' => 'getAdminDatas',
					'helper_class' => 'AdminMenuHelper',
					#'helper_data_json' => 1,
					#'routing_data_json' => 1,
					'active' => 1,
					'created_at' => date('Y-m-d h:i:s'),
					'updated_at' => date('Y-m-d h:i:s'),
				)
			);
		}
		
		DB::table('contents')->insert( 
			array(
				'routing_id' => $this->routingIds[0],
				'container_name' => 'right',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'getAdminDatas',
				'helper_class' => 'VideoStorageHelper',
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		DB::table('contents')->insert(
			array(
				'routing_id' => $this->routingIds[1],
				'container_name' => 'right',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'getAdminDatas',
				'helper_class' => 'VideoStorageHelper',
				'helper_data_json' => '{"id":"{id}"}',
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	
	}
        
        //categories
        private function contents2() {
		foreach($this->routingIds2 as $routeId) {
			DB::table('contents')->insert(
				array(
					'routing_id' => $routeId,
					'container_name' => 'left',
					'pos' => 1,
					'modul' => 'Custom',
					'helper_function' => 'getAdminDatas', //menünél fix (ez nem változik soha)
					'helper_class' => 'AdminMenuHelper',
					#'helper_data_json' => 1,
					#'routing_data_json' => 1,
					'active' => 1,
					'created_at' => date('Y-m-d h:i:s'),
					'updated_at' => date('Y-m-d h:i:s'),
				)
			);
		}
		
		DB::table('contents')->insert(  
			array(
				'routing_id' => $this->routingIds2[0],//63
				'container_name' => 'right',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'videoCategoryIndex',
				'helper_class' => 'VideoStorageHelper',
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		DB::table('contents')->insert( 
			array(
				'routing_id' => $this->routingIds2[1], //64
				'container_name' => 'right',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'videoCategoryIndex',
				'helper_class' => 'VideoStorageHelper',
				'helper_data_json' => '{"id":"{id}"}',
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	
	}

	
	public function up()
	{
		//Modul
		$this->modules();
		//Routing
		$this->routings();
		//Content
		$this->contents();
                
                //Routing2 - categories
		$this->routings2();
		//Content  - categories
		$this->contents2();
	}

	
	public function down()
	{
		//
	}

}
