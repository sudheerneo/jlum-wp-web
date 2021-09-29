jQuery(document).ready(function(){

    var bodyHeight = jQuery(document).height();
    var adminmenu_height = jQuery('#adminmenuwrap').height();
    if(bodyHeight > adminmenu_height){
        jQuery("#datamain").height(bodyHeight);
    }
    else
    {
        jQuery("#datamain").height(adminmenu_height);
    }


    var normal_attributes = [];

    var category_attributes = [];

    function isValidEmailAddress(emailAddress) {

        var pattern = new RegExp(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/);
        return pattern.test(emailAddress);
    }

    function change_field_attr(){
        var attr_val = jQuery('#sib_sel_attribute').val();
        var attr_type, attr_name, attr_text;
        if (attr_val == 'email' || attr_val == 'submit') {
            // get all info of attr
            var hidden_attr = jQuery('#sib_hidden_' + attr_val);
            attr_type = hidden_attr.attr('data-type');
            attr_name = hidden_attr.attr('data-name');
            attr_text = hidden_attr.attr('data-text');
        }
        else {
            jQuery.each(normal_attributes, function(index, value) {
                if (value['name'] == attr_val) {
                    attr_type = value['type'];
                    attr_name = value['name'];
                    attr_text = attr_name;
                }
            });

            jQuery.each(category_attributes, function(index, value) {
                if (value['name'] == attr_val) {
                    attr_type = value['type'];
                    attr_name = value['name'];
                    attr_text = attr_name;
                }
            });
        }

        // generate attribute html
        generate_attribute_html(attr_type, attr_name, attr_text);
    }

    function change_attribute_tag(attr_type, attr_name, attr_text){
        jQuery('#sib_field_label').attr('value', attr_text);
        jQuery('#sib_field_placeholder').attr('value', '');
        jQuery('#sib_field_initial').attr('value', '');
        jQuery('#sib_field_button_text').attr('value', attr_text);
        //jQuery('#sib_field_wrap').attr('checked', 'true');
        jQuery('.sib-attr-other').hide();
        jQuery('.sib-attr-normal').hide();
        jQuery('.sib-attr-category').hide();
        jQuery('#sib_field_required').removeAttr('checked');
        switch(attr_type)
        {
            case 'email':
                jQuery('#sib_field_required').attr('checked', 'true');
            case 'date':
                jQuery('#sib_field_placeholder').val('dd/mm/yyyy');
            case 'text':
            case 'float':
                jQuery('.sib-attr-normal').show();
                break;
            case 'category':
                jQuery('.sib-attr-category').show();
                break;
            case 'submit':
                jQuery('.sib-attr-other').show();
                break;
        }
    }

    // generate attribute html
    function generate_attribute_html(attr_type, attr_name, attr_text){
        var field_label = jQuery('#sib_field_label').val();
        var field_placeholder = jQuery('#sib_field_placeholder').val();
        var field_initial = jQuery('#sib_field_initial').val();
        var field_buttontext = jQuery('#sib_field_button_text').val();
        //var field_wrap = jQuery('#sib_field_wrap').is(':checked');
        var field_required = jQuery('#sib_field_required').is(':checked');
        if(field_required == true) field_label += '*';
        var field_type = jQuery('input[name=sib_field_type]:checked').val();

        var field_html = '';

        //if(field_wrap == true) {
        if(attr_type != 'submit') {
            field_html += '<p class="sib-' + attr_name + '-area"> \n';
        }
        else {
            field_html += '<p> \n';
        }
        //}

        if ((field_label != '') && (attr_type == 'category')) {
            if (field_type == 'select') {
                field_html += '    <label class="sib-' + attr_name + '-area">' + field_label + '</label> \n';
            }
            else {
                field_html += '    <div style="display:block;"><label class="sib-' + attr_name + '-area">' + field_label + '</label></div> \n';
            }
        }
        else if((field_label != '') && (attr_type != 'submit')) {
            field_html += '    <label class="sib-' + attr_name + '-area">' + field_label + '</label> \n';
        }


        switch (attr_type)
        {
            case 'email':
                field_html += '    <input type="email" class="sib-' + attr_name + '-area" name="' + attr_name + '" ';
                field_html += 'placeholder="' + field_placeholder + '" ';
                field_html += 'value="' + field_initial + '" ';
                if(field_required == true) {
                    field_html += 'required="required" ';
                }
                field_html += '> \n';
                break;
            case 'date':
            case 'text':
                field_html += '    <input type="text" class="sib-' + attr_name + '-area" name="' + attr_name + '" ';
                if(field_placeholder != '') {
                    field_html += 'placeholder="' + field_placeholder + '" ';
                }
                if(field_initial != '') {
                    field_html += 'value="' + field_initial + '" ';
                }
                if(field_required == true) {
                    field_html += 'required="required" ';
                }
                field_html += '> \n';
                break;
            case 'float':
                field_html += '    <input type="text" class="sib-' + attr_name + '-area" name="' + attr_name + '" ';
                if(field_placeholder != '') {
                    field_html += 'placeholder="' + field_placeholder + '" ';
                }
                if(field_initial != '') {
                    field_html += 'value="' + field_initial + '" ';
                }
                if(field_required == true) {
                    field_html += 'required="required" ';
                }
                field_html += 'pattern="[0-9]+([\\.|,][0-9]+)?" > \n';
                break;
            case 'submit':
                field_html += '    <input type="submit" class="sib-default-btn" name="' + attr_name + '" ';
                field_html += 'value="' + field_buttontext + '" ';
                field_html += '> \n';
                break;
            case 'category':
                var enumeration = [];
                jQuery.each(category_attributes, function(index, value) {
                    if (value['name'] == attr_name) {
                        enumeration = value['enumeration'];
                    }
                });

                if (field_type == 'select') {
                    field_html += '    <select class="sib-' + attr_name + '-area" name="' + attr_name + '" ';
                    if (field_required == true) {
                        field_html += 'required="required" ';
                    }
                    field_html += '> \n';
                }
                jQuery.each(enumeration, function(index, value) {
                    if (field_type == 'select') {
                        field_html += '      <option value="' + value['value'] + '">' + value['label'] + '</option> \n';
                    }
                    else {
                        field_html += '    <div style="display:block;"><input type="radio" class="sib-' + attr_name + '-area" name="' + attr_name + '" value="' + value['value'] + '" ';
                        if (field_required == true) {
                            field_html += 'required="required" ';
                        }
                        field_html += '>' + value['label'] + '</div> \n';
                    }
                });
                if (field_type == 'select') {
                    field_html += '    </select> \n';
                }
                break;
        }

        //if(field_wrap == true) {
        field_html += '</p>';
        //}

        jQuery('#sib_field_html').html(field_html);
    }

    function set_select_list() {
        var selected_list_id = jQuery('#sib_selected_list_id').val();

        var data = {
            frmid : jQuery('input[name=sib_form_id]').val(),
            action : 'sib_get_lists'
        };
        jQuery.post(ajax_sib_object.ajax_url, data, function(respond) {
            var select_html = '';
            var selected = respond.selected;

            jQuery.each(respond.lists, function(index, value) {
                if(value['name'] == 'Temp - DOUBLE OPTIN') return true;
                if ( selected.indexOf(value['id'].toString()) != '-1' ) {
                    select_html += '<option value="' + value['id'] + '" selected>' + value['name'] + '</option>';
                }
                else {
                    select_html += '<option value="' + value['id'] + '">' + value['name'] + '</option>';
                }
            });
            jQuery('#sib_select_list').html(select_html).trigger("chosen:updated");

            set_select_attributes();

        });
    }

    function set_select_template() {
        var selected_template_id = jQuery('#sib_selected_template_id').val();
        var selected_do_template_id = jQuery('#sib_selected_do_template_id').val();
        var default_template_name = jQuery('#sib_default_template_name').val();
        var data = {
            action : 'sib_get_templates'
        };
        jQuery.post(ajax_sib_object.ajax_url, data, function(respond) {
            var select_html = '<select id="sib_template_id" class="col-md-11" name="template_id">';
            if (selected_template_id == '-1') {
                select_html += '<option value="-1" selected>' + default_template_name + '</option>';
            }
            else {
                select_html += '<option value="-1">' + default_template_name + '</option>';
            }
            jQuery.each(respond.templates, function(index, value) {
                if (value['id'] == selected_template_id) {
                    select_html += '<option value="' + value['id'] + '" selected>' + value['name'] + '</option>';
                }
                else {
                    select_html += '<option value="' + value['id'] + '">' + value['name'] + '</option>';
                }
            });
            select_html += '</select>';
            jQuery('#sib_template_id_area').html(select_html);

            // For double optin.
            select_html = '<select class="col-md-11" name="doubleoptin_template_id" id="sib_doubleoptin_template_id">';
            if (selected_do_template_id == '-1') {
                select_html += '<option value="-1" selected>' + default_template_name + '</option>';
            }
            else {
                select_html += '<option value="-1">' + default_template_name + '</option>';
            }
            jQuery.each(respond.templates, function(index, value) {
                if (value['id'] == selected_do_template_id) {
                    select_html += '<option is_shortcode="' + value['is_dopt']  + '" value="' + value['id'] + '" selected>' + value['name'] + '</option>';
                }
                else {
                    select_html += '<option is_shortcode="' + value['is_dopt']  + '" value="' + value['id'] + '">' + value['name'] + '</option>';
                }
            });
            select_html += '</select>';
            jQuery('#sib_doubleoptin_template_id_area').html(select_html);
            // double optin template id
            jQuery('#sib_doubleoptin_template_id').on('change', function() {
                var shortcode_exist = jQuery(this).find(':selected').attr('is_shortcode');
                if (shortcode_exist == 0 && jQuery(this).val() != -1) {
                    jQuery('#sib_form_alert_message').show();
                    jQuery('#sib_disclaim_smtp').hide();
                    jQuery('#sib_disclaim_do_template').show();
                    jQuery(this).val('-1');
                }
                else {
                    jQuery('#sib_form_alert_message').hide();
                }
            });

            jQuery('#sib_setting_signup_spin').addClass('hide');
            //jQuery('#sib_setting_signup_body').removeClass('hide');
            //set_select_attributes();
        });
    }

    function set_select_attributes() {
        var data = {
            action : 'sib_get_attributes'
        };

        jQuery.post(ajax_sib_object.ajax_url, data, function(respond) {
            //normal_attributes = respond.attributes.normal_attributes;
            //category_attributes = respond.attributes.category_attributes;


            var iframWidth = jQuery('.form-field').width() - 48;
            jQuery('#sib-preview-form').width(iframWidth);

            normal_attributes = respond.attrs.attributes.normal_attributes;
            category_attributes = respond.attrs.attributes.category_attributes;
            var attr_email_name = jQuery('#sib_hidden_email').attr('data-text');
            var message_1 = jQuery('#sib_hidden_message_1').val();
            var message_2 = jQuery('#sib_hidden_message_2').val();
            var message_3 = jQuery('#sib_hidden_message_3').val();
            var message_4 = jQuery('#sib_hidden_message_4').val();
            var message_5 = jQuery('#sib_hidden_message_5').val();
            var select_html = '<select class="col-md-12" id="sib_sel_attribute">' +
                '<option value="-1" disabled selected>' + message_1 + '</option>' +
                '<optgroup label="' + message_2 + '">';
            select_html += '<option value="email">' + attr_email_name + '*</option>';
            jQuery.each(normal_attributes, function(index, value) {
                select_html += '<option value="' + value['name'] + '">' + value['name'] + '</option>';
            });
            select_html += '</optgroup>';
            select_html += '<optgroup label="' + message_3 + '">';
            jQuery.each(category_attributes, function(index, value) {
                if(value['name'] == 'DOUBLE_OPT-IN') return;
                select_html += '<option value="' + value['name'] + '">' + value['name'] + '</option>';
            });
            select_html += '</optgroup>';
            select_html += '<optgroup label="' + message_4 + '">';
            select_html += '<option value="submit">' + message_5 + '</option>';
            select_html += '</optgroup>';
            select_html += '</select>';

            jQuery('#sib_sel_attribute_area').html(select_html);
            jQuery('#sib_sel_attribute').on('change', function() {
                //
                jQuery('#sib-field-content').show();

                var attr_val = jQuery(this).val();
                var attr_type, attr_name, attr_text;
                if (attr_val == 'email' || attr_val == 'submit') {
                    // get all info of attr
                    var hidden_attr = jQuery('#sib_hidden_' + attr_val);
                    attr_type = hidden_attr.attr('data-type');
                    attr_name = hidden_attr.attr('data-name');
                    attr_text = hidden_attr.attr('data-text');
                }
                else {
                    jQuery.each(normal_attributes, function(index, value) {
                        if (value['name'] == attr_val) {
                            attr_type = value['type'];
                            attr_name = value['name'];
                            attr_text = attr_name;
                        }
                    });

                    jQuery.each(category_attributes, function(index, value) {
                        if (value['name'] == attr_val) {
                            attr_type = value['type'];
                            attr_name = value['name'];
                            attr_text = attr_name;
                        }
                    });
                }
                // change attribute tags
                change_attribute_tag(attr_type, attr_name, attr_text);

                // generate attribute html
                generate_attribute_html(attr_type, attr_name, attr_text);
            });
            jQuery('#sib_setting_form_spin').addClass('hide');
            //jQuery('#sib_setting_form_body').removeClass('hide');
            set_select_template();
        });
    }

    function update_preview(){

        var frmid = jQuery('#sib_form_id').val();
        var formHtml = jQuery('#sibformmarkup').val();
        var formCss = jQuery('#sibcssmarkup').val();
        var isDepend = jQuery('input[name=sib_css_type]:checked').val();
        var data = {
            action:'sib_update_form_html',
            frmid: frmid,
            frmData: formHtml,
            frmCss: formCss,
            isDepend: isDepend
        };
        jQuery.post(ajax_sib_object.ajax_url, data,function() {
            var preview_form = jQuery('#sib-preview-form');
            preview_form.attr('src', preview_form.attr('src') + '&action=update');
        });
    }
    // get cursor posistion of text area
    function get_cursor_position(node) {
        //node.focus();
        /* without node.focus() IE will returns -1 when focus is not on node */
        if(node.selectionStart) return node.selectionStart;
        else if(!document.selection) return 0;
        var c		= "\001";
        var sel	= document.selection.createRange();
        var dul	= sel.duplicate();
        dul.moveToElementText(node);
        sel.text	= c;
        var len		= (dul.text.indexOf(c));
        sel.moveStart('character',-1);
        sel.text	= "";
        return len;
    }
    // set cursor position at top of text area
    function setSelectionRange(input, selectionStart, selectionEnd) {
        if (input.setSelectionRange) {
            input.focus();
            input.setSelectionRange(selectionStart, selectionEnd);
        } else if (input.createTextRange) {
            var range = input.createTextRange();
            range.collapse(true);
            range.moveEnd('character', selectionEnd);
            range.moveStart('character', selectionStart);
            range.select();
        }
    }

    /*////////////////////////////////////////////////////////////////////////////////////*/

    //jQuery("[data-toggle='tooltip']").tooltip();
    jQuery('.popover-help-form').popover({
    });
    jQuery('.sib-spin').hide();
    jQuery('body').on('click', function(e) {
        if(!jQuery(e.target).hasClass('popover-help-form')) {
            jQuery('.popover-help-form').popover('hide');
        }
    });

    /////////////////////////////////
    /*       home settings         */
    /////////////////////////////////

    // var elements
    var sib_access_key = jQuery('#sib_access_key');
    var sib_validate_btn = jQuery('#sib_validate_btn');

    // validate button click process in welcome page
    sib_validate_btn.on('click', function(){

        var access_key = sib_access_key.val();

        // check validation
        var error_flag = 0;
        if(access_key == '') {
            sib_access_key.addClass('error');
            error_flag =1;
        }

        if(error_flag != 0) {
            return false;
        }

        // ajax process for validate
        var data = {
            action:'sib_validate_process',
            access_key: access_key
        };

        jQuery('.sib_alert').hide();
        jQuery('.sib-spin').show();
        sib_access_key.removeClass('error');
        jQuery(this).attr('disabled', 'true');

        jQuery.post(ajax_sib_object.ajax_url, data, function(respond) {
            jQuery('.sib-spin').hide();
            sib_validate_btn.removeAttr('disabled');
            if(respond == 'success') {
                jQuery('#success-alert').show();
                /*var cur_url = jQuery('#cur_refer_url').val();
                window.location.href = cur_url;*/
                window.location.reload();
            }
            else if (respond == 'curl_no_installed') {
                sib_access_key.addClass('error');
                jQuery('#failure-alert').html(jQuery('#curl_no_exist_error').val()).show();
            }
            else if (respond == 'curl_error') {
                sib_access_key.addClass('error');
                jQuery('#failure-alert').html(jQuery('#curl_error').val()).show();
            }           
            else {
                sib_access_key.addClass('error');
                jQuery('#failure-alert').html(jQuery('#general_error').val()).show();
            }
        });
    });

    sib_access_key.on('keypress', function(){
        jQuery(this).removeClass('error');
    });

    // Transactional emails
    jQuery('input[name=activate_email]').on('click', function(){
        var option_val = jQuery(this).val();
        var data = {
            action: 'sib_activate_email_change',
            option_val: option_val
        }
        jQuery.post(ajax_sib_object.ajax_url, data, function(respond) {
            if(respond == 'yes')
                jQuery('#email_send_field').show();
            else
                jQuery('#email_send_field').hide();
        });

        return true;
    });

    // change sender detail
    jQuery('#sender_list').on('change',function(){
        var data = {
            action: 'sib_sender_change',
            sender: jQuery(this).val()
        };
        jQuery.post(ajax_sib_object.ajax_url, data, function() {
            jQuery(this).blur();
        });

        return true;
    });

    // validate MA
    jQuery('#validate_ma_btn').on('click',function(){
        var option_val = jQuery('input[name=activate_ma]:checked').val();
        var data = {
            action:'sib_validate_ma',
            option_val: option_val
        };
        var uninstall = false;
        var uninstallMsg = jQuery('#sib-ma-unistall').val();
        if(option_val != 'yes'){
            uninstall = confirm(uninstallMsg);
        }
        if(option_val == 'yes' || uninstall) {
            jQuery(this).find('.sib-spin').show();
            jQuery('.sib-ma-alert').hide();
            jQuery(this).attr('disabled', 'true');
            jQuery.post(ajax_sib_object.ajax_url, data, function (respond) {
                jQuery('.sib-spin').hide();
                jQuery('#validate_ma_btn').removeAttr('disabled');
                if (respond == 'yes') {
                    jQuery('.sib-ma-active').show();
                } else if(respond == 'no') {
                    jQuery('.sib-ma-inactive').show();
                } else if(respond == 'disabled'){
                    jQuery('.sib-ma-disabled').show();
                    jQuery('#activate_ma_radio_no').prop('checked', true);
                }
                setTimeout(function(){
                    if(respond != 'disabled')
                        window.location.reload();
                },2000);

            });
        }
    });

    // send activate email button
    jQuery('#send_email_btn').on('click',function(){
        var activate_email = jQuery('#activate_email');
        var email = activate_email.val();
        if(email == '' || isValidEmailAddress(email) != true) {
            activate_email.removeClass('has-success').addClass('error');
            jQuery('#failure-alert').show();
            return false;
        }
        jQuery(this).attr('disabled', 'true');

        var data = {
            action:'sib_send_email',
            email:email
        };

        jQuery('.sib_alert').hide();
        activate_email.removeClass('error');
        jQuery(this).find('.sib-spin').show();
        jQuery.post(ajax_sib_object.ajax_url, data,function(respond) {
            jQuery('.sib-spin').hide();
            jQuery('#send_email_btn').removeAttr('disabled');
            if(respond != 'success') {
                jQuery('#activate_email').removeClass('has-success').addClass('error');
                jQuery('#failure-alert').show();
            } else {
                jQuery('#success-alert').show();
            }
        });
    });

    ////////////////////////////////
    /*       manage forms         */
    ////////////////////////////////

    jQuery('#sib-field-content').hide();

    // check confirm email
    var is_send_confirm_email = jQuery("input[name=is_confirm_email]:checked").val();

    if(is_send_confirm_email == '1') {
        jQuery('#sib_confirm_template_area').show();
        jQuery('#sib_confirm_sender_area').show();
    } else {
        jQuery('#sib_confirm_template_area').hide();
        jQuery('#sib_confirm_sender_area').hide();
    }

    // check double optin
    var is_double_optin = jQuery("input[name=is_double_optin]:checked").val();

    if(is_double_optin == '1') {
        jQuery('#is_confirm_email_no').prop("checked", true);
        jQuery('#sib_confirm_template_area').hide();
        jQuery('#sib_confirm_sender_area').hide();
        jQuery('#sib_double_sender_area').show();
        jQuery('#sib_doubleoptin_template_area').show();

    } else {
        jQuery('#sib_double_sender_area').hide();
        jQuery('#sib_double_redirect_area').hide();
        jQuery('#sib_doubleoptin_template_area').hide();
    }

    if (jQuery('#sib_setting_signup_body').find('#sib_select_list_area').length > 0 ) {
        set_select_list();
        jQuery('#sib_select_list').chosen({width:"100%"});
    }

    // keep change of fields
    jQuery('.sib_field_changes').on('change',function() {
        change_field_attr();
    });

    // add field to form textarea
    jQuery('#sib_add_to_form_btn').on('click',function() {
        var field_html = jQuery('#sib_field_html').val();
        var formMarkup = jQuery("#sibformmarkup");
        //var cursorPosition = formMarkup.prop("selectionStart");
        var cursorPosition = get_cursor_position(formMarkup[0]);
        var html = formMarkup.val();
        if(html.charCodeAt(cursorPosition) == 10 || html.charCodeAt(cursorPosition) == 13){ // 10 is value of new line
            field_html = "\n" + field_html;
        }else{
            field_html = field_html + "\n";
        }
        var formData = [html.slice(0, cursorPosition), field_html, html.slice(cursorPosition)].join('');
        formMarkup.val(formData);

        // hide field edit after add the field to form
        jQuery('#sib-field-content').hide();
        jQuery("#sib_sel_attribute").val('-1');

        /*/ refresh iframe form /*/
        // ajax to update form html
        update_preview();
        // set cursor position at top
        setSelectionRange(formMarkup[0], 0, 0);
        return false;
    });

    // click confirm email
    jQuery("input[name=is_confirm_email]").on('click',function() {
        var confirm_email = jQuery(this).val();
        var is_activated_smtp = parseInt(jQuery("#is_smtp_activated").val());

        if(confirm_email == '1') {
            jQuery('#sib_doubleoptin_template_id').val('-1');
            jQuery('#is_double_optin_no').prop("checked", true);
            jQuery('#sib_double_sender_area').hide();
            jQuery('#sib_double_redirect_area').hide();
            jQuery('#sib_confirm_template_area').show();
            jQuery('#sib_confirm_sender_area').show();
            jQuery('#sib_doubleoptin_template_area').hide();
            if (is_activated_smtp == 0) {
                jQuery('#sib_form_alert_message').show();
                jQuery('#sib_disclaim_smtp').show();
                jQuery('#sib_disclaim_do_template').hide();
            }
        } else {
            jQuery('#sib_confirm_template_area').hide();
            jQuery('#sib_confirm_sender_area').hide();
            jQuery('#sib_form_alert_message').hide();
        }
    });

    // click double optin
    jQuery('input[name=is_double_optin]').on('click', function() {
        var double_optin = jQuery(this).val();
        var is_activated_smtp = parseInt(jQuery("#is_smtp_activated").val());
        if(double_optin == '1') {
            jQuery('#sib_template_id').val('-1');
            jQuery('#is_confirm_email_no').prop("checked", true);
            jQuery('#sib_confirm_template_area').hide();
            jQuery('#sib_confirm_sender_area').hide();
            jQuery('#sib_double_sender_area').show();
            jQuery('#sib_double_redirect_area').show();
            jQuery('#sib_doubleoptin_template_area').show();
            if (is_activated_smtp == 0) {
                jQuery('#sib_form_alert_message').show();
                jQuery('#sib_disclaim_smtp').show();
                jQuery('#sib_disclaim_do_template').hide();
            }
        } else {
            jQuery('#sib_double_sender_area').hide();
            jQuery('#sib_double_redirect_area').hide();
            jQuery('#sib_doubleoptin_template_area').hide();
            jQuery('#sib_form_alert_message').hide();
        }
    });

    // click redirect url
    jQuery('input[name=is_redirect_url_click]').on('click', function() {
        jQuery('#sib_subscrition_redirect_area').toggle();
    });



    //// refresh iframe to preview form
    jQuery('#sib-preview-form-refresh').on('click',function(){
        // ajax to update form html
        update_preview();
    });

    //// display popup when delete form
    jQuery('.sib-form-delete').on('click', function() {
        alert('Are you sure you want to delete this form?');
    });

    //// custom or theme's css
    jQuery('input[name=sib_css_type]').on('change',function() {
        jQuery('#sibcssmarkup').toggle();
        update_preview();
    });

   /* // remove all transients
    jQuery('#sib-remove-cache').on('click',function(){
        var data = {
            action:'sib_remove_cache'
        };
        jQuery.post(ajax_sib_object.ajax_url, data,function() {
            window.location.reload();
        });

    });*/

   // remove all transients
    jQuery(window).focus(function() {

        var data = {
            action: 'sib_remove_cache'
        }
        jQuery.post(ajax_sib_object.ajax_url, data,function(respond) {

            if(respond == 'success') {
                //
            }
        });
    });

    /* sync wordpress users to sendinblue contact list */
    // sync popup
    jQuery('#sib-sync-btn').on('click', function() {
        var syncModal = jQuery('.sib-sync-modal');
        syncModal.modal();
        jQuery('#sync-failure').hide();

        // add to multilist field
        var list = jQuery('#sib_select_list');
        list[0].selectedIndex = 0;
        list.chosen({width:"100%"});

        syncModal.on('hidden.bs.modal', function () {
            //window.location.reload();
        });
    });

    var attrFieldLine = jQuery('.sync-attr-line').html();
    // sync add attr line filed
    jQuery('.modal-body').on('click', '.sync-attr-plus', function(){
        jQuery('.sync-attr-line').append(attrFieldLine);
        jQuery('.sync-attr-dismiss').show();
    });
    // sync dismiss attr line filed
    jQuery('.modal-body').on('click', '.sync-attr-dismiss', function(){
        jQuery(this).closest('.sync-attr').remove();
        var attrCount = jQuery('.sync-attr').length;
        if(attrCount == 1) jQuery('.sync-attr-dismiss').hide();
    });

    // set attribute matching
    jQuery('.modal-body').on('change', 'select', function () {
        if(jQuery(this).attr("class") == 'sync-wp-attr'){
            jQuery(this).closest('.sync-attr').find('.sync-match').val(jQuery(this).val());
        }else{
            jQuery(this).closest('.sync-attr').find('.sync-match').attr('name',jQuery(this).val());
        }
    });

    // sync users to sendinblue
    jQuery('#sib_sync_users_btn').on('click', function(){

        jQuery(this).attr('disabled', 'true');
        var postData = jQuery('#sib-sync-form').serializeObject();
        jQuery(this).closest('form').find('input[type=hidden]').each(function (index, value) {
            var attrName = jQuery(this).attr('name');
            if(jQuery('input[name='+attrName+']').length > 1){
                // the attribute is duplicated !
                postData['errAttr'] = attrName;
            }
        });

        var data = {
            action:'sib_sync_users',
            data: postData,
        };

        jQuery('.sib_alert').hide();
        jQuery(this).find('.sib-spin').show();
        jQuery.post(ajax_sib_object.ajax_url, data,function(respond) {
            jQuery('.sib-spin').hide();
            jQuery('#sib_sync_users_btn').removeAttr('disabled');
            if(respond.code != 'success') {
                jQuery('#sync-failure').show().html(respond.message);
            } else {
                // success to sync wp users
                jQuery('.tb-close-icon').click();
                window.location.reload();
            }
        });

    });
    jQuery('.sib-add-captcha').on('click', function(){
       var add_captcha = jQuery(this).val();
        if(add_captcha == '1')
        {
            jQuery('.sib-captcha-key').show('slow');
        }
        else
        {
            jQuery('.sib-captcha-key').hide('slow');
        }
    });
    jQuery('#sib_add_captcha_btn').on('click', function(){
        var site_key = jQuery('#sib_captcha_site').val();
        var field_html = '<div class="g-recaptcha" data-sitekey="'+site_key+'"></div>';
        var formMarkup = jQuery("#sibformmarkup");
        //var cursorPosition = formMarkup.prop("selectionStart");
        var cursorPosition = get_cursor_position(formMarkup[0]);
        var html = formMarkup.val();
        if(html.charCodeAt(cursorPosition) == 10 || html.charCodeAt(cursorPosition) == 13){ // 10 is value of new line
            field_html = "\n" + field_html;
        }else{
            field_html = field_html + "\n";
        }
        var formData = [html.slice(0, cursorPosition), field_html, html.slice(cursorPosition)].join('');
        formMarkup.val(formData);

        // hide field edit after add the field to form
        jQuery('#sib-field-content').hide();
        jQuery("#sib_sel_attribute").val('-1');

        /*/ refresh iframe form /*/
        // ajax to update form html
        update_preview();
        // set cursor position at top
        setSelectionRange(formMarkup[0], 0, 0);
        return false;
    });
});

// get serialized data form sync users form
jQuery.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    jQuery.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
