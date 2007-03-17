<?php
class DumpFiles
{
	public function __construct()
	{
		global $_CMAPP;
		$_conf = $_CMAPP['config'];
		
		echo '<pre>';
		echo 'Setting path: ';
		echo $path =  (string) $_conf->app[0]->paths[0]->files;
		echo ': OK;<br><br>';
		
		$q = new CMQuery('AMFile');
		$count = 0;	
		echo 'Listing files: ';
		$mod = ceil(3212/100);
		for($i=1; $i < $mod; $i++) {
			$q->setLimit(($i*100),100);
			$files = $q->execute();
			echo 'OK;<br><br>';
		
			echo 'Iterating:...';
			
			if($files->__hasItems()) {
				foreach($files as $file) {
					echo '#DUMPING: '.$file->name.' as '.$path.'/'.$file->codeFile.'_'.$file->name.': ';
					$f = fopen($path.'/'.$file->codeFile.'_'.$file->name, "a");
					fwrite($f,$file->data);
					fclose($f);
					echo 'OK;<br>';
					echo 'File Size: '.(round($file->size/1024, 2)).'KB<br>';
					echo 'Saving in new format: ';
				   	//unset($file->fieldsValues['data']);
				   	$file->data = '';
				   	$file->state = CMObj::STATE_DIRTY;
					//$file->save();
					echo 'OK;<br><br>';
				
					$count++;
				}
			}
		}
		echo 'FININSHED!<br>';
		echo 'TOTAL FILES DUMPPED: '.$count;
		echo '</pre>';	
	}
}
?>