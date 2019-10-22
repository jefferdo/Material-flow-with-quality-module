! function (e) {
    "use strict";
    var i = function () { };
    i.prototype.init = function () {
        e("#humanfd-datepicker").flatpickr({
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        })
    }, e.FormPickers = new i, e.FormPickers.Constructor = i
}(window.jQuery),
    function (e) {
        "use strict";
        e.FormPickers.init()
    }(window.jQuery);