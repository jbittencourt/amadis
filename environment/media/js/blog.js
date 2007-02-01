var blog_delete_message = "Do you really want to delete this blog post?";
var blog_delete_link;
var blog_handler_url;
var blogs_dcom = new Array();

var Blog_ImageOn = new Image();
var Blog_ImageOff = new Image();

function Blog_preLoadImages(image_url_on,image_url_off) {
  Blog_ImageOn.src = image_url_on;
  Blog_ImageOff.src = image_url_off;
}

function Blog_toogleComments(codePost) {
    var result = AMBlog.getCommentsToPost(codePost);
    
    if(AM_togleDivDisplay("post_"+codePost)=="opened") { 
        AM_getElement("post_"+codePost).innerHTML = result.box;
        AM_changeImage("post_comments_"+codePost,Blog_ImageOn);  
    }  else {
        AM_changeImage("post_comments_"+codePost,Blog_ImageOff);
    }
}


function Blog_loadComments(codePost) {
  var div = AM_getElement("post_"+codePost);
  var frame = AM_getElement(blogs_dcom[codePost],dcom_doc);
  if(frame==null) return false;
  AM_loadIframeIntoDiv(frame,div);
}


function Blog_deletePost(codePost){
  if ( confirm(blog_delete_message))
    location.href=blog_delete_link+codePost;

  return false;
}
