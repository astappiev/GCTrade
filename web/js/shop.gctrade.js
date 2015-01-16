var avg_price = {
    price: [],

    init: function () {
        $.ajax({
            url: "/cpanel/shop/good/avg",
            dataType: "json",
            success: function (data) {
                avg_price.price = data;
                avg_price.update();
            }
        });
    },

    update: function () {
        if(avg_price.price.length === 0) {
            avg_price.init();
        } else {
            $('table.avg-list > tbody tr').each(function() {
                var item_id = $('td', this).eq(1).text();
                var stack = $('td', this).eq(7).text();
                var price_sell = (avg_price.price[item_id].price_sell * stack).toFixed(2);
                var price_buy = (avg_price.price[item_id].price_buy * stack).toFixed(2);

                $('td', this).eq(4).addClass($('td', this).eq(3).text() >= +price_sell ? 'bg-success' : 'bg-danger').text(price_sell);
                $('td', this).eq(6).addClass($('td', this).eq(5).text() >= +price_buy ? 'bg-success' : 'bg-danger').text(price_buy);
            });
        }
    }
};

$('input#file-import-good').on('change', function(e){
    var $table = $("table#add-item-table", $(this).parents('.modal'));
    var file_extension = $(this).val().split(".").pop().toLowerCase();

    if($.inArray(file_extension, ["csv", "txt"]) == -1) {
        $table.before('<p class="error-type">Загружаемый файл должен быть типа CSV или TXT</p>');
        return false;
    }

    if (e.target.files != undefined) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var line = e.target.result.split("\n");

            $table.show();
            for(var i = 0; i < line.length; i++)
            {
                line[i] = $.trim(line[i]);
                if(line[i].slice(-1) == ';') line[i] = line[i].substring(0, line[i].length - 1);
                var column = line[i].split(/,|;/);
                if (column.length == 4)
                {
                    var table = '<tr id="' + column[0] + '">';
                    for(var j = 0; j < column.length; j++)
                    {
                        var temp = column[j].replace(/\s/g, '');
                        if (temp == 'null') temp = '—';
                        if(j == 0) table += '<td><img src="/images/items/' + temp + '.png" class="small-icon"/></td>';
                        else table += '<td>' + temp + '</td>';
                    }
                    table += '<td class="sp"></td></tr>';
                    $("tbody", $table).append(table);
                }
            }
        };
        reader.readAsText(e.target.files.item(0));
    }
    return false;
});

$("form#add-item-form").on('submit', function(e) {
    var $table = $('table#add-item-table', $(this).parents('.modal'));
    var item_id = $("input#item_id").val().replace(',', '.');
    var price_sell = $("input#price_sell").val();
    var price_buy = $("input#price_buy").val();
    var stuck = $("input#stuck").val();

    if(item_id && stuck && (price_sell || price_buy))
    {
        $table.show().children('tbody').append('<tr id="' + item_id + '"><td><img src="/images/items/' + item_id + '.png" class="small-icon"/></td><td>' + (price_sell || '—') + '</td><td>' + (price_buy || '—') + '</td><td>' + stuck + '</td><td id="sp"></td></tr>');
        $('form#add-item-form').trigger('reset');
        $('p#error-item-form').empty();
    } else if(!item_id) {
        $('p#error-item-form').text('Необходимо заполнить «ID товара».');
    } else if(!(price_sell || price_buy)) {
        $('p#error-item-form').text('Необходимо заполнить «Цена продажи» и/или «Цена пакупки».');
    } else if(!stuck) {
        $('p#error-item-form').text('Необходимо заполнить «Кол-во».');
    }

    return false;
});

$('button#submit-item-form').on('click', function() {
    var $table = $("table#add-item-table > tbody", $(this).parents('.modal'));
    var shop_id = $('input#shop_id-form-good').val();
    var token = $('input#token-form-good').val();
    $("td.sp", $table).spin('show');
    console.log('hi-1');

    $("tr", $table).each(function(index) {
        console.log('hi-2', index);
        var $that = this;
        var t = setTimeout(function() {
            var price_sell = $("td", $that).eq(1).text();
            var price_buy = $("td", $that).eq(2).text();

            $.ajax({
                type: "POST",
                url: "/cpanel/shop/good/create",
                cache: false,
                async: false,
                dataType: "json",
                data: '_csrf=' + token + '&&Good%5Bshop_id%5D=' + shop_id + '&Good%5Bitem_id%5D=' + $($that).attr("id") + '&Good%5Bprice_buy%5D=' + (price_buy === '—' ? '' : price_buy) + '&Good%5Bprice_sell%5D=' + (price_sell === '—' ? '' : price_sell) + '&Good%5Bstuck%5D=' + $("td", $that).eq(3).text(),
                success: function (data) {
                    console.log(data);
                    $("td", $that).eq(4).html('<span class="glyphicon glyphicon-' + (data.status == 1 ? 'ok-circle green' : (data.status == 2 ? 'refresh blue' : 'ban-circle red') ) + ' twosize" data-toggle="tooltip" title="' + data.message + '"></span>');
                }
            });
        }, 100 * index);
    });
});

