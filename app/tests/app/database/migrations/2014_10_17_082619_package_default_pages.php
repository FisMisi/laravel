<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageDefaultPages extends Migration {

	private $routingIds = array();
	
	private function routings(){
		$this->routingIds[0] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator',
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
				'routing_path' => '/',
				'layout_name' => 'full',
				'active' => 1,
				'needover18' => 1,
				'need_auth' => 0,
				'need_admin_auth' => 0,
				'system_route' => 0,
				'adminheader' => 0,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	}
	
	private function contents() {
		DB::table('contents')->insert(
			array(
				'routing_id' => $this->routingIds[0],
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

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
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
