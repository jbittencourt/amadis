<?


class AMDiarioPost extends CMObj {

   public function configure() {
    $this->setTable("DiarioPosts");

     $this->addField("codePost",CMObj::TYPE_INTEGER,20,1,0,1);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("titulo",CMObj::TYPE_VARCHAR,100,1,0,0);
     $this->addField("texto",CMObj::TYPE_TEXT,65535,1,0,0);
     $this->addField("tempo",CMObj::TYPE_INTEGER,11,1,0,0);

     $this->addPrimaryKey("codePost");

  }


  /**
   *
   * Funcao destinada a listagem de comentarios de um post, a partir do codigo desde post. 
   *
   * @access public
   * @return object Retorna lista de comentarios de um post.
   *
   */
  function listComments (){
    $query=new CMQuery(AMDiarioComentario);
    $query->setOrder("AMDiarioComentario::time desc");
    $query->setFilter("codePost = ".$this->codePost);

    $j = new CMJoin(CMJoin::INNER);
    $j->on("AMDiarioComentario::codeUser=AMUser::codeUser");
    $j->setClass(AMUser);
    $query->addJoin($j,"user");


    return $query->execute();
  }


  function countComments (){
    $query=new CMQuery(AMDiarioComentario);
    $query->setFilter("codePost = ".$this->codePost);
    $query->setCount();
    $result=$query->execute();
    return $result;
  }


}

?>
