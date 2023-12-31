!(function (s) {
  "use strict";
  var e;
  function c() {
    for (
      var e = document
          .getElementById("topnav-menu-content")
          .getElementsByTagName("a"),
        t = 0,
        s = e.length;
      t < s;
      t++
    )
      "nav-item dropdown active" === e[t].parentElement.getAttribute("class") &&
        (e[t].parentElement.classList.remove("active"),
        null !== e[t].nextElementSibling &&
          e[t].nextElementSibling.classList.remove("show"));
  }
  function r(e) {
    1 == s("#light-mode-switch").prop("checked") && "light-mode-switch" === e
      ? (s("html").removeAttr("dir"),
        s("#dark-mode-switch").prop("checked", !1),
        s("#rtl-mode-switch").prop("checked", !1),
        s("#dark-rtl-mode-switch").prop("checked", !1),
        s("#bootstrap-style").attr("href", "assets/css/bootstrap.min.css"),
        s("#app-style").attr("href", "assets/css/app.min.css"),
        sessionStorage.setItem("is_visited", "light-mode-switch"))
      : 1 == s("#dark-mode-switch").prop("checked") && "dark-mode-switch" === e
      ? (s("html").removeAttr("dir"),
        s("#light-mode-switch").prop("checked", !1),
        s("#rtl-mode-switch").prop("checked", !1),
        s("#dark-rtl-mode-switch").prop("checked", !1),
        s("#bootstrap-style").attr("href", "assets/css/bootstrap-dark.min.css"),
        s("#app-style").attr("href", "assets/css/app-dark.min.css"),
        sessionStorage.setItem("is_visited", "dark-mode-switch"))
      : 1 == s("#rtl-mode-switch").prop("checked") && "rtl-mode-switch" === e
      ? (s("#light-mode-switch").prop("checked", !1),
        s("#dark-mode-switch").prop("checked", !1),
        s("#dark-rtl-mode-switch").prop("checked", !1),
        s("#bootstrap-style").attr("href", "assets/css/bootstrap-rtl.min.css"),
        s("#app-style").attr("href", "assets/css/app-rtl.min.css"),
        s("html").attr("dir", "rtl"),
        sessionStorage.setItem("is_visited", "rtl-mode-switch"))
      : 1 == s("#dark-rtl-mode-switch").prop("checked") &&
        "dark-rtl-mode-switch" === e &&
        (s("#light-mode-switch").prop("checked", !1),
        s("#rtl-mode-switch").prop("checked", !1),
        s("#dark-mode-switch").prop("checked", !1),
        s("#bootstrap-style").attr(
          "href",
          "assets/css/bootstrap-dark-rtl.min.css",
        ),
        s("#app-style").attr("href", "assets/css/app-dark-rtl.min.css"),
        s("html").attr("dir", "rtl"),
        sessionStorage.setItem("is_visited", "dark-rtl-mode-switch"));
  }
  function l() {
    document.webkitIsFullScreen ||
      document.mozFullScreen ||
      document.msFullscreenElement ||
      (console.log("pressed"), s("body").removeClass("fullscreen-enable"));
  }
  s("#side-menu").metisMenu(),
    s("#vertical-menu-btn").on("click", function (e) {
      e.preventDefault(),
        s("body").toggleClass("sidebar-enable"),
        992 <= s(window).width()
          ? s("body").toggleClass("vertical-collpsed")
          : s("body").removeClass("vertical-collpsed");
    }),
    s("#sidebar-menu a").each(function () {
      var e = window.location.href.split(/[?#]/)[0];
      this.href == e &&
        (s(this).addClass("active"),
        s(this).parent().addClass("mm-active"),
        s(this).parent().parent().addClass("mm-show"),
        s(this).parent().parent().prev().addClass("mm-active"),
        s(this).parent().parent().parent().addClass("mm-active"),
        s(this).parent().parent().parent().parent().addClass("mm-show"),
        s(this)
          .parent()
          .parent()
          .parent()
          .parent()
          .parent()
          .addClass("mm-active"));
    }),
    s(document).ready(function () {
      var e;
      0 < s("#sidebar-menu").length &&
        0 < s("#sidebar-menu .mm-active .active").length &&
        300 < (e = s("#sidebar-menu .mm-active .active").offset().top) &&
        ((e -= 300),
        s(".vertical-menu .simplebar-content-wrapper").animate(
          { scrollTop: e },
          "slow",
        ));
    }),
    s(".navbar-nav a").each(function () {
      var e = window.location.href.split(/[?#]/)[0];
      this.href == e &&
        (s(this).addClass("active"),
        s(this).parent().addClass("active"),
        s(this).parent().parent().addClass("active"),
        s(this).parent().parent().parent().addClass("active"),
        s(this).parent().parent().parent().parent().addClass("active"),
        s(this).parent().parent().parent().parent().parent().addClass("active"),
        s(this)
          .parent()
          .parent()
          .parent()
          .parent()
          .parent()
          .parent()
          .addClass("active"));
    }),
    s('[data-bs-toggle="fullscreen"]').on("click", function (e) {
      e.preventDefault(),
        s("body").toggleClass("fullscreen-enable"),
        document.fullscreenElement ||
        document.mozFullScreenElement ||
        document.webkitFullscreenElement
          ? document.cancelFullScreen
            ? document.cancelFullScreen()
            : document.mozCancelFullScreen
            ? document.mozCancelFullScreen()
            : document.webkitCancelFullScreen &&
              document.webkitCancelFullScreen()
          : document.documentElement.requestFullscreen
          ? document.documentElement.requestFullscreen()
          : document.documentElement.mozRequestFullScreen
          ? document.documentElement.mozRequestFullScreen()
          : document.documentElement.webkitRequestFullscreen &&
            document.documentElement.webkitRequestFullscreen(
              Element.ALLOW_KEYBOARD_INPUT,
            );
    }),
    document.addEventListener("fullscreenchange", l),
    document.addEventListener("webkitfullscreenchange", l),
    document.addEventListener("mozfullscreenchange", l),
    s(".right-bar-toggle").on("click", function (e) {
      s("body").toggleClass("right-bar-enabled");
    }),
    s(document).on("click", "body", function (e) {
      0 < s(e.target).closest(".right-bar-toggle, .right-bar").length ||
        s("body").removeClass("right-bar-enabled");
    }),
    (function () {
      if (document.getElementById("topnav-menu-content")) {
        for (
          var e = document
              .getElementById("topnav-menu-content")
              .getElementsByTagName("a"),
            t = 0,
            s = e.length;
          t < s;
          t++
        )
          e[t].onclick = function (e) {
            "#" === e.target.getAttribute("href") &&
              (e.target.parentElement.classList.toggle("active"),
              e.target.nextElementSibling.classList.toggle("show"));
          };
        window.addEventListener("resize", c);
      }
    })(),
    [].slice
      .call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      .map(function (e) {
        return new bootstrap.Tooltip(e);
      }),
    [].slice
      .call(document.querySelectorAll('[data-bs-toggle="popover"]'))
      .map(function (e) {
        return new bootstrap.Popover(e);
      }),
    [].slice.call(document.querySelectorAll(".offcanvas")).map(function (e) {
      return new bootstrap.Offcanvas(e);
    }),
    window.sessionStorage &&
      ((e = sessionStorage.getItem("is_visited"))
        ? (s(".right-bar input:checkbox").prop("checked", !1),
          s("#" + e).prop("checked", !0),
          r(e))
        : sessionStorage.setItem("is_visited", "light-mode-switch")),
    s(
      "#light-mode-switch, #dark-mode-switch, #rtl-mode-switch, #dark-rtl-mode-switch",
    ).on("change", function (e) {
      r(e.target.id);
    }),
    s("#password-addon").on("click", function () {
      0 < s(this).siblings("input").length &&
        ("password" == s(this).siblings("input").attr("type")
          ? s(this).siblings("input").attr("type", "input")
          : s(this).siblings("input").attr("type", "password"));
    }),
    s(window).on("load", function () {
      s("#status").fadeOut(), s("#preloader").delay(350).fadeOut("slow");
    }),
    Waves.init(),
    s("#checkAll").on("change", function () {
      s(".table-check .form-check-input").prop(
        "checked",
        s(this).prop("checked"),
      );
    }),
    s(".table-check .form-check-input").change(function () {
      s(".table-check .form-check-input:checked").length ==
      s(".table-check .form-check-input").length
        ? s("#checkAll").prop("checked", !0)
        : s("#checkAll").prop("checked", !1);
    });
})(jQuery);

(function ( $ ) {
  if( $( ".select2" ).length ){
    $( ".select2" ).each(function( index ) {
      let select = $( this );
      var params = {};
      if ( select.attr("data-params") ){
        params = parseToFunction(select.data('params'), ([k,v]) =>
          (typeof v === 'string' && v.slice(0, 8) == "function") ? v.parseFunction() : v
        );
      }
      select.select2(params);
    });  
  }

  if( $( ".timepicker" ).length ){
    $(".timepicker").timepicker({
      showMeridian: !1,
      icons: {
          up: "mdi mdi-chevron-up",
          down: "mdi mdi-chevron-down"
      },
      appendWidgetTo: ".timepicker-input-group"
    })
  }
  if( $( ".dropify" ).length ){
    $( ".dropify" ).each(function( index ) {
      let dropify = $( this );
      var params = {};
      if ( dropify.attr("data-params") ){
        params = parseToFunction(dropify.data('params'), ([k,v]) =>
          (typeof v === 'string' && v.slice(0, 8) == "function") ? v.parseFunction() : v
        );
      }
      dropify.dropify(params);
    }); 
  }

  if( $( ".bs-filter-modal .bs-filter-button" ).length ){
    $(document).on("click",".bs-filter-modal .bs-filter-button" ,function() {
        $dtables[ $(this).data('table') ].draw();
        $('.bs-filter-modal').modal('hide');
    });
  }

  $(document).on("submit", ".ajax-modal-form", function(e) {
    var form = $( this );
    var formdata = new FormData( this );
    var action = $(this).attr( 'action' );
    $.ajax({
      url: action,
      type: "POST",
      data:  formdata,
      contentType: false,
      cache: false,
      processData:false,
      beforeSend: function() {
        form.find( "button[type='submit']" ).html( '<i class="fas fa-spinner fa-spin"></i> ' + form.find( "button[type='submit']" ).text() );
        form.find( "button[type='submit']" ).prop( "disabled", true );
      },
      complete: function() {
        form.find( "button[type='submit']" ).find('i.fa-spin').remove();
        form.find( "button[type='submit']" ).prop( "disabled", false );
      },
      success: function(res){
        res = objToFunc( res );
        if ( res.status ) {
          
          if ( res.hasOwnProperty('reset') && res.reset ) {
            form.trigger("reset");
          }

          if ( res.hasOwnProperty('messages') ) {
            $.each(res.messages, function (key, val) {
              $.growl.warning({ title:"", message: val, location: "tr"});
            });
          }

          if ( res.hasOwnProperty('_callback') ) {
            res._callback();
          }
        }
      },
      error: function(e) {
        if (e.status == 400) {
          e.responseJSON = objToFunc( e.responseJSON );
          if ( !e.responseJSON.status ) {
            if ( e.responseJSON.hasOwnProperty('debug') ) {
              console.log(e.responseJSON);
            }

            if ( e.responseJSON.hasOwnProperty('messages') ) {
              $.each(e.responseJSON.messages, function (key, val) {
                $.growl.error({ title:"", message: val, location: "tr"});
              });
            }

            if ( e.responseJSON.hasOwnProperty('_callback') ) {
              e.responseJSON._callback();
            }
          }
        }
      }
    });
    e.preventDefault();
  });


  if ( $('.ajax-form').length ) {
    $( ".ajax-form" ).each(function( index ) {
          var form = $( this );
          var action = form.attr( 'action' );
          form.on('submit', function(e) {
            e.preventDefault();
            $.ajax({
              url: action,
              type: "POST",
              data:  new FormData(this),
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function() {
                form.find( "button[type='submit']" ).html( '<i class="fas fa-spinner fa-spin"></i> ' + form.find( "button[type='submit']" ).text() );
                form.find( "button[type='submit']" ).prop( "disabled", true );
              },
              complete: function() {
                form.find( "button[type='submit']" ).find('i.fa-spin').remove();
                form.find( "button[type='submit']" ).prop( "disabled", false );
              },
              success: function(res){
                res = objToFunc( res );
                if ( res.status ) {
                  
                  if ( res.hasOwnProperty('reset') && res.reset ) {
                    form.trigger("reset");
                  }

                  if ( res.hasOwnProperty('messages') ) {
                    $.each(res.messages, function (key, val) {
                      $.growl.warning({ title:"", message: val, location: "tr"});
                    });
                  }

                  if ( res.hasOwnProperty('_callback') ) {
                    res._callback();
                  }
                }
              },
              error: function(e) {
                if (e.status == 400) {
                  e.responseJSON = objToFunc( e.responseJSON );
                  if ( !e.responseJSON.status ) {
                    if ( e.responseJSON.hasOwnProperty('debug') ) {
                      console.log(e.responseJSON);
                    }

                    if ( e.responseJSON.hasOwnProperty('messages') ) {
                      $.each(e.responseJSON.messages, function (key, val) {
                        $.growl.error({ title:"", message: val, location: "tr"});
                      });
                    }

                    if ( e.responseJSON.hasOwnProperty('_callback') ) {
                      e.responseJSON._callback();
                    }
                  }
                }
              }
            });
          });
      });
  }

  $(document).on("click","[data-ajax-button]" ,function(e) {
    var url = $(this).data('ajax-button');
    var message = $(this).data('message');
    if ( confirm( message ) ) {
      $.ajax({
        type: "POST",
        url: url,
        success: function(res) {
          res = objToFunc( res );
          if ( res.status ) {

            if ( res.hasOwnProperty('messages') ) {
              $.each(res.messages, function (key, val) {
                $.growl.warning({ title:"", message: val, location: "tr"});
              });
            }

            if ( res.hasOwnProperty('_callback') ) {
              res._callback();
            }
          }
        },
        error: function(e){
          if (e.status == 400 || e.status == 405 || e.status == 401 || e.status == 404) {
            e.responseJSON = objToFunc( e.responseJSON );
            if ( !e.responseJSON.status ) {
              if ( e.responseJSON.hasOwnProperty('messages') ) {
                $.each(e.responseJSON.messages, function (key, val) {
                  $.growl.error({ title:"", message: val, location: "tr"});
                });
              }

              if ( e.responseJSON.hasOwnProperty('_callback') ) {
                e.responseJSON._callback();
              }
            }
          }
        }
      });
    }
  });

  $(document).on("click","[data-open-modal]" ,function(e) {
    var url = $(this).data('open-modal');
    var title = $(this).data('modal-title');
    var button = $(this);
    $.ajax({
      type: "GET",
      url: url,
      beforeSend: function() {
        button.html( '<i class="fas fa-spinner fa-spin"></i> ' + button.text() );
        button.prop( "disabled", true );
      },
      complete: function() {
        button.find('i.fa-spin').remove();
        button.prop( "disabled", false );
      },
      success: function(res) {
        res = objToFunc( res );
        if ( res.status ) {

          if ( res.hasOwnProperty('messages') ) {
            $.each(res.messages, function (key, val) {
              $.growl.warning({ title:"", message: val, location: "tr"});
            });
          }

          if ( res.hasOwnProperty('size') ) {
            $('.bs-ajax-modal .modal-dialog').removeClass('modal-sm modal-lg modal-xl');

            if ( res.size == 'small' ) {
              $('.bs-ajax-modal .modal-dialog').addClass('modal-sm');
            }else if ( res.size == 'large' ) {
              $('.bs-ajax-modal .modal-dialog').addClass('modal-lg');
            }else if ( res.size == 'extra' ) {
              $('.bs-ajax-modal .modal-dialog').addClass('modal-xl');
            }
          }

          if ( res.hasOwnProperty('html') ) {
            $('.bs-ajax-modal .modal-title').html( title );
            $('.bs-ajax-modal .modal-body').html( res.html );
            $('.bs-ajax-modal').modal('show');
          }

          if ( res.hasOwnProperty('_callback') ) {
            res._callback();
          }
        }
      },
      error: function(e){
        if (e.status == 400 || e.status == 405 || e.status == 401 || e.status == 404) {
          e.responseJSON = objToFunc( e.responseJSON );
          if ( !e.responseJSON.status ) {
            if ( e.responseJSON.hasOwnProperty('messages') ) {
              $.each(e.responseJSON.messages, function (key, val) {
                $.growl.error({ title:"", message: val, location: "tr"});
              });
            }

            if ( e.responseJSON.hasOwnProperty('_callback') ) {
              e.responseJSON._callback();
            }
          }
        }
      }
    });
  });

  $(document).on("click","[data-export-button]" ,function(e) {
    var url = $(this).data('export-button');
    $.ajax({
      type: "POST",
      url: url,
      success: function(res) {
        res = objToFunc( res );
        if ( res.status ) {

          if ( res.hasOwnProperty('messages') ) {
            $.each(res.messages, function (key, val) {
              $.growl.warning({ title:"", message: val, location: "tr"});
            });
          }

          if ( res.hasOwnProperty('html') ) {
            $('#export_data').html(res.html);
            TableToExcel.convert(document.querySelector("#export_data"), {
              name: 'bot_'+Math.round(new Date().getTime()/1000)+'.xlsx',
              sheet: {
                name: "Ro'yxat"
              }
            });
          }

          if ( res.hasOwnProperty('_callback') ) {
            res._callback();
          }
        }
      },
      error: function(e){
        if (e.status == 400 || e.status == 405 || e.status == 401 || e.status == 404) {
          e.responseJSON = objToFunc( e.responseJSON );
          if ( !e.responseJSON.status ) {
            if ( e.responseJSON.hasOwnProperty('messages') ) {
              $.each(e.responseJSON.messages, function (key, val) {
                $.growl.error({ title:"", message: val, location: "tr"});
              });
            }

            if ( e.responseJSON.hasOwnProperty('_callback') ) {
              e.responseJSON._callback();
            }
          }
        }
      }
    });
  });

  $(document).on("click","[data-location]" ,function(e) {
    var url = $(this).data('location');
    window.location.href = url;
  });

  $(document).on("click","[data-toggle]" ,function(e) {
    let content = $(this).data('toggle');
    $(content).toggleClass('d-none');
  });

  var table_search_timer;
  $(document).on("keyup", "[data-table-search]" ,function(e) {
    var key = e.which;

    var table = $(this).data('table-search');
    var query = $(this).val();

    if(key == 13) {
      $dtables[table].search( query ).draw();
    }else{
      if(table_search_timer){ clearTimeout(table_search_timer);}

      table_search_timer = setTimeout(function(query) {
        $dtables[table].search( query ).draw();
      }, 2000, query);
    }
  });

  $(document).on("click", "[data-reload-table]" ,function(e) {
    var table = $(this).data('reload-table');
    $dtables[table].ajax.reload();
  });

  $(document).on("click", "[data-send-notification]" ,function(e) {
    var id = $(this).data('send-notification');
    $('#sendModal [name="template"]').val(id);
    $('#sendModal').modal('show');
  });

  function checkbox_counter( that ) {
    var parent = $(that).parent().parent().parent().parent();
    
    if ( $(that).parent().parent().parent().hasClass('members-list') || $(that).parent().parent().parent().hasClass('scroll-list') ) {
      parent = parent.parent();
    }
    
    var text = parent.find('button.dropdown-toggle span').text();
    var checked = parent.find('.form-check-input:checked').length;

    const regex = /\(\d+\)/i;
    text = text.replace(regex, '');

    if ( checked > 0 ) {
      text = text + ' ('+checked+')';
    }

    parent.find('button.dropdown-toggle span').text( text );
  }

  $(document).on("change", ".question-filter .form-check-input" ,function(e) {
    checkbox_counter( this );
    e.stopPropagation();
  });

  if ( $('.question-filter .form-check-input').length ) {
    $(".question-filter .form-check-input").each(function(i, e) {
      checkbox_counter( e );
    });
  }

  $(document).on("click", ".client-export-question" ,function(e) {
    var data = new FormData();
    var ids = [];
    $(".question-filter .filtered_poll_ids:checked").each(function(i, e) {
      ids.push($(this).val());
    });
    data.append('poll_ids', ids.join("|"));

    var qstatus = [];
    $(".question-filter .filtered_qstatus:checked").each(function(i, e) {
      qstatus.push($(this).val());
    });
    data.append('qstatus', qstatus.join("|"));

    let term = $('[data-table-search="client_questions"]').val();
    data.append('term', term);

    $.ajax({
      url: '/client/question/export',
      type: "POST",
      data:  data,
      contentType: false,
      cache: false,
      processData:false,
      beforeSend: function() {
        $( ".question-filter .client-export-question" ).html( '<i class="fas fa-spinner fa-spin"></i> ' + $( ".question-filter .client-export-question" ).text() );
        $( ".question-filter .client-export-question" ).prop( "disabled", true );
      },
      complete: function() {
        $( ".question-filter .client-export-question" ).find('i.fa-spin').remove();
        $( ".question-filter .client-export-question" ).prop( "disabled", false );
      },
      success: function(res){
        res = objToFunc( res );
        if ( res.status ) {
          
          if ( res.hasOwnProperty('messages') ) {
            $.each(res.messages, function (key, val) {
              $.growl.warning({ title:"", message: val, location: "tr"});
            });
          }

          if ( res.hasOwnProperty('_callback') ) {
            res._callback();
          }
        }
      },
      error: function(e) {
        if (e.status == 400) {
          e.responseJSON = objToFunc( e.responseJSON );
          if ( !e.responseJSON.status ) {
            if ( e.responseJSON.hasOwnProperty('debug') ) {
              console.log(e.responseJSON);
            }

            if ( e.responseJSON.hasOwnProperty('messages') ) {
              $.each(e.responseJSON.messages, function (key, val) {
                $.growl.error({ title:"", message: val, location: "tr"});
              });
            }

            if ( e.responseJSON.hasOwnProperty('_callback') ) {
              e.responseJSON._callback();
            }
          }
        }
      }
    });

    e.stopPropagation();
  });

  $(document).on("click", ".client-export-votes" ,function(e) {
    var data = new FormData();
    
    var vstatus = [];
    $(".question-filter .filtered_vstatus:checked").each(function(i, e) {
      vstatus.push($(this).val());
    });
    data.append('vstatus', vstatus.join("|"));

    var vnomination = [];
    $(".question-filter .filtered_vnomination:checked").each(function(i, e) {
      vnomination.push($(this).val());
    });
    data.append('vnomination', vnomination.join("|"));

    var vmember = [];
    $(".question-filter .filtered_vmember:checked").each(function(i, e) {
      vmember.push($(this).val());
    });
    data.append('vmember', vmember.join("|"));

    let term = $('[data-table-search="client_votes"]').val();
    data.append('term', term);

    $.ajax({
      url: '/client/votes/export',
      type: "POST",
      data:  data,
      contentType: false,
      cache: false,
      processData:false,
      beforeSend: function() {
        $( ".question-filter .client-export-votes" ).html( '<i class="fas fa-spinner fa-spin"></i> ' + $( ".question-filter .client-export-votes" ).text() );
        $( ".question-filter .client-export-votes" ).prop( "disabled", true );
      },
      complete: function() {
        $( ".question-filter .client-export-votes" ).find('i.fa-spin').remove();
        $( ".question-filter .client-export-votes" ).prop( "disabled", false );
      },
      success: function(res){
        res = objToFunc( res );
        if ( res.status ) {
          
          if ( res.hasOwnProperty('messages') ) {
            $.each(res.messages, function (key, val) {
              $.growl.warning({ title:"", message: val, location: "tr"});
            });
          }

          if ( res.hasOwnProperty('_callback') ) {
            res._callback();
          }
        }
      },
      error: function(e) {
        if (e.status == 400) {
          e.responseJSON = objToFunc( e.responseJSON );
          if ( !e.responseJSON.status ) {
            if ( e.responseJSON.hasOwnProperty('debug') ) {
              console.log(e.responseJSON);
            }

            if ( e.responseJSON.hasOwnProperty('messages') ) {
              $.each(e.responseJSON.messages, function (key, val) {
                $.growl.error({ title:"", message: val, location: "tr"});
              });
            }

            if ( e.responseJSON.hasOwnProperty('_callback') ) {
              e.responseJSON._callback();
            }
          }
        }
      }
    });

    e.stopPropagation();
  });

  $(document).on("click", ".client-export-users" ,function(e) {
    var data = new FormData();
    
    var ulanguage = [];
    $(".question-filter .filtered_language:checked").each(function(i, e) {
      ulanguage.push($(this).val());
    });
    data.append('ulanguage', ulanguage.join("|"));

    var ucity = [];
    $(".question-filter .filtered_ucity:checked").each(function(i, e) {
      ucity.push($(this).val());
    });
    data.append('ucity', ucity.join("|"));

    var ugender = [];
    $(".question-filter .filtered_ugender:checked").each(function(i, e) {
      ugender.push($(this).val());
    });
    data.append('ugender', ugender.join("|"));

    let term = $('[data-table-search="client_users"]').val();
    data.append('term', term);

    $.ajax({
      url: '/client/users/export',
      type: "POST",
      data:  data,
      contentType: false,
      cache: false,
      processData:false,
      beforeSend: function() {
        $( ".question-filter .client-export-users" ).html( '<i class="fas fa-spinner fa-spin"></i> ' + $( ".question-filter .client-export-users" ).text() );
        $( ".question-filter .client-export-users" ).prop( "disabled", true );
      },
      complete: function() {
        $( ".question-filter .client-export-users" ).find('i.fa-spin').remove();
        $( ".question-filter .client-export-users" ).prop( "disabled", false );
      },
      success: function(res){
        res = objToFunc( res );
        if ( res.status ) {
          
          if ( res.hasOwnProperty('messages') ) {
            $.each(res.messages, function (key, val) {
              $.growl.warning({ title:"", message: val, location: "tr"});
            });
          }

          if ( res.hasOwnProperty('_callback') ) {
            res._callback();
          }
        }
      },
      error: function(e) {
        if (e.status == 400) {
          e.responseJSON = objToFunc( e.responseJSON );
          if ( !e.responseJSON.status ) {
            if ( e.responseJSON.hasOwnProperty('debug') ) {
              console.log(e.responseJSON);
            }

            if ( e.responseJSON.hasOwnProperty('messages') ) {
              $.each(e.responseJSON.messages, function (key, val) {
                $.growl.error({ title:"", message: val, location: "tr"});
              });
            }

            if ( e.responseJSON.hasOwnProperty('_callback') ) {
              e.responseJSON._callback();
            }
          }
        }
      }
    });

    e.stopPropagation();
  });

  $(document).on("click", ".question-filter .question-resend" ,function(e) {
    if ( confirm('Siz chindan ham savollarni barchaga qayta yubormoqchimisiz?') ) {
      var data = new FormData();
      var ids = [];
      $(".question-filter .filtered_poll_ids:checked").each(function(i, e) {
        ids.push($(this).val());
      });
      data.append('poll_ids', ids.join("|"));

      let term = $('[data-table-search="client_questions"]').val();

      data.append('term', term);

      $.ajax({
        url: '/client/question/resend',
        type: "POST",
        data:  data,
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function() {
          $( ".question-filter .question-resend" ).html( '<i class="fas fa-spinner fa-spin"></i> ' + $( ".question-filter .question-resend" ).text() );
          $( ".question-filter .question-resend" ).prop( "disabled", true );
        },
        complete: function() {
          $( ".question-filter .question-resend" ).find('i.fa-spin').remove();
          $( ".question-filter .question-resend" ).prop( "disabled", false );
        },
        success: function(res){
          res = objToFunc( res );
          if ( res.status ) {
            
            if ( res.hasOwnProperty('messages') ) {
              $.each(res.messages, function (key, val) {
                $.growl.warning({ title:"", message: val, location: "tr"});
              });
            }

            if ( res.hasOwnProperty('_callback') ) {
              res._callback();
            }
          }
        },
        error: function(e) {
          if (e.status == 400) {
            e.responseJSON = objToFunc( e.responseJSON );
            if ( !e.responseJSON.status ) {
              if ( e.responseJSON.hasOwnProperty('debug') ) {
                console.log(e.responseJSON);
              }

              if ( e.responseJSON.hasOwnProperty('messages') ) {
                $.each(e.responseJSON.messages, function (key, val) {
                  $.growl.error({ title:"", message: val, location: "tr"});
                });
              }

              if ( e.responseJSON.hasOwnProperty('_callback') ) {
                e.responseJSON._callback();
              }
            }
          }
        }
      });
    }
  });

  $(document).on("keyup", ".filtered_member" ,function(e) {
    var query = $(this).val();
    
    if ( query.length < 2 ) {
      $('.members-list a').show();
      return;
    }

    query = query.toLowerCase();

    $('.members-list a').each(function () {
      let text = $(this).find('.form-check-label').text();
      if ( text.length > 0 && text.toLowerCase().includes(query) ) {
        $(this).next().show();
      }else {
        $(this).hide();
      }
    });

  });

  if( $('.edit-additiona-info').length ){
    $.fn.editableform.buttons = '<button type="submit" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="mdi mdi-check"></i></button><button type="button" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="mdi mdi-close"></i></button>';
    $(".edit-additiona-info").editable({
      mode: "inline",
      type: "text",
      autotext: 'never',
      emptytext: function (value, row) {
        return this.getAttribute('data-empty');
      },
      ajaxOptions: {
          type: 'post'
      },
      success: function(res, newValue) {
          if ( res.status ) {
              if ( res.hasOwnProperty('messages') ) {
                  $.each(res.messages, function (key, val) {
                      $.growl.warning({ title:"", message: val, location: "tr"});
                  });
              }
          }
      },
    });
  }

  if ( $('.save-infographic').length ) {
    $(document).on('click', '.save-infographic', function(e) {
      $("head").append('<style id="html2canvas_no_transitions" type="text/css">* {transition: none !important;} .highcharts-map-navigation{display:none;}</style>');
      html2canvas($(".save-infographic-content")[0], {allowTaint: false, removeContainer: true, x: 0, y: 0,scale: 2}).then(canvas => {
        canvas.toBlob(function(blob) {
          saveAs(blob, "bot_" + (new Date().toJSON().slice(0,16).replace('T', '-').replace(':', '-')) + ".png"); 
        });
      }).finally(() => $('#html2canvas_no_transitions').remove());
      /*html2canvas($(".save-infographic-content")[0], {
          onrendered: function(canvas) {
              theCanvas = canvas;


              canvas.toBlob(function(blob) {
                  saveAs(blob, "Dashboard.png"); 
              });
          }
      });*/
    });
  }

  if ( $('#vectormap').length ) {
    if ( $('.ct-table').length ) {
      $('#vectormap').css('height', $('.ct-table').height() );
    }

    var mapdata = $('#vectormap').data('mapdata');

    var getMapPoint = function function_name(key) {
      for (var i = 0; i < mapdata.length - 1; i++) {
        if ( mapdata[i][0] == key ) {
          return mapdata[i];
        }
      }

      return false;
    };

    (async () => {

        const topology = await fetch(
            '/assets/libs/highmaps/uz-all.topo.json'
        ).then(response => response.json());

        Highcharts.mapChart('vectormap', {
            chart: {
                map: topology
            },

            title: {
                text: ''
            },

            mapNavigation: {
                enabled: true,
                buttonOptions: {
                    verticalAlign: 'bottom'
                }
            },

            legend: {enabled:false},

            colorAxis: {
                min: 0
            },

            colors: ['#f2f2f2'],
            tooltip: {
              followPointer: false,
              backgroundColor: '#3881ee',
              borderWidth: 1,
              borderColor: '#3881ee',
              borderRadius: 5,
              formatter: function () {
                let data = getMapPoint(this.point['hc-key']);
                if ( data == false ) return;
                return '<div style="color: #fff"><b>'+ this.point.name +':</b><br><br>'  +
                '<span style="'+ 'tooltipStyle' + '">O\'zbek: ' + data[1] + '</span><br>' +
                '<span style="'+ 'tooltipStyle' + '">Rus: ' + data[2] + '</span><br>' + 
                '<span style="'+ 'tooltipStyle' + '">Aniqlanmagan: ' + data[3] + '</span><div>';
              }
            },
            series: [{
              data: mapdata,
              name: 'Foydalanuvchilar',
              states: {
                hover: {
                  color: '#f082ac'
                }
              },
              dataLabels: {
                enabled: true,
                format: '{point.name}'
              }
            }]
        });

    })();

  }

  if ( $('#vectormap2').length ) {
    if ( $('.ct-table').length ) {
      $('#vectormap2').css('height', $('.ct-table').height() );
    }

    var mapdata = $('#vectormap2').data('mapdata');

    var getMapPoint = function function_name(key) {
      for (var i = 0; i < mapdata.length - 1; i++) {
        if ( mapdata[i][0] == key ) {
          return mapdata[i];
        }
      }

      return false;
    };

    (async () => {

        const topology = await fetch(
            '/assets/libs/highmaps/uz-all.topo.json'
        ).then(response => response.json());

        Highcharts.mapChart('vectormap2', {
            chart: {
                map: topology
            },

            title: {
                text: ''
            },

            mapNavigation: {
                enabled: true,
                buttonOptions: {
                    verticalAlign: 'bottom'
                }
            },

            legend: {enabled:false},

            colorAxis: {
                min: 0
            },

            colors: ['#f2f2f2'],
            tooltip: {
              followPointer: false,
              backgroundColor: '#3881ee',
              borderWidth: 1,
              borderColor: '#3881ee',
              borderRadius: 5,
              formatter: function () {
                let data = getMapPoint(this.point['hc-key']);
                if ( data == false ) return;
                data[1] = data[1] + data[3];
                return '<div style="color: #fff"><b>'+ this.point.name +':</b><br><br>'  +
                '<span style="'+ 'tooltipStyle' + '">O\'zbek: ' + data[1] + '</span><br>' +
                '<span style="'+ 'tooltipStyle' + '">Rus: ' + data[2] + '</span><div>';
              }
            },
            series: [{
              data: mapdata,
              name: 'Foydalanuvchilar',
              states: {
                hover: {
                  color: '#f082ac'
                }
              },
              dataLabels: {
                enabled: true,
                format: '{point.name}'
              }
            }]
        });

    })();

  }

  $(document).on("click", "[data-reload-infographic]" ,function(e) {
    var data = new FormData();
    
    $(".filtered_vnomination:checked").each(function(i, e) {
      data.append('nomination[]', $(this).val());
    });
    

    var vmember = [];
    $(".filtered_vmember:checked").each(function(i, e) {
      data.append('member[]', $(this).val());
    });

    var vmember = [];
    $(".filtered_vstatus:checked").each(function(i, e) {
      data.append('status[]', $(this).val());
    });

    if ( Array.from(data.keys()).length > 0 ) {
      let queryString = new URLSearchParams( data ).toString();
      window.location.href = document.location.origin + document.location.pathname + '?' + queryString;
    }else{
      window.location.href = document.location.origin + document.location.pathname;
    }

  });

  $(document).on("change", ".infographic-content .form-check-input" ,function(e) {
    var that = this;
    var parent = $(that).parent().parent().parent().parent();
    
    if ( $(that).parent().parent().parent().hasClass('members-list') || $(that).parent().parent().parent().hasClass('scroll-list') ) {
      parent = parent.parent();
    }
    
    parent.find('button.dropdown-toggle').prop( "disabled", true );
    var text = parent.find('button.dropdown-toggle span').text();

    const regex = /\(\d+\)/i;
    text = text.replace(regex, '');

    parent.find('button.dropdown-toggle span').html( '<i class="fas fa-spinner fa-spin"></i> ' + text );

    e.stopPropagation();
  });

  if ( $('.infographic-content .form-check-input').length ) {
    $(".infographic-content .form-check-input").each(function(i, e) {
      checkbox_counter( e );
    });
  }

})(jQuery);