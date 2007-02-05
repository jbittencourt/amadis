<?php

class AMAdminLogs implements AMAjax {

	public function drawLog($logfilename){
		global $_CMAPP;
		$log = "";

		$arq = array_reverse(file($_CMAPP[path]."/log/".$logfilename));
		
		return $arq;
	
	}

	function xoadGetMeta() {
		XOAD_Client::mapMethods($this, array('drawLog')); //register methods to xoad knowledge
		XOAD_Client::publicMethods($this, array('drawLog'));//register public  methods to xoad access
	}
}

?>