var isGCRC = (localStorage.getItem('isGCRC') == 1 || localStorage.getItem('isGCRC') == null)?1:0;

function getJsonGCMap(url)
{
    var response = $.ajax({type: 'GET', url: url, async: false, dataType: 'json'}).responseText;
    if(response == '' || !response)
    {
        $(".breadcrumb").after('<div id="w7-danger" class="alert-danger alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Нет связи с сервером <a href="http://gcmap.ru" target="_blank" rel="tooltip" title="Карта сервера GreenCubes">gcmap.ru</a>, прошу прощение за временные неудобства.</div>');
    }
    return $.parseJSON(response);
}

function union_line(json1, json2){
    for(var i in json2) json1.push(json2[i]);
    return json1;
}

var planned = getJsonGCMap('http://gcmap.ru/api/metro/lines/type/indigo');
var barred = getJsonGCMap('http://gcmap.ru/api/metro/lines/type/black');
var unfinished = getJsonGCMap('http://gcmap.ru/api/metro/lines/type/yellow');
var line = (isGCRC)?getJsonGCMap('http://gcmap.ru/api/metro/lines/type/blue'):getJsonGCMap('http://gcmap.ru/api/metro/lines/type/default');

var cross = (isGCRC)?getJsonGCMap('http://gcmap.ru/api/metro/blocks/type/s_cross'):getJsonGCMap('http://gcmap.ru/api/metro/blocks/type/cross');
var station = (isGCRC)?getJsonGCMap('http://gcmap.ru/api/metro/blocks/type/s_station'):getJsonGCMap('http://gcmap.ru/api/metro/blocks/type/station');
var turn = (isGCRC)?getJsonGCMap('http://gcmap.ru/api/metro/blocks/type/s_turn'):getJsonGCMap('http://gcmap.ru/api/metro/blocks/type/turn');

var all_line = union_line(line, union_line(planned, union_line(barred, unfinished)));

function find_block(find_id)
{
    for(var i in turn)
    {
        if(turn[i].cb_id == find_id) return turn[i];
    }
    for(var i in station)
    {
        if(station[i].cb_id == find_id) return station[i];
    }
    for(var i in cross)
    {
        if(cross[i].cb_id == find_id) return cross[i];
    }
    return 0;
}

function find_line(find_id)
{
    for(var i in all_line)
    {
        if(all_line[i]._to == find_id || all_line[i]._from == find_id) return all_line[i];
    }
    return 0;
}

function count_length_line()
{
    var width = 0;
    for (var i in line)
    {
        var from = find_block(line[i]._from);
        var to = find_block(line[i]._to);
        if (from != 0 && to != 0) width += (Math.abs(from.x - to.x) + Math.abs(from.z - to.z));
    }
    return width;
}

function station_owner()
{
    var owner = {};
    for (var i in station)
    {
        if(station[i].owner != '')
        {
            if(!owner[station[i].owner]) owner[station[i].owner] = 1;
            else owner[station[i].owner]++;

        }
    }
    return owner;
}

function courators_list()
{
    var owner = station_owner();
    var courators_list = '';
    for (var i in Object.keys(owner))
    {
        if(i != Object.keys(owner).length - 1) courators_list += Object.keys(owner)[i] + ' (' + owner[Object.keys(owner)[i]] + '), ';
        else courators_list += Object.keys(owner)[i] + ' (' + owner[Object.keys(owner)[i]] + ')';
    }
    return courators_list;
}

function local_areas()
{
    var local_areas = '';
    var local = {};
    for (var i in station)
    {
        if (/^[A-Za-z0-9]{1,6}_/gi.test(station[i].name) && !(/^[hnwsre]_/gi.test(station[i].name)))
        {
            if(!local[station[i].name.split('_')[0]]) local[station[i].name.split('_')[0]] = 1;
            else local[station[i].name.split('_')[0]]++;
        }
    }
    for (var i in Object.keys(local))
    {
        if(local[Object.keys(local)[i]] > 1)
        {
            if(i != Object.keys(local).length - 1) local_areas += Object.keys(local)[i] + '_ (' + local[Object.keys(local)[i]] + '), ';
            else local_areas += Object.keys(local)[i] + '_ (' + local[Object.keys(local)[i]] + ')';
        }
    }
    return local_areas;
}

function station_list()
{
    var station_list = '';
    station.sort(function(station_one, station_two)
    {
        if (station_one.name < station_two.name) return -1;
        if (station_one.name > station_two.name) return 1;
        return 0;
    });
    for (var i in station)
    {
        if(station[i].name != '') if(i != station.length - 1) station_list += station[i].name + ', ';
        station_list += station[i].name;
    }
    return station_list;
}

function station_without_names()
{
    var res = [];
    for (var i in station)
    {
        if(station[i].name == '' || station[i].name == 'new station') res.push(station[i]);
    }
    return res;
}

