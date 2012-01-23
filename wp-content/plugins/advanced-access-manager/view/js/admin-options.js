/*
  Copyright (C) <2011>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

function mvbam_object(){
    /*
     * Indicates how many changes happend on form
     * 
     * @var int
     * @access private
     */
    this.formChanged = 0;
    
    /*
     * Indicate that we are soring Main Menu
     * 
     * @var bool
     * @access private
     */
    this.sorting = false;
    
    /*
     *Indicate that sorting has been present
     *
     *@var bool
     *@access private
     */
    this.sorted = false;
    
    /*
     * Continue submition
     * 
     * @var bool
     * @access private
     */
    this.submiting = false;
    
    /**
     * Hooks
     * 
     * @var object
     * @access private
     */
    this.hooks = {
        'tabs-loaded' : []
    };
    
    /*
     * Array of pre-defined capabilities for default WP roles
     * 
     * @var array
     * @access private
     */
    this.roleCapabilities = {
        radio2 : ['moderate_comments', 'manage_categories', 'manage_links', 'upload_files',
        'unfiltered_html', 'edit_posts', 'edit_others_posts', 'edit_published_posts',
        'publish_posts', 'edit_pages', 'read', 'level_7', 'level_6', 'level_5', 'level_4',
        'level_3', 'level_2', 'level_1', 'level_0', 'edit_others_pages', 'edit_published_pages',
        'publish_pages', 'delete_pages', 'delete_others_pages', 'delete_published_pages',
        'delete_posts', 'delete_others_posts', 'delete_published_posts', 'delete_private_posts',
        'read_private_posts', 'delete_private_pages', 'edit_private_pages', 'read_private_pages'],
        radio3 : ['upload_files', 'edit_posts', 'edit_others_posts', 'edit_published_posts', 
        'publish_posts', 'read', 'level_2', 'level_1', 'level_0', 'delete_posts', 'delete_published_posts',],
        radio4 : ['edit_posts', 'read', 'level_1', 'level_0', 'delete_posts'],
        radio5 : ['read', 'level_0']
    }
    
    /*
     * Current importing status
     * 
     * @var int
     */
    this.import_status = 0;
    
    /*
     * Show Confirm Apply to All User Role
     */
    this.hideApplyAll = wpaccessLocal.hide_apply_all;
    
    /**
     * Config XML Editor
     * 
     * @var object
     * @access public
     */
    this.editor = null;
    
}

/**
 * Register hooks
 */
mvbam_object.prototype.addHook = function(zone, callback){
    
    this.hooks[zone].push(callback);
}

/*
 * **
 */
mvbam_object.prototype.addNewRole = function(){
    var newRoleTitle = jQuery.trim(jQuery('#new-role-name').val());
    if (!newRoleTitle){
        jQuery('#new-role-name').effect('highlight',3000);
        return;
    }
    jQuery('#new-role-name').val('');
    jQuery('.new-role-name-empty').show();
    this.showAjaxLoader('#tabs');
    var params = {
        'action' : 'mvbam',
        'sub_action' : 'create_role',
        '_ajax_nonce': wpaccessLocal.nonce,
        'role' : newRoleTitle
    };
    var _this = this;
    jQuery.post(wpaccessLocal.ajaxurl, params, function(data){  
        if (data.result == 'success'){
            var nOption = '<option value="'+data.new_role+'">'+newRoleTitle+'</option>';
            jQuery('#role option:last').after(nOption);
            jQuery('#role').val(data.new_role);
            _this.getRoleOptionList(data.new_role);
            jQuery('div #new-role-form').hide();
            jQuery('.change-role').show();
            //jQuery('#new-role-message-error').hide();
            jQuery('.delete-role-table tbody').append(data.html);
            jQuery('#new-role-message-ok').show().delay(2000).hide('slow');
            _this.initRoleNameRow(jQuery('#dl-row-' + data.new_role));
            _this.toggleUserSelector();
        }else{
            _this.removeAjaxLoader('#tabs');
            //    jQuery('#new-role-message-ok').hide();
            jQuery('#new-role-message-error').show().delay(2000).hide('slow');
        }
    }, 'json'); 
}

mvbam_object.prototype.showAjaxLoader = function(selector, type){
    jQuery(selector).addClass('loading-new');
    var l_class = (type == 'small' ? 'ajax-loader-small' : 'ajax-loader-big');
    
    jQuery(selector).prepend('<div class="' + l_class +'"></div>');
    jQuery('.' + l_class).css({
        top: jQuery(selector).height()/2 - (type == 'small' ? 16 : 50),
        left: jQuery(selector).width()/2 - (type == 'small' ? 8 : 25) 
    });
}

