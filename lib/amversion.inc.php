<?
class AMVersion {

  public function version($id){
    global $_CMAPP;

    $fileLib = new AMLibraryFiles;

    //receive ID like 'state_fileCode' and split to work with the both infos individualy
    $oldId = $id;
    $ids = split("_", $id);
    //for a easier way to undestand the code i will extract the 2 info from array and put it in diff vars
    $state = $ids[0]; $fileCode = $ids[1];

    $fileLib->filesCode = $fileCode;
    try{
      $fileLib->load();
    }catch(AMException $e){ echo $e->getMessage(); }
    
    //change all states  and set new variables..
    /**
    if($state == "shared"){  
      $newState = "unshared";      
      $url = "$_CMAPP[media_url]/images/img_blt_ico_eye_off.gif";

      //so, its shared at DB and i will set 'n' to the file to make it unshare

      $fileLib->unsetShared();

    }
    else{
      $newState = "shared";
      $url = "$_CMAPP[media_url]/images/img_blt_ico_eye_on.gif";

      //in this case, ill make the inverse..setting 'y' to the file
      
      $fileLib->setShared();
    }
    **/

    $fileLib->save();
    $newId = $newState."_".$fileCode;
    
    //save it and return..
    $result = array();
    $result['url'] = $url;
    $result['id'] = $newId;
    $result['oldId'] = $oldId;

    return $result;
    
  }

}

?>