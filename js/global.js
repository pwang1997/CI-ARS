var getUrl = window.location;
const baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

$(document).ready(() => {
    //hide toast
    off();

    $(".close").click(function() {
        $('#overlay').hide();
    });
})