mvbam_object.prototype.removeAjaxLoader = function(selector, type){
    jQuery(selector).removeClass('loading-new');
    var l_class = (type == 'small' ? 'ajax-loader-small' : 'ajax-loader-big');
    jQuery('.' + l_class).remove();
}

mvbam_object.prototype.getRoleOptionList = function(currentRoleID){
    this.showAjaxLoader('#tabs');
        
    var params = {
        'action' : 'render_optionlist',
        '_ajax_nonce': wpaccessLocal.nonce,
        'role' : currentRoleID
    };
    jQuery('#current_role').val(currentRoleID);
    var _this = this;
    //restore some params
    this.sorting = false;
    this.sorted = false;
    
    jQuery.post(wpaccessLocal.handlerURL, params, function(data){
        jQuery('#metabox-wpaccess-options').replaceWith(data.html);
        _this.configureElements();
        jQuery('div #role-select').hide();
        jQuery('#current-role-display').html(jQuery('#role option:selected').text());
        jQuery('.change-role').show();
        //get list of users
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'get_userlist',
            '_ajax_nonce': wpaccessLocal.nonce,
            'role' : currentRoleID
        };
        jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
            if (data.status == 'success'){
                jQuery('#user').html(data.html);
                jQuery('div #user-select').hide();
                jQuery('.change-user').show();
                jQuery('#current-user-display').html(jQuery('#user option:eq(0)').text());
                jQuery('#current_user').val(0);
            }
        }, 'json');
    }, 'json');
}

mvbam_object.prototype.getUserOptionList = function(currentRoleID, currentUserID){
    this.showAjaxLoader('#tabs');
        
    var params = {
        'action' : 'render_optionlist',
        '_ajax_nonce': wpaccessLocal.nonce,
        'role' : currentRoleID,
        'user' : currentUserID
    };
    jQuery('#current_user').val(currentUserID);
    var _this = this;
    //restore some params
    this.sorting = false;
    this.sorted = false;
    
    jQuery.post(wpaccessLocal.handlerURL, params, function(data){
        jQuery('#metabox-wpaccess-options').replaceWith(data.html);
        _this.configureElements();
        jQuery('div #user-select').hide();
        jQuery('#current-user-display').html(jQuery('#user option:selected').text());
        jQuery('.change-user').show();
    }, 'json');
}


mvbam_object.prototype.configureElements = function(){ 
    this.configureMainMenu();
    this.configureMetaboxes();
    this.configureCapabilities();
    this.configureConfigTab();
    this.postPage();
    //execute hooks
    for(var i in this.hooks['tabs-loaded']){
        this.hooks['tabs-loaded'][i].call();
    }
}

mvbam_object.prototype.configureConfigTab = function(){ 
    //init codemirror
    mObj.editor = CodeMirror.fromTextArea(document.getElementById("access_config"), {
        mode: {
            name: "ini", 
            htmlMode: true
        },
        lineNumbers: true
    });
}

mvbam_object.prototype.configureMainMenu = function(){
    
    var _this = this;
    jQuery( "#tabs" ).tabs({
        show: function(event, ui) { 
            if (ui.index == 4){
                mObj.editor.refresh();
            } 
        }
    }); 
    this.configureAccordion("#main-menu-options");
    jQuery('#main-menu-options > div').each(function(){
        jQuery('#whole', this).bind('click',{
            _this: this
        }, function(event){
            var checked = (jQuery(this).attr('checked') ? true : false);
            jQuery('input:checkbox', event.data._this).attr('checked', checked);
        });
    });
    var _this = this;
    //add reorganize menu functionality
    //jQuery('#reorganize').button();
    jQuery('#reorganize').bind('click', function(event){
        event.preventDefault();
        if (_this.sorting){
            jQuery('#sorting-tip').hide();
            //jQuery('#reorganize').button('option', 'label', 'Reorganize');
            jQuery('#reorganize').html(wpaccessLocal.LABEL_129);
            //save confirmation message
            if (_this.sorted){
                jQuery( "#dialog-reorder-confirm #role-title" ).html(jQuery('#current-role-display').html());
                if (jQuery('#current_user').val()){
                    _this.saveMenuOrder(false);
                }else{
                    var pa = {
                        resizable: false,
                        height:180,
                        modal: true,
                        buttons: {}
                    }
                    pa.buttons[wpaccessLocal.LABEL_130] = function() {
                        _this.saveMenuOrder(false);
                        jQuery( this ).dialog( "close" );
                    }
                    pa.buttons[wpaccessLocal.LABEL_131] = function() {
                        _this.saveMenuOrder(true);
                        jQuery( this ).dialog( "close" );
                    }
                    jQuery( "#dialog-reorder-confirm" ).dialog(pa);
                }
            }
            _this.configureAccordion('#main-menu-options');
        }else{
            jQuery('#sorting-tip').show();
            //jQuery('#reorganize').button('option', 'label', 'Save Order');
            jQuery('#reorganize').html('Save Order');
            _this.configureAccordion('#main-menu-options', true);
        }
        _this.sorting = !_this.sorting;
    });
    
    //check Add-ons
    var params = {
        action: "mvbam",
        sub_action : 'check_addons',
        '_ajax_nonce': wpaccessLocal.nonce
    }
    jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
        if (data.status == 'success' && data.available){
            jQuery('.addons-info').removeClass('dialog');
            jQuery('.addons-info').bind('click', function(event){
                event.preventDefault();
                var pa = {
                    resizable: false,
                    height:190,
                    modal: true,
                    buttons : {}
                }
                pa.buttons[wpaccessLocal.LABEL_76] = function() {
                    jQuery( this ).dialog( "close" );
                }
                jQuery( "#addons-notice" ).dialog(pa);
            });
        } 
    }, 'json');   
}

