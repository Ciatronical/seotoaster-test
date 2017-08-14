var menuAnimation = true,
    checkboxRadio = true;
//    adaptiveBackground = false,
//    adaptiveBackgroundParent = '.page-teaser-image, .gallery_image';

var url = document.querySelector('[href*="themes"]').getAttribute('href'),
    websiteUrl = url.substring(url.indexOf('themes/', 10), 0),
    themeIndex = url.indexOf('themes/') + 7,
    themeName = url.substring(themeIndex, url.indexOf('/', themeIndex));

if(!jQuery().carousel){
    var carousel = document.createElement("script");
    carousel.src = websiteUrl+"themes/"+themeName+"/js/plugin/jquery.carousel.min.js";
    document.body.appendChild(carousel);
}

//if(adaptiveBackground && $(adaptiveBackgroundParent).length){
//    var adaptiveBG = document.createElement("script");
//    adaptiveBG.onload = callAdaptiveBackground;
//    adaptiveBG.src = websiteUrl+"themes/"+themeName+"/js/plugin/jquery.adaptive-background.min.js";
//    document.body.appendChild(adaptiveBG);
//}

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
    .on('click tapone', function(){
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
    .on('click tapone', function(){
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
    .on('click tapone', function(){
        $(this).toggleClass('active').nextAll('ul').toggleClass('open');
    });

$('.mobile-overlay').on('click tapone', function(){
    $('.menu-btn, .dropdown-btn').removeClass('active');
    $('.mobile-menu, .dropdown-menu, .mobile-overlay').removeClass('open');
});