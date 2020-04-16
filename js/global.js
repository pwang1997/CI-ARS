"use strict";
//global variable 
let getUrl = window.location;
const root_url = "http://54.183.88.168";
const base_url = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1]  + "/" + getUrl.pathname.split('/')[2];
// + "/" + getUrl.pathname.split('/')[2];
// const wsurl = 'ws://127.0.0.1:8080/server/server.php';
const wsurl = 'ws://54.183.88.168:8080/';

function get_url_params(url) {
    return url.split('/');
}

//receive session user_id, username, role from ajax call
async function get_all_students(quiz_id) {
    try {
        result = await $.ajax({
            url: `${root_url}/questions/get_all_students`,
            type: "POST",
            data: {
                quiz_id: quiz_id
            },
            done: (response) => {
                return response;
            },
            fail: () => {
                console.log("failed to fetch user data, please log in");
            }
        });
        return result;
    } catch (err) {
        console.log('fetch failed', err);
    }
}
function sleep(ms) {
    return new Promise(resolve=>setTimeout(resolve, ms));
}
async function sleep_half_second() {
    await sleep(500);
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

    $(".close").click(function () {
        $('#overlay').hide();
    });
    //end of toast
    //Header tooltips
    $('#sign-up').tooltip('enable');
    $('#sign-in').tooltip('enable');
    $('#sign-out').tooltip('enable');
    $('#user').tooltip('enable');
    //side bar
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
})