mvbam_object.prototype.saveMenuOrder = function(apply_all){
    this.sorted = false;
    this.sorting = false;
    var _this = this;
    var params = {
        'action' : 'mvbam',
        'sub_action' : 'save_order',
        '_ajax_nonce': wpaccessLocal.nonce,
        'apply_all' : (apply_all ? 1 : 0),
        'role' : jQuery('#current_role').val(),
        'user' : jQuery('#current_user').val(),
        'menu' : new Array()
    }
    //get list of menus in proper order
    jQuery('#main-menu-options > div').each(function(){
        params.menu.push(jQuery(this).attr('id'));
    });
    
    jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
        if (data.status == 'success'){
            if (_this.submiting){
                jQuery('#wp-access').submit();
            }
        }
    }, 'json');
}

mvbam_object.prototype.configureMetaboxes = function(){
    this.configureAccordion('#metabox-list');
    var _this = this;
    jQuery('.initiate-metaboxes').bind('click', function(event){
        event.preventDefault();
        jQuery('#initiate-message').hide();
        jQuery('#progressbar').progressbar({
            value: 0
        }).show();
        _this.initiationChain('');
    });
    
    jQuery('.initiate-url').bind('click', function(event){
        event.preventDefault();
        var val = jQuery('.initiate-url-text').val();
        if (jQuery.trim(val)){
            jQuery('#initiate-message').hide();
            jQuery('#progressbar').progressbar({
                value: 20
            }).show();
            var params = {
                'action' : 'mvbam',
                'sub_action' : 'initiate_url',
                '_ajax_nonce': wpaccessLocal.nonce,
                'url' : val
            };
            jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
                jQuery('.initiate-url-text').val('');
                jQuery('.initiate-url-empty').show();
                if (data.status == 'success'){
                    jQuery('#progressbar').progressbar('option', 'value', 100);
                    _this.grabInitiatedWM();
                }else{
                    _this.emergencyCall(data);
                }
            }, 'json');
        }else{
            jQuery('.initiate-url-text').effect('highlight',3000);
        }
    });
    
    jQuery('.initiate-url-empty').click(function(){
        event.preventDefault();
        jQuery('#initiateURL').focus();
    });
    jQuery('#initiateURL').focus(function(){
        jQuery('.initiate-url-empty').hide();
    });
    jQuery('#initiateURL').blur(function(){
        if (!jQuery.trim(jQuery(this).val())){
            jQuery('.initiate-url-empty').show();
        }
    });
}

mvbam_object.prototype.configureCapabilities = function(){
    var _this = this;
    
    jQuery('.default-roles > a').each(function(){
        var id = jQuery(this).attr('id');
        jQuery(this).bind('click', function(event){
            _this.changeCapabilities(event, id);
        })
    });
    jQuery('#new-capability').bind('click', function(e){
        e.preventDefault();
        _this.addNewCapability();
    });
    //add user to blog
    jQuery('#add-user-toblog').bind('click', function(){
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'add_blog_admin',
            '_ajax_nonce': wpaccessLocal.nonce,
        };
        jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
            if (data.status != 'success'){
                //TODO -Implement error
                alert(data.message);
            }
        }, 'json');
    });
    
}

mvbam_object.prototype.postPage = function(){
    jQuery("#tree").treeview({
        url: wpaccessLocal.ajaxurl,
        // add some additional, dynamic data and request with POST
        ajax: {
            data : {
                action: "mvbam",
                sub_action : 'get_treeview',
                '_ajax_nonce': wpaccessLocal.nonce
            },
            type : 'post'
        },
        animated: "medium",
        control:"#sidetreecontrol",
        persist: "location"
    });        
}

