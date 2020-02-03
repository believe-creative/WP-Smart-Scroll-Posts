<?php defined('ABSPATH') or die("No script kiddies please!"); 
$css_style = "style='display:none;'";
$css_style_block = "style='display:block;'";
if(isset($options['smart_scroll_ajax_image'])){
   $ajax_image_type = $options['smart_scroll_ajax_image'];
}
if(isset($options['smart_scroll_ajax_image_url']) && $ajax_image_type != 'default_loader'){
   $ajax_image_path = esc_url($options['smart_scroll_ajax_image_url']);
}else{
  $ajax_image_path= '';
}
?>
    <div class="ssp-col-full default_loader_content" <?php if(isset($ajax_image_type) &&  $ajax_image_type == "default_loader") echo $css_style_block ; else echo $css_style; ?>>
               <div class="ssp-field-wrapper form-field">
                    <label><?php _e('Loader Image Preview', 'smart-scroll-posts'); ?></label>
                    <div class="ssp-field image-wrapper">
                        <img src="<?php echo SSP_IMAGE_DIR.'smart_scroll-ajax_loader.gif';?>" id="ssp-ajax-loader"/>
                    </div>
                </div>
    </div>
     <div class="ssp-col-full custom_loader_content" <?php if(isset($ajax_image_type) && $ajax_image_type == "custom_loader") echo $css_style_block ; else echo $css_style; ?>>
               <div class="ssp-field-wrapper form-field">
                    <label><?php _e('Loader Image', 'smart-scroll-posts'); ?></label>
                    <div class="ssp-field">
                       <label for="upload_image">
							<input id="ssp_upload_image" type="text" size="36" name="ssp_upload_image" value="<?php echo $ajax_image_path; ?>" />
							<input id="ssp_upload_image_button" type="button" class="button button-primary"  value="Upload Image" />
							<br /><?php _e('Enter an URL or upload an image for the ajax loader.', 'smart-scroll-posts'); ?>
							</label>
                    </div>
                </div><!--ssp-field-wrapper-->
    </div>