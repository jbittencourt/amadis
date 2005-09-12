
var message_empty_search = "You must provide an search parameter";

function Search_validateForm(searchString) {
  if(searchString.length > 1) return true;
  else {
    window.alert(message_empty_search);
    return false;
  }
}