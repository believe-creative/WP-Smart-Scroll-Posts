jQuery(document).ready(function() {
        jQuery('#ssp_upload_image_button').click(function() {
        formfield = jQuery('#ssp_upload_image').attr('name');
        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        return false;
        });

        window.send_to_editor = function(html) {
        imgurl = jQuery('img',html).attr('src');
        jQuery('#ssp_upload_image').val(imgurl);
        tb_remove();
        }

});