(function ($) {
    $('#colorselect').on('change', function () {
        let color = JSON.parse(this.value);
        let html = "";
        let oqty = 0;
        $.each(color, function (indexInArray, valueOfElement) {
            html += "<option value=" + valueOfElement + ">" + indexInArray + "</option>";
            oqty += parseInt(valueOfElement);
        });
        $("#size").append(html);
        $("#sizer").show();
        $("#oqty").html(oqty);
        $("#oqtyf").val(oqty);
        $("#colorf").val($('#colorselect>option:selected').text());
    });
    $('#size').on('change', function () {
        let value = this.value;
        $("#qtyv").html(value);
        $("#iqtyf").val(value);
        $("#sizef").val($('#size>option:selected').text());
        $("#qtyr").show();
        $("#formsubmit").show();
    });
    $("#rqty").on("input", function (e) {
        let errorhtml = '<div class="pt-1"> <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> Possible value exceeded </div></div>';
        let rqty = parseInt(this.value);
        let iqty = parseInt($("#iqtyf").val());
        if (rqty > iqty || !$.isNumeric($(this).val())) {
            $("#errorbox").html(errorhtml);
            $(this).val("");
        }
        else {
            $("#errorbox").html("");
        }
    });

})(jQuery);

(function ($) {

    $("#AddMatN").on("click", function (e) {
        Swal.mixin({
            input: 'text',
            confirmButtonText: 'Next &rarr;',
            showCancelButton: true,
            progressSteps: ['1', '2', '3', '4']
        }).queue([
            'Enter Height (m)',
            'Enter Width (m)',
            'Enter Length (m)',
            'Enter Area (square m)'
        ]).then((result) => {
            if (result.value) {
                $(':button').prop('disabled', true);
                let dt = result.value;
                if (dt != null) {
                    try {
                        dt.forEach(element => {
                            if (!$.isNumeric(element)) {
                                throw new Error("Invalid Value: " + element)
                            }
                        });
                        let form = new FormData();
                        form.append("poid", $("#poid").val());
                        form.append("h", dt[0]);
                        form.append("w", dt[1]);
                        form.append("l", dt[2]);
                        $.ajax({
                            type: "post",
                            url: "/addRoll",
                            data: form,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $(':button').prop('disabled', false);
                                Swal.fire(
                                    'Confirm!',
                                    response,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                })
                            },
                            error: function (request, status, error) {
                                $(':button').prop('disabled', false);
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    html: '<pre><code> Something went wrong! ' +
                                        request.responseText +
                                        '</code></pre>',
                                })
                            }
                        });
                    } catch (error) {
                        $(':button').prop('disabled', false);
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong! ' + error,
                        })
                    }
                }
            }
        });
        $(':button').prop('disabled', false);
    });

    $("#AddMatNB").on("change", function (e) {

        var ext = $("#AddMatNB").val().split(".").pop().toLowerCase();

        if ($.inArray(ext, ["csv"]) == -1) {
            Swal.fire(
                'Import Process Canceled',
                'Proccess canceled due to a detected file error. You may try again!',
                'info'
            )
            return false;
        }

        if (e.target.files != undefined) {
            var reader = new FileReader();
            reader.onload = function (e) {
                csvResult = e.target.result.split(/\r|\n|\r\n/);
                csvResult.forEach(function (element, i) {
                    if (i !== 0) {
                        $('.csv').append(element + "<br/>");
                        try {
                            let form = new FormData();
                            form.append("poid", $("#poid").val());
                            form.append("h", dt[0]);
                            form.append("w", dt[1]);
                            form.append("l", dt[2]);
                            $.ajax({
                                type: "post",
                                url: "/addRoll",
                                data: form,
                                processData: false,
                                contentType: false,
                                success: function (response) {
                                    $(':button').prop('disabled', false);
                                    Swal.fire(
                                        'Confirm!',
                                        response,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    })
                                },
                                error: function (request, status, error) {
                                    $(':button').prop('disabled', false);
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        html: '<pre><code> Something went wrong! ' +
                                            request.responseText +
                                            '</code></pre>',
                                    })
                                }
                            });
                        } catch (error) {
                            $(':button').prop('disabled', false);
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong! ' + error,
                            })
                        }
                    }
                });

            }
            reader.readAsText(e.target.files.item(0));
        }
    });


})(jQuery);

