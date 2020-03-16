"use strict";

$(document).ready(() => {
    let timer_type, default_duration;
    let url_params = get_url_params(window.location.href);
    let quiz_id = url_params[url_params.length - 1];
    let question_id;
    //move question forward
    $('.next').click(function (e) {
        question_id = (this.id).split("_")[1];
        temp = $(`#list-question_${question_id}`).parent().next().children().first();
        console.log(temp)
        temp.tab('show')
        window.scrollTo(0, 0);
    });
    //move question backward
    $('.prev').click(function () {
        question_id = (this.id).split("_")[1];
        temp = $(`#list-question_${question_id}`).parent().prev().children().first();
        temp.tab('show')
        window.scrollTo(0, 0);
    });
    /**
     * update each question status based on current quiz instance
     */
    function update_question_status(quiz_id, teacher_id) {
        $.ajax({
            url: `${base_url}/update_question_instance_status_tab_list`,
            type: "POST",
            dataType: "JSON",
            data: {
                quiz_id: quiz_id,
                from_id: teacher_id
            },
            success: (response) => {
                // console.log(`${response}`);
                for(let i = 0; i < response.length; i++) {
                    question_id = response[i].question_id;
                    status = response[i].status;
                    if(status == "complete") {
                        $(`#list-question_${question_id}`).removeClass('bg-primary').addClass('bg-success');
                    }
                }
            },
            fail: () => {
                alert('failed to connect with the database');
            }
        });
    }
    get_session().then((user) => {
        user = JSON.parse(user);

        let action = null;
        let init_progress = null;
        let websocket = null;
        let msg = null;
        get_all_students(quiz_id).then((list_of_students) => {
            console.log(`list of students: ${list_of_students}`);
            //update question instances' status(tab list)
            update_question_status(quiz_id, user.id);

            if (window.WebSocket) {
                websocket = new WebSocket(wsurl);

                websocket.onopen = function (evevt) {
                    msg = {
                        'cmd': "connect",
                        'from_id': user.id,
                        'username': user.username,
                        'role': user.role,
                        'quiz_id': quiz_id,
                        'list_of_students': list_of_students
                    };
                    websocket.send(JSON.stringify(msg));
                    console.log("Connected to WebSocket server.");
                }
                //receive message
                websocket.onmessage = function (event) {
                    let msg = JSON.parse(event.data);

                    let type = msg.cmd; //cmd ie. start/pause/resume/close/timeout
                    let num_clients = msg.num_online_students;

                    // console.log(`${type} : ${uname} `)
                    console.log(msg);
                    if (type == "connect") { //update number of students in the class room
                        $('#num_online_students').html(num_clients - 1);
                    }
                }

                websocket.onerror = function (event) {
                    console.log("Connected to WebSocket server error");
                }

                let close_connection = async function () {
                    let msg = {
                        cmd: "closing_connection",
                        from_id: user.id,
                        role: user.role,
                        quiz_id: quiz_id
                    }
                    websocket.send(JSON.stringify(msg));

                    await $.ajax({
                        url: `${base_url}/update_quiz_instance`,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'from_id': user.id,
                            'quiz_id': quiz_id
                        }
                    }).then(() => {
                        websocket.onclose = function (event) {
                            console.log('websocket Connection Closed. ', event);
                        }; // disable onclose handler first
                    })

                };

                window.onbeforeunload = function () {
                    close_connection();
                }

                $('.exit').click(function () {
                    close_connection();
                    alert('Quiz Closed');
                    window.location.replace(document.referrer);
                });

                $(`.btn-summary`).click(function () {
                    question_id = (this.id).split("_")[1];
                    console.log(question_id);
                    let popup = window.open(`${base_url}/summary/${question_id}/${question_instance_id}`);
                    popup.blur();
                    window.focus();
                });

                $(".btn-display_answer").click(function () {
                    question_id = (this.id).split("_")[2];
                    let content_display_answer = $(`#display_answer_${question_id}`).html();
                    //display answer
                    if (content_display_answer == "Display Answer") {
                        let answers = [];
                        $.each($(`input[name=choice_row_${question_id}]:checked`), function () {
                            answers.push($(this).val());
                        });
                        answers = JSON.stringify(answers)
                        console.log(answers);
                        msg = {
                            'cmd': "display_answer",
                            'question_id': question_id,
                            "role": "teacher",
                            'answers': answers,
                            'quiz_id': quiz_id
                        }
                        $(`#display_answer_${question_id}`).html("Hide Answer")
                        websocket.send(JSON.stringify(msg));
                    } else if (content_display_answer == "Hide Answer") { //hide answer
                        msg = {
                            'cmd': "hide_answer",
                            "role": "teacher",
                            'question_id': question_id,
                            'quiz_id': quiz_id
                        }
                        $(`#display_answer_${question_id}`).html("Display Answer")
                        websocket.send(JSON.stringify(msg));
                    }
                });

                $(".start").click(function () {
                    if (websocket.readyState == WebSocket.OPEN) {
                        if (!$(this).hasClass('disabled')) {
                            let question_id = (this.id).split("_")[1];
                            $(`#list- question_${question_id}`).removeClass('bg-success').addClass('bg-primary');
                            try {
                                $.ajax({
                                    url: `${base_url}/add_question_instance`,
                                    type: "POST",
                                    dataType: "JSON",
                                    data: {
                                        'question_meta_id': question_id,
                                        'from_id': user.id,
                                        'quiz_id': quiz_id
                                    },
                                    success: function (response) {
                                        if (response.success) {
                                            console.log(response);
                                            let msg = {
                                                "cmd": "start",
                                                "username": user.username,
                                                "from_id": user.id,
                                                "role": user.role,
                                                "quiz_id": quiz_id,
                                                "question_id": question_id,
                                                "question_instance_id": response.question_instance_id,
                                                "targeted_time": $(`#progress_bar_${question_id}`).attr('aria-valuemax'),
                                            }

                                            let time_remain = $(`#progress_bar_${question_id}`).attr('aria-valuemax');
                                            default_duration = time_remain;
                                            timer_type = $(`#timerType_${question_id} > span`).html()
                                            action = "start";
                                            window.open(`${base_url}/summary/${question_id}/${response.question_instance_id}`)
                                            websocket.send(JSON.stringify(msg));
                                            if (timer_type == "timedown") {
                                                init_progress = default_duration;
                                                animate_time_down(default_duration, $(`#progress_bar_${question_id}`), websocket)
                                            } else if (timer_type == "timeup") {
                                                init_progress = 0;
                                                animate_time_up(default_duration, $(`#progress_bar_${question_id}`), websocket)
                                            }
                                        } else {
                                            alert("failed to insert question1");
                                        }
                                    },
                                    fail: function () {
                                        alert("failed to insert question2");
                                    }
                                })
                            } catch (ex) {
                                console.log(ex);
                            }
                        }
                        $(this).addClass('disabled');
                    } else {
                        alert('Cannot connect to the server');
                    }
                });

                $(".pause_answerable").click(function () {
                    console.log("PAUSE ANSWERABLE")
                    question_id = (this.id).split("_")[2];
                    $(`#pause_${question_id}`).html("Resume");
                    action = "pause";
                    sendPauseMessage(question_id, action, "pause_answerable", timer_type, websocket)
                })

                $(".pause_disable").click(function () {
                    console.log("PAUSE DISABLE")
                    question_id = (this.id).split("_")[2];
                    $(`#pause_${question_id}`).html("Resume");
                    action = "pause";
                    sendPauseMessage(question_id, action, "pause_disable", timer_type, websocket)
                })

                $('.pause').click(function () {
                    question_id = (this.id).split("_")[1];
                    let current_state = $(this).html();

                    if (current_state == "Pause") {
                        // action = "pause";
                        $(this).html("Resume");
                        //update question instance to new from pause
                        $.ajax({
                            url: `${base_url}/resume_question_instance`,
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                'question_meta_id': question_id,
                                'from_id': user.id,
                                'quiz_id': quiz_id
                            }
                        })
                        // $(this).addClass("dropdown-toggle");
                    } else if (current_state == "Resume") {
                        action = "resume";
                        $(this).html("Pause");
                        // $(this).removeClass("dropdown-toggle");
                        sendPauseMessage(question_id, action, "resume", timer_type, websocket)
                    }
                });

                //reset question timer
                $('.btn-close').click(function () {
                    question_id = (this.id).split("_")[1];
                    $(`#list-question_${question_id}`).removeClass('bg-primary').addClass('bg-success');
                    msg = {
                        "cmd": "close",
                        "username": user.username,
                        "role": user.role,
                        "question_id": question_id,
                        'quiz_id': quiz_id
                    }
                    //update question instance status to complete
                    $.ajax({
                        url: `${base_url}/complete_question_instance`,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'question_meta_id': question_id,
                            'from_id': user.id,
                            'quiz_id': quiz_id
                        }
                    });

                    try {
                        action = "close";
                        let element = null;
                        if (timer_type == "timeup") {
                            element = $(`#progress_bar_${question_id}`);
                            element.attr('aria-valuenow', 0);
                            element.css('width', '0%');
                            $(`#duration_${question_id}`).html(`Time: ${default_duration} seconds`);
                        } else if (timer_type == "timedown") {
                            element = $(`#progress_bar_${question_id}`);
                            element.attr('aria-valuenow', default_duration);
                            element.css('width', '100%');
                            $(`#duration_${question_id}`).html(`Remaining Time: ${default_duration} seconds`);
                        }
                        $(`#pause_${question_id}`).html('Pause');
                        websocket.send(JSON.stringify(msg));
                    } catch (ex) {
                        console.log(ex);
                    }
                    //remove disable class for start button
                    if ($(`#start_${question_id}`).hasClass('disabled')) {
                        $(`#start_${question_id}`).removeClass('disabled');
                    }

                });

                function sendPauseMessage(question_id, action, status, timer_type, websocket) {
                    let msg = {
                        "cmd": action,
                        "username": user.username,
                        "role": user.role,
                        "question_id": question_id,
                        "question_status": status,
                        "remaining_time": init_progress,
                        'quiz_id': quiz_id
                    };

                    $.ajax({
                        url: `${base_url}/pause_question_instance`,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'question_meta_id': question_id,
                            'from_id': user.id,
                            'quiz_id': quiz_id
                        }
                    })

                    //restore pause/resume state
                    let init = $(`#progress_bar_${question_id}`).attr('aria-valuenow');
                    let duration = $(`#progress_bar_${question_id}`).attr('aria-valuemax');
                    if (timer_type == "timeup") {
                        init = ($(`#duration_${question_id}`).html().split(' '))[1];
                        animate_time_up(duration, $(`#progress_bar_${question_id}`), websocket);
                    } else if (timer_type == "timedown") {
                        animate_time_down(duration, $(`#progress_bar_${question_id}`), websocket);
                    }
                    try {
                        websocket.send(JSON.stringify(msg));
                    } catch (ex) {
                        console.log(ex);
                    }
                };

                function animate_time_down(max_progress, $element, websocket) {
                    setTimeout(function () {
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
                                msg = {
                                    "cmd": "update_remaining_time",
                                    "role": user.role,
                                    "remaining_time": init_progress,
                                    'quiz_id': quiz_id
                                }

                                websocket.send(JSON.stringify(msg));
                                console.log(msg);
                                $element.parent().parent().prev().first().html(`Remaining Time: ${init_progress} seconds`);

                                animate_time_down(max_progress, $element, websocket);
                            } else {
                                msg = {
                                    "cmd": "timeout",
                                    "username": user.username,
                                    "role": user.role,
                                    'quiz_id': quiz_id
                                }
                                websocket.send(JSON.stringify(msg));
                                //remove disable, danger class on start button
                                question_id = ($element[0].id).split("_")[2];
                                if ($(`#start_${question_id}`).hasClass('disabled')) {
                                    $(`#start_${question_id}`).removeClass('disabled');
                                }
                                $element.removeClass('bg-danger');
                                return false;
                            }
                        } else if (action == "close") {
                            action = "close";
                            $element.removeClass('bg-danger');
                            return false;
                        } else {
                            return;
                            // animate_time_down(max_progress, $element, websocket);
                        }
                        // console.log(action);
                    }, 1000);
                };

                function animate_time_up(max_progress, $element, websocket) {
                    setTimeout(function () {
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
                            msg = {
                                "cmd": "update_remaining_time",
                                "remaining_time": init_progress,
                                "role": user.role,
                                'quiz_id': quiz_id
                            }

                            websocket.send(JSON.stringify(msg));
                            console.log(msg);

                            $element.parent().parent().prev().first().html(`Time: ${init_progress} seconds`);

                            animate_time_up(max_progress, $element, websocket);
                        } else if (action == "close") {
                            $element.removeClass('bg-danger');
                            return false;
                        } else if (action == "pause") {
                            return;
                        }
                    }, 1000);
                };
            }
        });


    });
})