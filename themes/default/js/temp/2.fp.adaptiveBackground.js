var adaptiveBackgroundParent;// // = '.page-teaser-image, .gallery_image'

if($(adaptiveBackgroundParent).length){
    var adaptiveBG = document.createElement("script");
    adaptiveBG.onload = callAdaptiveBackground;
    adaptiveBG.src = websiteUrl+"themes/"+themeName+"/js/plugin/jquery.adaptive-background.min.js";
    document.body.appendChild(adaptiveBG);
}

// Adaptive background => github.com/briangonzalez/jquery.adaptive-backgrounds.js
function callAdaptiveBackground(){
    $.adaptiveBackground.run({
        parent : adaptiveBackgroundParent
    });
}