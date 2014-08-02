function MapsIndexShop()
{
    var playerLayers = new L.LayerGroup(), shopsLayers = new L.LayerGroup();
    map = L.map('map', { maxZoom: 15, minZoom: 10, crs: L.CRS.Simple, layers: [playerLayers, shopsLayers] }).setView([-0.35764, 0.11951], 13);
    L.tileLayer('http://maps.gctrade.ru/tiles/{z}/tile_{x}_{y}.png', { noWrap: true }).addTo(map);
    L.control.layers(null, { "Персонаж": playerLayers, "Магазины": shopsLayers }).addTo(map);

    var shopIcon = L.icon({
        iconUrl: '/web/images/shop.png',
        iconSize: [36, 36],
        iconAnchor: [18, 18],
        popupAnchor: [0, -18]
    });

    function AdaptCords(pos) {
        var t = parseInt(pos[0], 10);
        pos[0] = -(parseInt(pos[1], 10)-2607);
        pos[1] = 19920 + t;
        return pos;
    }

    if(your_login) {
        var player, count = 0;
        var playerIcon = L.icon({
            iconUrl: '/api/head/' + your_login,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        var updateUser = setInterval(setUser, 15000);
        setUser();

        function setUser() {
            $.getJSON( "/api/world/" + your_login, function(user_data) {
                if(user_data["status"] == 1)
                {
                    var pos = [user_data["player"]["x"], user_data["player"]["z"]];
                    pos = AdaptCords(pos);
                    var cords = map.unproject([pos[0], pos[1]], map.getMaxZoom());

                    if(count++)
                    {
                        player.setLatLng(cords);
                    }
                    else
                    {
                        player = L.marker(cords, {icon: playerIcon});
                        playerLayers.addLayer(player);
                        map.setView(cords, 15);
                    }
                } else {
                    clearInterval(updateUser);
                    playerLayers.clearLayers();
                }
            });
        }
    }

    $.getJSON( "/api/shop", function(data) {
        for(var i = data.length; i--; )
        {
            if(data[i]["x_cord"] && data[i]["z_cord"])
            {
                var pos = [data[i]["x_cord"], data[i]["z_cord"]];
                pos = AdaptCords(pos);

                var popap = '<div class="shop-popup"><h4><a href="/shop/' + data[i]["alias"] + '" target="_blank">' + data[i]["name"] + '</a></h4>';
                if(data[i]["logo_url"]) popap += '<img src="' + data[i]["logo_url"] + '">';
                popap += '<p>' + data[i]["about"] + '</p><p class="plabel"><span class="label label-default">/go ' + data[i]["subway"] + '</span></p><p class="plabel"><a href="/shop/' + data[i]["alias"] + '" target="_blank"><span class="label label-primary">Прайс</span></a> <span class="label label-success">X ' + data[i]["x_cord"] + ', Z ' + data[i]["z_cord"] + '</span></p></div>';

                var shop = L.marker(map.unproject([pos[0], pos[1]], map.getMaxZoom()), {icon: shopIcon}).bindPopup(popap);
                shopsLayers.addLayer(shop);
            }
        }
    }).fail(function() {
        $("#map").append('<div class="alert alert-danger" role="alert">Ошибка получения данных.</div>');
    });
}

function MapsUserRegions()
{
    $("#customRegionTextarea").autosize();

    console.log(region_list);
    if(region_list["message"] == "Forbidden. Need authorization")
    {
        $("#map").append('<div class="alert alert-danger" role="alert">Ошибка Access_token-а, API GreenCubes не принимает наш ключик. Пишите Kernel-у.</div>');
        return;
    }
    if(region_list["message"] == "Rate limit exceeded, retry later")
    {
        $("#map").append('<div class="alert alert-warning" role="alert">Вы исчерпали лимит запросов к GreenCubes API, попробуйте позже.</div>');
        return;
    }

    var buildLayers = new L.LayerGroup(), fullLayers = new L.LayerGroup();
    map = L.map('map', { maxZoom: 15, minZoom: 10, crs: L.CRS.Simple, layers: [fullLayers, buildLayers] }).setView([-0.35764, 0.11951], 13);
    L.tileLayer('http://maps.gctrade.ru/tiles/{z}/tile_{x}_{y}.png', { noWrap: true }).addTo(map);
    L.control.layers(null, { "Полный доступ": fullLayers, "Частичный доступ": buildLayers }).addTo(map);

    function AdaptCords(pos) {
        var t = parseInt(pos[0], 10);
        pos[0] = -(parseInt(pos[2], 10)-2607);
        pos[2] = 19920 + t;
        return pos;
    }

    function isFull(rights) {
        for(var i = rights.length; i--; ) if(rights[i] == yii_login) return true;
        return false;
    }

    function DrawPolygon(pos1, pos2, region) {
        rights = isFull(region["full_access"]);
        color = (rights) ? 'blue' : 'red';
        pos1 = AdaptCords(pos1), pos2 = AdaptCords(pos2);
        var rectangle = L.rectangle([
            map.unproject([pos1[0], pos1[2]], map.getMaxZoom()),
            map.unproject([pos2[0], pos2[2]], map.getMaxZoom())
        ], { color: color, weight: 3, fillOpacity: 0.3 });
        var parent = region["parent"] ? '<br><i>Родительский:</i> ' + region["parent"] : '';
        rectangle.bindPopup('<b>' + region["name"] + '</b>' + parent + '<br><br><b>Владельцы:</b><br>' + region["full_access"] + '<br><b>Могут строить:</b><br>' + region["build_access"]).on('click', function (e) { this.bringToBack(); });

        if(rights) fullLayers.addLayer(rectangle);
        else buildLayers.addLayer(rectangle);
    }

    var area = 0, volume = 0, cost = 0;
    for (var i = region_list.length; i--;) {
         $.ajax({
         type: 'GET',
         url: 'https://api.greencubes.org/main/regions/' + region_list[i]["name"],
         dataType: 'json',
         success: function(region){
             if(region["name"]) {
                 pos1 = region["coordinates"]["first"].split(' ');
                 pos2 = region["coordinates"]["second"].split(' ');

                 var x = Math.abs(pos1[0]-pos2[0]), y = Math.abs(pos1[1]-pos2[1]), z = Math.abs(pos1[2]-pos2[2]);
                 area += x*z, volume += x*y*z, cost += Math.round(x*z*10+(x*y*z*10)/256);
                 DrawPolygon(pos1, pos2, region);
                 outSats();
             }
             }
         });
     }

    function cutText(text, num) {
        text = text.toString();
        if (text.length > num) return text.slice(0, num-3) + '...';
        return text;
    }

    function price_separator(str, separator){
        return str.toString().replace(/\d(?=(?:\d{3})+\b)/g, "$&" + (separator||' '));
    }

    function outSats() {
        var $list_usermap = $('.usermap dl.dl-horizontal');
        $('#area', $list_usermap).text(price_separator(area));
        $('#volume', $list_usermap).text(price_separator(volume));
        $('#cost', $list_usermap).text(price_separator(cost));
        $('#percent', $list_usermap).text(cutText((area*100)/area_world, 15));
    }
}