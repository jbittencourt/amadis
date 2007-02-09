<?php

class AMAdminLogs implements AMAjax {

	public function drawLog($logfilename, $numLines=100){
		global $_CMAPP;
		$log = "";
		
		$log = array_reverse(file($_CMAPP[path]."/log/".$logfilename));
		
		if(count($log)>100) $numLines = count($log);
		
		$arq = array_splice($log, 0, $numLines);
		
		return $arq;
	
	}

	function xoadGetMeta() {
		XOAD_Client::mapMethods($this, array('drawLog')); //register methods to xoad knowledge
		XOAD_Client::publicMethods($this, array('drawLog'));//register public  methods to xoad access
	}
}

?>