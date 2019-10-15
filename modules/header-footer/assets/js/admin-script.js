jQuery(document).ready(function ($) {
    "use strict";

    // $('.colorwayhf-select2').select2({
    //     dropdownParent: $('#colorwayhf_headerfooter_modal'),
    //     placeholder: "--",
    // });

    $('.row-actions .edit a, .page-title-action, .row-title').on('click', function (e) {
        e.preventDefault();
        var id = 0;
        var modal = $('#colorwayhf_headerfooter_modal');
        var parent = $(this).parents('.column-title');

        modal.addClass('loading');
        modal.modal('show');
        if (parent.length > 0) {
            id = parent.find('.hidden').attr('id').split('_')[1];

            $.get(window.colorwayhf.resturl + 'my-template/get/' + id, function (data) {
                ColorwayHF_Template_Editor(data);
                modal.removeClass('loading');
            });
        } else {
            var data = {
                title: '',
                type: 'header',
                condition_a: 'entire_site',
                condition_singular: 'all',
                activation: '',
            };
            ColorwayHF_Template_Editor(data);
            modal.removeClass('loading');
        }

        modal.find('form').attr('data-cw-id', id);
    });

    $('.cw-template-modalinput-type').on('change', function () {
        var type = $(this).val();
        var inputs = $('.cw-template-headerfooter-option-container');

        if (type == 'section') {
            inputs.hide();
        } else {
            inputs.show();
        }
    });


    $('.cw-template-modalinput-condition_a').on('change', function () {
        var condition_a = $(this).val();
        var inputs = $('.cw-template-modalinput-condition_singular-container');

        if (condition_a == 'singular') {
            inputs.show();
        } else {
            inputs.hide();
        }
    });

    $('.cw-template-modalinput-condition_singular').on('change', function () {
        var condition_singular = $(this).val();
        var inputs = $('.cw-template-modalinput-condition_singular_id-container');

        if (condition_singular == 'selective') {
            inputs.show();
        } else {
            inputs.hide();
        }
    });


    $('.colorwayhf-template-save-btn-editor').on('click', function () {
        var form = $('#colorwayhf-template-modalinput-form');
        form.attr('data-open-editor', '1');
        form.trigger('submit');
    });

    $('#colorwayhf-template-modalinput-form').on('submit', function (e) {
        e.preventDefault();
        var modal = $('#colorwayhf_headerfooter_modal');
        modal.addClass('loading');

        var form_data = $(this).serialize();
        var id = $(this).attr('data-cw-id');
        var open_editor = $(this).attr('data-open-editor');
        var admin_url = $(this).attr('data-editor-url');

        console.log(form_data);

        $.get(window.colorwayhf.resturl + 'my-template/update/' + id, form_data, function (output) {
            console.log(output);
            modal.removeClass('loading');

            // set list table data
            var row = $('#post-' + output.data.id);
            console.log(row.length);

            if (row.length > 0) {
                row.find('.column-type')
                        .html(output.data.type_html);

                row.find('.column-condition')
                        .html(output.data.cond_text);

                row.find('.row-title')
                        .html(output.data.title)
                        .attr('aria-label', output.data.title);

                console.log(output.data.title);
            }

            if (open_editor == '1') {
                window.location.href = admin_url + '?post=' + output.data.id + '&action=elementor';
            } else if (id == '0') {
                location.reload();
            }
        });

    });

    $('.cw-template-modalinput-condition_singular_id').select2({
        ajax: {
            url: window.colorwayhf.resturl + 'ajaxselect2/singular_list',
            dataType: 'json',
            data: function (params) {
                var query = {
                    s: params.term,
                }
                return query;
            }
        },
        cache: true,
        placeholder: "--",
        dropdownParent: $('#colorwayhf_headerfooter_modal_body')
                //minimumInputLength: 2,
    });

    function ColorwayHF_Template_Editor(data) {
        console.log(data);
        // set the form data
        $('.cw-template-modalinput-title').val(data.title);
        $('.cw-template-modalinput-condition_a').val(data.condition_a);
        $('.cw-template-modalinput-condition_singular').val(data.condition_singular);
        $('.cw-template-modalinput-condition_singular_id').val(data.condition_singular_id);
        $('.cw-template-modalinput-type').val(data.type);

        var activation_input = $('.cw-template-modalinput-activition');
        if (data.activation == 'yes') {
            activation_input.attr('checked', true);
        } else {
            activation_input.removeAttr('checked');
        }

        $('.cw-template-modalinput-activition, .cw-template-modalinput-type, .cw-template-modalinput-condition_a, .cw-template-modalinput-condition_singular')
                .trigger('change');

        var el = $('.cw-template-modalinput-condition_singular_id');
        $.ajax({
            url: window.colorwayhf.resturl + 'ajaxselect2/singular_list',
            dataType: 'json',
            data: {
                ids: String(data.condition_singular_id)
            }
        }).then(function (data) {
            console.log(data);
            if (data !== null && data.results.length > 0) {
                el.html(' ');
                $.each(data.results, function (i, v) {
                    var option = new Option(v.text, v.id, true, true);
                    el.append(option).trigger('change');
                });
                el.trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            }
        });
    }






    function cw_url_replace_param(url, paramName, paramValue) {
        if (paramValue == null) {
            paramValue = '';
        }
        var pattern = new RegExp('\\b(' + paramName + '=).*?(&|#|$)');
        if (url.search(pattern) >= 0) {
            return url.replace(pattern, '$1' + paramValue + '$2');
        }
        url = url.replace(/[?#]$/, '');
        return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue;
    }

    var tab_container = $('.wp-header-end');
    var tabs = '';
    var xs_types = {
        'all': 'All',
        'header': 'Header',
        'footer': 'Footer',
        //'section': 'Section',
    };
    var url = new URL(window.location.href);
    var s = url.searchParams.get("colorwayhf_type_filter");
    s = (s == null) ? 'all' : s;

    $.each(xs_types, function (k, v) {
        var url = cw_url_replace_param(window.location.href, 'colorwayhf_type_filter', k);
        var klass = (s == k) ? 'colorwayhf_type_filter_active nav-tab-active' : ' ';
        tabs += `
            <a href="${url}" class="${klass} colorwayhf_type_filter_tab_item nav-tab">${v}</a>
        `;
        tabs += "\n";
    });
    tab_container.after('<div class="colorwayhf_type_filter_tab_container nav-tab-wrapper">' + tabs + '</div><br/>');
});