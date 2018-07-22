// logging for client console
function fviva_log( s ) {
	if ( typeof console != 'undefined' && console.log ) {
		console.log( s );
	}
}

// load widget
jQuery(document).ready(function($) {
	// get page id
	var url; 
	if ( fviva_global_data.permalink ) {
		url = fviva_global_data.permalink;
	}  else {
		url = window.location.href;
		url = url.replace(/#.*/, ''); 
	} 
 
 FB.Event.subscribe('comment.create', function (response) {
            console.log('create', response);
            
            var href = response.href;
		      var commentID = response.commentID;
		      var parentCommentID = response.parentCommentID;

         console.log("comment created");
            var commentQuery = FB.Data.query("SELECT text, fromid FROM comment WHERE post_fbid='" + response.commentID +
                "' AND object_id IN (SELECT comments_fbid FROM link_stat WHERE url='" + response.href + "')");
            var userQuery = FB.Data.query("SELECT name FROM user WHERE uid in (select fromid from {0})", commentQuery);
            FB.Data.waitOn([commentQuery, userQuery], function () {
            	 var commentRow = commentQuery.value[0];
                var userRow = userQuery.value[0];
                var commentText = commentRow.text;
                var userName = userRow.name;
                var userId = commentRow.fromid;
                
            var data = {
				"action": "fviva_comment_created",
				"href": href,
				"commentID": commentID,
				"parentCommentID": parentCommentID,
				"commentText": commentText, 
				"userName": userName,
				"userId": userId
			};
        $.post( fviva_global_data.ajaxurl, data , function(jsonstr){
				var response = $.parseJSON(jsonstr);
				if ( response ) {
					if ( ! response.status ) {
						fviva_log( response );
						console.log(response);
					}
				}
			});   
                
            });   
});
       
    FB.Event.subscribe('comment.remove',
        function (response) {
        	 console.log('remove', response);
        });

});

