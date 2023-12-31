if (typeof String.prototype.parseFunction != 'function') {
    String.prototype.parseFunction = function () {
        var funcReg = /function *\(([^()]*)\)[ \n\t]*{(.*)}/gmi;
        var match = funcReg.exec(this.replace(/\n/g, ' '));

        if(match) {
            return new Function(match[1].split(','), match[2]);
        }

        return null;
    };
}

function parseToFunction(t, func) {
  switch (t?.constructor) {
    case Object:
      return Object.fromEntries(
        Object.entries(t).map(([k,v]) =>
          [k, func([k, parseToFunction(v, func)])]
        )
      )
    case Array:
      return t.map((v, k) => func([k, parseToFunction(v, func)]))
    default:
      return func([null, t])
  }
}

function objToFunc(obj) {
  return parseToFunction(obj, ([k,v]) =>
    (typeof v === 'string' && v.slice(0, 8) == "function") ? v.parseFunction() : v
  );
}

(function ( $ ) {
    
  $.fn.alterClass = function ( removals, additions ) {
      
      var self = this;
      
      if ( removals.indexOf( '*' ) === -1 ) {
          // Use native jQuery methods if there is no wildcard matching
          self.removeClass( removals );
          return !additions ? self : self.addClass( additions );
      }

      var patt = new RegExp( '\\s' + 
              removals.
                  replace( /\*/g, '[A-Za-z0-9-_]+' ).
                  split( ' ' ).
                  join( '\\s|\\s' ) + 
              '\\s', 'g' );

      self.each( function ( i, it ) {
          var cn = ' ' + it.className + ' ';
          while ( patt.test( cn ) ) {
              cn = cn.replace( patt, ' ' );
          }
          it.className = $.trim( cn );
      });

      return !additions ? self : self.addClass( additions );
  };

})( jQuery );