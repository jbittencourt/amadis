<?php

class AMState extends CMObj
{

	public function configure()
	{
		$this->setTable("States");

		$this->addField("codeState",CMObj::TYPE_INTEGER,11,1,0,1);
		$this->addField("name",CMObj::TYPE_VARCHAR,20,1,0,0);
		$this->addField("country",CMObj::TYPE_VARCHAR,20,1,0,0);
		$this->addField("code",CMObj::TYPE_VARCHAR,3,1,0,0);

		$this->addPrimaryKey("codeState");
	}

	public function listCities() {
		$q = new CMQuery('AMCity');
		$q->setFilter(" state='$this->codeState'");
		return $q->execute();
	}


	static public function listStates()
	{
		$q = new CMQuery('AMState');

		return $q->execute();
	}

}