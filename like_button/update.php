<?php

require_once( $_SERVER["DOCUMENT_ROOT"]. '/wordpress/wp-config.php' );
require_once( $_SERVER["DOCUMENT_ROOT"].'/wordpress/wp-content/plugins/like_button/functions.php' );

	if ( ! defined( 'ABSPATH' ) ) {	
		define('ABSPATH', dirname(__FILE__) . '/');	
	}

	$tableName="like__button";
	$post_id=$_POST["info"];
	$user_id=get_current_user_id();
	
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip=$_SERVER["HTTP_CLIENT_IP"];
	}elseif(!empty($_SERVER["HTTP_X_FORWARDER_FOR"])){
		$ip=$_SERVER["HTTP_X_FORWARDER_FOR"];
	}else{
		$ip=$_SERVER["REMOTE_ADDR"];
	}
	
	if(PostControl($post_id)){ //controls the post id for security
		if(LikeControl($post_id,$user_id,$ip)) // check the post for that Is post is liked?
		{
			deleteLike($post_id,$user_id,$ip,$tableName);
			echo 1; // it means the like status is changed to normal.
		}
		else
		{
			createTable($tableName);
			insertLike($post_id,$user_id,$ip,$tableName);
			echo 2; // it means the post is liked.
		}
	}
	


	?>