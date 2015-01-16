area_world = 24016*8016;

function price_separator(str, separator){
    return str.toString().replace(/\d(?=(?:\d{3})+\b)/g, "$&" + (separator||' '));
}

$(document).ready(function(){
    $('body').tooltip({
        selector: "[rel=tooltip]",
        placement: "bottom"
    });
});