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