function station_without_names_list()
{
    var st = station_without_names();
    var res = '';
    for (var i in st)
    {
        res += 'Номер cb: ' + st[i].cb_id + '.   Координаты (x, z): (' + st[i].x + ', ' + st[i].z + ');</br>';
    }
    return res;
}

function cb_bad_id()
{
    var res = [];
    for (var i in station)
    {
        if(station[i].cb_id > 10000 || station[i].cb_id < 0) res.push(station[i]);
    }
    for (var i in cross)
    {
        if(cross[i].cb_id > 10000 || cross[i].cb_id < 0) res.push(cross[i]);
    }
    return res;
}

function cb_bad_id_list()
{
    var cb = cb_bad_id();
    var res = '';
    for (var i in cb)
    {
        res += 'Номер cb: ' + cb[i].cb_id + '.   Координаты (x, z): (' + cb[i].x + ', ' + cb[i].z + ');</br>';
    }
    return res;
}

function cb_without_owners()
{
    var res = [];
    for (var i in cross)
    {
        if(cross[i].owner == '') res.push(cross[i])
    }
    for (var i in station)
    {
        if(station[i].owner == '') res.push(station[i]);
    }
    return res;
}

function cb_without_owners_list()
{
    var cb = cb_without_owners();
    var res = '';
    for (var i in cb)
    {
        res += 'Номер cb: ' + cb[i].cb_id + '.   Координаты (x, z): (' + cb[i].x + ', ' + cb[i].z + ');</br>';
    }
    return res;
}

function cb_not_line()
{
    var res = [];

    for (var i in station)
    {
        if(!find_line(station[i].cb_id)) res.push(station[i]);
    }
    for (var i in cross)
    {
        if(!find_line(cross[i].cb_id)) res.push(cross[i]);
    }
    for (var i in turn)
    {
        if(!find_line(turn[i].cb_id)) res.push(turn[i]);
    }
    return res;
}

function cb_not_line_list()
{
    var cb = cb_not_line();
    var res = '';
    for (var i in cb)
    {
        res += 'Номер cb: ' + cb[i].cb_id + '.   Координаты (x, z): (' + cb[i].x + ', ' + cb[i].z + ');</br>';
    }
    return res;
}

function command_cb(cb_id)
{
    var str = '';
    var cb = cb_id;
    var f_from = find_block(cb);;
    var f_to = [];

    for (var i in all_line)
    {
        if(all_line[i]._from == cb || all_line[i]._to == cb)
        {
            f_to = (all_line[i]._from == cb)?find_block(all_line[i]._to):find_block(all_line[i]._from);
            if(f_to.type == 's_turn' || f_to.type == 'turn')
            {
                var f_to_prev = f_from;
                var f_to_new = f_to;
                while(f_to_new.type == 's_turn' || f_to_new.type == 'turn')
                {
                    for (var j in all_line)
                    {
                        if((all_line[j]._to == f_to_new.cb_id && all_line[j]._from != f_to_prev.cb_id) || (all_line[j]._from == f_to_new.cb_id && all_line[j]._to != f_to_prev.cb_id))
                        {
                            f_to_prev = f_to_new;
                            f_to_new = (all_line[j]._from == f_to_prev.cb_id)?find_block(all_line[j]._to):find_block(all_line[j]._from);
                        }
                    }
                }

                if(f_from.x > f_to.x)
                {
                    str += '/cb cfg ' + f_from.cb_id + ' n ' + f_to_new.cb_id + '<br/>';
                }
                else if(f_from.x < f_to.x)
                {
                    str += '/cb cfg ' + f_from.cb_id + ' s ' + f_to_new.cb_id + '<br/>';
                }
                else if(f_from.z > f_to.z)
                {
                    str += '/cb cfg ' + f_from.cb_id + ' e ' + f_to_new.cb_id + '<br/>';
                }
                else if(f_from.z < f_to.z)
                {
                    str += '/cb cfg ' + f_from.cb_id + ' w ' + f_to_new.cb_id + '<br/>'
                }

                if(f_to_prev.x > f_to_new.x)
                {
                    str += '/cb cfg ' + f_to_new.cb_id + ' s 0<br/>';
                    str += '/cb cfg ' + f_to_new.cb_id + ' s ' + f_from.cb_id + '<br/>';
                }
                else if(f_to_prev.x < f_to_new.x)
                {
                    str += '/cb cfg ' + f_to_new.cb_id + ' n 0<br/>';
                    str += '/cb cfg ' + f_to_new.cb_id + ' n ' + f_from.cb_id + '<br/>';
                }
                else if(f_to_prev.z > f_to_new.z)
                {
                    str += '/cb cfg ' + f_to_new.cb_id + ' w 0<br/>';
                    str += '/cb cfg ' + f_to_new.cb_id + ' w ' + f_from.cb_id + '<br/>';
                }
                else if(f_to_prev.z < f_to_new.z)
                {
                    str += '/cb cfg ' + f_to_new.cb_id + ' e 0<br/>';
                    str += '/cb cfg ' + f_to_new.cb_id + ' e ' + f_from.cb_id + '<br/>';
                }
            }
            else
            {
                if(f_from.x > f_to.x)
                {
                    str += '/cb cfg ' + f_from.cb_id + ' n ' + f_to.cb_id + '<br/>';
                    str += '/cb cfg ' + f_to.cb_id + ' s 0<br/>';
                    str += '/cb cfg ' + f_to.cb_id + ' s ' + f_from.cb_id + '<br/>';
                }
                else if(f_from.x < f_to.x)
                {
                    str += '/cb cfg ' + f_from.cb_id + ' s ' + f_to.cb_id + '<br/>';
                    str += '/cb cfg ' + f_to.cb_id + ' n 0<br/>';
                    str += '/cb cfg ' + f_to.cb_id + ' n ' + f_from.cb_id + '</p>';
                }
                else if(f_from.z > f_to.z)
                {
                    str += '/cb cfg ' + f_from.cb_id + ' e ' + f_to.cb_id + '<br/>';
                    str += '/cb cfg ' + f_to.cb_id + ' w 0<br/>';
                    str += '/cb cfg ' + f_to.cb_id + ' w ' + f_from.cb_id + '<br/>';
                }
                else if(f_from.z < f_to.z)
                {
                    str += '/cb cfg ' + f_from.cb_id + ' w ' + f_to.cb_id + '<br/>'
                    str += '/cb cfg ' + f_to.cb_id + ' e 0<br/>';
                    str += '/cb cfg ' + f_to.cb_id + ' e ' + f_from.cb_id + '<br/>';
                }
            }
            str += '<br/>';
        }
    }
    if((f_from.type == 's_station' || f_from.type == 'station') && f_from.name != '')
    {
        str += '/st create ' + f_from.name + '<br/>';
        str += '/cb cfg ' + f_from.cb_id + ' st ' + f_from.name + '<br/>';
        str += '/cb cfg ' + f_from.cb_id + ' arr t<br/>';
        str += '/cb cfg ' + f_from.cb_id + ' dep t<br/>';
    }
    return str;
}