(function ($) {

    $("#addWF").on("click", function (e) {
        Swal.mixin({
            input: 'text',
            confirmButtonText: 'Next &rarr;',
            showCancelButton: true
        }).queue([
            'Enter Shrinkage',
        ]).then((result) => {
            if (result.value) {
                $(':button').prop('disabled', true);
                let dt = result.value;
                if (dt != null) {
                    try {
                        dt.forEach(element => {
                            if (!$.isNumeric(element)) {
                                throw new Error("Invalid Value: " + element)
                            }
                        });
                        let form = new FormData();
                        form.append("poid", $("#poid").val());
                        form.append("s", dt[0]);
                        $.ajax({
                            type: "post",
                            url: "/addWF",
                            data: form,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $(':button').prop('disabled', false);
                                Swal.fire({
                                    type: 'success',
                                    title: 'Waterfall No: ' + response + " is created.",
                                    html: '<form action="/buildWF" method="post">' +
                                        '<input type="hidden" name="id" value="' + response + '">' +
                                        '<button class="btn btn-success" type="submit" >' +
                                        '<i class="mdi mdi-shopping mr-2 text-white  font-18 vertical-middle"></i>' +
                                        'Add Sequence' +
                                        '</button >' +
                                        '</form >',
                                    confirmButtonText: 'Skip',
                                }).then(() => {
                                    //location.reload();
                                })
                            },
                            error: function (request, status, error) {
                                $(':button').prop('disabled', false);
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    html: '<pre><code> Something went wrong! ' +
                                        request.responseText +
                                        '</code></pre>',
                                })
                            }
                        });
                    } catch (error) {
                        $(':button').prop('disabled', false);
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong! ' + error,
                        })
                    }
                }
            }
        });
        $(':button').prop('disabled', false);
    });

})(jQuery);

(function ($) {

    $("#addRollWF").on("click", function (e) {
        addRollWF("Scan Ready");
    });

    function addRollWF(e) {
        text = e;
        Swal.mixin({
            input: 'text',
            confirmButtonText: 'Next &rarr;',
            text: text,
            showCancelButton: true
        }).queue([
            'Enter/ Scan Roll No',
        ]).then((result) => {
            if (result.value) {
                $(':button').prop('disabled', true);
                let dt = result.value;
                if (dt != null) {
                    try {
                        let form = new FormData();
                        form.append("wfid", $("#wfid").val());
                        form.append("roid", dt[0]);
                        $.ajax({
                            type: "post",
                            url: "/addRollWF",
                            data: form,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $(':button').prop('disabled', false);
                                addRollWF(response);
                            },
                            error: function (request, status, error) {
                                $(':button').prop('disabled', false);
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    html: '<pre><code> Something went wrong! ' +
                                        request.responseText +
                                        '</code></pre>',
                                })
                            }
                        });
                    } catch (error) {
                        $(':button').prop('disabled', false);
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong! ' + error,
                        })
                    }
                }
            }
            //location.reload();
        });
        $(':button').prop('disabled', false);
    }

})(jQuery);

function showRoll(id) {

    Swal.fire({
        title: '<strong>Roll No:.' + id + '</strong>',
        type: 'info',
        html:
            'You can use <b>bold text</b>, ' +
            '<a href="//sweetalert2.github.io">links</a> ' +
            'and other HTML tags',
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Great!',
        confirmButtonAriaLabel: 'Thumbs up, great!',
        cancelButtonText:
            '<i class="fa fa-thumbs-down"></i>',
        cancelButtonAriaLabel: 'Thumbs down'
    })
}
