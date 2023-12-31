var $apexcharts = {};

(function ( $ ) {
	if ( $( "[data-apexchart]" ).length ) {
        $( "[data-apexchart]" ).each(function( index ) {
            let chart = $( this );
            let name = chart.attr( 'data-apexchart' );
            let params = parseToFunction(chart.data('params'), ([k,v]) =>
          		(typeof v === 'string' && v.slice(0, 8) == "function") ? v.parseFunction() : v
        	);
            ($apexcharts[name] = new ApexCharts($('[data-apexchart='+name+']').get(0), params)).render();
        });
    }
})(jQuery);