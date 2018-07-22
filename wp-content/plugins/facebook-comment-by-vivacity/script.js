jQuery(document).ready(function($){
   
   jQuery("#shortcodedesc-comments").click(function(){
      jQuery("#shortcodedesctbl-comments").animate({
        height:'toggle'
      });
  });  
  jQuery("#donatehere-comments").click(function(){
      jQuery("#donateheretbl-comments").animate({
        height:'toggle'
      });
  }); 
   jQuery("#download-comments").click(function(){
      jQuery("#downloadtbl-comments").animate({
        height:'toggle'
      });
  }); 
  jQuery("#aboutauthor-comments").click(function(){
      jQuery("#aboutauthortbl-comments").animate({
        height:'toggle'
      });
  });
  

  
});


function setFries(){
    var el = document.getElementById("fbComments_hideWpComments");
    var st = document.getElementById("selected_types");
    if(el.checked){
      document.getElementById("posts_hideWpComments").disabled = true;
      document.getElementById("pages_hideWpComments").disabled = true;
    } else if(st.checked) {
     document.getElementById("selected_types").checked = true;
     document.getElementById("posts_hideWpComments").disabled = false;  
     document.getElementById("pages_hideWpComments").disabled = false; 
     }  
     else {
     	document.getElementById("posts_hideWpComments").disabled = true;
      document.getElementById("pages_hideWpComments").disabled = true;
     	}
  }


	
