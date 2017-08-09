var url = document.querySelector('[href*="themes"]').getAttribute('href'),
    websiteUrl = url.substring(url.indexOf('themes', 10), 0),
    themeIndex = url.indexOf('themes') + 7,
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