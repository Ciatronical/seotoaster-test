$(function() {
    var posterLink = $('a.social-poster-post'),
        posterUrl  = posterLink.data('url'),
        exploded   = top.location.href.split('/');
    if(!exploded.length || exploded[exploded.length-1] == '') {
        return false;
    }
    posterLink.data({url: posterUrl + 'url/' + exploded[exploded.length-1]});
});