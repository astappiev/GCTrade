/*
$('#el').spin(); // Creates a default Spinner using the text color of #el.
$('#el').spin('small'); // Creates a 'small' Spinner using the text color of #el.
$('#el').spin('large', '#fff'); // Creates a 'large' white Spinner.
$('#el').spin(false); // Stops and removes the spinner.
*/

(function(factory) {

  if (typeof exports == 'object') {
    factory(require('jquery'), require('spin'))
  }
  else if (typeof define == 'function' && define.amd) {
    define(['jquery', 'spin'], factory)
  }
  else {
    if (!window.Spinner) throw new Error('Spin.js not present')
    factory(window.jQuery, window.Spinner)
  }

}(function($, Spinner) {
  $.fn.spin = function(opts, color) {
    return this.each(function() {
      var $this = $(this),
        data = $this.data();

      if (data.spinner) {
        data.spinner.stop();
        delete data.spinner;
      }
      if (opts !== false) {
        opts = $.extend(
          { color: color || $this.css('color') },
          $.fn.spin.presets[opts] || opts
        )
        data.spinner = new Spinner(opts).spin(this)
      }
    })
  }

  $.fn.spin.presets = {
    tiny: { lines: 8, length: 2, width: 2, radius: 3 },
    small: { lines: 8, length: 5, width: 2, radius: 4 },
    large: { lines: 10, length: 8, width: 4, radius: 8 }
  }

}));