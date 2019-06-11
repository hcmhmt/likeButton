function button_click(post,buttonId){
	id='#'+buttonId; //converts to jquery type
		if(jQuery(id).hasClass("normal")){
			updateButton(post,id);
	    }else if(jQuery(id).hasClass("like")){
			updateButton(post,id)
		}
}
function updateButton(postInfo,buttonId){
	jQuery.ajax({
		type:"POST",
		url:"/wordpress/wp-content/plugins/like_button/update.php",
		cache:false,
		data:{info:postInfo},
		success : function( response ) {
			if(response == 1)	{
				activateNormalButton(buttonId);
			}
				
			else if(response == 2)	
				activateLikeButton(buttonId);
			else
				return 0;		
		},
		error : function(r){
			return 0;
		}
	});

}

function activateNormalButton(buttonId){
	jQuery(buttonId).removeClass("like");
	jQuery(buttonId).addClass("normal");
	jQuery(buttonId).text("Like");
}

function activateLikeButton(buttonId){
	jQuery(buttonId).removeClass('normal');
	jQuery(buttonId).addClass('like');
	jQuery( buttonId ).text( "Liked" );
}