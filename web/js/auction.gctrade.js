$(document).ready(function() {

   $("div[map]").each(function() {

       var pos1 = $(this).attr("data-first").split(' ');
       var pos2 = $(this).attr("data-second").split(' ');

       var map = L.map(this, {
           maxZoom: 15,
           minZoom: 10,
           crs: L.CRS.Simple
       }).setView([-0.35764, 0.11951], 13);

       L.tileLayer('http://maps.gctrade.ru/tiles/{z}/tile_{x}_{y}.png', {
           noWrap: true
       }).addTo(map);

       pos1 = AdaptCords(pos1);
       pos2 = AdaptCords(pos2);
       var rectangle = L.rectangle([
           map.unproject([pos1[0], pos1[2]], map.getMaxZoom()),
           map.unproject([pos2[0], pos2[2]], map.getMaxZoom())
       ], { weight: 2, fillOpacity: 0.4 }).addTo(map);

       map.fitBounds(rectangle.getBounds());
   });

    $('form#add-bid').on('submit', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var form = $(this);
        $('input[type=submit]', form).addClass('disabled');
        $.ajax({
            'type': 'POST',
            'async': false,
            'dataType': 'json',
            'url': form.attr('action'),
            'data': form.serialize(),
            'success': function(data) {
                console.log(data);
                if (typeof data !== 'undefined' && data.status !== 0) {
                    $('.error-msg-bid').text(data.message).parents(".list-group-item-danger").removeClass("list-group-item-danger").addClass("list-group-item-success");
                    location.reload();
                    form.trigger( 'reset' );
                } else if(data.status == 0) {
                    $('.error-msg-bid').text(data.message);
                } else {
                    console.log(data);
                }
            },
            'error': function(jqXHR, textStatus, errorThrown){
                console.log(textStatus, errorThrown);
            }
        });
        $('input[type=submit]', form).removeClass('disabled');
    });
});

function AdaptCords(pos) {
    var t = parseInt(pos[0], 10);
    pos[0] = -(parseInt(pos[2], 10)-2607);
    pos[2] = 19920 + t;
    return pos;
}

$('.countdown').countdown({
    callback: function(days, hours, minutes, seconds){

        var message = "";
        if(days > 0) message += days + " " + ( days == 1 ? 'день' : (days > 4 ? 'дней' : 'дня') ) + " ";
        message += (hours > 0 ? (hours > 9 ? hours : "0" + hours) : "00") + ":";
        message += (minutes > 0 ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":";
        message += (seconds > 0 ? (seconds > 9 ? seconds : "0" + seconds) : "00");

       return message;
    }
});