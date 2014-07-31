area_world = 24016*8016;

$(function($){
    $.fn.superLink = function(link) {
        link = link || 'a:first';
        return this.each(function(){
            var $container = $(this),
                $targetLink = $(link, $container).clone(true);

            /* Take all current mouseout handlers of $container and
             transfer them to the mouseout handler of $targetLink */
            var mouseouts = $(this).data('events') && $(this).data('events').mouseout;
            if (mouseouts) {
                $.each(mouseouts, function(i, fn){
                    $targetLink.mouseout(function(e){
                        fn.call($container, e);
                    });
                });
                delete $(this).data('events').mouseout;
            }

            /* Take all current mouseover handlers of $container and
             transfer them to the mouseover handler of $targetLink */
            var mouseovers = $(this).data('events') && $(this).data('events').mouseover;
            if (mouseovers) {
                $.each(mouseovers, function(i, fn){
                    $targetLink.mouseover(function(e){
                        fn.call($container, e);
                    });
                });
                delete $(this).data('events').mouseover;
            }

            $container.mouseover(function(){
                $targetLink.show();
            });

            $targetLink
                .click(function(){
                    $targetLink.blur();
                })
                .mouseout(function(e){
                    $targetLink.hide();
                })
                .css({
                    position: 'absolute',
                    top: $container.offset().top,
                    left: $container.offset().left,
                    /* IE requires background to be set */
                    backgroundColor: '#FFF',
                    display: 'none',
                    opacity: 0,
                    width: $container.outerWidth(),
                    height: $container.outerHeight(),
                    padding: 0
                })
                .appendTo('body');

        });
    };

});

$(document).ready(function(){
    $('body').tooltip({
        selector: "[rel=tooltip]",
        placement: "bottom"
    });

    $('tbody tr, .shop-list.view div.well').mouseover(function(){
        $(this).addClass('active');
    }).mouseout(function(){
        $(this).removeClass('active');
    }).superLink();

    $('span.complaint').click(function() {
        var flag = $(this);
        var that = $(flag).parents("tr");
        var type = $(flag).attr("data-type");
        var id_shop = $("div.body-content").attr('id');
        var id_item = $("td", that).eq(1).text();

        //console.log('item: ' + id_item + ', shop: ' + id_shop + ', type: ' + type);

        $.ajax({
            type: "GET",
            url: "/shop/complaint",
            cache: false,
            async: false,
            data: { id_item: id_item, id_shop: id_shop, type: type },
            success: function(text) {
                console.log(text);
                if(text == 'add')
                    $(flag).addClass("bad")
                else if(text == 'del')
                    $(flag).removeClass("bad")
                else if(text == 'guest')
                    alert('Вы должны быть авторизованы.');
            }
        });
    });

    $('.deleteShopItemMenu a').click(function(event){
        var question = confirm("Вы действительно хотите удалить магазин?");
        if(!question) event.preventDefault();
    });
});