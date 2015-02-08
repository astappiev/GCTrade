var user = {
    online: [],

    update: function() {
        $(".table > tbody > tr").each(function(index) {
            var that = this;
            setTimeout(function() {
                var login = $("td", that).eq(0).text();

                $.ajax({
                    url: "https://api.greencubes.org/users/" + login,
                    type: "GET",
                    dataType: 'json',
                    success: function(data){
                        if(data.status.main) {
                            if(!user.online[login]) {
                                var bodytext = $("td", that).eq(1).text();
                                var name = "Пользователь " + login + ", зашел поиграть!";
                                new Notification(name, {
                                    tag : "gc-see",
                                    body : bodytext,
                                    icon : "/api/head/" + login
                                });
                                user.online[login] = true;
                            }
                            $("td", that).eq(2).html('<span class="label label-info">В сети</span>');
                        } else {
                            if(user.online[login]) {
                                online[login] = false;
                            }

                            var date = (new Date(data.lastseen.main * 1000)).toGMTString();
                            var time = (Math.round(+new Date()/1000) - data.lastseen.main)/60; //минуты

                            if(time > 30240) { // 60*24*21
                                $("td", that).eq(2).html('<span class="label label-success" rel="tooltip" data-placement="top" title="' + date + '">Неактивен</span></li>');
                            } else {
                                $("td", that).eq(2).html('<span class="label label-default" rel="tooltip" data-placement="top" title="' + date + '">' + jQuery.timeago(data.lastseen.main*1000) + '</span></li>');
                            }
                        }

                    },
                    error: function() {
                        alert('Невозможно получить информацию о пользователе.');
                    }
                });
            }, 100 * index);
        });
        return this;
    }
};

$("button#delete").click(function(){
    var that = $(this).parents('tr');
    var id = $(that).attr('id');

    $.ajax({
        url: "/see/delete",
        type: "GET",
        data: { id: id },
        success: function(){
            $(that).fadeOut(300, function(){
                $(this).remove();
            });
        },
        error: function() {
            alert('Ошибка при удалении пользователя.');
        }
    });
});

$("#permission").click(function(){
    $(".alert").hide();
    if (Notification.permission === 'default') {
        Notification.requestPermission(function (permission) {

            if(!('permission' in Notification)) {
                Notification.permission = permission;
            }
            if (permission === "granted") {
                new Notification('Спасибо!', {
                    lang: 'ru-RU',
                    body: 'Теперь мы можем посылать вам уведомления, когда пользователь зайдет поиграть.'
                });
            }
        });
    }
});

$(document).ready(function() {
    $(".table > tbody td.status").spin('show');

    if (!("Notification" in window)) {
        alert("Ваш браузер не поддерживает HTML5 Notifications");
    } else if (Notification.permission === "granted") {
        console.log('С уведомлениями все впорядке.');
    } else {
        $(".alert").show();
    }

    user.update();
    setInterval(function() { user.update() }, 180000);
});