mvbam_object.prototype.addNewCapability = function(){
    
    jQuery( "#capability-form #new-cap" ).val('').focus();
    var pa = {
        resizable: false,
        height:150,
        modal: false,
        buttons: {}
    }
    pa.buttons[wpaccessLocal.LABEL_132] = function() {
        
        var cap = jQuery( "#capability-form #new-cap" ).val();        
        if (jQuery.trim(cap)){
            jQuery( this ).dialog( "close" );
            var params = {
                'action' : 'mvbam',
                'sub_action' : 'add_capability',
                '_ajax_nonce': wpaccessLocal.nonce,
                'cap' : cap,
                'role' : jQuery('#role').val(),
                'user' : jQuery('#user').val()
            };
            jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
                if (data.status == 'success'){
                    jQuery('.capability-item:last').after(data.html);
                    jQuery('.capability-item:last .info').remove();
                }else{
                    //TODO -Implement error
                    alert(data.message);
                }
            }, 'json');
        }else{
            jQuery( "#capability-form #new-cap" ).effect('highlight', 5000);
        }
    }
    pa.buttons[wpaccessLocal.LABEL_77] = function() {
        jQuery( this ).dialog( "close" );
    }
    jQuery( "#capability-form" ).dialog(pa);
}

mvbam_object.prototype.emergencyCall = function(data){
    var _this = this;
    jQuery.ajax(data.url, {
        success : function(){ 
            if (data.next){
                _this.initiationChain(data.next);
            }else{
                jQuery('#progressbar').progressbar("option", "value", 100);
                _this.grabInitiatedWM();
            }
        },
        error : function(jqXHR, textStatus, errorThrown){
            if (textStatus != 'timeout'){
                if (data.next){
                    _this.initiationChain(data.next);
                }else{
                    jQuery('#progressbar').progressbar("option", "value", 100);
                    _this.grabInitiatedWM();
                }
            }else{
                //TODO - Implement Error
                alert(wpaccessLocal.LABEL_133);
                jQuery('#progressbar').progressbar("option", "value", 100);
                _this.grabInitiatedWM();
            }
        },
        timeout : 5000
    });

}

mvbam_object.prototype.initiationChain = function(next){
    //start initiation
    var params = {
        'action' : 'mvbam',
        'sub_action' : 'initiate_wm',
        '_ajax_nonce': wpaccessLocal.nonce,
        'next' : next
    };
    var _this = this;
    jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
        jQuery('#progressbar').progressbar("option", "value", data.value);
        if (data.status == 'success'){
            if (data.next){
                _this.initiationChain(data.next);
            }else{
                jQuery('#progressbar').progressbar("option", "value", 100);
                _this.grabInitiatedWM();
            }
        }else{
            //try directly to go to that page
            _this.emergencyCall(data);
        }
    }, 'json');
}

mvbam_object.prototype.grabInitiatedWM = function(){
    jQuery('#progressbar').progressbar('destroy').hide();
    jQuery('#initiate-message').show();
    jQuery('#metabox-list').empty().css({
        'height' : '200px',
        'width' : '100%'
    });
    this.showAjaxLoader('#metabox-list');
    var params = {
        'action' : 'mvbam',
        'sub_action' : 'render_metabox_list',
        '_ajax_nonce': wpaccessLocal.nonce,
        'role' : jQuery('#current_role').val()
    };
    var _this = this;    
    jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
        if (data.status == 'success'){
            jQuery('#metabox-list').replaceWith(data.html);
            _this.configureAccordion('#metabox-list');
        }else{
            //TODO - Implement Error
            alert(wpaccessLocal.LABEL_90);
        }
    }, 'json');
}

mvbam_object.prototype.configureAccordion = function(selector, sortable){
    
    var icons = {
        header: "ui-icon-circle-arrow-e",
        headerSelected: "ui-icon-circle-arrow-s"
    };
    var _this = this;
    jQuery(selector).accordion('destroy');
    if (sortable === true){
        jQuery(selector).accordion({
            collapsible: true,
            header: 'h4',
            autoHeight: false,
            icons: icons,
            active: -1
        }).sortable({
            axis: "y",
            handle: "h4",
            stop: function() {
                stop = true;
                _this.sorted = true;
            }
        });
    }else{
        jQuery(selector).sortable('destroy');
        jQuery(selector).accordion({
            collapsible: true,
            header: 'h4',
            autoHeight: false,
            icons: icons,
            active: -1
        });
    }
}

