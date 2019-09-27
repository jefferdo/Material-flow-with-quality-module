(function ($) {
    $('#barcodeBox').css('display', 'none');

    let state = true;
    $('#bCodeS').on("click", function (e) {
        if (state) {
            $('#manBox').css('display', 'none');
            $('#barcodeBox').css('display', 'block');
            $('#bIconS').removeClass('mdi-barcode');
            $('#bIconS').addClass('mdi-close');
            $('#barV').focus();
            state = false;
        } else {
            $('#manBox').css('display', 'block');
            $('#barcodeBox').css('display', 'none');
            $('#bIconS').removeClass('mdi-close');
            $('#bIconS').addClass('mdi-barcode');
            state = true;
        }
    });
})(jQuery);

(function ($) {
    $('#poid').focus();
})(jQuery);

(function ($) {
    $.fn.numKey = function (options) {
        if ($(window).width() <= 640) {

            var defaults = {
                limit: 100,
                disorder: false
            }
            var options = $.extend({}, defaults, options);
            var input = $(this);
            var nums = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
            if (options.disorder) {
                nums.sort(function () {
                    return 0.5 - Math.random();
                });
            }
            var html = '\
		<div class="fuzy-numKey">\
		<div class="fuzy-numKey-active">﹀</div>\
		<table>\
		<tr>\
		<td class="fuzy-numKey-lightgray fuzy-numKey-active">' + nums[7] + '</td>\
		<td class="fuzy-numKey-lightgray fuzy-numKey-active">' + nums[8] + '</td>\
		<td class="fuzy-numKey-lightgray fuzy-numKey-active">' + nums[9] + '</td>\
		</tr>\
		<tr>\
		<td class="fuzy-numKey-lightgray fuzy-numKey-active">' + nums[4] + '</td>\
		<td class="fuzy-numKey-lightgray fuzy-numKey-active">' + nums[5] + '</td>\
		<td class="fuzy-numKey-lightgray fuzy-numKey-active">' + nums[6] + '</td>\
		</tr>\
		<tr>\
		<td class="fuzy-numKey-lightgray fuzy-numKey-active">' + nums[1] + '</td>\
		<td class="fuzy-numKey-lightgray fuzy-numKey-active">' + nums[2] + '</td>\
		<td class="fuzy-numKey-lightgray fuzy-numKey-active">' + nums[3] + '</td>\
		</tr>\
		<tr>\
		<td class="fuzy-numKey-darkgray fuzy-numKey-active">Clear</td>\
		<td class="fuzy-numKey-lightgray fuzy-numKey-active">' + nums[0] + '</td>\
		<td class="fuzy-numKey-darkgray fuzy-numKey-active">&larr;</td>\
		</tr>\
		</table>\
		<style type="text/css">\
		* {\
			padding: 0 0;\
			margin: 0 0;\
		}\
		.fuzy-numKey {\
			position: absolute;\
			bottom: 0;\
			display: none;\
			background-color: white;\
			text-align: center;\
			padding: 3%;\
			height: 50%;\
			z-index: 999;\
		}\
		.fuzy-numKey div {\
			height: 10%;\
			width: 100%;\
			font-weight: bold;\
			font-family: "Roboto";\
			background-color: #6658dd;\
			color: #fff;\
			margin-bottom: 2%;\
			border-radius: 12px;\
            line-height: 200%;\
		}\
		.fuzy-numKey table {\
			width: 100%;\
			height: 88%;\
		}\
        .fuzy-numKey td {\
            font-size: 100%;\
			font-weight: bold;\
			font-family: "Roboto";\
            width: 33%;\
            font-size:20px;\
            height: auto;\
			color: #fff;\
			border-radius: 12px;\
		}\
		.fuzy-numKey-darkgray{\
			background: red;\
		}\
		.fuzy-numKey-lightgray{\
            background: #6658dd;\
		}\
		.fuzy-numKey-active:active {\
			background: deepskyblue;\
		}\
		</style>\
		</div>';
            input.on("click", function () {
                $(this).attr('readonly', 'readonly');
                $(".fuzy-numKey").remove();
                $('body').append(html);
                $(".fuzy-numKey").show(100);
                $(".fuzy-numKey table tr td").on("click", function () {
                    if (isNaN($(this).text())) {
                        if ($(this).text() == 'Clear') {
                            input.val('');
                        } else {
                            input.val(input.val().substring(0, input.val().length - 1));
                        }
                    } else {
                        input.val(input.val() + $(this).text());
                        if (input.val().length >= options.limit) {
                            input.val(input.val().substring(0, options.limit));
                            remove();
                        }
                    }
                });
                $(".fuzy-numKey div").on("click", function () {
                    remove();
                });
            });

            function remove() {
                $(".fuzy-numKey").hide(100, function () {
                    $(".fuzy-numKey").remove();
                });
                input.removeAttr('readonly');
            }
        }
    }



    $("#uid").numKey({
        limit: 12,
        disorder: true
    });

    $("#password").numKey({
        limit: 12,
        disorder: true
    });


})(jQuery);

(function ($) {
    $("#btnqa_accept").on('click', function (e) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Confirm it!'
        }).then((result) => {
            if (result.value) {
                var form = new FormData();
                form.append('id', $("#id").val());
                form.append('csrfk', $("#csrfk").val());
                form.append('stage', $("#stage").val());
                $.ajax({
                    type: "post",
                    url: "/qa",
                    data: form,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        Swal.fire(
                            'Confirm!',
                            response,
                            'success'
                        ).then(() => {
                            window.location.replace("/")
                        })
                    },
                    error: function (error) {
                        alert('error');
                        
                        console.log(error);
                    }
                });
            }
        })
    });

    $("#btnqa_reject").on("click", function (e) {
        Swal.fire({
            title: 'Report Reason [1 - 99]',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            showLoaderOnConfirm: true,
            preConfirm: (key) => {
                if ($.isNumeric(key)) {
                    if (key > 0 && 100 > key) {
                        var form = new FormData();
                        form.append('id', $("#id").val());
                        form.append('csrfk', $("#csrfk").val());
                        form.append('stage', $("#stage").val());
                        $.ajax({
                            type: "post",
                            url: "/qa",
                            data: form,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire(
                                    'Confirm!',
                                    response,
                                    'success'
                                ).then(() => {
                                    window.location.replace("/")
                                })
                            },
                            error: function (error) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!'
                                })
                            }
                        });
                    } else {
                        Swal.showValidationMessage(
                            "only numbers between 1 - 99 is allowd"
                        )
                    }
                } else {
                    Swal.showValidationMessage(
                        "only numbers between 1 - 99 is allowd"
                    )
                }
            }
        })
    });
})(jQuery);