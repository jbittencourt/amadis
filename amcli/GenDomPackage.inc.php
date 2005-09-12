<?
class GenDomPackage extends DomDocument {

// <tool>
//   <name>amlec</name>
//   <type>box</type>
//   <categories>
//     <categorie>inicial</categorie>
//   </categories>
//   <visualization>
//     <columns>2</columns>
//   </visualization>
// </tool>

  public function __construct($conf) {
    parent::__construct();
    $this->loadXML("<tool></tool>");
    
    $name = $this->createElement("name");
    $name->appendChild($this->createTextNode($conf[name]));
    $this->documentElement->appendChild($name);
    
    $type = $this->createElement("type");
    $type->appendChild($this->createTextNode($conf[type]));
    $this->documentElement->appendChild($type);

    $categorie = $this->createElement("categorie");
    $categorie->appendChild($this->createTextNode($conf[categorie]));
    $columns = $this->createElement("columns");
    $columns->appendChild($this->createTextNode($conf[visualization]));
    
    $categories = $this->createElement("categories");
    $categories->appendChild($categorie);
    $visualization = $this->createElement("visualization");
    $visualization->appendChild($columns);

    $this->documentElement->appendChild($categories);
    $this->documentElement->appendChild($visualization);

  }
}

?>