mvbam_object.prototype.deleteRole = function(role){
    jQuery('#delete-role-title').html(jQuery('.delete-role-table #dl-row-'+role+' td:first').html());
    var _this = this;
    var pa = {
        resizable: false,
        height:180,
        modal: true,
        buttons: {}
    }
    pa.buttons[wpaccessLocal.LABEL_134] = function() {
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'delete_role',
            '_ajax_nonce': wpaccessLocal.nonce,
            'role' : role
        };
        jQuery.post(wpaccessLocal.ajaxurl, params, function(){ 
            jQuery('.delete-role-table #dl-row-' + role).remove();
            if (jQuery('#role option[value="'+role+'"]').attr('selected')){
                _this.getRoleOptionList(jQuery('#role option:first').val());
            }
            jQuery('#role option[value="'+role+'"]').remove();
            _this.toggleUserSelector();
        });
        jQuery( this ).dialog( "close" );
    }
            
    pa.buttons[wpaccessLocal.LABEL_77] = function() {
        jQuery( this ).dialog( "close" );
    }
    jQuery( "#dialog-delete-confirm" ).dialog(pa);
}

mvbam_object.prototype.changeCapabilities = function(event, type){
    
    event.preventDefault();
    
    switch(type){
        case 'radio1': //administrator
            jQuery('.capability-item input').attr('checked', true);
            break;
        
        case 'radio2': //editor
        case 'radio3': //author
        case 'radio4': //contributor
        case 'radio5': //subscriber
            jQuery('.capability-item input').attr('checked', false);
            for (var c in this.roleCapabilities[type]){
                jQuery('.capability-item input[name*="['+this.roleCapabilities[type][c]+']"]').attr('checked', true);
            }
            break;
        
        case 'radio6': //clear all
            jQuery('.capability-item input').attr('checked', false);
            break;
            
        default:
            break;
    }
}

mvbam_object.prototype.changeRole = function(){
    var currentRoleID = jQuery('#role').val();
    this.getRoleOptionList(currentRoleID); 
    this.formChanged = 0;
    
    this.toggleUserSelector();
}

mvbam_object.prototype.toggleUserSelector = function(){
    
    var currentRoleID = jQuery('#role').val();
    if (currentRoleID == '_visitor'){
        jQuery('.misc-user-section').hide();
    }else{
        jQuery('.misc-user-section').show();
    }
}

mvbam_object.prototype.changeUser = function(){
    var currentRoleID = jQuery('#current_role').val();
    var currentUserID = jQuery('#user').val();
    this.getUserOptionList(currentRoleID, currentUserID); 
    this.formChanged = 0;
}

mvbam_object.prototype.submitForm = function(){
    this.formChanged = -1;
    jQuery('#ajax-loading').show();
    var result = true;
    //check if user still reorganizing menu
    if (this.sorting && this.sorted){
        this.submiting = true;
        this.saveMenuOrder(false); //apply only for one role
        result = false; //wait until ordering saves
    }
    
    return result;
}

mvbam_object.prototype.restoreDefault = function(){
    var _this = this;
    var pa = {
        resizable: false,
        height:180,
        modal: true,
        buttons: {}
    }
    pa.buttons[wpaccessLocal.LABEL_135] = function() {
        var role = jQuery('#current_role').val();
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'restore_role',
            '_ajax_nonce': wpaccessLocal.nonce,
            'role' : role
        };
        var _dialog = this;
        jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
            if (data.status == 'success'){
                _this.getRoleOptionList(role);
            }else{
                //TODO - Implement error
                alert(wpaccessLocal.LABEL_136);
            }
            jQuery( _dialog ).dialog( "close" );
        },'json');
    }
    pa.buttons[wpaccessLocal.LABEL_77] = function() {
        jQuery( this ).dialog( "close" );
    }
    jQuery( "#dialog-confirm" ).dialog(pa);
}

