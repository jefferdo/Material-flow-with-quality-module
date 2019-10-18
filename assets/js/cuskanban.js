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



})(jQuery);
