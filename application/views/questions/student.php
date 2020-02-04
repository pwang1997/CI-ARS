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
            <div class="form-group">
                <textarea disabled class="form-control" id="content" rows="18" style="resize:none"><?php echo $this->session->content; ?></textarea>
            </div>
        </div>
        <div class="col-4">
            <br>
            <div class="d-flex flex-column">
                <div class="p-2">
                    <p id="timerType">Timer Type: <span><?php echo $this->session->timer_type; ?></span></p>
                </div>

                <div class="p-2">
                    <p id="difficulty">Difficulty: <span><?php echo $this->session->difficulty; ?></span></p>
                </div>

                <div class="p-2">
                    <p id="category">Category: <span><?php echo $this->session->category; ?></span></p>
                </div>

                <div class="p-2">
                    <p id="duration">Remaining Time: <span><?php echo $this->session->duration; ?></span>s</p>
                </div>

                <div class="p-2">
                    <button type="button" class="btn btn-primary submit">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- answer/choices -->
    <div class="row">
        <div class="float-left inline-block">
            <div class="col-md-12 choice_row" id="choice_row">
                <?php if ($this->session->question_type == "true_or_false") : ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="choice_row" value="true">
                        <label class="form-check-label">True</label></div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="choice_row" value="false">
                        <label class="form-check-label">False</label></div>
                <?php elseif ($this->session->question_type == "multiple_answer") : ?>
                    <?php $choices = (json_decode($this->session->choices));
                    foreach ($choices as $choice) : ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="choice_row" value="<?php echo $choice; ?>">
                            <label class="form-check-label"><?php echo $choice; ?></label>
                        </div>
                    <? endforeach; ?>
                <?php else : ?>
                    <?php $choices = (json_decode($this->session->choices));
                    foreach ($choices as $choice) : ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="choice_row" value="<?php echo $choice; ?>">
                            <label class="form-check-label"><?php echo $choice; ?></label>
                        </div>
                    <? endforeach; //end choices 
                    ?>
                <? endif; //end  question type
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        var wsurl = 'ws://127.0.0.1:9505/websocket/server.php';
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
                        url: "<?php echo base_url(); ?>questions/update_student_question_session",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'question_index': question_index,
                        },
                        success: function(response) {
                            if (response.success) {
                                console.log(response);
                                $('#content').val(response.result.content)
                                $('#timerType').val(response.result.timer_type)
                                $('#difficulty').val(response.result.difficulty)
                                $('#duration').val(response.result.duration)
                                // $('#duration').val(response.result.duration)
                            } else {
                                alert("failed to insert question1");
                            }
                        },
                        fail: function() {
                            alert("failed to insert question2");
                        }
                    })
                } else {
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
            $.ajax({
                url: "<?php echo base_url(); ?>questions/submit_student_response",
                type: "POST",
                dataType: "JSON",
                data: {
                    'student_id': <?php echo "'" . $this->session->id . "'"; ?>,
                    'answer': $('input[name=choice_row]:checked').val(),
                    'question_instance_id': question_instance_id
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                    } else {
                        alert("failed to insert question1");
                    }
                },
                fail: function() {
                    alert("failed to insert question2");
                }
            })
        });
    });
</script>