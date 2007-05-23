<?php
/**
 * Ajax wiki interface
 *
 */
class AMWiki implements AMAjax {
	
	function savePage($namespace, $title, $text)
	{
		$wikiPage = new AMWikiPage;
		$wikiPage->namespace = $namespace;
		$wikiPage->title = $title;
		try {
			$wikiPage->load();
		} catch(CMException $e) {
			return 0;
		}
		$wikiPage->text = $text;
		try {
			$wikiPage->save();
		}catch (CMException $e) {
			//notelastquery();
			//note($wikiPage);
			echo $e->getMessage();
		}
		return 1;
	}
	
  public function xoadGetMeta() 
  {
    $methods = array('savePage');
    XOAD_Client::mapMethods($this, $methods);

    $publicMethods = array('savePage');
    XOAD_Client::publicMethods($this, $publicMethods);

  }
	
}
?>