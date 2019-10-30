$(document).ready(function () {
    $("#basic-datatable").DataTable({
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    });

    var a = $("#datatable-buttons").DataTable({
        lengthChange: !1,
        buttons: ["copy", "print", "pdf"],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    });
    a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)")

    $("#datatable-buttonsMI").DataTable({
        lengthChange: !1,
        buttons: ["copy", "print", "pdf"],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    }).buttons().container().appendTo("#datatable-buttonsMI_wrapper .col-md-6:eq(0)");

    $("#datatable-buttonsCT").DataTable({
        lengthChange: !1,
        buttons: ["copy", "print", "pdf"],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    }).buttons().container().appendTo("#datatable-buttonsCT_wrapper .col-md-6:eq(0)");

    $("#datatable-buttonsSW").DataTable({
        lengthChange: !1,
        buttons: ["copy", "print", "pdf"],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    }).buttons().container().appendTo("#datatable-buttonsSW_wrapper .col-md-6:eq(0)");

    $("#selection-datatable").DataTable({
        select: {
            style: "multi"
        },
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    }), $("#key-datatable").DataTable({
        keys: !0,
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    })
});