mvbam_object.prototype.loadInfo = function(event, type, id){
    
    if (typeof(event.preventDefault) != 'undefined'){ //for IE
        event.preventDefault();
    }else{
        event.returnValue = false;
    }
    this.showAjaxLoader('.post-information', 'small');
    var _this = this;
    var params = {
        'action' : 'mvbam',
        'sub_action' : 'get_info',
        '_ajax_nonce': wpaccessLocal.nonce,
        'type' : type,
        'role' : jQuery('#current_role').val(),
        'user' : jQuery('#current_user').val(),
        'id' : id
    }
    
    jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
        _this.removeAjaxLoader('.post-information', 'small');
        if (data.status == 'success'){
            var pi = jQuery('.post-information');
            jQuery('#post-date', pi).html(data.html);
            
            //stop bothering user that detected form changes
            jQuery('input', pi).bind('change', function(){
                _this.formChanged--;
            });
             
            //configure information
            jQuery('#restrict_expire', pi).datepicker({
                'minDate' : new Date()
            });

            jQuery('.save-postinfo', pi).bind('click', function(event){
                event.preventDefault(); 
                //save information
                _this.saveInfo(_this, pi, type, id, 0);
            });
                
            jQuery('.save-postinfo-all', pi).bind('click', function(event){
                event.preventDefault();
                if (jQuery('#current_user').val() == 0){
                    if (_this.hideApplyAll == '1'){
                        _this.saveInfo(_this, pi, type, id, 1);
                    }else{
                        var pa = {
                            resizable: false,
                            height:204,
                            width: 320,
                            modal: true,
                            buttons: {}
                        }
                        pa.buttons[wpaccessLocal.LABEL_137] = function() {
                            _this.saveInfo(_this, pi, type, id, 1);
                            _this.hideApplyAll = (jQuery('#hide-apply-all').attr('checked') ? 1 : 0);
                            jQuery( this ).dialog( "close" );
                        }
                            
                        pa.buttons[wpaccessLocal.LABEL_77] = function() {
                            jQuery( this ).dialog( "close" );
                        }
                        //save information
                        jQuery( "#dialog-apply-all" ).dialog(pa); 
                    }
                }
            });
            
            
        }else{
            //TODO - Implement error
            alert(wpaccessLocal.LABEL_138);
        }
    },'json');
}

mvbam_object.prototype.saveInfo = function(obj, pi, type, id, apply){

    obj.showAjaxLoader('.post-information', 'small');

    var params = {
        'action' : 'mvbam',
        'sub_action' : 'save_info',
        '_ajax_nonce': wpaccessLocal.nonce,
        'role' : jQuery('#current_role').val(),
        'user' : jQuery('#current_user').val(),
        'info' : {
            'id' : id,
            'type' : type,
            'restrict' : jQuery('input[name="restrict_access"]', pi).attr('checked'),
            'restrict_front' : jQuery('input[name="restrict_front_access"]', pi).attr('checked'),
            'exclude_page' : jQuery('input[name="exclude_page"]', pi).attr('checked'),
            'restrict_expire' : jQuery('#restrict_expire', pi).val()
        },
        'apply' : apply,
        'apply_all_cb' : (jQuery('#hide-apply-all').attr('checked') ? 1 : 0)
    }
    jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
        obj.removeAjaxLoader('.post-information', 'small');
        if (data.status == 'success'){
            jQuery('#expire-success-message', pi).show().delay(5000).hide('slow');
        }else{
            jQuery('#expire-error-message', pi).show().delay(10000).hide('slow');
            if (data.message){
                jQuery( "#addon-proposal #addon-message" ).html(data.message);
                var pa = {
                    resizable: false,
                    height:190,
                    modal: true,
                    buttons: {}
                }
                pa.buttons[wpaccessLocal.LABEL_76] = function() {
                    jQuery( this ).dialog( "close" );
                }
                jQuery( "#addon-proposal" ).dialog(pa);
            }
        }
    }, 'json');
}

mvbam_object.prototype.handleError = function(err){
    
    jQuery('#error-message #error-text').html(err.toString());
    jQuery('#error-message').removeClass('message-passive');
}

mvbam_object.prototype.exportConf = function(){
    var url = wpaccessLocal.ajaxurl + '?action=mvbam&sub_action=export&_ajax_nonce=' + wpaccessLocal.nonce;
    jQuery("#exportIFrame").attr("src", url);
}

mvbam_object.prototype.applyConf = function(){
    var _this = this;
    var pa = {
        resizable: false,
        height:270,
        modal: true,
        buttons: {}
    }
    pa.buttons[wpaccessLocal.LABEL_76] = function() {
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'apply_all',
            '_ajax_nonce': wpaccessLocal.nonce
        };
        
        jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
            if (data.status == 'success'){
                _this.success_message(data.message);
            }else{
                _this.failed_message(data.message);
            }
        }, 'json');
        jQuery( this ).dialog( "close" );
    }
    pa.buttons[wpaccessLocal.LABEL_77] = function() {
        jQuery( this ).dialog( "close" );
    }
    jQuery( "#apply-config" ).dialog(pa);
}

