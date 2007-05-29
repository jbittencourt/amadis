<?php

/**
 * Class that represents each wiki page.
 *
 */
class AMWikiPage extends CMObj
{
    
    public $text;
    public $currentRevision;

    
    /**
     * Setup datase fields.
     *
     */
    public function configure() {
        $this->setTable("WikiPage");

        $this->addField("codePage",CMObj::TYPE_INTEGER,20,1,0,1);
        $this->addField("namespace",CMObj::TYPE_VARCHAR,100,1,0,0);
        $this->addField("title",CMObj::TYPE_VARCHAR,255,1,0,0);
        $this->addField("lastest",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("new",CMObj::TYPE_INTEGER,1,1,0,0);


        $this->addPrimaryKey("codePage");

    }
    
    
    public function load()
    {
        parent::load();

        if($this->new == 1) return true;
        
        //loads the current review
        $this->currentRevision = new AMWikiRevision;
        $this->currentRevision->page = (integer) $this->codePage;
        $this->currentRevision->codeRevision = (integer) $this->lastest;
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
    
    public function save() {
        //save before because the page is new, and is needed to
        //generate a codePage for it.
        if($this->state == CMObj::STATE_DIRTY_NEW) {
        	$title = $this->title;
        	$namespace = $this->namespace;
        	if(empty($title) && empty($namespace)) {
            //if(!($this->isVariableDefined('title') && $this->isVariableDefined('namespace'))) {
                Throw new AMException('You must define at least a title and a namespace');
            }
            $this->new = 1;
            parent::save();
        }
        
        if(!empty($this->text)) {
            if(!$this->new) {
                if($this->currentRevision->text == $this->text) return true;
            }
            
            $text = new AMWikiText;
            $text->text = $this->text;
           	$text->save();
            
            $rev = new AMWikiRevision;
            $rev->text = $text->codeText;
            $rev->page = $this->codePage;
            $rev->time = time();
            $rev->user = $_SESSION['user']->codeUser;
            
            if($this->new) {
                $rev->codeRevision = 1;
            } else {
                $rev->codeRevision = $this->lastest + 1;
            }
            $rev->save();
            $this->currentRevision = $rev;
            $this->lastest = $rev->codeRevision;
            $this->new = 0; //false
            parent::save();
        }
    }
    
    public function __set($name, $value) 
    {
        if(($name=='title') || ($name=='namespace')) {
            if(($this->state == CMObj::STATE_DIRTY) ||
               ($this->state == CMObj::STATE_PERSISTENT )) {
                   Throw new AMException('You cannot change the title or the namespace of the page. Please use the move method.');
            };
        } 
        
        parent::__set($name, $value);
    }

    /**
     * Move the current page to a new Title.
     *
     * @param String $new_title The new title of the page.
     * @todo implement this function
     */
    public function move($new_title) {
        
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
    
}

?>
