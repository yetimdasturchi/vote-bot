$(document).ready(function () {
  "use strict";

  if ( $(".repeater").attr("data-init") ){
    var initEmpty = ($(".repeater").attr("data-init") === 'true');
  }else{
    var initEmpty = true;
  }

  if ( $(".repeater").attr("data-sorting") ){
    var sorting = ($(".repeater").attr("data-sorting") === 'true');
  }else{
    var sorting = false;
  }

  var options_repeater = $(".repeater");

  options_repeater.repeater({
  	initEmpty: initEmpty,
    show: function () {
      $(this).slideDown();
    },
    hide: function (e) {
      $(this).slideUp(e);
    },
    ready: function (setIndexes) {
    	if( sorting ){
        $( '[data-repeater-list]' ).on('drop', setIndexes);
      }
    },
  });

  if ( sorting ) {
    $( '[data-repeater-list]' ).sortable({
      axis: "y",
      cursor: 'pointer',
      opacity: 0.5
    }).disableSelection();
  }
});
