// Variables
var process_loadmore     = ssp_frontend_js_params.smartscroll_load_ajax_type;
var MainClass            = ssp_frontend_js_params.smartscroll_MainClass;
var ajax_container       = ssp_frontend_js_params.smartscroll_ajax_container;
var markup_type          = ssp_frontend_js_params.smartscroll_markup_type;
var order_next_posts     = ssp_frontend_js_params.smartscroll_order_next_posts;
var post_link_target     = ssp_frontend_js_params.smartscroll_post_link_target;
var posts_featured_size     = ssp_frontend_js_params.smartscroll_posts_featured_size;

var replace_url          = ssp_frontend_js_params.smartscroll_replace_url;
var AjaxURL              = ssp_frontend_js_params.smartscroll_ajaxurl;
var loadertype           = ssp_frontend_js_params.smartscroll_loader_type;
var image_loader_ajax    = ssp_frontend_js_params.smartscroll_loader_img;
var curr_url             = window.location.href;
var curr_title           = window.document.title.split("-");
var current_id           = ssp_frontend_js_params.smartscroll_postid;
var cat_options          = ssp_frontend_js_params.smartscroll_category_options;
var default_loader       = ssp_frontend_js_params.smartscroll_default_loader;
var postperpage          = ssp_frontend_js_params.smartscroll_posts_limit;
var postsajax = [];
//jQuery.noConflict();

