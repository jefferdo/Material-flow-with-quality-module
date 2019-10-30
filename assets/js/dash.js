$(function () {
    function blink_text() {
        $('.live').fadeOut(500);
        $('.live').fadeIn(500);
    }

    function updateSw() {
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
    setInterval(blink_text, 1000);
    setInterval(updateSw, 15000);
});