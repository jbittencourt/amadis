
var message_empty_search = "You must provide an search parameter";

function Search_validateForm(searchString) {
  while(''+searchString.charAt(0)==' ') {
    searchString=searchString.substring(1,searchString.length);
  }
  if(searchString.length > 1) return true;
  else {
    window.alert(message_empty_search);
    return false;
  }
}