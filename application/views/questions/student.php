<?php if (strcmp($this->session->role, 'teacher') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
<link rel="stylesheet" href="../../css/spinner.css">

<div class="question_off visible">
    <div class="d-flex flex-column align-items-center justify-content-center">
        <div class="row">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="row">
            <strong>Please prepare for quiz</strong>
        </div>
    </div>
</div>
<div class="question_on invisible">
    <!-- content + buttons  -->
    <div class="row">
        <div class="col-8">
            <br>
            <div class="form-group row" style="position:relative;">
                <div class="col-sm-8" id="scrolling-container" style="height:425px; min-width:100%; min-height:100%">
                    <div id="editor" style="min-height:100%; height:auto;"></div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <br>
            <div class="d-flex flex-column">
                <div class="p-2">
                    <p id="duration">
                    </p>
                    <div class="progress">
                    </div>
                </div>
                <div class="p-2">
                    <button type="button" class="btn btn-primary submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- answer heading -->
    <div class="form-group row question_on invisible">
        <div class="col-sm-2 offset-sm-8" style="padding-left: 0px;">Answer</div>
    </div>
    <!-- answer/choices -->
    <?php $choices = (isset($this->session->choices)) ? json_decode($this->session->choices) : [];
    $i = 1;
    foreach ($choices as $choice) : ?>
        <div class="form-group row choice_row">
            <label for="choice<?= $i; ?>" class="col-sm-2 col-form-label">:Choice <?= $i; ?></label>
            <div class="col-sm-6">
                <input type="text" disabled choice_row class="form-control" name="choice_row" placeholder="<?= $choice; ?>">
            </div>
            <div class="form-check col-sm-1">
                <input class="form-check-input" type="checkbox" name="answers" value="<?= $choice; ?>">
            </div>
        </div>
    <? $i++;
    endforeach; ?>
    <div class="options"></div>
</div>

<script>
    $(document).ready(() => {
        var quill = new Quill('#editor', {
            modules: {
                "toolbar": false
            },
            theme: 'snow' // or 'bubble'
        });
        quill.enable(false);

        // var wsurl = 'ws://127.0.0.1:9505/websocket/server.php';
        var wsurl = 'ws://127.0.0.1:8080/server/server.php';
        var websocket, cmd, message, client_name, question_index, role, question_instance_id;

        if (window.WebSocket) {
            websocket = new WebSocket(wsurl);

            websocket.onopen = function(evevt) {
                console.log("Connected to WebSocket server.");
            }

            websocket.onmessage = function(event) {
                var msg = JSON.parse(event.data);

                cmd = msg.cmd;
                message = msg.message;
                client_name = msg.client_name;
                question_index = msg.question_index;
                role = msg.role;
                question_instance_id = msg.question_instance_id;

                console.log(msg);
                if (cmd == "start") {
                    $('.question_on').removeClass("invisible").addClass("visible");
                    $('.question_off').addClass("invisible").removeClass("visible");

                    $.ajax({
                        url: "<?php echo base_url(); ?>questions/get_question_for_student",
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
                                    animate_time_down(duration, duration, $(`#progress_bar`))
                                } else if (timer_type == "timeup") {
                                    $(`#duration`).html(`Time: 0 seconds`);

                                    $(`.progress`).html(`<div class="progress-bar" id="progress_bar" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="${duration}"></div>`);
                                    animate_time_up(0, duration, $(`#progress_bar`))
                                }
                                // update question choices
                                // arr_choices = response.result.choices.split(",");
                                var arr = JSON.parse("[" + response.result.choices + "]")[0];
                                // console.log(arr);
                                for (i = 0; i < arr.length; i++) {
                                    newContent = `<div class="form-group row choice_row">
                                                    <label for="choice${i}" class="col-sm-2 col-form-label">:Choice ${i}</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" disabled choice_row class="form-control" name="choice_row" placeholder="${arr[i]}">
                                                    </div>
                                                    <div class="form-check col-sm-1">
                                                        <input class="form-check-input" type="checkbox" name="answers" value="${arr[i]}">
                                                    </div>
                                                </div>`;
                                    $('.options').append(newContent);
                                }
                            } else {
                                alert("failed to insert question1");
                            }
                        },
                        fail: function() {
                            alert("failed to insert question2");
                        }
                    })
                } else if (cmd == "timeout" || cmd == "close") {
                    $('.question_on').removeClass("visible").addClass("invisible");
                    $('.question_off').addClass("visible").removeClass("invisible");
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
        }

        function sendAnswers() {
            answers = [];
            //get all values of choices
            $('input[name="answers"]').each(function() {
                if ($(this).is(':checked')) {
                    answers.push(this.value);
                }
            });
            answers = answers.filter(Boolean);
            console.log(answers);
            $.ajax({
                url: "<?php echo base_url(); ?>questions/submit_student_response",
                type: "POST",
                dataType: "JSON",
                data: {
                    'student_id': <?php echo "'" . $this->session->id . "'"; ?>,
                    'answer': JSON.stringify(answers),
                    'question_instance_id': question_instance_id
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        // alert('submitted')
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

        var action = "",
            timer_type = "",
            default_duration = "";

        function animate_time_down(init_progress, max_progress, $element) {
            setTimeout(function() {
                // console.log(action);
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
                            "msg": null,
                            "client_name": <?php echo "'" . $this->session->username . "'"; ?>,
                            "role": <?php echo "'" . $this->session->role . "'"; ?>,
                            "question_index": null,
                            "question_instance_id": null
                        }
                        websocket.send(JSON.stringify(msg));
                        sendAnswers();
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
                console.log(action);
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
                    return false;
                } else {
                    animate_time_up(init_progress, max_progress, $element);
                }
            }, 1000);
        };
    });
</script>