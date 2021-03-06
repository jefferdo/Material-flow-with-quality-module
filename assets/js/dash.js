$(function () {
    function blink_text() {
        $('.live').fadeOut(500);
        $('.live').fadeIn(500);
    }

    function updateSW() {
        $.ajax({
            type: "get",
            url: "/getSW",
            success: function (response) {
                $("#sewinglive").html(response);
            },
            error: function (error) {

            }
        });
    }

    var live = setInterval(blink_text, 1000);
    var sw = setInterval(updateSW, 1000);

    $("#sewingcard").hover(function () {
        $('.LiveIcon').html("Live OFF").removeClass("badge-danger").addClass("badge-secondary").stop();
        clearInterval(live);
        clearInterval(sw)
    }, function () {
        $('.LiveIcon').html("Live").removeClass("badge-secondary").addClass("badge-danger");
        live = setInterval(blink_text, 500);
        sw = setInterval(updateSW, 1000);
    });
});