<?php
class AMModulesConfiguration extends CMObj {

	public function configure() 
	{
    	$this->setTable("ModulesConfiguration");

     	$this->addField("module",CMObj::TYPE_VARCHAR,255,1,0,0);
     	$this->addField("property",CMObj::TYPE_VARCHAR,255,1,0,0);
		$this->addField("value",CMObj::TYPE_VARCHAR,255,1,0,0);
  	}
  	
  	public function getConfigValue($module, $value)
  	{
  		return $_SESSION['config'][$module][$value];	
  	}
  	
  	public function getConfiguration($module)
  	{
  		return $_SESSION['config'][$module];
  	}
  	
  	public function loadConfig()
  	{
  		$q = new CMQuery('AMModulesConfiguration');
  		$configTable = $q->execute();
  		
  		if($configTable->__hasItems()) {
  			$_SESSION['config'] = array();
  			foreach($configTable as $item) {
  				if(!isset($_SESSION['config'][$item->module]))
  					$_SESSION['config'][$item->module] = array($item->property => $item->value);
  				else $_SESSION['config'][$item->module][$item->property] = $item->value;
  			}
  		}
  	}
  	
  	public static function hasConfig($module)
  	{
  		return isset($_SESSION['config'][$module]) ? true : false;
  	}
}