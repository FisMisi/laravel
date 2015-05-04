<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageOrders extends Migration 
{

        //orders
	private $routingIds = array();
        
	private function modules() {
		DB::table('modules')->insert(
			array(
				'modul_name' => 'Orders',
				'modul_title' => 'Orders',
				'helper' => 'OrderController',
				'admin_route' => '/administrator/orders',
				'has_public' => 0,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	}
	
	private function routings() { 
		$this->routingIds[0] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator/orders',
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
				'routing_path' => '/administrator/orders/{id}',
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
					'helper_function' => 'getAdminDatas', //Fix
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
				'helper_function' => 'getOrderDatas',
				'helper_class' => 'OrderController',
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
				'helper_function' => 'getOrderDatas',
				'helper_class' => 'OrderController',
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
	}

	
	public function down()
	{
		//
	}
}
