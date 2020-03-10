//global variable 
var getUrl = window.location;
const base_url = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
const wsurl = 'ws://127.0.0.1:8080/server/server.php';

function get_url_params(url) {
    return url.split('/');
}
$(document).ready(() => {
    //start of toast 
    function on() {
        $('#overlay').show();
    }

    function off() {
        $('#overlay').hide();
    }
    //hide toast
    off();

    $(".close").click(function() {
        $('#overlay').hide();
    });
    //end of toast
})