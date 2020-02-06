<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>


<?php foreach ($question_list as $question) : ?>
    <input type="hidden" id="quiz_index_<?php echo $question['id']; ?>" value=<?php echo $quiz_index; ?>>
    <!-- content + buttons  -->
    <div class="row">
        <div class="col-8">
            <br>
            <div class="form-group">
                <textarea disabled class="form-control" id="content_<?php echo $question['id']; ?>" rows="18" style="resize:none" placeholder="<?php echo $question['content']; ?>"></textarea>
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
                    <p id="duration_<?php echo $question['id']; ?>">Remaining Time: <span><?php echo $question['duration']; ?></span>s</p>
                </div>

                <div class="p-2">
                    <button type="button" class="btn btn-primary start" id="start_<?php echo $question['id']; ?>">Start</button>
                </div>

                <div class="p-2">
                    <button type="button" class="btn btn-primary pause" id="pause_<?php echo $question['id']; ?>">Pause</button>
                </div>

                <div class="p-2">
                    <button type="button" class="btn btn-primary btn-close" id="close_<?php echo $question['id']; ?>">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- answer/choices -->
    <div class="row">
        <div class="float-left inline-block">
            <div class="col-md-12 choice_row" id="choice_row_<?php echo $question['id']; ?>">
                <?php if ($question['question_type'] == "true_or_false") : ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="choice_row_<?php echo $question['id']; ?>" value="true" <?php if ($question['answer'] == "true") echo "checked"; ?>>
                        <label class="form-check-label">True</label></div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="choice_row_<?php echo $question['id']; ?>" value="false" <?php if ($question['answer'] == "false") echo "checked"; ?>>
                        <label class="form-check-label">False</label></div>
                <?php elseif ($question['question_type'] == "multiple_answer") : ?>
                    <?php $choices = (json_decode($question['choices']));
                    foreach ($choices as $choice) : ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="choice_row_<?php echo $question['id']; ?>" value="<?php echo $choice; ?>" <?php if ($choice == $question['answer']) echo "checked" ?>>
                            <label class="form-check-label"><?php echo $choice; ?></label>
                        </div>
                    <? endforeach; ?>
                <?php else : ?>
                    <?php $choices = (json_decode($question['choices']));
                    foreach ($choices as $choice) : ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="choice_row_<?php echo $question['id']; ?>" value="<?php echo $choice; ?>" <?php if ($choice == $question['answer']) echo "checked" ?>>
                            <label class="form-check-label"><?php echo $choice; ?></label>
                        </div>
                    <? endforeach; //end choices 
                    ?>
                <? endif; //end  question type
                ?>
            </div>
        </div>
    </div>
    <br><br>
    <div class="border-top my-3 d-block"></div>
<?php endforeach; //end question_list 
?>

<script>
    $(document).ready(() => {
        // var wsurl = 'ws://127.0.0.1:9505/websocket/server.php';
        var wsurl = 'ws://127.0.0.1:8080/server/server.php';
        var websocket;
        if (window.WebSocket) {
            websocket = new WebSocket(wsurl);

            //连接建立
            websocket.onopen = function(evevt) {
                console.log("Connected to WebSocket server.");
            }
            //收到消息
            websocket.onmessage = function(event) {
                var msg = JSON.parse(event.data); //解析收到的json消息数据

                var type = msg.type; // 消息类型
                var umsg = msg.message; //消息文本
                var uname = msg.name; //发送人

                console.log(type + " " + umsg + " " + uname);
                if (type == 'usermsg') {
                    console.log(`usermsg ${umsg}: ${uname}`)
                }
                if (type == 'system') {
                    console.log(`system ${umsg}: ${uname}`)
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
            question_id = (this.id).split("_")[1];
            // console.log(question_id);
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
                            console.log("success");
                            console.log(response);
                            var msg = {
                                "cmd": "start",
                                "msg": null,
                                "client_name": <?php echo "'" . $this->session->username . "'"; ?>,
                                "role": <?php echo "'" . $this->session->role . "'"; ?>,
                                "question_index": question_id,
                                "question_instance_id": response.question_instance_id
                            }
                            websocket.send(JSON.stringify(msg));
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


        });

        $('.pause').click(function() {
            question_id = (this.id).split("_")[1];
            // console.log(question_id);
            var msg = {
                "cmd": "pause",
                "msg": null,
                "client_name": <?php echo "'" . $this->session->username . "'"; ?>,
                "role": <?php echo "'" . $this->session->role . "'"; ?>,
                "question_index": question_id
            }
            try {
                websocket.send(JSON.stringify(msg));
            } catch (ex) {
                console.log(ex);
            }
        });

        $('.btn-close').click(function() {
            question_id = (this.id).split("_")[1];
            // console.log(question_id);
            var msg = {
                "cmd": "close",
                "msg": null,
                "client_name": <?php echo "'" . $this->session->username . "'"; ?>,
                "role": <?php echo "'" . $this->session->role . "'"; ?>,
                "question_index": question_id
            }
            try {
                websocket.send(JSON.stringify(msg));
            } catch (ex) {
                console.log(ex);
            }
        });
    })
</script>