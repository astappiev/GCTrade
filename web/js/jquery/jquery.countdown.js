(function($){
	
	var days	= 24*60*60,
		hours	= 60*60,
		minutes	= 60;
	
	$.fn.countdown = function(prop){

		var options = $.extend({
			callback	: function(){}
		},prop);

		var $labels = $(this);
		var left, d, h, m, s;
		
		(function tick(){

			$labels.each(function() {
				left = Math.floor($(this).attr('data-time') - Math.floor((new Date()).getTime() / 1000));

				if(left < 0){
					left = 0;
				}

				d = Math.floor(left / days);
				left -= d*days;

				h = Math.floor(left / hours);
				left -= h*hours;

				m = Math.floor(left / minutes);
				left -= m*minutes;

				s = left;

				$(this).text(options.callback(d, h, m, s));
			});

			setTimeout(tick, 1000);
		})();
		
		return this;
	};
})(jQuery);