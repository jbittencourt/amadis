<?

$url = $_CMAPP[media_url];
$path = "cmwebservice/cmwsmartform/media/javascript/htmlarea";

echo "_editor_url = '$url/mediawrapper.php?type=js&frm_file=$path/';";
echo "_editor_url_js = '$url/mediawrapper.php?type=js&frm_file=$path/';";
echo "_editor_url_plugins = ''; ";
echo "_editor_url_images = '$_CMAPP[images_url]/htmlarea/';";
echo "_editor_url_css = '$url/mediawrapper.php?type=js&frm_file=$path/';";
echo "_editor_url_popups = '$url/mediawrapper.php?type=js&frm_file=$path/';";

?>
//    echo "HTMLArea.loadPlugin(\"FullPage\");";
function initDocument() {
//    echo "  editor.registerPlugin(FullPage);";


//Example on how to add a custom button when you construct the HTMLArea:
//echo "for (var i in editor) { document.write(i+"<br>") }";
  var editor = new HTMLArea("<?=$_SESSION[smartform][cmwhtmlarea][name]?>");
  var cfg = editor.config; // this is the default configuration;

  cfg.btnList["btn-save"] =
    [ function(editor) { document.getElementById('form_file').submit(); },
      "<?=$_language[save]?> ", // tooltip
      "<?=$_CMAPP[images_url]?>/save.gif", // image
      false // disabled in text mode
      ];
  cfg.toolbar.push(["linebreak", "btn-save"]); // add the new button to the toolbar
  
  //cfg.registerButton("btn-save", "<?=$_language[save]?>", "<?=$_CMAPP[images_url]?>/save.gif.gif", false, function(editor) { alert("merda"); });
  cfg.registerButton({
      id       : "btn-save",      // the ID of your button
      tooltip  : "<?=$_language[save]?>",    // the tooltip
      image    : "<?=$_CMAPP[images_url]?>/save.gif",  // image to be displayed in the toolbar
      textMode : false,            // disabled in text mode
      action   : function(editor) { // called when the button is clicked
	var form = window.document.getElementById("form_file");
	var textarea = window.document.getElementById("frm_file_content");
	textarea.value = editor.getHTML();
	form.submit();
      },
	// will be disabled if outside a <p> element
  });

  editor.generate();


};




