if(!jQuery().chosen && $(".chosen-select").length){
    var script = document.createElement("script");
    script.onload = function(){
        callChosen();
    };
    script.type = "text/javascript";
    script.src = "../themes/"+themeName+"/js/plugin/jquery.chosen.min.js";
    document.body.appendChild(script);
}else if(jQuery().chosen && $(".chosen-select").length){
    callChosen();
}

// Call Chosen plugin DEMO => harvesthq.github.io/chosen/
function callChosen(){
    $(".chosen-select").chosen({
        no_results_text : "Oops, nothing found!",
        width           : "100%"
    });
    $(".chosen-select-deselect").chosen({
        allow_single_deselect : true
    });
}

// call carousel plugin
$(".accordion").accordion({
    heightStyle : "content",
    icons       : {
        "header"       : "icon-arrow-right2",
        "activeHeader" : "icon-arrow-down2"
    } // or false
}).find('.ui-accordion-header a').click(function(){
        window.location = $(this).attr('href');
        return false;
    });

// call datepicker plugin
if(!device.type){
    $(".datepicker").datepicker({ dateFormat : "dd/mm/yy" });
}else{
    $(".datepicker").prop('type', 'date');
}

// call tabs plugin
$(".tabs").tabs();

// TODO добавить все опции
// Dialog plugin
$(".dialog").each(function(){
    var el = $(this).data('element');
    var effect = $(this).data('effect') ? $(this).data('effect') : 'fade';
    var duration = $(this).data('duration') ? $(this).data('duration') : 500;
    var width = $(this).data('width') ? $(this).data('width') : 600;
    var height = $(this).data('height') ? $(this).data('height') : 'auto';
    $(el).dialog({
        autoOpen  : false,
        show      : {
            effect   : effect,
            duration : duration
        },
        hide      : {
            effect   : effect,
            duration : duration
        },
        modal     : true,
        width     : width,
        height    : height,
        maxHeight : ($(window).height()-$(window).height()*0.1),
        resizable : false,
        draggable : true
    });
    $(el+'.settings').dialog('option', 'buttons', [
//        {
//            text    : "Cancel",
//            'class' : 'inverse-red small mr-grid',
//            click   : function(){
//                $(this).dialog("close");
//            }
//        },
        {
            text    : "Save",
            'class' : 'green small',
            click   : function(){
                location.reload();
            }
        }
    ]);
});
var $container = $(".ui-dialog-content");
$(document).on('click', ".dialog", function(){
    $container.dialog("close");
    var el = $(this).data('element');
    $(el).dialog("open");
});

// call tooltip plugin
var $tooltip = $(".tooltip[title]");
$tooltip.tooltip({
    track : true
});
$tooltip.find('a').removeAttr('title');
$tooltip.each(function(){
    var tooltipClass = $(this).data('class');
    if($(this).hasClass('right')){
        showTooltip($(this), tooltipClass, 'right');
    }
    if($(this).hasClass('left')){
        showTooltip($(this), tooltipClass, 'left');
    }
    if($(this).hasClass('top')){
        showTooltip($(this), tooltipClass, 'top');
    }
    if($(this).hasClass('bottom')){
        showTooltip($(this), tooltipClass, 'bottom');
    }
});
function showTooltip(el, addClass, position){
    var my = '', at = '', arrowWidth = 10;
    switch(position){
        case 'right' :
            my = "left+"+arrowWidth+" center";
            at = "right center";
            break;

        case 'left' :
            my = "right-"+arrowWidth+" center";
            at = "left center";
            break;

        case 'top' :
            my = "center bottom-"+arrowWidth+"";
            at = "center top";
            break;

        case 'bottom' :
            my = "center top+"+arrowWidth+"";
            at = "center bottom";
            break;
    }

    el.tooltip("option", {
        track        : false,
        tooltipClass : addClass,
        position     : {
            my    : my,
            at    : at,
            using : function(position, feedback){
                $(this).css(position);
                $("<span>").addClass("arrow").addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
            }
        }
    });
}

