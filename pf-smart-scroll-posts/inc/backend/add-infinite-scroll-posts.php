<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
    <div class="clear" style="clear:both;"></div>
    <div class="wrap">
    <div class="ssp-backend-wrapper clearfix">
        <div class="ssp-form-wraper">
            <div class="ssp-head">
                <div class="ssp-setting-header clearfix">
                    <div class="ssp-title-section"><?php _e( 'Smart Scroll Posts', 'smart-scroll-posts' ); ?></div>
                    <p>Version <?php echo SSP_VERSION;?></p>
                    <!-- <div class="ssp-right-header-block">
                        <div class="ssp-header-icons">
                            <p><?php _e( 'Follow us for new updates', 'smart-scroll-posts' ); ?></p>
                            <div class="ssp-social-bttns">
                                <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FAccessPress-Themes%2F1396595907277967&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=1411139805828592" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px; width:50px " allowtransparency="true"></iframe>
                                &nbsp;&nbsp;
                                <iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" src="http://platform.twitter.com/widgets/follow_button.5f46501ecfda1c3e1c05dd3e24875611.en.html#_=1421918256492&amp;dnt=true&amp;id=twitter-widget-0&amp;lang=en&amp;screen_name=apthemes&amp;show_count=false&amp;show_screen_name=true&amp;size=m" class="twitter-follow-button twitter-follow-button" title="Twitter Follow Button" data-twttr-rendered="true" style="width: 126px; height: 20px;"></iframe>
                                <script>!function (d, s, id) {
                                        var js, fjs = d.getElementsByTagName(s)[0];
                                        if (!d.getElementById(id)) {
                                            js = d.createElement(s);
                                            js.id = id;
                                            js.src = "//platform.twitter.com/widgets.js";
                                            fjs.parentNode.insertBefore(js, fjs);
                                        }
                                    }(document, "script", "twitter-wjs");</script>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <?php
             $options = get_option( SSP_SETTINGS );
             //print_r($options);

            if( isset( $_SESSION['ssp_message'] ) ) { ?>
                            <div class="ssp_message">
                                <p>
                                <?php
                                    echo $_SESSION['ssp_message'];
                                    unset( $_SESSION['ssp_message'] );
                                ?>
                                </p>
                            </div>
                        <?php
            } ?>
            <form action="<?php echo admin_url() . 'admin-post.php' ?>" method='post'>
                    <ul class="ssp-setting-tabs">
                        <li class="ssp-active-tab"><a href="javascript:void(0)" id="ssp-custom-css-settings" class="ssp-tabs-trigger">
                        <?php _e( 'Scroll Settings', 'smart-scroll-posts' ); ?></a>
                        </li>
                         <!-- <li><a href="javascript:void(0)" id="ssp-optionsettings" class="ssp-tabs-trigger">
                         <?php _e( 'Options', 'smart-scroll-posts' ); ?></a>
                         </li> -->
                        <!-- <li><a href="javascript:void(0)" id="ssp-about" class="ssp-tabs-trigger">
                        <?php _e( 'More WordPress Stuff', 'smart-scroll-posts' ); ?>
                         </a>
                         </li> -->
                    </ul>

                <div class='ssp-tab-contents ssp-active-tab' id='tab-ssp-custom-css-settings'>
                        <p class="ssp-info"><?php _e( 'Define / setup the plugin here. ', 'smart-scroll-posts' ); ?> </p>
                      
                      <!--hidden field for add action hook name for admin_post_ save settings--> 
                        <input type="hidden" name="action" value="ssp_save_options" />
                 
                 
                  <div class="ssp-field-wrapper form-field clearfix">
                     <div class="ssp-row">
                           <div class="ssp-col-full">
                                <label><?php _e('Load Ajax Type', 'smart-scroll-posts'); ?></label>
                                 <div class="ssp-field">
                                    <select name="load_ajax_type" class="load_ajax_type">
                                        <option value="append_data_on" <?php if(isset($options['load_ajax_type']) == 'append_data_on') echo 'selected';?>><?php _e( 'Append Posts On Page Scroll', 'smart-scroll-posts' ); ?></option>                                     
                                    </select>
                                 </div>
                                
                            </div>
                             <div class="ssp-col-full3" style="<?php if(isset($options['load_ajax_type']) == 'append_data_on'){
                             echo 'display:block'; }else { echo 'display:none'; } ?>">
                                    <label class="display"><?php _e('Main Container Class', 'smart-scroll-posts'); ?></label>
                                <div class="ssp-field">
                                <?php $appendClass = ( isset( $options['appendElementClass'] ) ) ? $options['appendElementClass'] : ''; ?>
                                  <input type="text"  name="main_container_class" class="ssp_input_field" value="<?php echo esc_attr( $appendClass ); ?>"/>
                                  <span class="default_smart_value"> <?php _e('Default Value : smart_scroll_container', 'smart-scroll-posts'); ?> </span>
                                    <p><?php _e( 'Note: Main Container Class where ajax posts will be appended.', 'smart-scroll-posts' ); ?> </p>                      
                                 </div>
                            </div>


                            <div class="ssp-col-full ssp-col-fulll">
		                        <label><?php _e('AJAX Container Class', 'smart-scroll-posts'); ?></label>
		                        <div class="ssp-field">
                             <?php $container_class = ( isset( $options['container_class'] ) ) ? $options['container_class'] : ''; ?>
		                            <input type="text" name="set_ajax_container_class" class="ssp_input_field" placeholder="eg. ajax_posts_container" value="<?php echo esc_attr( $container_class ); ?>"/>
                                <span class="default_smart_value"><?php _e('Default Value : smart_ajax_container', 'smart-scroll-posts'); ?></span>
		                        <p><?php _e( 'Note: Ajax div container appended on each infinte page scroll ajax call.', 'smart-scroll-posts' ); ?> </p>                      
                            </div>
		                        <div class="ssp-error"></div>
                            </div>

                        <div class="ssp-col-full">
                            <label><?php _e('Choose Ajax Loader type', 'smart-scroll-posts'); ?></label>
                            <div class="ssp-field">
                                <label class="label-inline"><input type="radio" name="loader_set_type" value="default_loader" <?php if(isset($options['smart_scroll_ajax_image']) && $options['smart_scroll_ajax_image'] == 'default_loader') echo 'checked="checked"';?>/><?php _e('Default ajax loader', 'smart-scroll-posts'); ?></label>
                                <label class="label-inline"><input type="radio" name="loader_set_type" value="custom_loader" <?php if(isset($options['smart_scroll_ajax_image']) && $options['smart_scroll_ajax_image'] == 'custom_loader') echo 'checked="checked"';?>/><?php _e('Upload Own Image', 'smart-scroll-posts'); ?></label>
                            </div>
                                  
                        </div>

                        <?php include_once('ajax_loader_adder.php'); ?>

                          <div class="ssp-col-full">
            									<label class="display"><?php _e('Change URL ?', 'smart-scroll-posts'); ?></label>
            								<div class="ssp-field">
            								<?php if(isset($options['replace_url'])){
            								      $rurl = $options['replace_url']; } ?>
            									<label class="label-inline">
            									   <input type="radio" <?php if(isset($rurl) && $rurl == 1) echo 'checked="checked"';?> value="1" name="replace_url">
            									    <?php _e('YES', 'smart-scroll-posts'); ?>
            									</label>
            								    <label class="label-inline">
            									   <input type="radio" <?php if(isset($rurl) && $rurl == 0) echo 'checked="checked"';?> value="0" name="replace_url">
            									   <?php _e('NO', 'smart-scroll-posts'); ?>
            								   </label><br/>
                              <span class="default_smart_value"><?php _e( 'Select YES - to change URL when a next post is loaded. ', 'smart-scroll-posts' ); ?> </span>                      
            								 </div>
            							</div>
                             <!-- <div class="ssp-col-full ssp-col-fulll">
                                <label class="display"><?php _e('Posts Limit', 'smart-scroll-posts'); ?></label>
                                <div class="ssp-field">
                                <?php $post_limit = ( isset( $options['post_limit'] ) ) ? $options['post_limit'] : ''; ?>
                                  <input type="number" name="post_limit"  value="<?php echo esc_attr( $post_limit ); ?>"/>
                                  <br/><span class="default_smart_value"> <?php _e('Fill number of posts you want to display on next posts on page scroll.If left empty will load infinite posts of first post of same category.', 'smart-scroll-posts'); ?> </span>                    
                                 </div>
                            </div> -->

                          <div class="ssp-col-full">
                             <label><?php _e('Choose New/Old Posts', 'smart-scroll-posts'); ?></label>
                                            <div class="ssp-field">
                                                <select name="order_next_posts" class="accesspress-ssp_posts">
                                                     <option value="newer_posts" <?php if(isset($options['order_next_posts']) && $options['order_next_posts'] == 'newer_posts') echo 'selected="selected"';?>><?php _e('Old Posts', 'smart-scroll-posts'); ?></option> 
                                                    <option value="older_posts" <?php if(isset($options['order_next_posts']) && $options['order_next_posts'] == 'older_posts') echo 'selected="selected"';?>><?php _e('New Posts', 'smart-scroll-posts'); ?></option>
                                                </select>
                                                <p class="description"><?php _e('Choose which posts to show next on page scroll i.e next and previous posts.','smart-scroll-posts');?></p>
                                            </div>
                            </div>

                            <!-- <div class="ssp-col-full">
                             <label><?php _e('Choose Posts Link Target', 'smart-scroll-posts'); ?></label>
                                            <div class="ssp-field">
                                             <select name="post_link_target">
                                                     <option value="_blank" <?php if(isset($options['post_link_target']) && $options['post_link_target'] == '_blank') echo 'selected="selected"';?>><?php _e('_blank','smart-scroll-posts');?></option>
                                                     <option value="_self" <?php if(isset($options['post_link_target']) && $options['post_link_target'] == '_self') echo 'selected="selected"';?>><?php _e('_self','smart-scroll-posts');?></option>
                                                     <option value="_parent" <?php if(isset($options['post_link_target']) && $options['post_link_target'] == '_parent') echo 'selected="selected"';?>><?php _e('_parent','smart-scroll-posts');?></option>
                                                     <option value="_top" <?php if(isset($options['post_link_target']) && $options['post_link_target'] == '_top') echo 'selected="selected"';?>><?php _e('_top','smart-scroll-posts');?></option>
                                                    </select>
                                              
                                                <p class="description"><?php _e('Choose posts link target for all next posts after scroll.','smart-scroll-posts');?></p>
                                            </div>
                            </div> -->

                              <!-- <div class="ssp-col-full">
                             <label><?php _e('Posts Featured Image Size', 'smart-scroll-posts'); ?></label>
                                            <div class="ssp-field">
                                            <?php $image_sizes = PF_SSP_Smart_Scroll_Posts::ssp_get_image_sizes(); ?>
                                                <select name="posts_featured_size">
                                                <?php if(isset($image_sizes) && !empty($image_sizes)):
                                             foreach ($image_sizes as $size_name => $key): 
                                              ?>
                                                    <option value="<?php echo $size_name;?>" <?php if(isset($options['posts_featured_size']) &&
                                                     $options['posts_featured_size'] == $size_name) echo 'selected="selected"';?>><?php echo ucwords($size_name);?></option>
                                                  
                                               <?php endforeach; 
                           endif;
                           ?>
                                               </select>
                                                <p class="description"><?php _e('Choose which posts to show next on page scroll i.e new posts or older posts.','smart-scroll-posts');?></p>
                                            </div>
                            </div> -->

                
                            <!-- Commented by Srinu -->
                           <!-- <div class="ssp-col-full">
                             <label><?php _e('Choose HTML Markup', 'smart-scroll-posts'); ?></label>
                                            <div class="ssp-field">
                                                <select name="markup_type" class="accesspress-ssp_html_markup">
                                                    <option value="default_markup" <?php if(isset($options['markup_type']) && $options['markup_type'] == 'default_markup') echo 'selected="selected"';?>><?php _e('Default Markup', 'smart-scroll-posts'); ?></option>
                                                    <option value="custom_markup" <?php if(isset($options['markup_type']) && $options['markup_type'] == 'custom_markup') echo 'selected="selected"';?>><?php _e('Custom', 'smart-scroll-posts'); ?></option>
                                                </select>
                                            </div>
						               	</div>

                             <div class="ssp-col-full1" style="<?php if($options['markup_type'] == 'default_markup'){
                                             echo 'display:block'; }else { echo 'display:none'; } ?>">
                                  <label class="display"><?php _e('DEFAULT MARKUP', 'smart-scroll-posts'); ?></label>
                                <div class="ssp-field">
                                   <textarea name="default_markup" id="default_markup" readonly="readonly"><?php if(isset($options['default_markup'])) echo esc_attr(stripslashes($options['default_markup']));?></textarea>
                                    <span class="default_smart_value">
                                     <?php _e('NOTE: Default Html Markup Setup appended inside each Ajax Call', 'smart-scroll-posts'); ?>
                                    </span>
                                 </div>
                              </div>

                							 <div class="ssp-col-full2" style="<?php if($options['markup_type'] == 'custom_markup'){
                                             echo 'display:block'; }else { echo 'display:none'; } ?>">
                									<label class="display"><?php _e('CUSTOM MARKUP', 'smart-scroll-posts'); ?></label>
                								<div class="ssp-field">
                						       <textarea name="custom_markup" id="custom_markup"><?php if(isset($options['custom_markup'])) echo esc_attr(stripslashes($options['custom_markup']));?></textarea>
                								<br/>
                                <span class="default_smart_value">
                                <?php _e('IMPORTANT NOTE:<br/>You can change html classes.Tags such as #post_title, #post_content, #post_image are compulsory to display Post title, contents and Featured Image.', 'smart-scroll-posts'); ?><br/>
                                <?php _e('Extra tags added to display with dynamic post id, post class , author name, posts category lists, date in year, month, day, posts 
                                comment number, post link target and post permalink, simply use 
                                tags along with # symbol as #postID, #post_class, #author_name, #category_name, #post_year, 
                                #post_day, #post_month, #post_comment, #post_target and #post_permalink.<br/>Please use same tag name for all.','smart-scroll-posts');?>
                                </span>
                                 </div>
                							</div> -->

                       </div>
                   </div><!-- ssp-field-wrapper close -->
                </div>

       <!-- options settings start -->
       <!-- <div class='ssp-tab-contents ssp-active-tab' id='tab-ssp-optionsettings' style="display:none;">

                    <h3><?php _e( 'Choose Options:', 'smart-scroll-posts' ); ?> </h3>
                    <span class="social-text">
                    <?php _e( 'Please choose the options where you want to load ajax contents:', 'smart-scroll-posts' ); ?>
                    </span>

                     <p>
                     <input type="checkbox" id="ssp_singleposts" value="single" name="ssp_options_settings[options_settings][]" 
                    <?php if ( in_array( "single", $options['options_settings'] ) || 
                        in_array( "single", $options['options_settings'] ) ) {
                        echo "checked='checked'";
                        } ?> >
                      <label for="ssp_singleposts"><?php _e( 'Single Posts', 'smart-scroll-posts' ); ?>
                     </label>
                     </p>

                    <p>
                    <input type="checkbox" id="ssp_single_page" value="single_page" 
                    name="ssp_options_settings[options_settings][]" 
                    <?php if ( in_array( "single_page", $options['options_settings'] ) ) {
                    echo "checked='checked'";
                    } ?> >

                    <label for="ssp_single_page"><?php _e( 'Single Page', 'smart-scroll-posts' ); ?></label>

                     <span class="social-text">
                       <?php _e( '( Available For Pro Version Only )', 'smart-scroll-posts' ); ?>
                    </span>
                    </p>

                    <br/>

                    <h3><?php _e( 'Choose Category:', 'smart-scroll-posts' ); ?></h3>
                    <span class="social-text">
                    <?php _e( 'Please choose the categories:', 'smart-scroll-posts' ); ?>
                    </span>

                          <?php
                          $ssp_object = new PF_SSP_Smart_Scroll_Posts();
                          $posts_types = $ssp_object->get_registered_post_types();
                            foreach ($posts_types as $post) {
                            
                             $taxonomies = get_object_taxonomies($post, 'objects');
                              if(!empty($taxonomies)){
                            echo "<p>".ucwords($post)."</p>";
                            }
                            foreach ($taxonomies as $key) {
                               $post_args = array();
                               $post_types = get_post_types($post_args);
                                $terms = get_terms($key->name, array(
                                  'orderby'    => 'count',
                                  'hide_empty' => 0,
                                  ) ); 
                             foreach ($terms as $term) {
                             
                                $termid  = $term->term_id;?>

                                <input type="checkbox" id="ssp_category_select_<?php echo $termid;?>" <?php if ( in_array(  $termid , $options['category_options'] ) ) {
                                          echo "checked='checked'";
                                          } ?> value="<?php echo $term->term_id;?>" name="ssp_options_settings[category_options][]" />
                                          <label for="ssp_category_select_<?php echo $termid;?>"> <?php echo $term->name; ?></label>
                            <?php 
                                 } 
                               }
                            }  ?>
                    <br/><br/>
      <input type="checkbox" id="ssp_category_select_all" <?php if ( in_array( 'all', $options['category_options'] ) ) {
       echo "checked='checked'"; } ?> value="all" name="ssp_options_settings[category_options][]" />
        <label for="ssp_category_select_all"> <?php _e('ALL', 'smart-scroll-posts'); ?></label>
    </div> -->
    <!-- options settings end -->
    <!-- about start -->
                    <div class='ssp-tab-contents' id='tab-ssp-about' style="display:none;">
                        <?php include( 'ssp-about.php' ); ?>
                    </div>
    <!-- about end -->
                    <div class="ssp-actions">
                        <input type="submit" class="ssp-bottom-submit" name='ssp_save_settings' value="<?php _e( 'Save', 'smart-scroll-posts' ); ?>" />

                        <?php wp_nonce_field( 'ssp_nonce_save_settings', 'ssp_add_nonce_save_settings' ); ?>

                        <?php $nonce = wp_create_nonce( 'ssp-restore-default-settings-nonce' ); ?>
  <a class="ssp-btn-wrap" href="<?php echo admin_url() . 'admin-post.php?action=ssp_restore_default_settings&_wpnonce=' . $nonce; ?>" 
  onclick="return confirm('<?php _e( 'Are you sure you want to restore default settings?', 'smart-scroll-posts' ); ?>')">
  <input type="button" value="<?php _e( 'Restore Default Settings', 'smart-scroll-posts' ); ?>" class="ssp-reset-button"/>
  </a>
                    </div>
            </form>
        </div><!-- ssp-form-wraper -->
    </div> <!--  ssp backend wrapper -->
    </div> <!-- wrap -->