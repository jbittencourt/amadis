
function drawLog(param, numLines) {

  AMAdminLogs.drawLog(param, numLines, AMAdminLogsCallBack.ondrawLog);
}

AMAdminLogsCallBack = {
  ondrawLog : function(result) {
     var obj = AM_getElement("logger");
     var txt = "";
	    
	if(result != null){
	    for(i=0;i<result.length;i++){
    	 	txt += result[i]+"<br />";
	     }
     	obj.innerHTML = txt;
	    obj.style.display = "block";
    }else{
    	obj.style.display = "none";	
   } 
  }
}
