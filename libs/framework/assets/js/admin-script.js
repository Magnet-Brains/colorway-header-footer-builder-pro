jQuery(document).ready(function ($) {
    "use strict";
    
    function cwAdminonHashChange() {
        var hash = window.location.hash;
        if (hash) {
            // using ES6 template string syntax
            $(`${hash}-tab`).trigger('click');
        }
    }
    cwAdminonHashChange();


    $('#cw-admin-settings-form').on('submit', function(e){
        var form = $(this);
        var btn = form.find('.cw-admin-settings-form-submit');
        var formdata = form.serialize();
        form.addClass('is-loading');
        btn.attr("disabled", true);

        $.post( ajaxurl + '?action=cw_admin_action', formdata, function( data ) {
            form.removeClass('is-loading');
            btn.removeAttr("disabled");
            show_header_footer_menu();
        });


        e.preventDefault();
    });


    $('#cw-admin-license-form').on('submit', function(e){
        var form = $(this);
        var btn = form.find('.cw-admin-license-form-submit');
        var formdata = form.serialize();
        var result = form.find('.colorwayhf-header-footer-form-result .attr-alert');
        form.addClass('is-loading');
        // btn.attr("disabled", true);

        $.post( ajaxurl + '?action=cw_admin_license', formdata, function( data ) {
            form.removeClass('is-loading');
            btn.removeAttr("disabled");

            result
                .attr('class', 'attr-alert attr-alert-' + data.status)
                .html(data.message);
            if(data.validate == 1){
                setTimeout((function() {
                    window.location.reload();
                }), 2000);
            }
        }, 'json');

        e.preventDefault();
    });

    // only for header footer module
    function show_header_footer_menu(){
        var checked = $('#cw-admin-switch__module__list____header-footer').prop('checked');
        var menu_html = $('#colorwayhf-template-admin-menu').html();
        var menu_parent = $('#toplevel_page_colorwayhf .wp-submenu');
        var menu_item = menu_parent.find('a[href="edit.php?post_type=colorwayhf_template"]');
        
        if(checked == true){
            if(menu_item.length > 0 || menu_parent.attr('item-added') == 'y'){
                menu_item.parent().show();
            }else{
                menu_parent.find('li.wp-first-item').after(menu_html);
                menu_parent.attr('item-added', 'y');
            }
        }else{
            menu_item.parent().hide();
        }
    };


}); // end ready function