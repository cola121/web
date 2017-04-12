// JavaScript Document

(function ($) {

    $(window).load(function () {
        $("#status").fadeOut(); // will first fade out the loading animation
        $("#preloader").delay(400).fadeOut("medium"); // will fade out the white DIV that covers the website.
    })

    $(document).ready(function() {

        //Remove 300ms lag set by -webkit-browsers
        window.addEventListener('load', function () {
            FastClick.attach(document.body);
        }, false);
    })
})