$(function($){
  var categoryid = $('#ssp_main_cateid').val();
  if(cat_options.indexOf(categoryid) >= 0 || cat_options.indexOf('all') >= 0) {
    if(image_loader_ajax == '' || loadertype == 'default_loader'){
      image_loader_ajax = default_loader;

    }
   $('<div class="ssp_divider" data-title="' + curr_title[0] + '" data-url="' + curr_url+ '" id="'+current_id+'" ></div>').prependTo('.'+MainClass);
  $( "<div id='smart-ajax-loader' style='display:none;'><img src='"+image_loader_ajax+"' alt='ajaxloadergif'/></div><div class='clear'></div><div class='show_no_posts'></div>").insertAfter( "." + MainClass );
   $('.show_no_posts').css('display','none');
 }
    var processing = false;

    $(window).scroll(function(){
         if (processing)
            return false;

    //  var hH = $('.smart_content_wrapper').offset().top;
      //if ( ($(document).height() <= ( $(window).scrollTop() + $(window).height() + hH)) &&  !processing ) {

       var hH = $('.smart_content_wrapper').outerHeight();
       var scrollTop     = $(window).scrollTop();
       var elementOffset = $('.smart_content_wrapper').offset().top;
       var distance      = (elementOffset - scrollTop);    //return div position from window top to div top
       var divHeight     =  $( '.smart_content_wrapper').offset().top;
       var divHh         =  $( '.smart_content_wrapper').height();
       var scrollHeight = $(document).height();
      var scrollPosition = scrollTop + $(window).height();

      var scroll_ninety_percent = (scrollHeight / 100) * 75;

if ( (distance <= 91 && divHh <= 250 ) || (scrollPosition >= scroll_ninety_percent) && !processing) {

         processing = true;

          setTimeout(function() {
            var post_id    = $('#ssp_main_postid').val();
            var exclude_posts    = $('#ssp_main_exclude_posts').val();
            if(post_id){
              if(cat_options.indexOf(categoryid) >= 0 || cat_options.indexOf('all') >= 0) {
                 if(postperpage != ''){
                 if(postsajax.length <= postperpage - 1){
                    myssp_load_morefunc(post_id,categoryid, exclude_posts);
                    postsajax.push(post_id);
                  }else{
                       $("#smart-ajax-loader").hide();
                       $('.show_no_posts').html('NO MORE POSTS');
                       $('.show_no_posts').show();
                  }
                 }else{
                    myssp_load_morefunc(post_id,categoryid, exclude_posts);
                 }

                }
            }

         }, 2000 );
           //console.log("totalpost="+postsajax);
         //console.log("counttotalpost="+postsajax.length);

    }

    });



	 function myssp_load_morefunc(value,categoryid, exclude_posts){
       $.ajax({
          type: 'POST',
          url: AjaxURL,
          data: {
          	  'ID'          : value,
              'catid'       : categoryid,
              'order_next_posts': order_next_posts,
              'exclude_posts' : exclude_posts,
              '_wpnonce'    : ssp_frontend_js_params.smartscroll_ajax_nonce,
              'action'      :'ssp_populate_posts'
          },

          // beforeSend: function() {
          //   $("#smart-ajax-loader").show();
          // },

          success: function(data){
            if (data != false) {
              $('.'+ MainClass).append("<section class='"+ajax_container+"'>"+data+"</section>").delay(5000);
              $('.'+ajax_container+'.lead, .page_short_desc').remove();
	            var lastPostId = $('.'+ MainClass + ' div.ssp_divider:last').attr('id');
              var lastCateId = $('.'+ MainClass + ' div.ssp_divider:last').attr('data-cat-id');
              var post_url = $('.'+ MainClass + ' div.ssp_divider:last').attr('data-url');

	            $('#ssp_main_postid').val(lastPostId);
              $('#ssp_main_cateid').val(lastCateId);

              var next_posts_css_files = $('#'+lastPostId+' input.next_post_css_files').val().split(',');
              for(var n=0; n<next_posts_css_files.length;n++){
                var css_href_value = next_posts_css_files[n];
                var styleExists = false;
                $("link").each(function(k,link){
                  if(link.href == css_href_value){
                     styleExists = true;
                     return;
                  }
                });
                if(!styleExists){
                  $("head").append('<link rel="stylesheet" href="'+css_href_value+'" type="text/css" />');
                }
              }
              var next_posts_js_files = $('#'+lastPostId+' input.next_post_js_files').val().split(',');
              for(var p=0;p<next_posts_js_files.length;p++)
              {
                var scriptExits = false;
                var js_src_value= next_posts_js_files[p];
                $("script").each(function(j,script){
                  if(script.src == js_src_value){
                    if(js_src_value.indexOf('jquery.min.js')>0 || js_src_value.indexOf('cookie-notice')>0 || js_src_value.indexOf('pf-smart-scroll-posts')>0 || js_src_value.indexOf('playfields.js')>0){
                     scriptExits = true;
                     return;
                    }
                    $(script).remove();
                  }

                });
                if(!scriptExits){
                  $("footer").append('<script type="text/javascript" src="'+js_src_value+'"></script>');
                }
              }

              // $.ajax({
              //    type: 'GET',
              //    url: post_url,
              //    contentType: 'text/html',
              //    success: function(next_post_data){
              //        var nodes = $(next_post_data);
              //        var scripts = [];
              //
              //        $.each(nodes,function(i,el){
              //            var next_post_main_tag = $(el.outerHTML).find('main')[0];
              //            // if(next_post_main_tag != undefined){
              //            //   $("."+ajax_container+" div#"+lastPostId+" main").html($(next_post_main_tag).html());
              //            // }
              //          if(el.nodeName=='SCRIPT' && el.src!=""){
              //            var scriptExits = false;
              //            $("script").each(function(j,script){
              //              if(script.src == el.src){
              //                if(el.src.indexOf('jquery.min.js')>0 || el.src.indexOf('cookie-notice')>0 || el.src.indexOf('pf-smart-scroll-posts')>0){
              //                 scriptExits = true;
              //                 return;
              //                }
              //                $(script).remove();
              //              }
              //
              //            });
              //            if(!scriptExits){
              //              $("footer").append(el.outerHTML);
              //            }
              //          }
              //          if(el.nodeName=='LINK'){
              //            var styleExists = false;
              //            $("link").each(function(k,link){
              //              if(link.href == el.href){
              //                 styleExists = true;
              //                 return;
              //              }
              //            });
              //            if(!styleExists){
              //              $("head").append(el.outerHTML);
              //            }
              //
              //          }
              //          if(el.nodeName == 'STYLE'){
              //            var styleTagExists = false;
              //            $("style").each(function(l,style){
              //              if(style == el){
              //                  styleTagExists = true;
              //                  return;
              //                 //$(style).remove();
              //                 //$("footer").append(el.outerHTML);
              //              }
              //
              //            });
              //            if(!styleTagExists){
              //              $("footer").append(el.outerHTML);
              //            }
              //          }
              //        });
              //
              //    }
              // });
              // setTimeout(function(){
              //
              //   //$("head").append("<style>"+$(".vc_content_css").last().val()+"</style>");
              //   //$("head").append("<link rel='stylesheet' id='animate-css-css1'  href='http://localhost/playfields/wp-content/plugins/js_composer/assets/lib/bower/animate-css/animate.min.css' type='text/css' media='all' />");
              //   // To render svg's
              //   $("img").map(function(){
              //     var img_ref=this;
              //       if($(this).attr("src").indexOf(".svg")>=0)
              //       {
              //         $.ajax({
              //            type: 'GET',
              //            url: $(this).attr("src"),
              //            success: function(data){
              //                $(img_ref).closest("div").append($(data).find("svg")[0]);
              //                $(img_ref).remove();
              //            }
              //         });
              //       }
              //   });
              //   $(window).trigger('resize');
              //   $(window).load();
              // },1000);

            setTimeout(function(){


            var menu_height = $(".show_header_nav").innerHeight();
            $(".main-jumbotron,.sub-jumbotron").css({"min-height":"inherit"});
            $(".medium .main-jumbotron, .medium .sub-jumbotron").css("min-height", window.innerHeight*0.5 + menu_height);
            $(".long .main-jumbotron, .long .sub-jumbotron").css("min-height", window.innerHeight*0.75 + menu_height);
            $(".full .main-jumbotron, .full .sub-jumbotron").css("min-height", window.innerHeight + menu_height);

            if($(".sub-jumbotron")[0] && $(".sub-jumbotron").height() && $(".sub-jumbotron").height()<$(".small_header_block").innerHeight())
            {
                $(".main-jumbotron,.sub-jumbotron").css("min-height", $(".small_header_block").innerHeight());
            }
					$('.sub-jumbotron').map(function(){
				var screen_height = $(this).innerHeight();
				$(this).closest(".show_header_title_panel")
				if($(this).closest(".show_header_title_panel").hasClass("normal")){
				$(this).closest(".show_header_title_panel").css({"height":"inherit"});
			}else{
				$(this).closest(".show_header_title_panel").css('height',screen_height + 'px');
			}
			});

              },500);

              var exclude_posts_update = $('#ssp_main_exclude_posts').val();
              if(exclude_posts_update != ''){
                var exclude_posts_update_val = exclude_posts_update+','+lastPostId;
                $('#ssp_main_exclude_posts').val(exclude_posts_update_val);
              }else{
                $('#ssp_main_exclude_posts').val(lastPostId);
              }
              processing = false;
              $("#smart-ajax-loader").hide();

              if ( typeof _gaq === 'undefined' && typeof ga === 'undefined' && typeof __gaTracker === 'undefined' ) {
                console.log('No google analytics enabled');
                return;
              }
              // Clean Post URL before tracking.
              post_url = post_url.replace(/https?:\/\/[^\/]+/i, '');
              // This uses Google's classic Google Analytics tracking method.
              if ( typeof _gaq !== 'undefined' && _gaq !== null ) {
                _gaq.push(['_trackPageview', post_url]);
              }
              // This uses Google Analytics Universal Analytics tracking method.
              if ( typeof ga !== 'undefined' && ga !== null ) {
                ga( 'send', 'pageview', post_url );
              }
              // This uses Monster Insights method of tracking Google Analytics.
              if ( typeof __gaTracker !== 'undefined' && __gaTracker !== null ) {
                __gaTracker( 'send', 'pageview', post_url );
              }

            }else{
              $('#ssp_main_postid').val('');
              $('#ssp_main_cateid').val('');
              $('#ssp_main_exclude_posts').val();
              $("#smart-ajax-loader").hide();
              //$('.show_no_posts').html('NO MORE POSTS');
              //$('.show_no_posts').show();

            }

          }
       });


       return false;
    };

  if(replace_url == '1'){
    var last_scroll = 0;
    $( window ).scroll( function() {
      var scroll_pos = $( window ).scrollTop();
      if ( Math.abs( scroll_pos - last_scroll ) > $( window ).height() * 0.1 ) {
        last_scroll = scroll_pos;
        var last=null;
        $( 'div.ssp_divider').map( function(index,item) {
          var scroll_pos = $( window ).scrollTop();
          var window_height = $( window ).height();
          //var el_top = $( this ).offset().top;
          var el_height=$(this).offset().top;
          //console.log(el_height,( scroll_pos+window_height),index);
          if (  el_height <( scroll_pos+window_height)  ) {
            var ele=$( this );
            last=ele;
            return( false );
          }
        });
        if ( window.location.href !== last.attr( "data-url" ) ) {
              history.replaceState( null, null, last.attr( "data-url" ) );
              $( "meta[property='og:title']" ).attr( 'content', last.attr( "data-title" ) );
              $( "title" ).html( last.attr( "data-title" ) );
              $( "meta[property='og:url']" ).attr( 'content', last.attr( "data-url" ) );

              $('.page_title,.pf-page-title').text(last.attr( "data-title" ));

            }
            if($('.page_title,.pf-page-title').text()!=last.attr( "data-title"))
            {
                $('.page_title,.pf-page-title').text(last.attr( "data-title" ));
            }

      }
      if(scroll_pos == 0){
        $('.page_title,.pf-page-title').text($('input#parent_page_title_before_scroll').val());
      }
    });

  }

});