// call smoke.js plugin
/*$('[data-smoke]').on('click', function(){
    var type = $(this).data('smoke');
    var msg = $(this).data('message');
    var classname = $(this).data('classname');
    var duration = $(this).data('duration') ? $(this).data('duration') : 3000;
    switch(type){
        case 'alert':
            smoke.alert(msg, function(e){
            }, {
                classname : classname
            });
            break;
        case 'confirm':
            smoke.confirm(msg, function(e){
                //if(e){ }else{ }
            }, {
                ok             : "Yes",
                cancel         : "No",
                classname      : classname,
                reverseButtons : true
            });
            break;
        case 'prompt':
            smoke.prompt(msg, function(e){
                //if(e){ }else{ }
            }, {
                ok             : "Yes",
                cancel         : "No",
                classname      : classname,
                reverseButtons : true,
                value          : ""
            });
            break;
    }
});*/

// checkbox & radio button
(function(){
    if(!$('.ie8').length && checkboxRadio){
        $('input:checkbox, input:radio').not('.processed, .icon, .hidden').each(function(){
            var id = $(this).prop('id'), labelClass;
            if(!id.length){
                id = 'chr-'+Math.floor((Math.random()*100000)+1);
                $(this).prop('id', id);
            }
            if($(this).prop('class') || $(this).prop('class')){
                labelClass = $(this).prop('class');
                labelClass += ' ' + $(this).data('class');
            }
            if($(this).is(':radio')){
                $(this).addClass('radio-upgrade filed-upgrade');
            }else{
                $(this).addClass('checkbox-upgrade filed-upgrade');
            }
            if(!$(this).closest('.btn-set').length){
                var $parent = $(this).parent('label');
                if($parent.length){
                    $parent.prop({
                        'for'   : id
                    });
                    !$(this).hasClass('switcher') ? $(this).after('<span class="checkbox_radio '+ labelClass +'"></span>') : $(this).after('<span class="checkbox_radio '+ labelClass +'"><span></span></span>');
                }else{
                    !$(this).hasClass('switcher') ? $(this).wrap('<label for="'+id+'" class="checkbox_radio-wrap pointer '+ labelClass +'"></label>').after('<span class="checkbox_radio"></span>') : $(this).wrap('<label for="'+id+'" class="checkbox_radio-wrap pointer '+ labelClass +'"></label>').after('<span class="checkbox_radio"><span></span></span>');
                }
            }
            $(this).addClass('processed');
        });
    }
})();

// Adaptive background => github.com/briangonzalez/jquery.adaptive-backgrounds.js
function callAdaptiveBackground(){
    $.adaptiveBackground.run({
        parent : adaptiveBackgroundParent
    });
}

// Animation Main Menu
if(menuAnimation && !device.type){
    onoffAnimateMenu();
    window.onresize = onoffAnimateMenu;
}
function onoffAnimateMenu(e){
    if($(window).width()>=768){
        $('.main_menu').on('mouseenter mouseleave', '.category', animateMenu);
    }else{
        $('.main_menu').off('mouseenter mouseleave', '.category', animateMenu);
        $('.main_menu ul').attr('style', '');
    }
}
function animateMenu(e){
    if(e.type=="mouseenter"){
        $(this).find('ul').slideDown('fast');
    }else{
        $(this).find('ul').stop(true, false).slideUp();
    }
}

// Show button "Top" at the scrolling of documents
var $win = $(window), topBox = $('<div id="go-top"></div>').css({
    position  : 'fixed',
    bottom    : '20px',
    right     : '20px',
    'z-index' : '555',
    'cursor'  : 'pointer',
    'display' : 'none'
}).addClass('btn icon icon18 icon-arrow-up7 t-hide m-hide');
$('body').append(topBox);
$win.scroll(function(e){
    $win.scrollTop()<=300 ? $('#go-top').hide('slow') : $('#go-top').show('slow');
});
$('#go-top').click(function(e){
    $("html, body").animate({ scrollTop : 0 }, 1000);
});