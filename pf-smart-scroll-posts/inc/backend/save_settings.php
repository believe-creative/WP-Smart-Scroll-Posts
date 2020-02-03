<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//insert value if exists inside wp_option table if value is empty then the option value default will be set
$ssp_settings  = array();
$load_ajax_type  = sanitize_text_field($_POST['load_ajax_type']);
$main_container  = sanitize_text_field($_POST['main_container_class']);
$elementid       = sanitize_text_field($_POST['set_ajax_container_class']);
$post_limit       = sanitize_text_field($_POST['post_limit']);
if(empty($elementid)){
	$ajax_container = 'smart_ajax_container';
}else{
    $ajax_container = $elementid;
}
$markup_type     = sanitize_text_field($_POST['markup_type']);
$order_next_posts     = sanitize_text_field($_POST['order_next_posts']);
$post_link_target     = sanitize_text_field($_POST['post_link_target']);
$posts_featured_size     = sanitize_text_field($_POST['posts_featured_size']);
$content         = $_POST['custom_markup'];

$options_settings = array();
if ( isset( $_POST['ssp_options_settings']['options_settings'] ) ) {
	foreach ( $_POST['ssp_options_settings']['options_settings'] as $key => $value ) {
		$options_settings[] = sanitize_text_field($value);
	}
}
$category_options = array();  
if ( isset( $_POST['ssp_options_settings']['category_options'] ) ) {
	
				foreach ( $_POST['ssp_options_settings']['category_options'] as $keys => $values ) {
					   $category_options[] = sanitize_text_field($values);
				}
		
}else{
	$category_options[] = 'all';
}

 
$ajax_image_path =  sanitize_text_field($_POST['ssp_upload_image']);
if(!empty($ajax_image_path)){
   $ajaxloader_useruploadimg  = $ajax_image_path;
   $ajax_img_type   = sanitize_text_field($_POST['loader_set_type']);
}else{
	 $ajaxloader_useruploadimg  = '';
	 $ajax_img_type   = 'default_loader';
}

$ssp_settings['load_ajax_type']              = $load_ajax_type; 
$ssp_settings['appendElementClass']          = $main_container;
$ssp_settings['container_class']             = $ajax_container;
$ssp_settings['markup_type']                 = $markup_type;
$ssp_settings['order_next_posts']                 = $order_next_posts;
$ssp_settings['post_link_target']                 = $post_link_target;
$ssp_settings['posts_featured_size']                 = $posts_featured_size;
$ssp_settings['default_markup']      = "<article id='post-#postID' class='#post_class'>
<div class='main_content'>
<div class='posted-date'>
<span>#post_day</span>
<span>#post_month #post_year</span>
<span>Total Comment: #post_comment</span>
</div>
<div class='post-metas'>
<p><i class='fa fa-user'></i>By<span> #author_name</span></p>    
<p><i class='fa fa-folder'></i>Posted In<span> #category_name</span></p>
</span>
</div>
<div class='featured_image'><img src='#post_image'/></div>
<header class='entry-header'>
<h2 class='entry-title'><a href='#post_permalink' target='#post_target'>#post_title</a></h2>
</header>
<div class='entry-content'>
<p>#post_content</p></div>
</div></article>";

$ssp_settings['custom_markup']               = $content;
$ssp_settings['options_settings']            = $options_settings;

$ssp_settings['category_options']            = $category_options;


$ssp_settings['smart_scroll_ajax_image']     = $ajax_img_type;
$ssp_settings['post_limit']     = $post_limit;
$ssp_settings['smart_scroll_ajax_image_url'] = $ajaxloader_useruploadimg;
$ssp_settings['replace_url'] = intval($_POST['replace_url']);

update_option( SSP_SETTINGS, $ssp_settings);

$_SESSION['ssp_message'] = __( 'Smart Scroll Posts Data Saved Successfully.', 'smart-scroll-posts' );
wp_redirect( admin_url() . 'admin.php?page=ssp_scroll_form' );
exit;


//update_option will check to see if the option already exists.
// If it does not, it will be added with add_option('option_name', 'option_value'). 