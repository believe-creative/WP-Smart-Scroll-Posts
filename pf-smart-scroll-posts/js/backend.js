(function ($) {
    $(function () {
        $('.ssp-top-button').click(function(){
            $('body').animate({
                scrollTop:0
            },200);
        });

	    $( '.ssp-tabs-trigger' ).click(function(){
		    $( '.ssp-tabs-trigger' ).parent().removeClass( 'ssp-active-tab' );
		    $(this).parent().addClass( 'ssp-active-tab' );
		    var board_id = 'tab-'+$(this).attr('id');
		    $('.ssp-tab-contents').hide();
		    $('#'+board_id).show();
	   	});

       $('.accesspress-ssp_html_markup').change(function(){ 
			    var value = $(this).val();
			    if(value == "default_markup"){
			         $('.ssp-col-full1').toggle(); 
		              $('.ssp-col-full2').hide();
			    }else if(value == "custom_markup"){
			       $('.ssp-col-full1').hide();
                   $('.ssp-col-full2').toggle();
			    }
			});
     
      $('.load_ajax_type').change(function(){ 
			    var value = $(this).val();
			    if(value == "append_data_on"){
			       $('.ssp-col-full3').toggle(1000);
			    }else if(value == "onclick_button"){
			       $('.ssp-col-full3').hide(1000);
			    }
			});


        $('input[name="loader_set_type"]').click(function () {
        	
            if ($(this).val() == 'default_loader')
                        {
                            $('.default_loader_content').show();
                            $('.custom_loader_content').hide();

                        }
                        else
                        {
                            $('.default_loader_content').hide();
                            $('.custom_loader_content').show();
                        }
          });


        });
}(jQuery));