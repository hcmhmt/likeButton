<?php
/*
Plugin Name: Like Button Plugin
Description: Like Button Plugin
Version:     0.0.1
Author:      hcmhmt - m-haci@hotmail.com
License:     GPL2
*/


// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {	
	exit;	
}

require_once( $_SERVER["DOCUMENT_ROOT"].'/wordpress/wp-content/plugins/like_button/functions.php' );

add_action( 'wp_enqueue_scripts', 'wp_jq' );
add_action( 'wp_enqueue_scripts', 'wp_script' );
add_action( 'wp_enqueue_scripts', 'wp_style' );
add_action( 'admin_menu', 'like_statistic');
add_filter( 'the_content','functions');
add_action('admin_enqueue_scripts', 'admin_style');


function admin_style() {
		wp_enqueue_style('admin-styles', '/wp-content/plugins/like_button/public/css/bootstrap.min.css');
		}
function wp_jq() {
		$url= plugins_url("/public/js/jq.js",__FILE__);
		wp_register_script('jquery', $url);
		wp_enqueue_script('jquery');
		}
function wp_style() {
		$url= plugins_url("/public/css/buttons.css",__FILE__);
		wp_register_style('buttonCss', $url);
		wp_enqueue_style('buttonCss');
		}
function wp_script() {
		$url= plugins_url("/public/js/buttons.js",__FILE__);
		wp_register_script('buttonJs', $url);
		wp_enqueue_script('buttonJs');
		}		
		

function functions($content){
	
	$ip=getIP();
	if(IsLike(get_the_ID(),get_current_user_id(),$ip)){ //
		$button="<span class='btn1 like' id='buttonPost".get_the_ID()."' onclick='button_click(".(get_the_ID()).",this.id)'>Liked</span>";
	}else{
		$button="<span class='btn1 normal' id='buttonPost".get_the_ID()."' onclick='button_click(".(get_the_ID()).",this.id)'>Like</span>";
	}

	return $content.$button;
}

//admin menu
function like_statistic(){
 add_menu_page('Like Statistics','Like Statistics', 'manage_options', 'like-statistics', 'like_statistics_management');
}

function like_statistics_management(){
	global $wpdb;
	$rowcount = $wpdb->get_var("SELECT count(*) 
								FROM wp_terms term 
								WHERE EXISTS (
									SELECT * 
									FROM like__button as likebutton, wp_term_relationships as terml 
									WHERE likebutton.post_id=terml.object_id and terml.term_taxonomy_id=term.term_id)"); // row count
									
	$num_of_page=ceil($rowcount/10); //each page has 10 record
	if($_GET["table_page"]>0 && $_GET["table_page"]<=$num_of_page){
		$limit=($_GET["table_page"]*10)-10;
	}else {
		$limit=0;
	}
	
	$posts = $wpdb->get_results("SELECT name, count(post_id) as c
								from wp_terms term, like__button as likebutton, wp_term_relationships as terml
								where likebutton.post_id=terml.object_id and terml.term_taxonomy_id=term.term_id
								group by name
								order by c desc
								limit $limit,10"); // datas for table of like
	
	echo'
	<div class="container">
	<table class="table table-hover table-responsive-md table-bordered">
		<thead class="thead-dark">
			<tr class="row">
				
				<th class="col d-flex justify-content-center">Tag Name</th>
				<th class="col d-flex justify-content-center">Number of Likes</th>
			</tr>
		</thead>
		<tbody>';
				foreach ($posts as $likedPost ) {
					echo "<tr class='table-secondary row '><td class='col d-flex justify-content-center'>".$likedPost->name."</td>";
					echo "<td class='col d-flex justify-content-center'>".$likedPost->c."</td></tr>";
				}
		echo "</tbody></table><div class='row'>";
		
		for ($x=1;$x<=$num_of_page;$x++){
			echo "<a href='".admin_url("admin.php?page=like-statistics&table_page=$x")."' class='btn btn-dark mr-1'>".($x)."</a>";
		}
		
		echo "</div>
		</div>";//container
}



?>

