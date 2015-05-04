<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageModulFelhasznalokezelo extends Migration {

	private $routingIds = array();
	private $otherRoutingIds = array();
	
	private function modules() {
		DB::table('modules')->insert(
			array(
				'modul_name' => 'Felhasznalokezelo',
				'modul_title' => 'Users',
				'helper' => 'FelhasznalokezeloHelper',
				'admin_route' => '/administrator/felhasznalokezelo',
				'has_public' => 0,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	}
	
	private function otherRouting() {
		$otherRouting = array("/adminLogin", "/under", "/over18", "/login", "/registration");
		
		foreach($otherRouting as $routing) {
			$this->otherRoutingIds[] = DB::table('routings')->insertGetId(
				array(
					'routing_path' => $routing,
					'layout_name' => 'full',
					'active' => 1,
					'needover18' => 0,
					'need_auth' => 0,
					'need_admin_auth' => 0,
					'system_route' => 1,
					'adminheader' => $routing == "/adminLogin" ? 1 : 0,
					'created_at' => date('Y-m-d h:i:s'),
					'updated_at' => date('Y-m-d h:i:s'),
				)
			);
		}	
	}
	
	
	private function routings() {
		$this->routingIds[0] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator/felhasznalokezelo',
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
				'routing_path' => '/administrator/felhasznalokezelo/admin',
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
				'routing_path' => '/administrator/felhasznalokezelo/{id}',
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
		$this->otherRouting();
	}
	
	private function otherContents() {
		DB::table('contents')->insert(
			array(
				'routing_id' => $this->otherRoutingIds[0],
				'container_name' => 'contents',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'getViewDatas',
				'helper_class' => 'AdminLoginHelper',
				#'helper_data_json' => 1,
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		DB::table('contents')->insert(
			array(
				'routing_id' => $this->otherRoutingIds[1],
				'container_name' => 'contents',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'getViewDatas',
				'helper_class' => 'UnderHelper',
				#'helper_data_json' => 1,
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		DB::table('contents')->insert(
			array(
				'routing_id' => $this->otherRoutingIds[2],
				'container_name' => 'contents',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'getViewDatas',
				'helper_class' => 'Over18Helper',
				#'helper_data_json' => 1,
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		DB::table('contents')->insert(
			array(
				'routing_id' => $this->otherRoutingIds[3],
				'container_name' => 'contents',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'getViewDatas',
				'helper_class' => 'LoginHelper',
				#'helper_data_json' => 1,
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		DB::table('contents')->insert(
			array(
				'routing_id' => $this->otherRoutingIds[4],
				'container_name' => 'contents',
				'pos' => 1,
				'modul' => 'Custom',
				'helper_function' => 'getViewDatas',
				'helper_class' => 'RegistrationHelper',
				#'helper_data_json' => 1,
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		
	}

	
	private function contents() {
		for($i = 0;$i < 3;$i++) {
		#foreach($this->routingIds as $routingId) {
			DB::table('contents')->insert(
				array(
					'routing_id' => $this->routingIds[$i],
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
				'helper_class' => 'FelhasznalokezeloHelper',
				#'helper_data_json' => 1,
				#'routing_data_json' => 1,
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
				'helper_class' => 'FelhasznalokezeloHelper',
				#'helper_data_json' => 1,
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
				'helper_function' => 'getAdminDatas',
				'helper_class' => 'FelhasznalokezeloHelper',
				'helper_data_json' => '{"id":"{id}"}',
				#'routing_data_json' => 1,
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		$this->otherContents();
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
