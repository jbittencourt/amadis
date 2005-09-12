<?


class AMWSmartform extends CMWSmartform {

  public function __construct($objClass,$name,$action="",$fields_rec="",$fields_hidden="",$fields_ausentes="",$method="POST",$enctype="") {
    parent::__construct($objClass,$name,$action,$fields_rec,$fields_hidden,$fields_ausentes,$method,$enctype);
    

    //ajust the form format
    $format = new CMHTMLFormat;
    $format->setTabela("table cellspacing=1 cellpadding=2 width=\"70%\"","/table");
    $this->setHtmlFormat($format);
    $this->components[submit_group]->setOrder(array("cancel","submit"));
    $this->components[submit_group]->setAlign("right");

    $this->setLabelClass("fontgray");
    $this->setDesign(CMWFormEL::WFORMEL_DESIGN_OVER);


  }


  /**
   *  Transform an textarea field into a AMWRichTextArea
   *
   * @param string $field  Name of the field to be changed;
   **/
  public function setRichTextArea($field) {
    if(!array_key_exists($field,$this->components)) {
      Throw new CMWSmartFormEFieldNotFound($field);
    }

    if(!is_a($this->components[$field],CMWTextarea)) {
      Throw new CMWSmartFormException("You are trying to transform a field that is not an textarea into an AMWRichTextArea");
    }
    $old = $this->components[$field];
    //multiply by * because the textarea is defined in cols and rows and the HTMLArea in pixels
    $comp = new AMWRichTextArea($old->name,$old->getValue());
    $comp->setLabel($old->getLabel());
    unset($this->components[$field]);
    $this->addComponent($field,$comp);
  }

}


?>