<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" );
/*
	Plugin name: Playfields Smart Scroll Posts
	Description: An awesome and most powerful plugin for infinite posts load on page scroll functionality to your website.
	Version: 1.0.0
	Author: Playfields
	Author URI: https://www.playfields.co
	Text Domain: smart-scroll-posts
	Domain Path: /languages/
	License: GPLv2 or later
*/

//Decleration of the necessary constants for plugin
if( !defined( 'SSP_VERSION' ) ) {
    define( 'SSP_VERSION', '1.0.0' );
}

if( !defined( 'SSP_IMAGE_DIR' ) ) {
    define( 'SSP_IMAGE_DIR', plugin_dir_url( __FILE__ ) . 'images/' );
}

if( !defined( 'SSP_JS_DIR' ) ) {
    define( 'SSP_JS_DIR', plugin_dir_url( __FILE__ ) . 'js/' );
}

if( !defined( 'SSP_CSS_DIR' ) ) {
    define( 'SSP_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css/' );
}

if( !defined( 'SSP_LANG_DIR' ) ) {
    define( 'SSP_LANG_DIR', basename( dirname( __FILE__ ) ) . '/languages/' );
}

if( !defined( 'SSP_SETTINGS' ) ) {
    define( 'SSP_SETTINGS', 'ssp-settings' );
}
//Declaration of the class for necessary configuration of a plugin
if( !class_exists( 'PF_SSP_Smart_Scroll_Posts' ) ) {
    class PF_SSP_Smart_Scroll_Posts
    {

     var $ssp_settings;

	   function __construct() {
           /* backend section */
           $this->ssp_settings = get_option('SSP_SETTINGS');
           add_action( 'init', array($this, 'session_init') );                                     //start the session if not started yet.
           register_activation_hook(__FILE__,array($this,'ssp_plugin_activation'));               //load the default setting for the plugin while activating
           add_action( 'init', array($this, 'ssp_plugin_text_domain') );                       //load the plugin text domain
           add_action('admin_menu', array($this, 'ssp_control_menu'));                      //register the plugin menu/submenu in backend
           add_action('admin_enqueue_scripts',array($this,'ssp_register_admin_assets'));     //registers all the assets required for wp-admin
	         add_action( 'admin_post_ssp_save_options', array($this, 'ssp_save_options') ); //save the options in the wordpress options table.
           add_action( 'admin_post_ssp_restore_default_settings', array($this, 'ssp_restore_default_settings') ); //save the options in the wordpress options table.

          /* Frontend section */
           add_action('wp_enqueue_scripts',array($this,'ssp_register_frontend_assets'));        //registers assets for frontend
           add_action('wp_footer', array($this,'add_content_footer'),5);
           add_action('wp_ajax_ssp_populate_posts',array($this,'ssp_populate_posts'));
           add_action('wp_ajax_nopriv_ssp_populate_posts',array($this,'ssp_populate_posts'));
           add_filter( 'the_content', array($this,'smart_ajax_page_content'));
           remove_filter('the_content', 'wpautop');
           // add_action( 'wp_head', array($this,'ssp_count_views' ));

      }

       /**
       * Session Start with the call of Init Hook
       * */
        function session_init() {
            if( !session_id() ) {
                session_start();
            }
        }

          /*  function ssp_count_views()
          {
            if( is_singular() ) {
              global $post;
              if ( ! get_post_meta($post->ID, 'total_count_views', true ) ) {
                 add_post_meta( $post->ID, 'total_count_views', 0 );
              }else{
                $count = get_post_meta( $post->ID, 'total_count_views', true );
               $count++;
               update_post_meta( $post->ID, 'total_count_views', $count );
              }

            }
          } */

        /**
          * Plugin Activation and default field values set.
         * */
	    function ssp_plugin_activation(){
          // if( !get_option( SSP_SETTINGS ) ) {
                include( 'inc/backend/activation.php' );
           // }
	    }

      function ssp_plugin_text_domain(){
          load_plugin_textdomain( 'smart-scroll-posts', false, SSP_LANG_DIR );
      }

        /**
          * Add Plugin Menu with submenu in Backend
         * */
      function ssp_control_menu(){
        add_menu_page( 'Smart Scroll Posts', 'PF Smart Scroll Posts', 'manage_options', 'ssp_scroll_form', array($this, 'fn_ssp_scroll_posts') );
      }

        /**
          * Plugin Menu Main Page
         * */
        function fn_ssp_scroll_posts(){
           include( 'inc/backend/add-infinite-scroll-posts.php');
        }

       /**
       * Backend CSS And JS
       * */
        function ssp_register_admin_assets(){
           if( isset( $_GET['page'] ) && $_GET['page'] == 'ssp_scroll_form') {
               wp_enqueue_script('media-upload');
               wp_enqueue_script('thickbox');
               wp_enqueue_style('thickbox');
               wp_enqueue_style('ssp_backend_style', SSP_CSS_DIR . 'backend/backend.css', SSP_VERSION);
               wp_enqueue_style( 'ssp-fontawesome', SSP_CSS_DIR. 'font-awesome/font-awesome.min.css' );
               wp_enqueue_script( 'ssp-backend-js', SSP_JS_DIR . 'backend.js', array('jquery'), SSP_VERSION );
               wp_enqueue_script( 'ssp-media-uploader-backend-js', SSP_JS_DIR . 'media-uploader.js', array('jquery'), SSP_VERSION );
           }

        }

        /**
          * Save Settings Form to database
         * */
        function ssp_save_options()
        {
           if (isset($_POST['ssp_add_nonce_save_settings'], $_POST['ssp_save_settings']) && wp_verify_nonce($_POST['ssp_add_nonce_save_settings'], 'ssp_nonce_save_settings')) {
             include( 'inc/backend/save_settings.php' );
          }
           else {
            die( 'No script kiddies please!' );
           }
        }

        /**
          * Clear Database Settings and save
         * */
        function ssp_restore_default_settings(){
          $nonce = $_REQUEST['_wpnonce'];
          if(isset($_GET) && wp_verify_nonce($nonce,'ssp-restore-default-settings-nonce' )){
            include( 'inc/backend/activation.php' );
            $_SESSION['ssp_message'] = __( 'Restored Default Settings Successfully.', 'smart-scroll-posts' );
            wp_redirect( admin_url() . 'admin.php?page=ssp_scroll_form');

          }else{
            die( 'No script kiddies please!' );
          }

        }

        /**
          * Registers assets for frontend
         * */
         function ssp_register_frontend_assets() {
            // Add some parameters for the JS.
           /**
              * Load the Javascript if found as a singluar post.
            */
        if (is_singular()) {
              /**
             * Frontend Style
             * */
             wp_enqueue_style('ssp-frontend-css', SSP_CSS_DIR . 'frontend/frontend.css',false, SSP_VERSION);//registering animate.css
             /**
             * Frontend JS
             * */
              // echo get_template_directory_uri()/images/loader.gif
             wp_enqueue_script('ssp-frontend-js', SSP_JS_DIR . 'frontend.js', array('jquery'), true, SSP_VERSION);//registering frontend js
             $options = get_option( SSP_SETTINGS );
             global $post;
              // Variables for JS scripts
              wp_localize_script('ssp-frontend-js', 'ssp_frontend_js_params', array(
                'smartscroll_load_ajax_type'    => esc_attr($options['load_ajax_type']),
                'smartscroll_MainClass'         => esc_attr($options['appendElementClass']),
                'smartscroll_ajax_container'    => esc_attr($options['container_class']),
                'smartscroll_markup_type'       => esc_attr($options['markup_type']),
                'smartscroll_replace_url'       => intval($options['replace_url']),
                'smartscroll_ajaxurl'           => admin_url('admin-ajax.php'),
                'smartscroll_loader_type'       => $options['smart_scroll_ajax_image'],
                'smartscroll_loader_img'        => esc_url($options['smart_scroll_ajax_image_url']),
                'smartscroll_default_loader'    => SSP_IMAGE_DIR. 'smart_scroll-ajax_loader.gif',
                'smartscroll_posts_limit'       => isset($options['post_limit'])?esc_attr($options['post_limit']):'',
                'smartscroll_category_options'  => $options['category_options'],
                'smartscroll_order_next_posts'  => $options['order_next_posts'],
                'smartscroll_post_link_target'  => $options['post_link_target'],
                'smartscroll_posts_featured_size'  => $options['posts_featured_size'],
                'smartscroll_postid'            => $post->ID,
                'smartscroll_ajax_nonce'        => wp_create_nonce('ssp-ajax-nonce'),
              ));



            } // END if is_singular() && get_post_type()

         }

         function add_content_footer(){
            global $post;
            $post_id = $post->ID;
            if(get_post_type($post_id) == 'post'){
              $category = get_the_category($post->ID);
              if(empty($category)){
               $category_id = '';
              }else{
                $category_idd =$category[0]->cat_ID;
                $category_id = $category_idd;
              }
            }else{
              $tax_name = '';
              if(isset(get_object_taxonomies( get_post_type($post_id))[0])){
                $tax_name = get_object_taxonomies( get_post_type($post_id))[0];
              }
              $category = wp_get_post_terms($post->ID, $tax_name, array("fields" => "all"));
              if(empty($category)){
               $category_id = '';
              }else{
                $category_idd =$category[0]->term_id;
                $category_id = $category_idd;
              }
            }
            if(get_post_type($post_id) != 'page'){

            echo '<input type="hidden" id="ssp_main_postid" value="'.$post_id.'"/>';
            echo '<input type="hidden" id="ssp_main_cateid" value="'. $category_id .'"/>';
            echo '<input type="hidden" id="ssp_main_exclude_posts" value="'.$post_id.'"/>';
            }

         }

         function get_post_siblings( $limit = 1, $post_id, $exclude_posts=[], $current_tax='', $current_term='',$current_posttype='', $date_order='DESC', $date = '' ) {
         global $wpdb;
         $exclude_posts = implode(',', $exclude_posts);
              $taxonomy = $current_tax;
              // Custom Post Type
              $postType = $current_posttype;
              // Get term id value from incoming URL
              $term_id = $current_term;
               if($date_order == 'ASC'){
                  $prev_greater_or_lessthan = '<';
               }else{
                  $prev_greater_or_lessthan = '>';
               }
                if( empty( $date ) )
                  $post = get_post($post_id);
                $date = $post->post_date;


                $limit = absint( $limit );
                if( !$limit )
                    return;
                if(get_post_type($post_id) == 'post'){
                  // Get previous/next posts into $p based on selected date order.
                      $p = $wpdb->get_results( "
                          (
                              SELECT
                                  p1.post_title,
                                  p1.post_date,
                                  p1.ID
                              FROM
                                  $wpdb->posts p1
                              INNER JOIN $wpdb->term_relationships
                              AS tr
                              ON p1.ID = tr.object_id
                              INNER JOIN $wpdb->term_taxonomy tt
                              ON tr.term_taxonomy_id = tt.term_taxonomy_id
                     WHERE
                         p1.post_date $prev_greater_or_lessthan '$date' AND
                         p1.post_type = '$postType' AND
                         p1.ID NOT IN ($exclude_posts) AND
                         p1.post_status = 'publish'
                              AND tt.taxonomy = '$taxonomy'
                              AND tt.term_id
                              IN ($term_id)
                     ORDER by p1.post_date $date_order
                     LIMIT $limit
                                  )");
                }else{
                  // Get previous/next posts into $p based on selected date order.
                    $p = $wpdb->get_results( "
                   (
                       SELECT
                         p1.post_title,
                         p1.post_date,
                         p1.ID
                     FROM
                         $wpdb->posts p1
                              INNER JOIN $wpdb->term_relationships
                              AS tr
                              ON p1.ID = tr.object_id
                              INNER JOIN $wpdb->term_taxonomy tt
                              ON tr.term_taxonomy_id = tt.term_taxonomy_id
                     WHERE
                         p1.post_date $prev_greater_or_lessthan '$date' AND
                         p1.post_type = '$postType' AND
                         p1.ID NOT IN ($exclude_posts) AND
                         p1.post_status = 'publish'
                              AND tt.taxonomy = '$taxonomy'
                              AND tt.term_id
                              IN ($term_id)
                     ORDER by p1.post_date $date_order
                     LIMIT $limit
                   )");
                }

                if(get_post_type($post_id) == 'post'){
                  // if post is last one then this one will add the remaining posts of same category, if posts available into $n.
                  $n = $wpdb->get_results( "
                     (
                           SELECT
                              p2.post_title,
                              p2.post_date,
                              p2.ID
                          FROM
                              $wpdb->posts p2
                               INNER JOIN $wpdb->term_relationships
                              AS tr
                              ON p2.ID = tr.object_id
                              INNER JOIN $wpdb->term_taxonomy tt
                              ON tr.term_taxonomy_id = tt.term_taxonomy_id
                     WHERE
                         p2.ID NOT IN ($exclude_posts) AND
                         p2.post_type = '$postType' AND
                         p2.post_status = 'publish'
                              AND tt.taxonomy = '$taxonomy'
                              AND tt.term_id
                              IN ($term_id)
                     ORDER by p2.post_date $date_order
                     LIMIT $limit
                     )");
                }else{
                  // if post is last one then this one will add the remaining posts of same category, if posts available into $n.
                  $n = $wpdb->get_results( "
                     (
                     SELECT
                         p2.post_title,
                         p2.post_date,
                         p2.ID
                     FROM
                         $wpdb->posts p2
                               INNER JOIN $wpdb->term_relationships
                              AS tr
                              ON p2.ID = tr.object_id
                              INNER JOIN $wpdb->term_taxonomy tt
                              ON tr.term_taxonomy_id = tt.term_taxonomy_id
                     WHERE
                         p2.ID NOT IN ($exclude_posts) AND
                         p2.post_type = '$postType' AND
                         p2.post_status = 'publish'
                              AND tt.taxonomy = '$taxonomy'
                              AND tt.term_id
                              IN ($term_id)
                     ORDER by p2.post_date $date_order
                     LIMIT $limit
                     )");
                }

                  $adjacents = array();
                  $adjacents['prev'] = array();
                  $adjacents['next'] = array();

                  for( $i=0; $i<count($p); $i++ ) {
                      $adjacents['prev'][] = $p[$i];
                  }

                  for( $i=0; $i<count($n); $i++ ) {
                      $adjacents['next'][] = $n[$i];
                  }
                  $previous_post = $adjacents['prev'][0];
                  if(empty($previous_post)){
                    $previous_post = $adjacents['next'][0];
                  }
            return $previous_post->ID;
        }

      /* Default and Custom Template ajax call */
        function ssp_populate_posts(){
       if ( !empty( $_POST ) && wp_verify_nonce( $_POST['_wpnonce'], 'ssp-ajax-nonce' ) ) {
            if (!isset($_POST['ID']))
              die();

              $post_id      = sanitize_text_field($_POST['ID']);
              if(get_post_type($post_id) == 'case_studies' || get_post_type($post_id) == 'post'){
              $markup       = sanitize_text_field($_POST['markup_type']);
              $in_same_term = sanitize_text_field($_POST['catid']);
              $nextpoststype = sanitize_text_field($_POST['order_next_posts']);
              $exclude_posts = explode(",", sanitize_text_field($_POST['exclude_posts']));
              if($nextpoststype == 'newer_posts'){
                  $date_order = 'ASC';
              }else{
                  $date_order = 'DESC';
              }
              if(get_post_type($post_id) == 'post'){
                  $all_custom_posttype_posts = get_posts(array(
                                                          'post_type'     =>'post',
                                                          'orderby'       => 'date',
                                                          'order'         => $date_order,
                                                          'fields'        => 'ids',
                                                          'posts_per_page'=> -1
                                                          )
                                                    );
              } else{
                $all_custom_posttype_posts = get_posts(array(
                                                          'post_type'     =>get_post_type($post_id),
                                                          'orderby'       => 'date',
                                                          'order'         => $date_order,
                                                          'fields'        => 'ids',
                                                          'posts_per_page'=> -1
                                                          )
                                                    );
              }
              $posts_exists = false;
              $pre_post_id = '';

              if($in_same_term){
                if(get_post_type($post_id) == 'post'){
                  $pre_post_id = $this->get_post_siblings( 1, $post_id, $exclude_posts, 'category', $in_same_term, get_post_type($post_id), $date_order);
                }else{
                  $pre_post_id = $this->get_post_siblings( 1, $post_id, $exclude_posts, get_object_taxonomies( get_post_type($post_id))[0], $in_same_term, get_post_type($post_id), $date_order);
                }
              }else{
                $updated_arr = array_values(array_diff($all_custom_posttype_posts, $exclude_posts));
                  if($updated_arr){
                    foreach ($updated_arr as $key => $updated) {
                        $posts_exists = true;
                       echo $this->post_html($updated, $posts_exists);
                     }
                   }else{
                    $posts_exists = false;
                    die();
                   }
              }
              //$post_id      = $this->get_previous_post_id( $post_id, $in_same_term , $nextpoststype ); // previous post id means next post id


              if($pre_post_id){
                if(!in_array($pre_post_id, $exclude_posts)){
                $posts_exists = true;
                $post_id = $pre_post_id;
                echo $this->post_html($post_id, $posts_exists);
                }else{
                  $updated_arr = array_values(array_diff($all_custom_posttype_posts, $exclude_posts));
                  if($updated_arr){
                    foreach ($updated_arr as $key => $updated) {
                        $posts_exists = true;
                       echo $this->post_html($updated, $posts_exists);
                     }
                   }else{
                    $posts_exists = false;
                    die();
                   }
                }
              }else{
                $updated_arr = array_values(array_diff($all_custom_posttype_posts, $exclude_posts));
                  if($updated_arr){
                    foreach ($updated_arr as $key => $updated) {
                        $posts_exists = true;
                       echo $this->post_html($updated, $posts_exists);
                     }
                   }else{
                    $posts_exists = false;
                    die();
                   }
              }
            }
          }else{
            die( 'No script kiddies please!');
          }

        }
        function post_html($post_id, $posts_exists){
          if($posts_exists){
                if(get_post_type($post_id) == 'post'){
                  $args     = array(
                    'p'     =>  $post_id,
                    'post_type'=>'post'
                    );
                }else{
                  $args     = array(
                    'p'     =>  $post_id,
                    'post_type' => get_post_type($post_id)
                    );
                }

              $query = new WP_Query($args);
				 $vc_tags = true;
              while ( $query->have_posts() ) : $query->the_post();
                global $post;
                if(get_post_type($post->ID) == 'post'){
                  $post_categories = get_the_category($post->ID)[0]->cat_ID;
                }else{
                  $post_categories = get_post_primary_category($post->ID, get_object_taxonomies( get_post_type($post->ID))[0])['primary_category']->term_id;
                }
                ?>

            <div class="ssp_divider" data-title="<?php the_title();?>" data-url="<?php the_permalink(); ?>" id="<?php echo get_the_ID();?>"
            data-cat-id="<?=$post_categories;?>">
               <?php
               $next_post_html = file_get_contents(get_permalink($post->ID));
               $doc = new DOMDocument();
                $doc->loadHTMLFile(get_permalink($post->ID));
                $xpath = new DOMXpath($doc);
                $stylesheets = $xpath->query("//link[@rel='stylesheet']/@href");
                $css_files = '';
                if ($stylesheets->length != 0) {
                    foreach ($stylesheets as $stylesheet) {
                        $css_files.= $stylesheet->nodeValue.',';
                    }
                }
                $js_scripts = $xpath->query("//script[@type='text/javascript']/@src");
                $js_files = '';
                if ($js_scripts->length != 0) {
                    foreach ($js_scripts as $js_script) {
                        $js_files.= $js_script->nodeValue.',';
                    }
                }
               //preg_match_all ("/src=[\"']?([^\"']?.*(js))[\"']?/i" , $next_post_html , $js_files_matches );
               //$js_files = implode(",", $js_files_matches[1]);
               preg_match_all ("#<style[^><]*>(.*?)</style>#is" , $next_post_html , $inline_css_matches );
               ?>
               <input type="hidden" class="next_post_css_files" value="<?=rtrim($css_files, ',')?>" />
               <input type="hidden" class="next_post_js_files" value="<?=rtrim($js_files, ',')?>" />
               <div class="next_post_style_tags">
                 <?php foreach ($inline_css_matches[0] as $inline_css) {
                   echo $inline_css;
                 }
                 ?>
               </div>
            <?php
              preg_match("/<article[^>]*>(.*?)<\/article>/is", $next_post_html, $main_tag);
             $main_tag_info = $main_tag[1];
                partial('template-parts/header/header', 'part', ['scroll_specific'=>'scroll_specific']);
					 if (strpos($post->post_content, 'vc_section') !== false && strpos($post->post_content, 'vc_row') !== false) {
						  $vc_tags = false;
					 }
				 	if($vc_tags){ ?>

						<div class="site-main <?php echo add_space_if_no_vc_section(get_the_ID());?>">
						<?php }else{
							?>
							<div id="main">
							<?php
						} ?>

							<?php
              echo $main_tag_info;
								//get_template_part( 'template-parts/content', get_post_type());
							?>
							</div>
						<?php
						 if(get_post_type($post->ID) == 'post'){
							partial('template-parts/content/related', 'posts', ['currentID'=>$post->ID]);
						 }else{
							partial('template-parts/content/enquiry', 'form');
							partial('template-parts/content/related', 'case-studies', ['currentID'=>$post->ID]);
						 }
						  ?>

             </div>
              <?php
              endwhile;
            }else{
              return false;
            }
            die();
        }

        function smart_ajax_page_content( $content )
        {
             return '<div class="smart_content_wrapper">'.$content.'</div>';
        }

         //prints the array in pre format
        function displayArr($array) {
            echo "<pre>";
            print_r($array);
            echo "</pre>";
        }

         //returns all the registered post types only
       function get_registered_post_types() {
           $post_types = get_post_types();
           unset($post_types['page']);
           unset($post_types['attachment']);
           unset($post_types['revision']);
           unset($post_types['nav_menu_item']);
           unset($post_types['wp-types-group']);
           unset($post_types['wp-types-user-group']);
           return $post_types;
       }

       // returns all the registered taxonomies
       function get_registered_taxonomies() {
           $output = 'objects';
           $args = '';
           $taxonomies = get_taxonomies($args, $output);
           unset($taxonomies['post_tag']);
           unset($taxonomies['nav_menu']);
           unset($taxonomies['link_category']);
           unset($taxonomies['post_format']);
           return $taxonomies;
       }


     /**
     * Get size information for all currently-registered image sizes.
     *
     * @global $_wp_additional_image_sizes
     * @uses   get_intermediate_image_sizes()
     * @return array $sizes Data for all currently-registered image sizes.
     */
      public static function ssp_get_image_sizes() {
      global $_wp_additional_image_sizes;

      $sizes = array();

      foreach ( get_intermediate_image_sizes() as $_size ) {
        if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
          $sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
          $sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
          $sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
          $sizes[ $_size ] = array(
            'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
            'height' => $_wp_additional_image_sizes[ $_size ]['height'],
            'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
          );
        }
      }

      return $sizes;
    }



	}
   $ssp_object = new PF_SSP_Smart_Scroll_Posts();
}
