<?php

/**
 * Class that represents each wiki page.
 *
 */
class AMWikiFile extends CMObj
{
    
    public $fileName;
    public $currentRevision;
    public $namespace;
    public $new;

    
    public function configure() {
        $this->setTable("WikiFile");

        $this->addField("revision",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("file",CMObj::TYPE_INTEGER,20,1,0,0);

        $this->addPrimaryKey("revision");
        $this->addPrimaryKey("file");

    }

/*    public function load()
    {
        parent::load();

        return;
        if($this->new == 1) return true;
        
        //loads the current review
        $this->currentRevision = new AMWikiRevision;
        $this->currentRevision->page = (integer) $this->codePage;
        $this->currentRevision->codeRevision = (integer) $this->lastest;
        try {
            $this->currentRevision->load();
        } catch(CMDBNoRecord $e) {
            Throw new AMException('This wiki page has no revisions.');
        }
		
        if(ereg('^image_', $this->title)) {
			$this->text = str_replace('image_', '', $this->title)."\n".str_repeat('=', strlen($this->title))."\n\n";
			$this->text .= '<image ../../files/'.str_replace('image_', $image->file.'_', $this->title).'">';
        }
    }
  */  
    public function save() {
    	//Save image first
    	$file = new AMFile;
    	$file->loadFileFromRequest('frm_image');
    	try {
    		$file->save();
    		$wikiPage = new AMWikiPage;
    		$wikiPage->namespace = $this->namespace;
    		$wikiPage->title = 'image_' . $file->name;
    		try {
    			$wikiPage->load();
				
    			$this->revision = $wikiPage->lastest + 1;
    			$this->file = $file->codeFile;

    			try {
    				parent::save();
    			}catch(CMExcption $e) {
    				//parent::delete();
    			}
    		}catch(CMException $e) {
    			$wikiPage->text = $wikiPage->title;
    			
    			$wikiPage->save();
    			
    			//$this->new = $wikiPage->new;
    		}

    		$this->revision = $wikiPage->lastest;
    		$this->file = $file->codeFile;
    		
    		try {
    			parent::save();
    		} catch(CMException $e) {
    			//parent::delete();
    		}
    	}catch(CMException $e) {
    		new AMLog('AMWiki::save image', $e, AMLog::LOG_WIKI);
    		return 0;
    	}
    }

    public function getOldRevision($codeRevision)
    {
        //loads the current review
        $this->currentRevision = new AMWikiRevision;
        $this->currentRevision->page = (integer) $this->codePage;
        $this->currentRevision->codeRevision = (integer) $codeRevision;
        $text = new AMWikiText;
        try {
            $this->currentRevision->load();
            $text->codeText = $this->currentRevision->text;
            $text->load();
        } catch(CMDBNoRecord $e) {
            Throw new AMException('This wiki page has no revisions.');
        }
        $this->text = $text->text;
    	
    }
    
    public function getHistoy()
    {
    	$q = new CMQuery('AMWikiRevision');
    	
    	//$j1 = new CMJoin(CMJoin::LEFT);
    	//$j1->setClass('AMWikiText');
        //$j1->on("AMWikiRevision::text = AMWikiText::codeText");
    	
        $j2 = new CMJoin(CMJoin::LEFT);
    	$j2->setClass('AMUser');
        $j2->on("AMWikiRevision::user = AMUser::codeUser");
        
    	//$q->addJoin($j1, 'texts');
    	$q->addJoin($j2, 'users');
    	
    	$q->setProjection('AMWikiRevision::*, AMUser::*');
    	$q->setFilter('AMWikiRevision::page = '. $this->codePage);
    	$q->setOrder('AMWikiRevision::time DESC');
    	
    	return $q->execute();

    }

    function getFiles()
    {
    	$q = new CMQuery('AMWikiPage');
    	$q->setFilter("namespace = '$this->namespace' AND title LIKE 'image_%'");

    	$images = $q->execute();
    	$files = array();
    	if($images->__hasItems()) {
    		foreach($images as $image) {
    			
    			$files['image_'.$image->codePage] = array('name'=>$image->title);
    			
    			$f = new CMQuery('AMWikiFile');
    			$f->setFilter("revision = '$image->lastest' AND page = '$image->codePage'");

    			$j = new CMJoin(CMJoin::LEFT);
    			$j->setClass('AMWikiRevision');
    			$j->on('AMWikiRevision::codeRevision = AMWikiFile::revision');
    			
    			$f->addJoin($j, 'revis');
    			$result = $f->execute();
				if($result->__hasItems()) {
					foreach($result as $k=>$item) {
						$files['image_'.$image->codePage]['codeFile'] = $item->file;
					}
				}
    		}
    	}
    	return $files;
    	noteLastquery();
    }
    
    
}

?>
