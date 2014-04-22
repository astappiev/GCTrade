$('p#cord').each(function(){
    if($.cookie('cord_x') && $.cookie('cord_z'))
    {
        var cord_x = $(this).attr('data-x');
        var cord_z = $(this).attr('data-z');
        var cookie_x = $.cookie('cord_x');
        var cookie_z = $.cookie('cord_z');

        $(this).text('Растояние: ' + Math.round(Math.pow(Math.pow(cord_x - cookie_x, 2) + Math.pow(cord_z - cookie_z, 2), 1/2)) + ' блоков');
    }
    else
    {
        $(this).text('');

        var cord_x = prompt('Введите вашу координату по X:', 100);
        $.cookie('cord_x', cord_x);
        var cord_z = prompt('Введите вашу координату по Z:', 100);
        $.cookie('cord_z', cord_z);
    }

    //$.cookie('cord_x', null);
    //$.cookie('cord_z', null);
});