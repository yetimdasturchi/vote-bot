var $dtables = {};

(function ( $ ) {
    function generateDatableSettings(name, table ) {
        let settings = parseToFunction(table.data('params'), ([k,v]) =>
          (typeof v === 'string' && v.slice(0, 8) == "function") ? v.parseFunction() : v
        );

        if ( settings.hasOwnProperty('serverSide') ) {
            settings.initComplete = function() {
                var $searchInput = $("div.dataTables_filter input");

                $searchInput.unbind();

                $searchInput.bind("keyup", function(e) {
                    if(this.value.length == 0 || e.keyCode == 13) {
                        $dtables[name].search( this.value ).draw();
                    }
                });

                if ( settings.hasOwnProperty('initCompleted') ) {
                    settings.initCompleted();
                }
            }
        }

        return settings;
    }


    if ( $( "[data-datatable]" ).length ) {
        $( "[data-datatable]" ).each(function( index ) {
            var table = $( this );
            var name = table.attr( 'data-datatable' );
            $dtables[name] = $('[data-datatable='+name+']').DataTable(generateDatableSettings(name, table));
        });
    }
})(jQuery);