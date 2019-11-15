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

    function hasMatch(JSON, key, value) {
        var hasMatch = false;
        for (var index = 0; index < JSON.length; ++index) {

            var line = JSON[index];

            if (line[key] == value) {
                hasMatch = true;
                break;
            }
        }
        return hasMatch;
    }

    function addNewPO(po, count) {
        $('#preloader').delay(350).fadeIn('slow');
        $('#status').fadeIn();
        for (let index in po) {
            var dfrd1 = $.Deferred();
            console.log(po[index]);
            $.ajax({
                type: "post",
                url: "/addNewPO",
                data: { "po": po[index] },
                success: function (response) {
                    console.log("Success! " + response);
                    if ((po.length - 1) == index) {
                        console.log("Resolving!");
                        dfrd1.resolve();
                        $('#status').fadeOut();
                        $('#preloader').delay(350).fadeOut('slow');
                    }
                    count++;
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }
        return dfrd1.promise();
    }

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
                csvResult = $.csv.toObjects(e.target.result);
                Swal.fire({
                    title: csvResult.length + ' Rolls detected, Ready to finish adding?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add as New Purchase Orders!'
                }).then((result) => {
                    if (result.value) {
                        $('#preloader').delay(350).fadeIn('slow');
                        $('#status').fadeIn();
                        csvResult.forEach(function (row, i) {
                            $.ajax({
                                type: "post",
                                url: "/addNewPO",
                                data: { "roll": row },
                                success: function (response) {
                                    console.log("Success! " + response);
                                    if ((po.length - 1) == index) {
                                        console.log("Resolving!");
                                        dfrd1.resolve();
                                        $('#status').fadeOut();
                                        $('#preloader').delay(350).fadeOut('slow');
                                    }
                                    count++;
                                },
                                error: function (error) {
                                    console.error(error);
                                }
                            });

                            console.log(row);
                            if ((csvResult.length - 1) == i) {
                                console.log("Resolving!");
                                $('#status').fadeOut();
                                $('#preloader').delay(350).fadeOut('slow');
                            }
                        });
                        Swal.fire(
                            'Added Successfully!',
                            'This page will be refreshed.',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        })
                    } else {
                        window.location.reload();
                    }
                })

            }
            reader.readAsText(e.target.files.item(0));
        }
    });

    $("#AddPONB").on("change", function (e) {

        var data = {
            "PO": [
            ]
        };

        var ext = $("#AddPONB").val().split(".").pop().toLowerCase();

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
                csvResult = $.csv.toObjects(e.target.result);
                csvResult.forEach(function (row, i) {

                    var ponoCSV = row.CustomerPONumber;

                    var colorNameCSV = row.color;
                    var sizeNameCSV = row.size;
                    var CutQtyCSV = Math.round(row.CutQty).toString();
                    var Qty = parseInt(row.Qty);

                    if (data.PO.length != 0) {
                        for (let index in data.PO) {
                            //console.log(data.PO[index]);
                            if (data.PO[index].PONO === ponoCSV) {
                                //console.log("Found: " + ponoCSV);
                                if (data.PO[index].Color.hasOwnProperty(row.color)) {
                                    //console.log("Color found " + row.color);
                                    data.PO[index].Color[colorNameCSV][sizeNameCSV] = CutQtyCSV;
                                    data.PO[index].Qty = parseInt(data.PO[index].Qty) + Qty;
                                } else {
                                    //console.log("New color " + row.color);
                                    let color = JSON.parse('{"' + colorNameCSV + '": {"' + sizeNameCSV + '":"' + CutQtyCSV + '"}}');
                                    $.extend(data.PO[index].Color, color)
                                    data.PO[index].Qty = parseInt(data.PO[index].Qty) + Qty;
                                }
                            }
                            else {
                                if (!hasMatch(data.PO, "PONO", ponoCSV)) {
                                    //console.log("New PO: " + row.CustomerPONumber);
                                    let ponew2 =
                                    {
                                        "PONO": row.CustomerPONumber,
                                        "Customer": row.customer,
                                        "Style": row.StyleNumber,
                                        "Qty": row.Qty,
                                        "Product": row.product,
                                        "Color": {}
                                    };
                                    let color = JSON.parse('{"' + colorNameCSV + '": {"' + sizeNameCSV + '":"' + CutQtyCSV + '"}}');
                                    ponew2.Color = color;
                                    data['PO'].push(ponew2);
                                }

                            }
                        }
                    } else {
                        //console.log("New PO: " + row.CustomerPONumber);
                        let ponew1 =
                        {
                            "PONO": row.CustomerPONumber,
                            "Customer": row.customer,
                            "Style": row.StyleNumber,
                            "Qty": row.Qty,
                            "Product": row.product,
                            "Color": {}
                        };
                        let color = JSON.parse('{"' + colorNameCSV + '": {"' + sizeNameCSV + '":"' + CutQtyCSV + '"}}');
                        ponew1.Color = color;
                        data['PO'].push(ponew1);
                    }
                });

                var count = data.PO.length;

                Swal.fire({
                    title: count + ' Purchase Orders detected, Ready to finish adding?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add as New Purchase Orders!'
                }).then((result) => {
                    if (result.value) {
                        addNewPO(data.PO, 0).done(function () {
                            Swal.fire(
                                'Added Successfully!',
                                'This page will be refreshed.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            })
                        });
                    } else {
                        window.location.reload();
                    }
                })
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
