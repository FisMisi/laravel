<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageModelCategories extends Migration 
{
    private $routingIds = array();
	
	private function modules() {
		DB::table('modules')->insert(
			array(
				'modul_name' => 'ModelCategory',
				'modul_title'=> 'Model Categories',
				'helper'     => 'ModelCategoryHelper',
				'admin_route'=> '/administrator/model_categories',
				'has_public' => 0, 
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	}
	
	private function routings() {  
		$this->routingIds[0] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator/model_categories', //54 types list
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
				'routing_path' => '/administrator/model_categories/{id}',//55 typhoz tartozó category list
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
                
                $this->routingIds[2] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator/model_categories/type/{id}',//56  type modfy, create
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
		
		$this->routingIds[3] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator/model_categories/cat/{type_id}/{id}',//57   category modf/create
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
				'helper_class' => 'ModelCategoryHelper',
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
				'helper_class' => 'ModelCategoryHelper',
				'helper_data_json' => '{"id":"{id}"}',
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		DB::table('contents')->insert( 
			array(
				'routing_id' => $this->routingIds[2],
				'container_name' => 'right',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'getAdminDatasType',
				'helper_class' => 'ModelCategoryHelper',
				'helper_data_json' => '{"id":"{id}"}',
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
                
                DB::table('contents')->insert( 
			array(
				'routing_id' => $this->routingIds[3],
				'container_name' => 'right',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'getAdminDatas',
				'helper_class' => 'ModelCategoryHelper',
				'helper_data_json' => '{"type_id":"{type_id}","id":"{id}"}',
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	
	}

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//Modul
		$this->modules();
		//Routing
		$this->routings();
		//Content
		$this->contents();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}
}
