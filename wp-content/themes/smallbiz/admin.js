// Remove this file. Use admin.min.js for distribution.
// http://www.refresh-sf.com/yui/
// http://javascriptobfuscator.com/default.aspx
// http://www.atasoyweb.net/Javascript_Encrypter/javascript_encrypter_eng.php

if(typeof e2w == "undefined"){   e2w = {};         }
if(!e2w.admin){                  e2w.admin = {};   }
e2w.template_url = "";

(function($) { 
 e2w.admin = {
     authCallback:function(resp){
         var respDiv = $("#authDialogContainer").find(".response");
         respDiv.html(resp.message+"&nbsp");
         respDiv.attr("class", resp.messageClass);
         if(resp.authkey){
             var form = $("#smallbiz_options_form");
             if(!form.find("input[name='authkey_add']").length){
                 form.append("<input type='hidden' name='authkey_add' />");
                 form.append("<input type='hidden' name='authemail_add' />");
                 form.append("<input type='hidden' name='authname_add' />");
             }
             form.find("input[name='authkey_add']").val(resp.authkey);
             form.find("input[name='authemail_add']").val(resp.email);
             form.find("input[name='authname_add']").val(resp.name);
             form.submit();
         }
         
     },
     showDemoAuthDialog: function(){
         var h = $(window).height();
         var w = $(window).width();
         var overlay = $("#authDialogOverlay");
         var dialog = $("#authDialogContainer");
         var resizeOverlay = function(){
             var overlay = $("#authDialogOverlay");
             overlay.css("width", $(window).width());
             overlay.css("height", $(window).height());         
             var dialog = $("#authDialogContainer");
             dialog.css("width", $(window).width());
             dialog.css("height", $(window).height());  
             var dialogBox = $("#authDialogBox");
             dialogBox.css("marginTop", (($(window).height() - (1.2* dialogBox.height())) / 2) );
         }
         if(overlay.length == 0){
             overlay = $("<div id='authDialogOverlay' />");
             if(dialog.length == 0){
                 dialog = $("<div id='authDialogContainer' />");                 
                 var html = [
                     '<div class="close">X</div>',
                     '<h2>SmallBiz Theme Login</h2>',
                     '<p>Enter your login information to unlock the theme:</p>',
                   
                     '<p>email:<br /><input id="themeLoginEmail" name="payer_email" type="text" />',
                     '<p>password:<br /><input id="themeLoginPassword" name="password" type="password" />',
                     '<div class="response"></div>',
                     '<div><input id="themeLoginSubmit" type="submit" value="Login" /></div>',
                      '<p>&nbsp;</p>',
                     '<p><strong>Need Logins?</strong></p>',
                     '<p>Purchase information should have opened in a new Window. Check your Pop-up blocker settings and notification.</p>',
                     '<p><strong>Need Help?</strong></p>',
                     '<p>Email us at support@expand2web.com</p>'
                 ];
                 dialog.append($("<div id='authDialogBox' />").html(html.join(" ")));
                 // anon function so we can bind it to enter as well if we want later
                 var submit = function(){
                    $.ajax({
                            url: "http://www.smallbiztheme.com/checkout/unlock.php",
                            data: {email: $("#themeLoginEmail").val(), pass:$("#themeLoginPassword").val()},
                            dataType: 'jsonp',
                            jsonp: 'callback',
                            jsonpCallback: 'e2w.admin.authCallback',
                            success: function(){}
                    });                    
                 }
                 dialog.find("#themeLoginSubmit").click(submit);
                 dialog.find(".close").click(function(){
                         jQuery("#authDialogContainer,#authDialogOverlay").hide();
                 });
             }
             $(document.body).append(overlay);
             $(document.body).append(dialog);
             overlay.css("opacity","0.9");
             $(window).resize(resizeOverlay);
         }
         overlay.show();
         dialog.show();
         resizeOverlay();
     },
     toggleDropdown: function(elementId){
        if(document.getElementById(elementId).style.display == 'none'){ document.getElementById(elementId).style.display = 'block'; }else{ document.getElementById(elementId).style.display = 'none'; }
        if(this.isDemo()){
            setTimeout(function(){ e2w.admin.drawBlock(elementId); }, 200); // for chrome    
        }
     },
     drawBlock: function(elementId){
         // could have been avoided with a set naming scheme:
        if(elementId != "mobileoptions" && elementId != "fboptions"){
            elementId = "outerbox-" + elementId;
        }
        var outerbox = $("#"+String(elementId).toLowerCase());
        var top_extra_margin = 0;
        if(elementId == "outerbox-headeroptions"){
            outerbox = $("#header_banner_table");
            outerbox.css("maxWidth", 1000); // for just two on top row
        } else if (elementId == "mobileoptions"){
            outerbox.find("input").attr("disabled","true").click(function(){return false;});
            outerbox.find("textarea").attr("disabled","true").click(function(){return false;});
            // hackishly disable tinymce
            noTinyMCE = function(){
                jQuery("#main_text-mobile-home-text").show();
                jQuery("#main_text-mobile-home-text_parent").hide();
                jQuery("#editor-toolbar-mobile-home-text").hide();
            };
            noTinyMCE();
            setTimeout(noTinyMCE, 1000);            
        }
        outerbox.css("position","relative");
        outerbox.find(".buy-blocker").remove();
        var blocker_bg = $("<div class='buy-blocker buy-blocker-bg' />").css("visibility","hidden");
        var blocker_fg = $("<a class='buy-blocker buy-blocker-fg' target='_blank' href='http://www.smallbiztheme.com/checkout/' />").css("visibility","hidden");
        blocker_fg.click(function(){
                e2w.admin.showDemoAuthDialog();
        });
        outerbox.append(blocker_bg, blocker_fg); 
        var blockers = outerbox.find(".buy-blocker");
        var margin = [150, 260];
        if(elementId == "outerbox-headeroptions"){
            margin[0] = 130;
            margin[1] = 200;
            top_extra_margin = 0;
        }
        if(outerbox.height() < margin[1]){
            margin[1] = 10;
        }
        blockers.css("left",parseInt(margin[0] / 2)+"px");  
        blockers.css("top",parseInt(top_extra_margin+(margin[1] / 2))+"px");  
    
        blockers.css("width",outerbox.width() - margin[0]);  
        blockers.css("height",outerbox.height() - margin[1]);  
        blocker_bg.css("opacity","0.5");  
        blocker_fg.css("line-height", blocker_fg.height()+"px");
        blocker_fg.html("Buy Now");
        
        blocker_fg.addClass("blockerfg-"+elementId);
        blocker_bg.addClass("blockerbg-"+elementId);
        
        blocker_fg.css("visibility","visible")    
        blocker_bg.css("visibility","visible")    
     
     },
     change_active_selector: function (newValue, checkName, selectorType, optSuffix){
        //"layout","themeLayout",".css"   
        if(typeof(newValue) == "undefined"){ newValue == -1; }
        var radios = jQuery("input[name="+checkName+"]");
        if(newValue == -1){ 
            // If we didn't pass a value, just change things so the one matching the checkbox is selected:
            newValue=radios[0].value;
            radios.each(function(idx){
                    if(radios[idx].checked){
                        newValue=radios[idx].value;
                    }
            });
        };
        // Don't allow a change in demo mode, or restrict a change to only the top two:
        if(this.isDemo()){
            if(selectorType == "themeLayout"){
                newValue = radios[0].value;
            } else if (selectorType == "themeHeader"){
                if(newValue != radios[0].value && newValue != radios[1].value){
                    // restrict to the first two values.
                    newValue = radios[0].value;
                }
            }
        }
        if(newValue != -1 ) {
            // Alter the checkboxes.
            radios.each(function(idx){
                    if(radios[idx].value == newValue){
                        radios[idx].checked = true;
                    }
            });
        }
        // update default colors:
        var update_default_colors = function(newValue){
            var header = newValue.substring(0, newValue.indexOf("."));
            url = e2w.template_url + '/default-colors.php?header='+header;
            jQuery.ajax({
              url: url,
              success: function(result){
                jQuery("input.color").each(function(i, v){
                    var input = jQuery(v);
                    var color = result[input.attr("name")];
                    if(color){
                        input.val(color);
                        v.color.fromString(color);
                    } 
                });
              }
            });
        };
        if(selectorType == "themeHeader"){
            update_default_colors(newValue);
        }
        
        var divs = jQuery("div."+selectorType+"Selector_option");
        divs.each(function(idx){
          if(newValue == (String(divs[idx].id).replace(selectorType+'Selector_', '')+optSuffix)){
             jQuery(this).addClass(selectorType+"Selector_optionSelected");
          } else {
             jQuery(this).removeClass(selectorType+"Selector_optionSelected");
          }
        });
        if(selectorType == "themeHeader"){
            e2w.admin.check_enable_upload_banner();
        } else {
            jQuery("#"+selectorType+"Selector_message").show();
            jQuery(".smallbizClickToSave").each(function(){
                jQuery(this).unbind('click');
                jQuery(this).click(function(){
                        jQuery("#smallbiz_options_form").submit();
                });
            });        
        }
     },
     check_enable_upload_banner:function(){
        var new_banner_radio = document.getElementById('banner_');
        if(new_banner_radio){
            var new_banner       = document.getElementById('new_banner');
            var new_banner_label = document.getElementById('new_banner_label');
            if(new_banner_radio.checked){
                new_banner.disabled = false;
                new_banner_label.style.color ="";
            } else {
                new_banner_label.style.color ="#919191";
                new_banner.disabled = "disabled";
            }
            e2w.admin.display_new_banner_message();
        }
     },
     display_new_banner_message: function(){
        var new_banner_message = document.getElementById('new_banner_message');
        var new_banner_radio = document.getElementById('banner_');
        var new_banner_input = document.getElementById('new_banner');
        if(new_banner_message){
            if(new_banner_input.value != "" && new_banner_radio.checked){
                new_banner_message.innerHTML = "Please click the Save Changes button at the bottom of this page to activate your new header image.";
            } else {
                new_banner_message.innerHTML = "";
            }
        }
     },
     isDemo: function(){
         if(e2w.auth && e2w.auth.key){
             if(e2w.auth.key.substring(0,32) == hex_md5((String(location.href).substring(0,String(location.href).substring(0,String(location.href).lastIndexOf("themes.php") -1).lastIndexOf("/"))) + e2w.auth.key.substring(32))){
                     return false;
             }
         }
         return true;
     }     
 };

//    console.log(   blocker);

})(jQuery)

// Hide options we disable and set the changed homepage value for us to import:
// (The hiding is also done via CSS, but this is for browsers that do not support the > selector)
jQuery(document).ready(function($){
    if(e2w.admin.isDemo()){
        $("#alreadyPurchasedTop").show();
        $("#alreadyPurchasedTop a").click(function(){
                e2w.admin.showDemoAuthDialog();
        });
    }
    $("#wpbody-content div.wrap>div").each(function(i, v){
      var div = jQuery(v);
      if(String(div.attr("id")).indexOf("dropdowns") == 0){
           var arr = $(this).attr("id").split("-");
           div.click(function(){
                   var id = $(this).attr("id");
                   var arr = id.split("-");
                   var content = $("#"+arr[1]+"options");
                   if(content){
                       if(content.is(":visible")){
                           $.cookie("smallbiz_dropdown_show_"+arr[1], true);
                       } else {
                           $.cookie("smallbiz_dropdown_show_"+arr[1], null);
                       }
                   }
           });
           if($.cookie("smallbiz_dropdown_show_"+arr[1])){
               e2w.admin.toggleDropdown(arr[1]+"options");
           }
       }
    }); 
});

