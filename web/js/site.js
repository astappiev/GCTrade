$(document).ready(function(){
    $('body').tooltip({
        selector: "[rel=tooltip]",
        placement: "bottom"
    });

    $(function($) {
        $('tbody tr[data-href] td.name').addClass('clickable').click( function() {
            window.location = $(this).parent().attr('data-href');
        }).find('a').hover( function() {
            $(this).parents('tr').unbind('click');
        }, function() {
            $(this).parents('tr').click( function() {
                window.location = $(this).attr('data-href');
            });
        });
    });

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
            }
        });
    });

    // has-error
    //Координаты задано не верно.
});