$('button#submit-edit-form').on('click', function() {
    $('form#edit-item-form').submit();
});

$('form#edit-item-form').on('submit', function(e){
    var $form = $(this);
    var $submit_button = $('#submit-edit-form');
    var form_data = $form.serialize();
    $submit_button.addClass('disabled');

    $.ajax({
        type: "POST",
        url: "/cpanel/shop/good/create",
        cache: false,
        async: false,
        dataType: "json",
        data: form_data,
        success: function(data) {
            if(data.status == 2) {
                var $item = $('table.item-list tr#item_' + data.id);
                $('td', $item).eq(3).text($('input[name="Good[price_sell]"]', $form).val() || '—');
                $('td', $item).eq(5).text($('input[name="Good[price_buy]"]', $form).val() || '—');
                $('td', $item).eq(7).text($('input[name="Good[stuck]"]', $form).val());

                $form.trigger('reset');
                $(".item-edit-modal").modal('hide');
                avg_price.update();
                e.stopImmediatePropagation();
            }
        }
    });

    $submit_button.removeClass('disabled');
    e.preventDefault();
});

$('.item-modal').on('hidden.bs.modal', function() {
    location.reload();
});

$('.import-good-modal').on('hidden.bs.modal', function() {
    location.reload();
});

$('button#submit-book-form').on('click', function() {
    $('form#add-book-form').submit();
});

$('.book-modal').on('hidden.bs.modal', function() {
    if($("input#book-id", this).val()) {
        $("form", this).trigger('reset');
        $("input#book-id", this).val('');
    }
});

$('form#add-book-form').on('submit', function(e){
    var $form = $(this);
    var $submit_button = $('#submit-book-form');
    var form_data = $form.serialize();
    $submit_button.addClass('disabled');

    $.ajax({
        type: "POST",
        url: "/cpanel/shop/book/create",
        cache: false,
        async: false,
        dataType: "json",
        data: form_data,
        success: function(data) {
            if(data.status == 1) {
                if ($('#empty-items').length) {
                    location.reload();
                } else {
                    $('table.item-list tr:last').after('<tr id="item_' + data.id + '">\
                        <td><img src="/images/items/' + $('select[name="Book[item_id]"]', $form).val() + '.png" alt="Книга с текстом" class="small-icon"></td>\
                        <td class="name">' + $('input[name="Book[name]"]', $form).val() + '</td>\
                        <td class="name">' + $('input[name="Book[author]"]', $form).val() + '</td>\
                        <td>' + ($('input[name="Book[price_sell]"]', $form).val() || '—') + '</td>\
                        <td class="control">\
                            <div class="btn-group btn-group-sm">\
                                <button class="btn btn-primary edit-book-buttons" title="Редактировать"><span class="glyphicon glyphicon-pencil"></span></button>\
                                <button class="btn btn-danger remove-book-buttons" title="Удалить"><span class="glyphicon glyphicon-remove"></span></button>\
                            </div>\
                        </td>\
                    </tr>');
                    $form.trigger('reset');
                    $(".book-modal").modal('hide');
                }
                e.stopImmediatePropagation();
            } else if(data.status == 2) {
                var $item = $('table.item-list tr#item_' + data.id);
                $('td', $item).eq(0).html('<img src="/images/items/' + $('select[name="Book[item_id]"]', $form).val() + '.png" alt="Книга с текстом" class="small-icon">');
                $('td', $item).eq(1).text($('input[name="Book[name]"]', $form).val());
                $('td', $item).eq(2).text($('input[name="Book[author]"]', $form).val());
                $('td', $item).eq(3).text($('input[name="Book[price_sell]"]', $form).val() || '—');

                $form.trigger('reset');
                $(".book-modal").modal('hide');
                e.stopImmediatePropagation();
            }
        }
    });

    $submit_button.removeClass('disabled');
    e.preventDefault();
});

$('select#book-item_id').on('change', function() {
    var $form = $(this).parents('form');
    $('label[for="book-item_id"] img', $form).attr('src', '/images/items/' + $(this).val() + '.png');
});

