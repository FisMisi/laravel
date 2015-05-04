<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageProposer extends Migration {

	private $routingIds = array();

	private function modules() {
		DB::table('modules')->insert(
			array(
				'modul_name' => 'Proposer',
				'modul_title' => 'Proposer',
				'helper' => 'ProposerHelper',
				'admin_route' => '/administrator/proposer',
				'has_public' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
	}
	
	private function routings() {
		$this->routingIds[0] = DB::table('routings')->insertGetId(
			array(
				'routing_path' => '/administrator/proposer',
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
				'routing_path' => '/administrator/proposer/{id}',
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

	public function contents() {
		foreach ($this->routingIds as $ri) {
		
			DB::table('contents')->insert(
				array(
					'routing_id' => $ri,
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
				'helper_class' => 'ProposerHelper',
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
				'helper_class' => 'ProposerHelper',
				'helper_data_json' => '{"id":"{id}"}',
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
