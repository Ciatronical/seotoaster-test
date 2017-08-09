var menuAnimation = true,
    checkboxRadio = true;
//    adaptiveBackground = false,
//    adaptiveBackgroundParent = '.page-teaser-image, .gallery_image';

//    $('.panel.collapse').find('.panel-header').prepend('<i class="icon-plus collapse-btn"></i>');
$(document).on('click', '.collapse-btn', function(){
    $btn = $(this);
    $(this).closest('.panel-header').next('.panel-content').slideToggle(function(){
        $btn.toggleClass('icon-minus icon-plus')
    });
})

if(document.getElementsByClassName('menu-btn').length){
    var menuOverlay = document.createElement("div");
    menuOverlay.className = "mobile-overlay menu-overlay";
    document.body.appendChild(menuOverlay);
}
if(document.getElementsByClassName('dropdown-btn').length){
    var dropdownOverlay = document.createElement("div");
    dropdownOverlay.className = "mobile-overlay dropdown-overlay";
    document.body.appendChild(dropdownOverlay);
}

$('.menu-btn')
    .each(function(){
        var menu = $(this).data('menu'),
            position = $(this).data('menu-position');
        $(menu).addClass('mobile-menu '+position);
    })
    .on('tapone', function(){
        var menu = $(this).data('menu');
        if($(this).hasClass('active')){
            $('.menu-overlay').removeClass('open');
        }else{
            $('.menu-overlay').addClass('open');
            $('.menu-btn').removeClass('active');
            $('.mobile-menu').removeClass('open');
        }
        $(this).toggleClass('active');
        $(menu).toggleClass('open');
    });

$('.dropdown-btn')
    .each(function(){
        var menu = $(this).data('menu'),
            position = $(this).data('menu-position');
            height = $(this).data('menu-height');
        $(menu).addClass('dropdown-menu '+position).height(height);
    })
    .on('tapone', function(){
        var menu = $(this).data('menu');
        if($(this).hasClass('active')){
            $('.dropdown-overlay').removeClass('open');
        }else{
            $('.dropdown-overlay').addClass('open');
            $('.dropdown-btn').removeClass('active');
            $('.dropdown-menu').removeClass('open');
        }
        $(this).toggleClass('active');
        $(menu).toggleClass('open');
    });

$('.sub-menu-btn')
    .on('tapone', function(){
        $(this).toggleClass('active').nextAll('ul').toggleClass('open');
    });

$('.mobile-overlay')
    .on('tapone', function(){
        $('.menu-btn, .dropdown-btn').removeClass('active');
        $('.mobile-menu, .dropdown-menu, .mobile-overlay').removeClass('open');
    });

(function(){
    _tabs();
    _dialog();
    _tooltip();
    _accordion();
    _datepicker();
    _checkboxRadio();

    if(menuAnimation && !device.type){
        _onoffAnimateMenu();
        window.onresize = _onoffAnimateMenu;
    }

    $(function(){
        $('body').on('DOMNodeInserted DOMNodeRemoved', function(event) {
            if (event.type == 'DOMNodeInserted') {
                _checkboxRadio()
            } else {
            }
        });
    })
})();

$(document).ajaxStop(function(){
    _checkboxRadio();
});