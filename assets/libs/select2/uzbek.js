/*! Select2 4.0.13 | https://github.com/select2/select2/blob/master/LICENSE.md */

!(function () {
  if (jQuery && jQuery.fn && jQuery.fn.select2 && jQuery.fn.select2.amd)
    var n = jQuery.fn.select2.amd;
  n.define("select2/i18n/uzbek", [], function () {
    function n(n, e, r, u) {
      return (n % 10 < 5 && n % 10 > 0 && n % 100 < 5) || n % 100 > 20
        ? n % 10 > 1
          ? r
          : e
        : u;
    }
    return {
      errorLoading: function () {
        return "Natijalarni yuklab bo'lmadi";
      },
      inputTooLong: function (e) {
        var n = e.input.length - e.maximum,
          r = "Iltimos, " + n + " dona begilini o'chiring";
        return 1 != n && (r += ""), r;
      },
      inputTooShort: function (e) {
        var r = e.minimum - e.input.length;
        return "Iltimos, kamida yana " + r + " belgi kiriting";
      },
      loadingMore: function () {
        return "Ma'lumotlarni yuklash…";
      },
      maximumSelected: function (e) {
        return "Siz " + e.maximum + " donadan ortiq elementlarni belgilay omaysiz";
      },
      noResults: function () {
        return "Ma'lumot topilmadi";
      },
      searching: function () {
        return "Qidirish...";
      },
      removeAllItems: function () {
        return "Barcha elementlarni o'chirish";
      },
    };
  }),
    n.define,
    n.require;
})();
