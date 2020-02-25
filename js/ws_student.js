$(document).ready(() => {
    var action = "",
        timer_type = "",
        default_duration = "";
    get_session().then((user) => {
        user = JSON.parse(user);
        action = null;

        if (window.WebSocket) {
            websocket = new WebSocket(wsurl);
            // sessionStorage.setItem("ws_instance", websocket);

            websocket.onopen = function(evevt) {
                console.log("Connected to WebSocket server.");
                msg = {
                    'cmd': "connect",
                    'from_id': user.id,
                    'username': user.username,
                    'role': user.role
                };
                websocket.send(JSON.stringify(msg));
            }

            websocket.onmessage = function(event) {
                var msg = JSON.parse(event.data);
                cmd = msg.cmd;
                message = msg.message;
                client_name = msg.client_name;
                question_status = msg.question_status; //open, pause_answerable, pause_disable and close
                question_index = msg.question_id;
                role = msg.role;
                question_instance_id = msg.question_instance_id;
                resource_id = msg.resource_id;

                console.log(msg);
                //question starts
                if (cmd == "start") {
                    action = cmd;
                    $('.question_on').removeClass("invisible").addClass("visible");
                    $('.question_off').addClass("invisible").removeClass("visible");

                    $.ajax({
                        url: `${base_url}/questions/get_question_for_student`,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'question_index': question_index,
                        },
                        success: function(response) {
                            if (response.result != null) {
                                console.log(response);
                                $('#content').val(response.result.content)
                                $('#editor').html(response.result.content)
                                timer_type = response.result.timer_type;
                                choices = response.result.choices;
                                duration = response.result.duration;
                                default_duration = duration;
                                // console.log(timer_type)
                                action = "start";
                                if (timer_type == "timedown") {
                                    $(`#duration`).html(`Remaining Time: ${duration} seconds`);
                                    $(`.progress`).html(`<div class="progress-bar" id="progress_bar" role="progressbar" style="width:100%" aria-valuenow="${duration}" aria-valuemin="0" aria-valuemax="${duration}"></div>`);
                                    animate_time_down(duration, duration, $(`#progress_bar`), id, user.username, user.role)
                                } else if (timer_type == "timeup") {
                                    $(`#duration`).html(`Time: 0 seconds`);
                                    $(`.progress`).html(`<div class="progress-bar" id="progress_bar" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="${duration}"></div>`);
                                    animate_time_up(0, duration, $(`#progress_bar`), user.id, user.username, user.role)
                                }
                                // update question choices
                                // arr_choices = response.result.choices.split(",");
                                var arr = JSON.parse("[" + response.result.choices + "]")[0];
                                for (i = 0; i < arr.length; i++) {
                                    newContent = `<div class="form-group row choice_row">
                                                        <label for="choice${i}" class="col-sm-2 col-form-label">:Choice ${i + 1}</label>
                                                        <div class="col-sm-6">
                                                            <button type="button" class="btn btn-outline-primary col-sm-12" name=choice id=choice_${i}>${arr[i]}</button>
                                                        </div>
                                                    </div>`;
                                    $('.options').append(newContent);
                                }
                                toggleActive();
                            } else {
                                alert("failed to insert question1");
                            }
                        },
                        fail: function() {
                            alert("failed to insert question2");
                        }
                    })
                } else if (cmd == "timeout") { // question time out
                    console.log('timeout')
                    $('.submit').addClass('disabled');
                    $('button[name=choice]').addClass('disabled');
                } else if (cmd == "close") { //remove question contents
                    $('.question_on').removeClass("visible").addClass("invisible");
                    $('.question_off').addClass("visible").removeClass("invisible");
                    $('.options').empty(); //remove options
                    $('.progress').empty(); //remove timer progress bar
                    $('.choice_row').parent().empty(); //remove choices
                } else if (cmd == "pause") { //question pauses

                } else if (cmd == "resume") { //question resumes

                } else {
                    action = cmd;
                }
            }

            websocket.onerror = function(event) {
                console.log("Connected to WebSocket server error");
            }

            websocket.onclose = function(event) {
                console.log('websocket Connection Closed. ');
            }

            function sendAnswers() {
                answers = [];
                //get all values of choices
                $('button[name=choice]').each(function() {
                    if ($(this).hasClass('active')) {
                        answers.push($(this)[0].innerHTML);
                    }
                });
                answers = answers.filter(Boolean);
                console.log(answers)
                $.ajax({
                    url: `${base_url}/questions/submit_student_response`,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        'student_id': user.id,
                        'answer': JSON.stringify(answers),
                        'question_instance_id': question_instance_id
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            msg = {
                                "cmd": response.cmd,
                                "answers": response.msg,
                                "username": user.username,
                                "role": user.role,
                                "question_id": null,
                                "question_instance_id": response.question_instance_id
                            }
                            console.log(msg)
                            websocket.send(JSON.stringify(msg));
                        } else {
                            alert("failed to insert question1");
                        }
                    },
                    fail: function() {
                        alert("failed to insert question2");
                    }
                })
            }

            $('.submit').click(function(e) {
                e.preventDefault();
                sendAnswers();
            });
        } else {
            alert('this browser does not support websocket, please use Google Chrome or FireFox');
        }

        function toggleActive() {
            $('button[name=choice]').each(function() {
                btn_id = $(this)[0].id;
                $(`#${btn_id}`).on('click', function() {
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

        function animate_time_down(init_progress, max_progress, $element) {
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
                        $element.parent().prev().first().html(`Remaining Time: ${init_progress} seconds`);
                        animate_time_down(init_progress, max_progress, $element);
                    } else {
                        msg = {
                            "cmd": "timeout",
                            "username": username,
                            "role": role,
                        }
                        websocket.send(JSON.stringify(msg));
                        sendAnswers();
                        $element.removeClass('bg-danger');
                        return false;
                    }
                } else if (action == "close") {
                    return false;
                } else if (action == "pause") {
                    console.log("quiz has been paused")
                    animate_time_down(init_progress, max_progress, $element);
                }
            }, 1000);
        };

        function animate_time_up(init_progress, max_progress, $element) {
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
                    $element.parent().prev().first().html(`Time: ${init_progress} seconds`);
                    animate_time_up(init_progress, max_progress, $element);
                } else if (action == "close") {
                    $element.removeClass('bg-danger');
                    return false;
                } else {
                    animate_time_up(init_progress, max_progress, $element);
                }
            }, 1000);
        };
    });
})