$('body').on('click', 'button.remove-book-buttons', function() {
    var $item = $(this).parents('tr');
    $.get("/cpanel/shop/book/delete", { id: $item.attr('id').split('_')[1] }).done(function() {
        $item.hide('slow', function(){
            $item.remove();
        });
    });
}).on('click', 'button.edit-book-buttons', function() {
    var $item = $(this).parents('tr');

    $.ajax({
        url: "/cpanel/shop/book/view",
        async: false,
        dataType: "json",
        data: { id: $item.attr('id').split('_')[1] },
        success: function (data) {
            var $modal = $(".book-modal");
            var $form = $("form", $modal);
            $modal.modal('show');

            $("input#book-id", $form).val(data.model.id);
            $("select#book-item_id", $form).val(data.model.item_id);
            $('label[for="book-item_id"] img', $form).attr('src', '/images/items/' + data.model.item_id + '.png');
            $("input#book-name", $form).val(data.model.name);
            $("input#book-author", $form).val(data.model.author);
            $("textarea#book-description", $form).html(data.model.description);
            $("input#book-price_sell", $form).val(data.model.price_sell);
        }
    });
}).on('click', 'button.remove-item-buttons', function() {
    var $item = $(this).parents('tr');
    $.get("/cpanel/shop/good/delete", { id: $item.attr('id').split('_')[1] }).done(function() {
        $item.hide('slow', function(){
            $item.remove();
        });
    });
}).on('click', 'button.edit-item-buttons', function() {
    var $item = $(this).parents('tr');

    $.ajax({
        url: "/cpanel/shop/good/view",
        async: false,
        dataType: "json",
        data: { id: $item.attr('id').split('_')[1] },
        success: function (data) {
            var $modal = $(".item-edit-modal");
            var $form = $("form", $modal);
            $modal.modal('show');

            $("input#good-id", $form).val(data.model.id);
            $("input#good-item_id", $form).val(data.model.item_id);
            $('label[for="good-item_id"] img', $form).attr('src', '/images/items/' + data.model.item_id + '.png');
            $("input#good-price_sell", $form).val(data.model.price_sell);
            $("input#good-price_buy", $form).val(data.model.price_buy);
            $("input#good-stuck", $form).val(data.model.stuck);
        }
    });
}).ready(function() {
    if($('table.avg-list').length) {
        avg_price.update();
    }
});

$('input#file-import-book').on('change', function(e){
    var file_extension = $(this).val().split(".").pop().toLowerCase();

    if($.inArray(file_extension, ["csv", "txt"]) == -1) {
        $("table#import-book-table").before('<p class="error-type">Загружаемый файл должен быть типа CSV или TXT</p>');
        return false;
    }

    if (e.target.files != undefined) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var line = e.target.result.split("\n");

            $("table#import-book-table").show();
            for(var i = 0; i < line.length; i++)
            {
                line[i] = $.trim(line[i]);
                if(line[i].slice(-1) == ';') line[i] = line[i].substring(0, line[i].length - 1);
                var column = line[i].split(/,|;/);
                if (column.length == 5)
                {
                    var table = '<tr id="' + column[0] + '">';
                    for(var j = 0; j < column.length; j++)
                    {
                        var temp = column[j].replace(/\s/g, '');
                        if (temp == 'null') temp = '—';
                        if(j == 0) table += '<td><img src="/images/items/' + temp + '.png" class="small-icon"/></td>';
                        else table += '<td>' + temp + '</td>';
                    }
                    table += '<td class="sp"></td></tr>';
                    $("table#import-book-table tbody").append(table);
                }
            }
        };
        reader.readAsText(e.target.files.item(0));
    }
    return false;
});

$('button#import-book').on('click', function() {
    var $table = $("table#import-book-table > tbody");
    var shop_id = $('input#shop-import-book').val();
    var token = $('input#token-import-book').val();
    $("td.sp", $table).spin('show');

    $("tr", $table).each(function(index) {
        var $that = this;
        var t = setTimeout(function() {

            var price_sell = $("td", $that).eq(4).text();
            $.ajax({
                type: "POST",
                url: "/cpanel/shop/book/create",
                cache: false,
                async: false,
                dataType: "json",
                data: '_csrf=' + token + '&&Book%5Bshop_id%5D=' + shop_id + '&Book%5Bitem_id%5D=' + $($that).attr("id") + '&Book%5Bname%5D=' + $("td", $that).eq(1).text() + '&Book%5Bauthor%5D=' + $("td", $that).eq(2).text() + '&Book%5Bdescription%5D=' + $("td", $that).eq(3).text() + '&Book%5Bprice_sell%5D=' + (price_sell === '—' ? '' : price_sell),
                success: function (data) {
                    $("td", $that).eq(5).html('<span class="glyphicon glyphicon-' + (data.status == 1 ? 'ok-circle green' : (data.status == 2 ? 'refresh blue' : 'ban-circle red') ) + ' twosize" data-toggle="tooltip" title="' + data.message + '"></span>');
                }
            });
        }, 100 * index);
    });
});

$('.import-book-modal').on('hidden.bs.modal', function() {
    location.reload();
});