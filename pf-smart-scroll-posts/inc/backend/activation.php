<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$ssp_settings = array();
   $options_settings = array(
    'single'
  );

$category_options = array('all');

$ssp_settings['load_ajax_type']      = "append_data_on";              // options append or on click button 
$ssp_settings['appendElementClass']  = "smart_scroll_container";     // to where the ajax data be appended in.append_container
$ssp_settings['container_class']     = "smart_ajax_container";      //  container load on each ajax load //ajax_posts_container
$ssp_settings['markup_type']         = "default_markup";           // html type custom or default used 
$ssp_settings['order_next_posts']    = "older_posts";           // html type custom or default used 
$ssp_settings['post_link_target']    = "_self";           
$ssp_settings['posts_featured_size']    = "large";           
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
$ssp_settings['custom_markup'] =  "<article id='post-#postID' class='#post_class'>
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
$ssp_settings['replace_url'] = "1";
$ssp_settings['post_limit'] = "";
$ssp_settings['smart_scroll_ajax_image'] = 'default_loader';
$ssp_settings['smart_scroll_ajax_image_url'] =  SSP_IMAGE_DIR. 'smart_scroll-ajax_loader.gif';

$ssp_settings['options_settings'] = $options_settings;
$ssp_settings['category_options'] = $category_options;

update_option( SSP_SETTINGS , $ssp_settings );
