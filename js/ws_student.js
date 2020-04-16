"use strict";

$(document).ready(() => {
    get_session().then((user) => {
        user = JSON.parse(user);
        let current_site = getUrl.pathname.split('/');
        current_site = `${current_site[current_site.length - 3]}/${current_site[current_site.length - 2]}`;
        let websocket, cmd, message,
            client_name, question_index,
            role, question_instance_id, init_progress = null;
        let action, timer_type, content;
        let msg = null, duration = null;
        if (window.WebSocket) {
            websocket = new WebSocket(wsurl);

            websocket.onopen = () => {
                console.log("Connected to WebSocket server.");
                msg = {
                    'cmd': "connect",
                    'from_id': user.id,
                    'username': user.username,
                    'role': user.role,
                    'current_site': current_site
                };

                websocket.send(JSON.stringify(msg));
            }
            websocket.onmessage = function (event) {
                msg = JSON.parse(event.data);
                if (msg.cmd !== "update_remaining_time") {
                    console.log(msg);
                }

                cmd = msg.cmd;
                message = msg.message;
                client_name = msg.client_name;
                question_index = msg.question_id;
                role = msg.role;
                let remaining_time = msg.remaining_time;

                if (msg.question_instance_id != null) {
                    question_instance_id = msg.question_instance_id;
                }
                let targeted_time = msg.targeted_time;

                if (cmd == "notification") {
                    location.replace(`${base_url}/../questions/student/${msg.quiz_id}`);
                } else if (cmd == "start") {
                    $('.question_on').removeClass("invisible").addClass("visible");
                    $('.question_off').addClass("invisible").removeClass("visible");

                    $.ajax({
                        url: `${base_url}/get_question_for_student`,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'question_index': question_index,
                        },
                        success: function (response) {
                            if (response.result != null) {
                                console.log(response);
                                $('#content').html(response.result.content)
                                // $('#editor').html(response.result.content)
                                timer_type = response.result.timer_type;
                                duration = response.result.duration;
                                // console.log(timer_type)
                                action = "start";
                                if (timer_type == "timedown") {
                                    $(`#duration`).html(`Remaining Time: ${duration} seconds`);
                                    $(`.progress`).html(`<div class="progress-bar" id="progress_bar" role="progressbar" style="width:100%" aria-valuenow="${duration}" aria-valuemin="0" aria-valuemax="${duration}"></div>`);
                                    init_progress = duration;
                                    animate_time_down(duration, $(`#progress_bar`))
                                } else if (timer_type == "timeup") {
                                    $(`#duration`).html(`Time: 0 seconds`);
                                    $(`.progress`).html(`<div class="progress-bar" id="progress_bar" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="${duration}"></div>`);
                                    init_progress = 0;
                                    animate_time_up(duration, $(`#progress_bar`))
                                }

                                $('#status').html(`Status: Running`);
                                $('#targeted_time').html(`Targeted Time: ${targeted_time} s`)
                                // update question choices
                                // arr_choices = response.result.choices.split(",");
                                let arr = JSON.parse("[" + response.result.choices + "]")[0];
                                for (let i = 0; i < arr.length; i++) {
                                    let newContent = `<div class="form-group row choice_row">
                                                    <div class="col-sm-6">
                                                        <button type="button" class="btn btn-outline-secondary col-sm-12" name=choice id=choice_${i}>${arr[i]}</button>
                                                    </div>
                                                </div>`;
                                    $('.options').append(newContent);
                                }
                                toggleActive();
                            } else {
                                alert("failed to insert question1");
                            }
                        },
                        fail: function () {
                            alert("failed to insert question2");
                        }
                    })
                } else if (cmd == "timeout") {
                    //disable the interface
                    console.log('timeout')
                    $('#status').html(`Status: Timeout`);
                    $('.submit').prop('disabled', true);
                    $('button[name=choice]').prop('disabled', true);

                } else if (cmd == "close") { //remove question contents
                    $('.question_on').removeClass("visible").addClass("invisible");
                    $('.question_off').addClass("visible").removeClass("invisible");
                    $('.options').empty(); //remove options
                    $('.progress').empty(); //remove timer progress bar
                    $('#duration').empty(); //remove timer progress bar
                    $('#status').empty(); //remove timer progress bar
                    $('#targeted_time').empty(); //remove timer progress bar
                    $('.choice_row').parent().empty(); //remove choices
                    $('.submit').prop('disabled', false);

                    action = "close";
                    $(`#quiz_status`).html("Please prepare for quiz");
                } else if (cmd == "closing_connection") {
                    window.location.replace(`${root_url}/users/student`);
                } else if (cmd == "pause") {
                    action = "pause";
                    init_progress = remaining_time;
                    console.log(`remaining time: ${remaining_time}`)
                    if (timer_type == "timeup") {
                        $(`#progress_bar`).parent().prev().first().html(`Time: ${init_progress} seconds`);
                        animate_time_up(duration, $(`#progress_bar`))
                    } else if (timer_type == "timedown") {
                        $(`#progress_bar`).parent().prev().first().html(`Remaining Time: ${init_progress} seconds`);
                        animate_time_down(duration, $(`#progress_bar`))
                    }
                    if (msg.question_status == "pause_answerable") {
                        // do nothing
                        $('#status').html(`Status: Pause(Answerable)`);
                    } else if (msg.question_status == "pause_disable") {
                        $('#status').html(`Status: Pause(Disabled)`);
                        $('.submit').prop('disabled', true);
                        $('button[name=choice]').prop('disabled', true);
                    }
                } else if (cmd == "resume") {
                    action = "resume";
                    $('#status').html(`Status: Running`);
                    $('.submit').prop('disabled', false);
                    $('button[name=choice]').prop('disabled', false);
                    init_progress = remaining_time;
                    if (timer_type == "timeup") {
                        $(`#progress_bar`).parent().prev().first().html(`Time: ${init_progress} seconds`);
                        animate_time_up(duration, $(`#progress_bar`))
                    } else if (timer_type == "timedown") {
                        $(`#progress_bar`).parent().prev().first().html(`Remaining Time: ${init_progress} seconds`);
                        animate_time_down(duration, $(`#progress_bar`))
                    }

                } else if (cmd == "display_answer") {
                    $('#status').html(`Status: Displaying Answer`);
                    $('.submit').prop('disabled', true);
                    $('button[name=choice]').prop('disabled', true);
                    let answers = msg.answers;
                    let arr_answers = answers.split(",");
                    for (let i = 0; i < arr_answers.length; i++) {
                        arr_answers[i] = arr_answers[i].replace("[", "").replace("]", "").replace('"', "").replace('\"', "")
                    }

                    let student_answers = [];
                    $(`button[name=choice]`).each(function () {
                        content = $(this).html();
                        // console.log(arr_answers.includes(content))
                        if ($(this).hasClass('active')) { //add trace for student's answer
                            $(this).addClass('student_answers');
                        }
                        if ($(this).hasClass('active') && !arr_answers.includes(content)) {
                            $(this).addClass('bg-danger');
                            $(this).addClass('teacher_answers') // teacher's answer
                        } else if (arr_answers.includes(content)) {
                            $(this).addClass('bg-success');
                            $(this).addClass('teacher_answers')
                        }
                    });
                    console.log(student_answers)
                } else if (cmd == "hide_answer") {
                    $('#status').html(`Status: Running`);
                    $('.submit').prop('disabled', false);
                    $('button[name=choice]').prop('disabled', false);
                    $(`button[name=choice]`).each(function () {
                        $(this).removeClass('bg-success').removeClass('bg-danger') //negate display_answer
                    });
                } else if (cmd == "update_remaining_time") {
                    init_progress = remaining_time;
                    if (timer_type == "timeup") {
                        $(`#progress_bar`).parent().prev().first().html(`Time: ${init_progress} seconds`);
                    } else if (timer_type == "timedown") {
                        $(`#progress_bar`).parent().prev().first().html(`Remaining Time: ${init_progress} seconds`);
                    }
                }
            }

            websocket.onerror = function (event) {
                console.log("Connected to WebSocket server error");
            }

            websocket.onclose = function (event) {
                console.log('websocket Connection Closed. ');
                $('.question_on').removeClass("visible").addClass("invisible");
                $('.question_off').addClass("visible").removeClass("invisible");
                $('.options').empty(); //remove options
                $('.progress').empty(); //remove timer progress bar
                $('.choice_row').parent().empty(); //remove choices
            }; // disable onclose handler first

        }

        $('.submit').click(function (e) {
            e.preventDefault();
            // console.log(question_instance_id);
            sendAnswers(question_instance_id);
        });

        function sendAnswers(question_instance_id) {
            let answers = [];
            //get all values of choices
            $('button[name=choice]').each(function () {
                if ($(this).hasClass('active')) {
                    answers.push($(this)[0].innerHTML);
                }
            });
            answers = answers.filter(Boolean);
            // console.log(answers)
            $.ajax({
                url: `${base_url}/submit_student_response`,
                type: "POST",
                dataType: "JSON",
                data: {
                    'student_id': user.id,
                    'answer': JSON.stringify(answers),
                    'question_instance_id': question_instance_id
                },
                success: function (response) {
                    if (response.success) {
                        console.log(response);
                        let msg = {
                            "cmd": response.cmd,
                            "answers": response.msg,
                            "username": user.username,
                            "role": user.role,
                            "question_instance_id": question_instance_id
                        }
                        console.log(msg)
                        websocket.send(JSON.stringify(msg));
                        alert('answer submitted')
                    } else {
                        alert("Error: 1");
                    }
                },
                fail: function () {
                    alert("Error: 2");
                }
            })
        }

        //toggle choice buttons with 'active'
        function toggleActive() {
            $('button[name=choice]').each(function () {
                let btn_id = $(this)[0].id;
                $(`#${btn_id}`).on('click', function () {
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active')
                        $(this).removeClass('btn-primary').addClass('btn-outline-secondary');
                    } else {
                        $(this).addClass('active')
                        $(this).removeClass('btn-outline-secondary').addClass('btn-primary');
                    }
                })
            })
        }

        function animate_time_down(max_progress, $element) {
            setTimeout(function () {
                if (websocket.readyState === WebSocket.CLOSED) {
                    alert('server is not available at the moment');
                    return;
                }
                if (action == "start" || action == "resume") {
                    init_progress = init_progress - 1;
                    if (init_progress >= 0) {
                        $element.attr('aria-valuenow', init_progress);
                        let percentage = init_progress / max_progress;
                        $element.css('width', percentage * 100 + "%");
                        if (percentage <= 0.5) {
                            $element.addClass('bg-warning');
                        }
                        if (percentage <= 0.2 || init_progress <= 5) { //remaining time is less than 5 seconds
                            $element.removeClass('bg-warning');
                            $element.addClass('bg-danger');
                        }
                        $element.parent().prev().first().html(`Remaining Time: ${init_progress} seconds`);
                        animate_time_down(max_progress, $element);
                    } else {
                        let msg = {
                            "cmd": "timeout",
                            "username": user.username,
                            "role": user.role,
                        }
                        websocket.send(JSON.stringify(msg));
                        sendAnswers(question_instance_id);
                        $element.removeClass('bg-danger');
                        return false;
                    }
                } else if (action == "close") {
                    $element.removeClass('bg-danger');
                    return;
                } else if (action == "pause") {
                    return;
                }
            }, 1000);
        };

        function animate_time_up(max_progress, $element) {
            setTimeout(function () {
                if (websocket.readyState === WebSocket.CLOSED) {
                    alert('server is not available at the moment');
                    return;
                }
                if (action == "start" || action == "resume") {
                    init_progress = init_progress + 1;
                    if (init_progress <= max_progress) {
                        $element.attr('aria-valuenow', init_progress);
                        let percentage = init_progress / max_progress;
                        $element.css('width', percentage * 100 + "%");
                        if (percentage >= 0.5) {
                            $element.addClass('bg-warning');
                        }
                        if (percentage >= 0.9) {
                            $element.removeClass('bg-warning');
                            $element.addClass('bg-danger');
                        }
                    }
                    //update timer
                    $element.parent().prev().first().html(`Time: ${init_progress} seconds`);
                    animate_time_up(max_progress, $element);
                } else if (action == "close") {
                    $element.removeClass('bg-danger');
                    return;
                } else if (action == "pause") {
                    return;
                }
            }, 1000);
        };
    });
});