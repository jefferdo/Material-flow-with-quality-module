! function(o) {
    "use strict";
    var t = function() {
        this.$body = o("body")
    };
    o("#upcoming, #inprogress, #completed").sortable({
        connectWith: ".taskList",
        placeholder: "task-placeholder",
        forcePlaceholderSize: !0,
        update: function(t, e) {
            o("#todo").sortable("toArray"), o("#inprogress").sortable("toArray"), o("#completed").sortable("toArray")
        }
    }).disableSelection(), t.prototype.init = function() {}, o.KanbanBoard = new t, o.KanbanBoard.Constructor = t
}(window.jQuery),
function(o) {
    "use strict";
    o.KanbanBoard.init()
}(window.jQuery);