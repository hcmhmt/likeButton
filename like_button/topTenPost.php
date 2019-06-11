<?php
/*
Plugin Name: Top 10 Posts 
Description: Top 10 Posts
Version:     0.0.1
Author:      hcmhmt - m-haci@hotmail.com 
License:     GPL2
*/

class likeNumbers extends WP_Widget {

    public function __construct()
    {
	    parent::__construct('widget_postLikeNum', 'Top 10 Post', [
        'classname' => 'widget_postLikeNum',
        'description' => 'Sorts your top 10 most popular post.'
    ]);
    }
    
    // Yönetim panelindeki görülecek alanı burada hazırlayacağız
    public function form($instance)
    {
		$widget_title = !empty($instance['widget_title']) ? $instance['widget_title'] : 'Top 10 Post';
		?>
			<p>
				<label for="<?php echo $this->get_field_id('widget_title') ?>">Başlık:</label>
				<input type="text" id="<?php echo $this->get_field_id('widget_title') ?>" name="<?php echo $this->get_field_name('widget_title') ?>" value="<?php echo $widget_title ?>">
			</p>
		<?php
    }
    

	public function update($new_instance, $old_instance)
	{

		$old_instance['widget_title'] = $new_instance['widget_title'];
		return $old_instance;
	}
    

    public function widget($args, $instance)
    {
		$widget_title = $instance['widget_title'];
		$title = apply_filters('widget_title', $instance['widget_title']);

		echo $args['before_widget']. $args['before_title']. $title .  $args['after_title'];

			global $wpdb;
	


	
	$posts = $wpdb->get_results("select p.id,p.post_title, count(l.post_id) as coun 
								from wp_posts p, like__button l 
								where p.id=l.post_id 
								group by p.post_title 
								order by coun desc 
								limit 0,10"); // datas for table of like
	$n=0;
	?>
	
	<link rel="stylesheet" href="<?php echo get_site_url()."/wp-content/plugins/like_button/public/css/";?>bootstrap.min.css">
	<table class="table table-bordered table-secondary">
		<thead >
			<tr class="table-active">
			     <th scope="col">#</th>
				 <th scope="col">Posts</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($posts as $likedPost ) {
				$n++;
				echo "<tr>
						<th scope='row'>".$n."</th>
						<td>".$likedPost->post_title."</td>
					  </tr>";
			}?>
		</tbody>
	</table>
	<?php
		//echo $args['after_widget'];
    }
}

function register_likeNumbers_widget(){
	register_widget('likeNumbers');
}

add_action('widgets_init','register_likeNumbers_widget');