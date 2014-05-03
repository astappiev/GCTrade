var online = new Array();

$("button#delete").click(function(){
    var that = $(this).parents('tr');
    var id = $(that).attr('id');

    $.ajax({
        url: "/see/delete",
        type: "GET",
        data: { id: id },
        async: false,
        success: function(){
            $(that).fadeOut(300, function(){ $(this).remove();});
        },
        error: function() {
            alert('Ошибка удаления.');
        }
    });
});

function lastseen(login){
    var lastseen = $.ajax({
        url: "/see/lastseen",
        type: "GET",
        cache: false,
        async: false,
        data: { login: login }
    }).responseText;
    return time(lastseen);
};

function update_world()
{
    online = [];
    var json = $.parseJSON($.ajax({type: 'GET', url: '/api/world', async: false, dataType: 'json'}).responseText);

    for (var i in json){
        online.push(json.name);
    }
    if(online.length>0) $('.alert').hide();
    else
    {
        $('.alert').show();
        $(".alert").alert();
    }
    console.log('Кол-во игроков онлайн: ' + online.length);
};

function isonline(login){
    for (var i =  0; i < online.length; i++){
        if (online[i] == login) return true;
    }
    return false;
};

$(document).ready(function() {
    $(".table > tbody td.status").spin('small');

    if (!("Notification" in window)) {
        alert("Ваш браузер не поддерживает HTML5 Notifications");
    }
    else if (Notification.permission === "granted") {
        console.log('С уведомлениями все нормально.');
    }
    else
    {
        $(".alert").removeClass('hidden').addClass('visible');
    }

    $("#permission").click(function(){
        $(".alert").removeClass('visible').addClass('hidden');
        if (Notification.permission === 'default') {
            Notification.requestPermission(function (permission) {

                if(!('permission' in Notification)) {
                    Notification.permission = permission;
                }
                if (permission === "granted") {
                    var notification = new Notification('Спасибо!', {
                        lang: 'ru-RU',
                        body: 'Теперь вы сможете получать уведомления.'
                    });
                }
            });
        }
    });

    update_world();
    setTimeout(function(){
        $(".table > tbody > tr").each(function(index) {
            var that = this;
            var t = setTimeout(function() {
                var login = $("td", that).eq(0).text();
                if(isonline(login))
                {
                    var text = '<span class="label label-info">В сети</span>';
                    var bodytext = $("td", that).eq(1).text();
                    var name = login + " сейчас в сети!";
                    var mailNotification = new Notification(name, {
                        tag : "gc-see",
                        body : bodytext,
                        icon : "/images/see.png"
                    });
                }
                else var text = lastseen(login);
                $("td", that).eq(2).html(text);
            }, 100 * index);
        });
    }, 1000);

    setInterval(function(){
        update_world();
        setTimeout(function(){
            $(".table > tbody > tr").each(function(index) {
                var that = this;
                var t = setTimeout(function() {
                    var text = $("td", that).eq(2).html();
                    $("td", that).eq(2).empty().spin('small');
                    var login = $("td", that).eq(0).text();
                    var online = isonline(login);
                    if(online == true && text == '<span class="label label-info">В сети</span>')
                        text = '<span class="label label-info">В сети</span>';
                    else if(online == true)
                    {
                        text = '<span class="label label-info">В сети</span>';
                        var bodytext = $("td", that).eq(1).text();
                        var name = login + " сейчас в сети!";

                        var mailNotification = new Notification(name, {
                            tag : "gc-see",
                            body : bodytext,
                            icon : "/images/see.png"
                        });
                    }
                    else if(text == '<span class="label label-info">В сети</span>')
                    {
                        text = '<strong class="label label-default">Совсем недавно</strong>';
                    }
                    $("td", that).eq(2).html(text);
                }, 500 * index);
            });
        }, 1000);
    }, 180000);
});

function time(value){
    var theDate = new Date(value * 1000);
    var date = theDate.toGMTString();

    var current_time = Math.round(+new Date()/1000);
    var time = (current_time-value)/60; //минуты

    var text_date = 'def';
    if(time > 30240) // 60*24*21
    {
        return '<span class="label label-success" rel="tooltip" data-placement="top" title="' + date + '">Неактивен</span></li>';
    }
    else if(time > 5760) // 60*24*4
        text_date = Math.round(time/1440) + ' дней назад';
    else if(time > 2880) // 60*24*2
        text_date = Math.round(time/1440) + ' дня назад';
    else if(time > 1440) // 60*24
        text_date = 'Вчера';
    else if(time > 240) // 60*4
        text_date = Math.round(time/60) + ' часов назад';
    else if(time > 120) // 60*4
        text_date = Math.round(time/60) + ' часа назад';
    else if(time > 60) // 60*4
        text_date = 'Час назад';
    else if(time < 61)
        text_date = 'Менее часа назад';
    else
        text_date = 'Не определено';

    return '<span class="label label-default" rel="tooltip" data-placement="top" title="' + date + '">' + text_date + '</span></li>';
};