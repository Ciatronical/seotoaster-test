var $widgetsList;

$(function(){
    $widgetsList = $('#list-of-widgets');

    getNetContentList();


    $(document).on('click', '.widget-list-item', function(){
        tinymce.activeEditor.execCommand('mceInsertContent', false, '{$plugin:netcontent:'+$(this).data('netcontentName')+'}');
        $('.netcontent-list-link').click();
    }).on('click', '.close-hint', function(){
        $('#netcontent-hint-block').empty().hide();
    });

});



function getNetContentList() {
    $.ajax({
        type: 'GET',
	url: $('#website_url').val() + 'plugin/netcontent/run/widgetlist/',
	dataType: 'json',
	success: function (response) {
            if(!response.error) {
                $widgetsList.empty();
                getSync();
                $('<div>').attr('id', 'netcontent-hint-block').appendTo($widgetsList);
                var netContentList = $('<ul>').addClass('netcontent-widget-list').prependTo($widgetsList);
                $.each(response.responseText, function(){
                    var widget  = this;
                    var netItem = $('<li>').html('<span class="widget-help ticon-question-sign info icon16"></span><span class="widget-name">' + widget.widgetName + '</span>').appendTo(netContentList);
                    var p2pState = (widget.p2p == true) ? ':p2p' :'';
                    $(netItem).data('netcontentName', widget.widgetName + p2pState);
                    (widget.publish == true) ? $(netItem).addClass('widget-list-item') : $(netItem).addClass('widget-list-item widget-list-item-empty') ;
                    var netContentHint = $('#netcontent-hint-block');
                    $(netItem).find('.widget-help').mouseover(function(){
                        netContentHint.html('<h3>' + widget.widgetName + '</h3>' + widget.content).stop(true, true).show();
                    }).mouseout(function() {
                        netContentHint.empty();
                        netContentHint.stop(true, true).hide();
                    });
                });
            }
            else {
                $widgetsList.empty();
                $widgetsList.css({'fontWeight': 'bold'}).text(response.responseText);
                if(typeof response.responseText.notConected == 'undefined' || !response.responseText.notConected) {
                    getSync();
                }
                else {
                    $widgetsList.empty().append($('<img class="connectImage" src="' + $('#website_url').val() + 'plugins/netcontent/web/images/sambaConnect.jpg">'));
                    $('img.connectImage').wrap($('<a>').attr({'href': 'javascript:;', 'class': 'tpopup', 'data-url': $('#website_url').val() + 'plugin/widcard/run/getWebsiteIdCard', 'data-pwidth': '480', 'data-pheight':'560'}));
                }
            }
	},
        error: function (response){
            $widgetsList.html(response.responseText);
        }
    });
}

function getSync() {
    $('<button>').attr({'id': 'widgetSync', 'class': 'btn block small success'}).text('CHECK FOR UPDATES').appendTo($widgetsList);
    $('#widgetSync').click(function() {
        $widgetsList.text('Loading...');
        $.getJSON($('#website_url').val() + 'plugin/netcontent/run/syncNetContent/', function(response) {
            if(response.error == true) {
                $widgetsList.html($('<img class="connectImage" src="' + $('#website_url').val() + 'plugins/netcontent/web/images/sambaConnect.jpg">'));
                $('img.connectImage').wrap($('<a>').attr({'href':'http://mojo.seosamba.com', 'target':'_blank'}));
            }
            else {
                getNetContentList();
            }
        });
    });
}