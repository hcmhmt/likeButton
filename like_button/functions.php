<?php
function createTable($tableName){
    $sql = "CREATE TABLE IF NOT EXISTS {$tableName} (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `post_id` bigint(20) unsigned NOT NULL,
        `user_id` bigint(20) unsigned NOT NULL,
        `ip` varchar(40) NOT NULL,
        `created_at` datetime NOT NULL,
        PRIMARY KEY (`id`),
        KEY `post_id` (`post_id`),
		KEY `ip` (`ip`),
        KEY `user_id` (`user_id`)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function insertLike($post_id,$user_id,$ip,$tableName){
	$date=date("Y/m/d H:i");
	$sql="INSERT INTO ".$tableName." (post_id,user_id,ip,created_at)values('".$post_id."','".$user_id."','".$ip."','".$date."')";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
function deleteLike($post_id,$user_id,$ip,$tableName){
	
	global $wpdb;
     $wpdb->query( $wpdb->prepare( "DELETE FROM ".$tableName." WHERE post_id = %d and user_id =%d and ip=%s ",$post_id,$user_id,$ip));

}

function PostControl($post_id){
	
	global $wpdb;
    $rowcount =$wpdb->get_var("select * from wp_posts where id=$post_id and post_status='publish'");
	if($rowcount){
		return true;
	}else{
		return false;
	}
	
}

function LikeControl($post_id,$user_id,$ip){
	global $wpdb;
    $rowcount =$wpdb->get_var("SELECT COUNT(id) FROM like__button WHERE (user_id =".$user_id." And ip = '".$ip."') AND post_id = ".$post_id);
	if($rowcount){
		return true;
	}else{
		return false;
	}
}

function IsLike($post_id,$user_id,$ip){
	global $wpdb;
	$rowcount = $wpdb->get_var("SELECT COUNT(id) FROM like__button WHERE (user_id =".$user_id." AND ip = '".$ip."') AND post_id = ".$post_id);
	
	if($rowcount){
		return true;
	}else{
		return false;
	}
}
		
function getIP() {
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip=$_SERVER["HTTP_CLIENT_IP"];
	}elseif(!empty($_SERVER["HTTP_X_FORWARDER_FOR"])){
		$ip=$_SERVER["HTTP_X_FORWARDER_FOR"];
	}else{
		$ip=$_SERVER["REMOTE_ADDR"];
	}
	return $ip;
}


?>