$(document).ready(() => {
    //move question forward
    $('.next').click(function(e) {
        question_id = (this.id).split("_")[1];
        temp = $(`#list-question_${question_id}`).parent().next().children().first();
        console.log(temp)
        temp.tab('show')
        window.scrollTo(0, 0);
    });
    //move question backward
    $('.prev').click(function() {
        question_id = (this.id).split("_")[1];
        temp = $(`#list-question_${question_id}`).parent().prev().children().first();
        temp.tab('show')
        window.scrollTo(0, 0);
    });

    var action = "",
        timer_type = "",
        default_duration = "";
    get_session().then((user) => {
        user = JSON.parse(user);
        action = null;
        init_progress = null;
        if (window.WebSocket) {
            websocket = new WebSocket(wsurl);

            websocket.onopen = function(evevt) {
                    msg = {
                        'cmd': "connect",
                        'from_id': user.id,
                        'username': user.username,
                        'role': user.role
                    };
                    websocket.send(JSON.stringify(msg));
                    console.log("Connected to WebSocket server.");
                }
                //receive message
            websocket.onmessage = function(event) {
                var msg = JSON.parse(event.data);

                var type = msg.cmd; //cmd ie. start/pause/resume/close/timeout
                var uname = msg.username;
                var role = msg.role;
                var num_clients = msg.num_online_students;

                // console.log(`${type} : ${uname} `)
                console.log(msg);
                if (type == "connect") { //update number of students in the class room
                    $('#num_online_students').html(num_clients - 1);
                } else if (type == "submit") { //update number of students answered the question
                    $.ajax({
                        url: `${base_url}/get_num_students_answered`,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'question_instance_id': msg.question_instance_id
                        },
                        success: function(response) {
                            $('#num_students_answered').html(response.num_students_answered);
                            $('#num_students_answered').attr('name', msg.question_instance_id)
                        }
                    })
                }
            }

            websocket.onerror = function(event) {
                console.log("Connected to WebSocket server error");
            }

            close_connection = function() {
                $msg = {
                    cmd: "closing_connection"
                }
                websocket.send(JSON.stringify($msg));

                websocket.onclose = function(event) {
                    console.log('websocket Connection Closed. ', event);
                }; // disable onclose handler first
            };

            window.onbeforeunload = function() {
                close_connection();
            }

            $('.exit').click(function() {
                close_connection();
                alert('Quiz Closed');
                window.location.replace(`${base_url}/../users/teacher`);
            });

            $(`.btn-summary`).click(function() {
                question_id = (this.id).split("_")[1];
                console.log(question_id);
                var popup = window.open(`${base_url}/summary/${question_id}/${question_instance_id}`);
                popup.blur();
                window.focus();
            });

            $(".btn-display_answer").click(function() {
                question_id = (this.id).split("_")[2];
                content_display_answer = $(`#display_answer_${question_id}`).html();
                //display answer
                if (content_display_answer == "Display Answer") {
                    var answers = [];
                    $.each($(`input[name=choice_row_${question_id}]:checked`), function() {
                        answers.push($(this).val());
                    });
                    answers = JSON.stringify(answers)
                    console.log(answers);
                    msg = {
                        'cmd': "display_answer",
                        'question_id': question_id,
                        'answers': answers
                    }
                    $(`#display_answer_${question_id}`).html("Hide Answer")
                } else if (content_display_answer == "Hide Answer") { //hide answer
                    msg = {
                        'cmd': "hide_answer",
                        'question_id': question_id
                    }
                    $(`#display_answer_${question_id}`).html("Display Answer")
                }
                websocket.send(JSON.stringify(msg));

            });

            $(".start").click(function() {
                if (!$(this).hasClass('disabled')) {
                    question_id = (this.id).split("_")[1];
                    $(`#list- question_${question_id}`).removeClass('bg-success').addClass('bg-primary');
                    try {
                        $.ajax({
                            url: `${base_url}/add_question_instance`,
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                'question_meta_id': question_id,
                            },
                            success: function(response) {
                                if (response.success) {
                                    console.log(response);
                                    var msg = {
                                        "cmd": "start",
                                        "username": user.username,
                                        "role": user.role,
                                        "question_id": question_id,
                                        "question_instance_id": response.question_instance_id,
                                        "targeted_time": $(`#progress_bar_${question_id}`).attr('aria-valuemax'),
                                    }

                                    time_remain = $(`#progress_bar_${question_id}`).attr('aria-valuemax');
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
                            fail: function() {
                                alert("failed to insert question2");
                            }
                        })
                    } catch (ex) {
                        console.log(ex);
                    }
                }
                $(this).addClass('disabled');
            });

            $(".pause_answerable").click(function() {
                console.log("PAUSE ANSWERABLE")
                question_id = (this.id).split("_")[2];
                $(`#pause_${question_id}`).html("Resume");
                action = "pause";
                sendPauseMessage(question_id, action, "pause_answerable", timer_type, websocket)
            })

            $(".pause_disable").click(function() {
                console.log("PAUSE DISABLE")
                question_id = (this.id).split("_")[2];
                $(`#pause_${question_id}`).html("Resume");
                action = "pause";
                sendPauseMessage(question_id, action, "pause_disable", timer_type, websocket)
            })

            $('.pause').click(function() {
                question_id = (this.id).split("_")[1];
                var current_state = $(this).html();

                if (current_state == "Pause") {
                    // action = "pause";
                    $(this).html("Resume");
                    // $(this).addClass("dropdown-toggle");
                } else if (current_state == "Resume") {
                    action = "resume";
                    $(this).html("Pause");
                    // $(this).removeClass("dropdown-toggle");
                    sendPauseMessage(question_id, action, "resume", timer_type, websocket)
                }
            });

            //reset question timer
            $('.btn-close').click(function() {
                question_id = (this.id).split("_")[1];
                $(`#list-question_${question_id}`).removeClass('bg-primary').addClass('bg-success');
                var msg = {
                    "cmd": "close",
                    "username": user.username,
                    "role": user.role,
                    "question_id": question_id
                }
                try {
                    action = "close";
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
                var msg = {
                    "cmd": action,
                    "username": user.username,
                    "role": user.role,
                    "question_id": question_id,
                    "question_status": status,
                    "remaining_time": init_progress
                }

                //restore pause/resume state
                init = $(`#progress_bar_${question_id}`).attr('aria-valuenow');
                duration = $(`#progress_bar_${question_id}`).attr('aria-valuemax');
                if (timer_type == "timeup") {
                    init = ($(`#duration_${question_id}`).html().split(' '))[1];
                    init_progress = init;
                    animate_time_up(duration, $(`#progress_bar_${question_id}`), websocket)
                } else if (timer_type == "timedown") {
                    init_progress = init;
                    animate_time_down(duration, $(`#progress_bar_${question_id}`), websocket)
                }
                try {
                    websocket.send(JSON.stringify(msg));
                } catch (ex) {
                    console.log(ex);
                }
            };

            function animate_time_down(max_progress, $element, websocket) {
                setTimeout(function() {
                    if (action == "start" || action == "resume") {
                        init_progress -= 1;
                        if (init_progress >= 0) {
                            $element.attr('aria-valuenow', init_progress);
                            percentage = init_progress / max_progress;
                            $element.css('width', percentage * 100 + "%");
                            if (percentage <= 0.5) {
                                $element.addClass('bg-warning');
                            }
                            if (percentage <= 0.2 || init_progress <= 5) { //remaining time is less than 5 seconds
                                $element.removeClass('bg-warning');
                                $element.addClass('bg-danger');
                            }
                            $element.parent().parent().prev().first().html(`Remaining Time: ${init_progress} seconds`);

                            msg = {
                                "cmd": "update_remaining_time",
                                "remaining_time": init_progress
                            }

                            websocket.send(JSON.stringify(msg));
                            animate_time_down(max_progress, $element, websocket);
                        } else {
                            msg = {
                                "cmd": "timeout",
                                "username": user.username,
                                "role": user.role,
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
                setTimeout(function() {
                    if (action == "start" || action == "resume") {
                        init_progress++;
                        if (init_progress <= max_progress) {
                            $element.attr('aria-valuenow', init_progress);
                            percentage = init_progress / max_progress;
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

                        $element.parent().parent().prev().first().html(`Time: ${init_progress} seconds`);

                        msg = {
                            "cmd": "update_remaining_time",
                            "remaining_time": init_progress
                        }

                        websocket.send(JSON.stringify(msg));

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
})