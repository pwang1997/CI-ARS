<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
</div>
<!-- <h3>Number of Online Students: <span id="num_online_students">0</span></h3>
<h3>Number of Answered Students: <span id="num_students_answered" name="">0</span></h3> -->


<div class="row">
    <div class="col-2">
        <div class="list-group overflow-auto" id="list-tab" role="tablist" style="height:20em">
            <?php
            $index = 1;
            foreach ($question_list as $question) : ?>
                <a class="list-group-item list-group-item-action <?php if ($index == 1) echo 'active'; ?> bg-primary" id="list-question_<?= $question['id']; ?>" data-toggle="list" href="#list-<?= $question['id']; ?>" role="tab" aria-controls="<?= $index; ?>">Question <?= $index++; ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-10">
        <div class="tab-content" id="nav-tabContent">
            <?php $index = 1;
            foreach ($question_list as $question) : ?>
                <div class="tab-pane fade <?php if ($index == 1) echo "show active"; ?>" id="list-<?= $question['id']; ?>" role="tabpanel" aria-labelledby="list-question_<?= $question['id']; ?>">
                    <h5>Quesation <?= $index; ?></h5>
                    <div id="question_<?= $question['id'] ?>">
                        <input type="hidden" id="quiz_index_<?php echo $question['id']; ?>" value=<?php echo $quiz_index; ?>>
                        <!-- content + buttons  -->
                        <div class="row">
                            <div class="col-8">
                                <br>
                                <div class="form-group row" style="position:relative;">
                                    <div class="col-sm-8" id="scrolling-container" style="height:425px; min-width:100%; min-height:100%">
                                        <div class="editor" id="editor_<?= $question['id']; ?>" style="min-height:100%; height:auto;"><?= $question['content']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <br>
                                <div class="d-flex flex-column">
                                    <div class="p-2">
                                        <p id="timerType_<?php echo $question['id']; ?>">Timer Type: <span><?php echo $question['timer_type']; ?></span></p>
                                    </div>

                                    <div class="p-2">
                                        <p id="difficulty_<?php echo $question['id']; ?>">Difficulty: <span><?php echo $question['difficulty']; ?></span></p>
                                    </div>

                                    <div class="p-2">
                                        <p id="category_<?php echo $question['id']; ?>">Category: <span><?php echo $question['category']; ?></span></p>
                                    </div>

                                    <div class="p-2">
                                        <p id="duration_<?php echo $question['id']; ?>">
                                            <?php if ($question['timer_type'] == 'timedown') : ?>
                                                Remaining Time:<?php echo $question['duration']; ?> seconds
                                            <?php else : ?>
                                                Time:<?php echo $question['duration']; ?> seconds
                                            <? endif; ?>
                                        </p>
                                        <div class="progress">
                                            <?php if ($question['timer_type'] == 'timedown') : ?>
                                                <div class="progress-bar" id="progress_bar_<?= $question['id']; ?>" role="progressbar" style="width:100%" aria-valuenow="<?= $question['duration'] ?>" aria-valuemin="0" aria-valuemax="<?= $question['duration'] ?>"></div>
                                            <?php else : ?>
                                                <div class="progress-bar" id="progress_bar_<?= $question['id']; ?>" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="<?= $question['duration'] ?>"></div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                    <div class="row p-2">
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-primary start" id="start_<?php echo $question['id']; ?>">Start</button>
                                        </div>

                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-primary pause" id="pause_<?php echo $question['id']; ?>">Pause</button>
                                        </div>

                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-primary btn-close" id="close_<?php echo $question['id']; ?>">Close</button>
                                        </div>
                                    </div>

                                    <div class="p-2">
                                        <button type="button" class="btn btn-primary btn-summary" id="summary_<?php echo $question['id']; ?>">Summary</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br><br>
                        <!-- answer heading -->
                        <div class="form-group row">
                            <div class="col-sm-2 offset-sm-8" style="padding-left: 0px;">Answer</div>
                        </div>
                        <!-- answer/choices -->
                        <div id="option_row<?= $question['id']; ?>">
                            <?php $choices = (json_decode($question['choices']));
                            $answers = json_decode($question['answer']);
                            $i = 1;
                            foreach ($choices as $choice) : ?>
                                <div class="form-group row choice_row">
                                    <label for="choice<?= $i; ?>" class="col-sm-2 col-form-label">:Choice <?= $i; ?></label>
                                    <div class="col-sm-6">
                                        <input type="text" disabled class="form-control" name="choice<?= $i; ?>" autocomplete="on" placeholder="<?php echo $choice; ?>">
                                    </div>
                                    <div class="form-check col-sm-1">
                                        <input class="form-check-input" disabled type="checkbox" name="choice_row_<?php echo $question['id']; ?>" value="<?= $choice ?>" <?php if (in_array($choice, $answers)) echo "checked"; ?>>
                                    </div>
                                </div>
                            <? $i++;
                            endforeach; ?>
                        </div>
                    </div>
                    <br><br>
                    <button class="btn btn-primary prev <?php if($index==1) echo "disabled";?>"  type="button" id="prev_<?= $question['id']; ?>">Previous</button>
                    <button class="btn btn-primary next <?php if($index == count($question_list)) echo "disabled";?>"  type="button" id="next_<?= $question['id']; ?>">Next</button>
                    <div class="border-top my-3 d-block"></div>
                    <?php $index++; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        ids = $("[id^=question_]");
        arr_ids = [];
        for (i = 0; i < ids.length; i++) {
            arr_ids.push((ids[i]).id.substring(9));
        };

        quills = [];
        for (i = 0; i < ids.length; i++) {
            id = "#editor_" + arr_ids[i];
            quill = new Quill(`#editor_${arr_ids[i]}`, {
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        ['image', 'code-block']
                    ]
                },
                scrollingContainer: '#scrolling-container',
                placeholder: 'Question Content',
                theme: 'snow' // or 'bubble'
            });
            // quills.push(quill);
            quill.enable(false);
        }

        // var wsurl = 'ws://127.0.0.1:9505/websocket/server.php';
        var wsurl = 'ws://127.0.0.1:8080/server/server.php';
        var websocket;
        if (window.WebSocket) {
            websocket = new WebSocket(wsurl);

            websocket.onopen = function(evevt) {
                console.log("Connected to WebSocket server.");
                msg = {
                    'cmd': "connect",
                    'from_id': <?php echo "'" . $this->session->id . "'"; ?>,
                    'username': <?php echo "'" . $this->session->username . "'"; ?>,
                    'role': <?php echo "'" . $this->session->role . "'"; ?>
                };

                websocket.send(JSON.stringify(msg));
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
                        url: <?php echo "'" . base_url() . "questions/get_num_students_answered'"; ?>,
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

            websocket.onclose = function(event) {
                console.log('websocket Connection Closed. ');
            }
        }

        $(".start").click(function() {
            if (!$(this).hasClass('disabled')) {
                question_id = (this.id).split("_")[1];
                $(`#list-question_${question_id}`).removeClass('bg-success').addClass('bg-primary');
                try {
                    $.ajax({
                        url: "<?php echo base_url(); ?>questions/add_question_instance",
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
                                    "username": <?php echo "'" . $this->session->username . "'"; ?>,
                                    "role": <?php echo "'" . $this->session->role . "'"; ?>,
                                    "question_id": question_id,
                                    "question_instance_id": response.question_instance_id
                                }
                                websocket.send(JSON.stringify(msg));
                                time_remain = $(`#progress_bar_${question_id}`).attr('aria-valuemax');
                                default_duration = time_remain;
                                timer_type = $(`#timerType_${question_id} > span`).html()
                                action = "start";
                                if (timer_type == "timedown") {
                                    animate_time_down(default_duration, default_duration, $(`#progress_bar_${question_id}`))
                                } else if (timer_type == "timeup") {
                                    animate_time_up(0, default_duration, $(`#progress_bar_${question_id}`))
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

        $('.pause').click(function() {
            if (action == "start") {
                action = "pause";
                $(this).html("Resume");
            } else if (action == "resume") {
                action = "pause";
                $(this).html("Resume");
            } else if (action == "pause") {
                action = "resume";
                $(this).html("Pause");
            }

            question_id = (this.id).split("_")[1];
            var msg = {
                "cmd": action,
                "username": <?php echo "'" . $this->session->username . "'"; ?>,
                "role": <?php echo "'" . $this->session->role . "'"; ?>,
                "question_id": question_id
            }
            //restore pause/resume state
            init = $(`#progress_bar_${question_id}`).attr('aria-valuenow');
            duration = $(`#progress_bar_${question_id}`).attr('aria-valuemax');
            if (timer_type == "timeup") {
                init = ($(`#duration_${question_id}`).html().split(' '))[1];
                animate_time_up(init, duration, $(`#progress_bar_${question_id}`))
            } else if (timer_type == "timedown") {
                animate_time_down(init, duration, $(`#progress_bar_${question_id}`))
            }
            try {
                websocket.send(JSON.stringify(msg));
            } catch (ex) {
                console.log(ex);
            }
        });

        $('.next').click(function() {
            question_id = (this.id).split("_")[1];
            temp = $(`#list-question_${question_id}`);
            temp.next().tab('show')
            window.scrollTo(0,0);
        });

        $('.prev').click(function() {
            question_id = (this.id).split("_")[1];
            temp = $(`#list-question_${question_id}`);
            temp.prev().tab('show')
            window.scrollTo(0,0);
        });

        //reset question timer
        $('.btn-close').click(function() {
            question_id = (this.id).split("_")[1];
            $(`#list-question_${question_id}`).removeClass('bg-primary').addClass('bg-success');
            var msg = {
                "cmd": "close",
                "username": <?php echo "'" . $this->session->username . "'"; ?>,
                "role": <?php echo "'" . $this->session->role . "'"; ?>,
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

        $(`.btn-summary`).click(function() {
            question_id = (this.id).split("_")[1];
            console.log(question_id);
            question_instance_id = $('#num_students_answered').attr('name');
            window.open(<?php echo "'" . base_url() . "questions/summary/'"; ?> + question_id + "/" + question_instance_id)
        });

        //DOM variables
        var action = "",
            timer_type = "",
            default_duration = "";

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
                            "username": <?php echo "'" . $this->session->username . "'"; ?>,
                            "role": <?php echo "'" . $this->session->role . "'"; ?>,
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
                    animate_time_down(init_progress, max_progress, $element);
                }
                // console.log(action);
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
    })
</script>