mvbam_object.prototype.deleteCapability = function(cap, label){
    jQuery('#delete-capability-title').html(label);
    var _this = this;
    var pa = {
        resizable: false,
        height:180,
        modal: true,
        buttons: {}
    }
    pa.buttons[wpaccessLocal.LABEL_24] = function() {
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'delete_capability',
            '_ajax_nonce': wpaccessLocal.nonce,
            'cap' : cap
        };
        jQuery.post(wpaccessLocal.ajaxurl, params, function(data){ 
            if (data.status == 'success'){
                jQuery('#cap-' + cap).parent().parent().remove();
            }else{
                alert(data.message);
            }
        }, 'json');
        jQuery( this ).dialog( "close" );
    }
    pa.buttons[wpaccessLocal.LABEL_77] = function() {
        jQuery( this ).dialog( "close" );
    }
    jQuery( "#dialog-delete-capability" ).dialog(pa);
}

mvbam_object.prototype.check_first_time = function(){
    
    var _this = this;
    if (wpaccessLocal.first_time == 1){
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'create_super',
            '_ajax_nonce': wpaccessLocal.nonce,
            'answer' : 0
        };
        var pa = {
            resizable: false,
            height:460,
            width: 450,
            modal: true,
            buttons: {}
        }
        pa.buttons[wpaccessLocal.LABEL_76] = function() {
            params.answer = 1;
            var _dialog = this;
            _this.showAjaxLoader('#dialog-firsttime');
            jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
                _this.removeAjaxLoader('#dialog-firsttime');
                if (data.result == 'success'){
                    window.location.reload(true);
                }else{
                    //TODO - Implement error
                    alert(wpaccessLocal.LABEL_90);
                }
                jQuery( _dialog ).dialog( "close" );
            }, 'json');
                    
        }
               
        jQuery( "#dialog-firsttime" ).dialog(pa);
    }
}

mvbam_object.prototype.success_message = function(message){
    var pa = {
        resizable: false,
        height:180,
        width: 350,
        modal: true,
        buttons: {}
    }
    pa.buttons[wpaccessLocal.LABEL_76] = function() {
        jQuery( this ).dialog( "close" );
    }
    jQuery("#success-message .message-text").html(message);
    jQuery("#success-message").dialog(pa);
}

mvbam_object.prototype.failed_message = function(message){
    var pa = {
        resizable: false,
        height:180,
        width: 350,
        modal: true,
        buttons: {}
    }
    pa.buttons[wpaccessLocal.LABEL_76] = function() {
        jQuery( this ).dialog( "close" );
    }
    jQuery("#failed-message .message-text").html(message);
    jQuery("#failed-message").dialog(pa);
}

mvbam_object.prototype.updateRoleName = function(role_id, row_id, or_label, obj){
    
    var _this = this;
    var e_label = jQuery(obj).val();
    if (e_label != or_label){
        this.showAjaxLoader('.delete-role-table', 'small');
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'update_role_name',
            '_ajax_nonce': wpaccessLocal.nonce,
            'role_id' : role_id,
            'label' : e_label
        };
        jQuery.post(wpaccessLocal.ajaxurl, params, function(data){
            _this.removeAjaxLoader('.delete-role-table', 'small');
            if (data.status == 'success'){
                _this.UpdateRoleNameOk(e_label, row_id, role_id);
            }else{
                _this.UpdateRoleNameError(or_label, row_id, role_id);
            }
        }, 'json');
    }else{
        _this.UpdateRoleNameOk(e_label, row_id, role_id);
    }
}

mvbam_object.prototype.UpdateRoleNameOk = function(e_label, row_id, role_id){
    
    jQuery('#edit-role-name').replaceWith('<span class="role-name">'+e_label+'</span>');
    this.initRoleNameRow(jQuery('#dl-row-' + role_id));
    jQuery('#' + row_id).effect('highlight',{
        color: '#72f864'
    }, 3000);
    if (jQuery('#current_role').val() == role_id){
        jQuery('#current-role-display').html(e_label);
    }
    jQuery('#role option[value="'+role_id+'"]').text(e_label);
}

mvbam_object.prototype.UpdateRoleNameError = function(or_label, row_id, role_id){
    
    jQuery('#edit-role-name').replaceWith('<span class="role-name">'+or_label+'</span>');
    _this.initRoleNameRow(jQuery('#dl-row-' + role_id));
    jQuery('#' + row_id).effect('highlight',{
        color: '#f6a499'
    }, 3000);
}

mvbam_object.prototype.initRoleNameList = function(){
    var _this = this;
    jQuery('.delete-role-table tbody > tr').each(function(){
        _this.initRoleNameRow(this);
    });
}

