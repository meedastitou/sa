$(function(){
    'use strict';

    // Switch Between Login & Signup

    $('.login-page h1 span').click(function (){
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).show(100);

    });
    
    // Hide Placeholder On Form Focus
    $('[placeholder]').focus(function(){
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function(){
        $(this).attr('placeholder', $(this).attr('data-text'));
    });


    
    // Add Asterisk On Required Field

    $('input').each(function (){
        if ($(this).attr('required') === 'required'){
            $(this).after('<span class="asterisk">*</span>');
        }
    });
 


    // Confrimation Massage on Button

    $('.confirm').click(function(){
        return confirm('Are You Sure?');
    });

    $('.live').keyup(function (){

        $($(this).data('class')).text($(this).val());
    });


});
// Start item page

const show_img = document.getElementById("show_img_item");
const small_img = document.getElementsByClassName("small_img_item");

small_img[0].onclick = function(){
    show_img.src  = small_img[0].src;
}
small_img[1].onclick = function(){
    show_img.src  = small_img[1].src;
}
small_img[2].onclick = function(){
    show_img.src  = small_img[2].src;
}
small_img[3].onclick = function(){
    show_img.src  = small_img[3].src;
}
small_img[4].onclick = function(){
    show_img.src  = small_img[4].src;
}
small_img[5].onclick = function(){
    show_img.src  = small_img[5].src;
}
// Start Item Page