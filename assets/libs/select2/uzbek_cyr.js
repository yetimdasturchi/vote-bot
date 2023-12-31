/*! Select2 4.0.13 | https://github.com/select2/select2/blob/master/LICENSE.md */

!(function () {
  if (jQuery && jQuery.fn && jQuery.fn.select2 && jQuery.fn.select2.amd)
    var n = jQuery.fn.select2.amd;
  n.define("select2/i18n/uzbek_cyr", [], function () {
    function n(n, e, r, u) {
      return (n % 10 < 5 && n % 10 > 0 && n % 100 < 5) || n % 100 > 20
        ? n % 10 > 1
          ? r
          : e
        : u;
    }
    return {
      errorLoading: function () {
        return "Натижаларни юклаб бўлмади";
      },
      inputTooLong: function (e) {
        var n = e.input.length - e.maximum,
          r = "Илтимос, " + n + " дона бегилини ўчиринг";
        return 1 != n && (r += ""), r;
      },
      inputTooShort: function (e) {
        var r = e.minimum - e.input.length;
        return "Илтимос, камида яна " + r + " белги киритинг";
      },
      loadingMore: function () {
        return "Маълумотларни юклаш…";
      },
      maximumSelected: function (e) {
        return "Сиз " + e.maximum + " донадан ортиқ элементларни белгилай омайсиз";
      },
      noResults: function () {
        return "Маълумот топилмади";
      },
      searching: function () {
        return "Қидириш...";
      },
      removeAllItems: function () {
        return "Барча элементларни ўчириш";
      },
    };
  }),
    n.define,
    n.require;
})();
