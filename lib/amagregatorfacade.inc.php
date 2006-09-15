<?php


class AMAgregatorFacade implements AMAjax {

    protected $sourceObj;

    /**
     * This function returns a list of the sources from an agregator.
     *
     * @param int $codeProject
     * @return array
     */
    public function getFormatedSources($codeProject) {

        $q = new CMQuery('AMProjectBlogs');
        $q->setFilter("AMProjectBlogs::codeProject=".$codeProject);
        $sources = $q->execute();
        $ret = array();
        $ret['items'] = array();
        if($sources->__hasItems()) {
            foreach($sources as $item) {
				$ret['items'][] = "<input type=checkbox id=external_".$item->codeBlog." name=external_".$item->codeBlog.">".$item->title."<br>\n";
            }
        }
        return $ret;
    }
    
    /**
     * This function add a new source for aggregator
     *
     * @param int $projId - Id of a AMADIS project.
     * @param string $address - Address of the RSS Feed
     * @param string $title - Title of the source
     * @param enum $type - Type of the source, can be INTERNAL_SOURCE or EXTERNAL_SOURCE
     * 
     * @see AMProjectBlogs
     * @return array
     */
    public function addSource($projId, $address, $title, $type=AMProjectBlogs::ENUM_INTERNAL_SOURCE) {
        $source = new AMProjectBlogs;
        $source->codeProject = $projId;
        $source->address = $address;
        $source->title = $title;
        $source->type = ($type == AMProjectBlogs::ENUM_INTERNAL_SOURCE ? AMProjectBlogs::ENUM_INTERNAL_SOURCE : AMProjectBlogs::ENUM_EXTERNAL_SOURCE);
        
        $ret = array();
		try{
		    $source->save();
			$ret['error'] = 'saved';
		}catch(CMException $e) {
			$ret['error'] = "not_saved";
		    $box = new AMAlertBox(AMAlertBox::ERROR, "AMAgregatorFacade::addSource".$e->getMessage());
		}
		$ret['msg'] = $box->__toString();
		return $ret;
    }
    
    /**
     * This function delete a RSS Feed Source
     *
     * @param int $id - blog Identifier
     * @return array
     */
	public function removeSource($id) {
	    $source = new AMBProjectBlogs;
	    $source->codeBlog = $id;

	    $ret = array();
	    try {
	        $source->load();
	        try {
	            $source->delete();
	            $ret['error'] = 'deleted';
	        }catch(CMException $e) {
	            $ret['erro'] = 'not_deleted';
	            $box = new AMAlertBox(AMAlertBox::ERROR, "AMAgregatorFacade::removeSource".$e->getMessage());
	        }
	    }catch(CMException $e) {
	        $ret['error'] = 'not_loaded';
	        $box = new AMAlertBox(AMAlertBox::ERROR, "AMAgregatorFacade::removeSource".$e->getMessage());
	    }
	    $ret['msg'] = $box->__toString();
	    return $ret;
	}

	public static function getSources($id) {
		$q = new CMQuery('AMProjectBlogs');
		
		$j = new CMJoin(CMJoin::INNER);
		$j->setClass("AMAgregator");
		$j->on("AMAgregator::codeSource = AMProjectBlogs::codeProject");

		$q->addJoin($j, "agregator");

		$q->setFilter("AMProjectBlogs::codeProject=".$id);
		return $q->execute();
	}
	
	/**
	 * Register Xoad handlers
	 */
	public function xoadGetMeta() {
        $methods = array('getFormatedSources', 'getSources', 'addSource', 'removeSource');
        XOAD_Client::mapMethods($this, $methods);
        XOAD_Client::publicMethods($this, $methods);
    }
}
?>