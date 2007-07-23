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


var Blog = {
	newPost : function() {
		$('new-post').toggle();
		$('frm_codePost').value = '';
		$('frm_title').value = '';

		/**
		 * TODO - Find a way to clean the editor when create a new post. 
		 */
		//enableDesignMode('frm_body', 'write', false);
	},

	cancelEdit : function() {
		$('new-post').hide();
	},	

	editPost : function(id) {
		$('new-post').show();
		window.location = "#editor";
		$('frm_codePost').value = id;
		$('frm_title').value = $('post-title-'+id).innerHTML;
		enableDesignMode('frm_body', $('post-content-'+id).innerHTML, false);
	},

	replyComment : function(codePost, codeComment) {
		if($('post_'+codePost).style == 'block') {
			$('post_'+codePost).hide();
			return;	
		}
		new Ajax.Updater('reply-'+codeComment, 'ajax/comments.php?frm_action=reply_comment', {
			parameters : '&frm_codePost='+codePost+'&frm_codeComment='+codeComment
		});
		$('reply-'+codeComment).toggle();
	},
	
	loadComments : function(codePost) {
		if($('post_'+codePost).style == 'block') {
			$('post_'+codePost).hide();
			return;	
		}
		new Ajax.Updater('post_'+codePost, 'ajax/comments.php?frm_codePost='+codePost, {
			parameters : { evalScripts : true }
		});
		$('post_'+codePost).toggle();
	}
}