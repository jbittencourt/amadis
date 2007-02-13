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
    public function addSource($projId, $title, $link) 
    {
    	global $_CMAPP;
    	
        $source = new AMProjectBlogs;
        $source->codeProject = $projId;
        $source->address = $link;
        $source->title = $title;
        $source->type = AMProjectBlogs::ENUM_TYPE_EXTERNAL;
        
        $ret = array();
		try{
		    $source->save();
		    
			$t = "<img src='$_CMAPP[images_url]/icon_rss_on.gif' id='status_$source->codeSource' onclick=\"Aggregator_toggleStatus(this.id);\" class='cursor' align='absmiddle'> "
			   . "<img src='$_CMAPP[images_url]/icon_excluir_agregador.gif' id='delete_$source->codeSource' onclick=\"Aggregator_deleteSource(this.id);\" align='absmiddle'>"
			   . "<span class='font_rss'>$source->title</span><br>";
			$ret['src'] = $t;
			$ret['id'] = $source->codeSource;
		}catch(CMException $e) {
			$ret = 0;
			new AMErrorReport($e, 'AMAgregatorFacade::addSource saving', AMLog::LOG_AGGREGATOR);
		}
		return $ret;
    }
    
    /**
     * This function delete a RSS Feed Source
     *
     * @param int $id - blog Identifier
     * @return array
     */
	public function deleteSource($id) {
	    $source = new AMProjectBlogs;
	    $source->codeSource = $id;

	    $ret = array();
	    try {
	        $source->load();
	        try {
	            $source->delete();
	            $ret['id'] = $id;
	        }catch(CMException $e) {
	            $ret = 0;
	            new AMErrorReport($e, 'AMAgregatorFacade::deleteSource deleting', AMLog::LOG_AGGREGATOR);
	        }
	    }catch(CMException $e) {
	        $ret = 0;
	        new AMErrorReport($e, 'AMAgregatorFacade::deleteSource deleting', AMLog::LOG_AGGREGATOR);
	    }
	    return $ret;
	}

	public function toggleStatus($id)
	{
		global $_CMAPP;
		
		$src = new AMProjectBlogs;
		$src->codeSource = $id;
		$ret = array('id'=>'status_'.$id);
		try {
			$src->load();
		}catch(CMException $e) {
			new AMErrorReport($e, 'AMAgregatorFacade::toggleStatus loading', AMLog::LOG_AGGREGATOR);
			$ret = 0;
		}
		
		if($src->status == AMProjectBlogs::ENUM_STATUS_ENABLE) {
			$src->status = AMProjectBlogs::ENUM_STATUS_DISABLE;
			$ret['src'] = $_CMAPP['images_url'].'/icon_rss_off.gif';
		} else {
			$src->status = AMProjectBlogs::ENUM_STATUS_ENABLE;
			$ret['src'] = $_CMAPP['images_url'].'/icon_rss_on.gif';
		}
		
		try {
			$src->save();
		}catch (CMException $e){
			new AMErrorReport($e, 'AMAgregatorFacade::toggleStatus saving', AMLog::LOG_AGGREGATOR);
			$ret = 0;
		}
		
		return $ret;
		
	}
	
	public static function getSources($id) {
		$q = new CMQuery('AMProjectBlogs');

		$q->setFilter("AMProjectBlogs::codeProject=".$id);
		return $q->execute();
	}

	public function addFilter($id, $keyword, $count) 
	{
		global $_CMAPP;
		
		$filter = new AMAgregator;
		$filter->codeAggregator = $id;
		
		$ret = array();
		
		try {
			$filter->load();
			$filter->keywords .= ','.$keyword;
		}catch(CMException $e) {
			$filter->keywords = $keyword;
		}
		
		try {
			$filter->save();
			$ret['src'] = $keyword." <img src='$_CMAPP[images_url]/icon_excluir_agregador.gif' onclick=\"Aggregator_deleteFilter($id, '".$keyword."', $count);\" align='absmiddle'><br>";
			$ret['count'] = $count;
		}catch(CMException $e) {
			$ret = 0;
			new AMErrorReport($e, 'AMAgregatorFacade::addFilter saving', AMLog::LOG_AGGREGATOR);
		}
		
		return $ret;
	}
	
	public function deleteFilter($id, $keyword, $count)
	{
		$filter = new AMAgregator;
		$filter->codeAggregator = $id;
		try {
			$filter->load();
			$tmp = explode(',', $filter->keywords);
			$k = array_search($keyword, $tmp);
			unset($tmp[$k]);
			$filter->keywords = implode(',', $tmp);
			try {
				$filter->save();
				$ret['id'] = $count;
			}catch(CMException $e){
				$ret = 0;
				new AMErrorReport($e, 'AMAgregatorFacade::deleteFilter saving', AMLog::LOG_AGGREGATOR);
			}
		}catch(CMException $e) {
			$ret = 0;
			new AMErrorReport($e, 'AMAgregatorFacade::deleteFilter loading', AMLog::LOG_AGGREGATOR);
		}
		return $ret;
	}
	/**
	 * Register Xoad handlers
	 */
	public function xoadGetMeta() {
        $methods = array('getFormatedSources', 'getSources', 'addSource', 'deleteSource', 'toggleStatus', 'addFilter', 'deleteFilter');
        XOAD_Client::mapMethods($this, $methods);
        XOAD_Client::publicMethods($this, $methods);
    }
}