<?php 

namespace Core\Tasks;

use Illuminate\Database\Capsule\Manager as Capsule;

Class Table extends Task{

	public function up()
	{
		if(!Capsule::schema()->hasTable($this->name)) {
			Capsule::schema()->create($this->name, $this->callback);
		}
		return $this;
	}

	public function down()
	{
		if(Capsule::schema()->hasTable($this->name)) {
			Capsule::schema()->drop($this->name);
		}    			
		return $this;
	}
}