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
                    <div id="editor" style="min-height:100%; height:auto;"><?= $this->session->content ?></div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <br>
            <div class="d-flex flex-column">
                <div class="p-2">
                    <p id="duration">
                    </p>
                    <div class="pad">
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
                                $('#editor').val(response.result.content)
                                // $('#progress_bar').val(response.result.duration)
                                timer_type = response.result.timer_type;
                                choices = response.result.choices;
                                duration = response.result.duration;
                                console.log(timer_type)
                                if (timer_type == "timedown") {
                                    $(`#duration`).html(`Remaining Time: ${duration} seconds`);
                                    $(`.pad`).html(`<progress class="progress" id="progress_bar" value="${duration}" max="${duration}">${duration} seconds</progress>`);
                                    animate_time_down(duration, duration, $(`#progress_bar`))
                                } else {
                                    $(`#duration`).html(`Time: 0 seconds`);
                                    $(`.pad`).html(`<progress class="progress" id="progress_bar" value="0" max="${duration}">0 seconds</progress>`);
                                    animate_time_up(0, duration, $(`#progress_bar`))
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
                }
            }

            websocket.onerror = function(event) {
                console.log("Connected to WebSocket server error");
            }

            websocket.onclose = function(event) {
                console.log('websocket Connection Closed. ');
            }
        }


        $('.submit').click(function(e) {
            e.preventDefault();
            answers = [];
            //get all values of choices
            $('input[name="answers"]').each(function() {
                if ($(this).is(':checked')) {
                    answers.push($(this).parent().prev().children().first().val());
                }
            });

            answers = answers.filter(Boolean);

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
                        alert('submitted')
                    } else {
                        alert("failed to insert question1");
                    }
                },
                fail: function() {
                    alert("failed to insert question2");
                }
            })
        });

        function animate_time_down(init_progress, max_progress, $element) {
            setTimeout(function() {
                init_progress -= 1;
                if (init_progress >= 0) {
                    $element.attr('value', init_progress);
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
                    return false;
                }
            }, 1000);
        };

        function animate_time_up(init_progress, max_progress, $element) {
            setTimeout(function() {
                init_progress++;
                if (init_progress <= max_progress) {
                    $element.attr('value', init_progress);
                    $element.parent().prev().first().html(`Time: ${init_progress} seconds`);
                    animate_time_up(init_progress, max_progress, $element);
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
                    return false;
                }
            }, 1000);
        };
    });
</script>