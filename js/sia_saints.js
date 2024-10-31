/*
    Plugin Name: Saints
    Author URI: https://github.com/Siamajor
    License:     GPL2
*/
(function ($) {
  $.urlParam = function (name) {
    var results = new RegExp("[?&]" + name + "=([^&#]*)").exec(
      window.location.href
    );
    if (results == null) return "";
    else var Res = results[1] || 0;
    return Res;
  };
  var DateNew = $.urlParam("date");

  /*** атрибуты к ссылкам и класс */
  $(document).ready(function () {
    $('.description a, .descr_kondak a, .descr_tropar a, .descr_molitva a').addClass("modal-link");
    $("a[href^=#]").removeClass("modal-link");
    $("a[href^=#]").removeAttr("target");
    $('.description img').replaceWith('');
    $('.presentations div:eq(1)').addClass("pres-sedm");
  });
  /*** календарь */
  $(document).ready(function () {
    var datepicker = jQuery("#scalendar").datepicker({
      toggleSelected: true,
      inline: true,
      dateFormat: "yyyy-mm-dd",
      todayButton: new Date(),
      onSelect: function (dateText, inst) {
        window.location = '?date=' + dateText;
      }
    });
  });
})(jQuery);