mvbam_object.prototype.initRoleNameRow = function(obj){
    
    var _this = this;
    var row_id = jQuery(obj).attr('id');
    var role_id = row_id.match(/\-([0-9a-z_]+)$/i);
    jQuery('.role-name', obj).bind('click', function(){
        var label = jQuery(this).text();
        jQuery(this).replaceWith('<input type="text" value="'+label+'" id="edit-role-name" />');
        jQuery('#edit-role-name').bind('blur', function(){
            _this.updateRoleName(role_id[1], row_id, label, this);
        });
        jQuery('#edit-role-name').keypress(function(event){
            if (event.which == 13){
                event.preventDefault();
                _this.updateRoleName(role_id[1], row_id, label, this);
            };
        });
        jQuery('#edit-role-name').focus();
    });
}


//**********************************************



jQuery(document).ready(function(){
    
    try{
        
        mObj = new mvbam_object();

        jQuery('.change-role').bind('click', function(e){
            e.preventDefault();
            jQuery('div #role-select').show();
            jQuery(this).hide();
        });
        
        jQuery('.change-user').bind('click', function(e){
            e.preventDefault();
            jQuery('div #user-select').show();
            jQuery(this).hide();
        });
           
        jQuery('#role-ok').bind('click', function(e){
            e.preventDefault();
            if (mObj.formChanged > 0){
                var pa = {
                    resizable: false,
                    height: 180,
                    modal: true,
                    buttons: {}
                }
                pa.buttons[wpaccessLocal.LABEL_143] = function() {
                    jQuery( this ).dialog( "close" );
                    mObj.changeRole();
                }
                pa.buttons[wpaccessLocal.LABEL_77] = function() {
                    jQuery( this ).dialog( "close" );
                }
                jQuery( "#leave-confirm" ).dialog(pa); 
            }else{
                mObj.changeRole();
            }
        });
        
        jQuery('#user-ok').bind('click', function(e){
            e.preventDefault();
            if (mObj.formChanged > 0){
                var pa = {
                    resizable: false,
                    height: 180,
                    modal: true,
                    buttons: {}
                }
                pa.buttons[wpaccessLocal.LABEL_143] = function() {
                    jQuery( this ).dialog( "close" );
                    mObj.changeUser();
                }
                pa.buttons[wpaccessLocal.LABEL_77] = function() {
                    jQuery( this ).dialog( "close" );
                }
                jQuery( "#leave-confirm" ).dialog(pa); 
            }else{
                mObj.changeUser();
            }
        });
    
        jQuery('.new-role-name-empty').click(function(e){
            e.preventDefault();
            jQuery('#new-role-name').focus();
        });
        jQuery('#new-role-name').focus(function(){
            jQuery('.new-role-name-empty').hide();
        });
        jQuery('#new-role-name').blur(function(){
            if (!jQuery.trim(jQuery(this).val())){
                jQuery('.new-role-name-empty').show();
            }
        });
        jQuery('#new-role-name').keypress(function(event){
            if (event.which == 13){
                event.preventDefault();
                mObj.addNewRole();
            };
        });
    
        jQuery('#wp-access').keypress(function(event){
            if (event.which == 13){
                event.preventDefault();
            };
        });
   
        jQuery('#new-role-ok').bind('click', function(e){
            e.preventDefault();
            mObj.addNewRole();
        });
    
        jQuery('#role-cancel').bind('click', function(e){
            e.preventDefault();
            jQuery('div #role-select').hide();
            jQuery('.change-role').show();
        });
        jQuery('#user-cancel').bind('click', function(e){
            e.preventDefault();
            jQuery('div #user-select').hide();
            jQuery('.change-user').show();
        });
        jQuery('#new-role-cancel').bind('click', function(e){
            e.preventDefault();
            jQuery('div #new-role-form').hide();
            jQuery('.change-role').show();
        });
    
        jQuery('#role-tabs').tabs();
    
        jQuery('.restore-conf').bind('click', function(e){
            e.preventDefault();
            mObj.restoreDefault();
        });

        jQuery('#wp-access').bind('change', function(e){
            mObj.formChanged++; 
        });
        jQuery('#role, #user').bind('change', function(e){
            mObj.formChanged -= 1;
        });

        jQuery('.message-active').show().delay(5000).hide('slow');
    
        mObj.configureElements();
        
        jQuery('.export-conf').bind('click', function(){
            mObj.exportConf(); 
        });
        
        jQuery('.apply-conf').bind('click', function(){
            mObj.applyConf(); 
        });  
        
        jQuery('#wp-access').show();
        
        mObj.initRoleNameList();
        
        mObj.check_first_time();
        
    }catch(err){
        mObj.handleError(err);
        jQuery('#wp-access').show();
    }
});

function loadInfo(event, type, id){
    mObj.loadInfo(event, type, id);
}