$(document).ready(function(){
    if(isGCRC) $("label#gcrc").addClass("active");
    else $("label#metro").addClass("active");

    $("label#gcrc").click(function() {
        if(isGCRC == 0)
        {
            localStorage.setItem('isGCRC', '1');
            location.reload();
        }
    });
    $( "label#metro" ).click(function() {
        if(isGCRC == 1)
        {
            localStorage.setItem('isGCRC', '0');
            location.reload();
        }
    });

    $('dt#length_line').append(count_length_line());
    $('dt#cb_count').append(station.length + cross.length);
    $('dt#courators_count').append(Object.keys(station_owner()).length);
    $('dt#station_count').append(station.length);

    $('#courators_list').append(courators_list());
    $('#station_list').append(station_list());
    $('#local_areas').append(local_areas());

    $('#st_bad_list_counter').append(station_without_names().length);
    $('#st_bad_list .panel-body').append(station_without_names_list());

    $('#cb_bad_list_counter').append(cb_bad_id().length);
    $('#cb_bad_list .panel-body').append(cb_bad_id_list());

    $('#cb_not_owner_counter').append(cb_without_owners().length);
    $('#cb_not_owner .panel-body').append(cb_without_owners_list());

    $('#not_line_counter').append(cb_not_line().length);
    $('#not_line .panel-body').append(cb_not_line_list());

    $('#find_cb_owner button').click(function()
    {
        var owner = $('#find_cb_owner input').val();
        var cb_list = [];
        for (var i in cross)
        {
            if(cross[i].owner == owner) cb_list.push(cross[i])
        }
        for (var i in station)
        {
            if(station[i].owner == owner) cb_list.push(station[i])
        }
        var res = '';
        for (var i in cb_list)
        {
            res += 'Номер cb: ' + cb_list[i].cb_id + '.   Координаты (x, z): (' + cb_list[i].x + ', ' + cb_list[i].z + ');</br>';
        }
        $('p#find_cb_owner').empty().append(res);
    });

    $('#full_info_cb button').click(function()
    {
        var cb = find_block($('#full_info_cb input').val());
        var res = 'Название кб: ';
        if(cb.name != '') res += cb.name;
        else res += 'нет названия';
        res += ';<br>';
        res += 'Номер cb: ' + cb.cb_id + ';<br/>Координаты (x, z): (' + cb.x + ', ' + cb.z + ');</br>Владелец: ';
        if(cb.owner != '') res += cb.owner;
        else res += 'нет владельца';
        res += ';<br>';

        $('p#full_info_cb').empty().append(res);
    });

    $('#config_cb button').click(function()
    {
        var id = $('#config_cb input').val();
        var res = '';
        if(find_block(id).type == 's_turn' || find_block(id).type == 'turn') res += 'Блоки данного типа (Повороты), не поддерживаются!';
        else res += command_cb(id);

        $('pre#config_cb').empty().append(res);
    });
});