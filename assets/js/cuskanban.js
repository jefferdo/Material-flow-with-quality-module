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
            progressSteps: ['1', '2', '3']
        }).queue([
            'Enter Height (m)',
            'Enter Width (m)',
            'Enter Length (m)'
        ]).then((result) => {
            let dt = result.value;
            if (dt != null) {
                try {
                    dt.forEach(element => {
                        if (!$.isNumeric(element)) {
                            throw new Error("Invalid Value")
                        }
                        else if (dt.length > 0) {
                            let form = new FormData();
                            form.append("poid", $("#poid").val());
                            $.ajax({
                                type: "post",
                                url: "/addRoll",
                                data: form,
                                dataType: "dataType",
                                success: function (response) {

                                }
                            });

                            Swal.fire({
                                title: 'All done!',
                                html:
                                    'Your answers: <pre><code>' +
                                    dt +
                                    '</code></pre>',
                                confirmButtonText: 'Lovely!'
                            })
                        }
                        else {

                        }
                    });
                } catch (error) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! ' + error,
                    })
                }
            }
        });
    });

})(jQuery);
