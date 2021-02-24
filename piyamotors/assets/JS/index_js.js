$(document).ready(function() {
    $('.text-div').css('display', 'none');
    $('.img-div').animate({
        'margin-left': '50vw',
        'opacity': '0'
    }, 10, function() {
        $('.img-div').animate({
            'margin-left': '0vw',
            'opacity': '1'
        }, 600);
    });
    $('.text-div').animate({
        'margin-left': '-150vw',
        'opacity': '0'
    }, 10, function() {
        $('.text-div').css('display', 'block');
        $('.text-div').animate({
            'margin-left': '0vw',
            'opacity': '1'
        }, 600);
    });
});


var d = new Date();
var yr = d.getFullYear();
document.getElementById('year').innerHTML = yr;