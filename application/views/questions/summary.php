<div class="question_on">
    <!-- content + buttons  -->
    <h5>Question:</h5>
    <h6 class="ml-2" id="editor"></h6>
    <!-- answer/choices -->
    <div>
        <?php $choices = (isset($this->session->choices)) ? json_decode($this->session->choices) : [];
        $i = 1;
        foreach ($choices as $choice) : ?>
            <div class="form-group row choice_row">
                <div class="col-sm-6">
                    <input type="text" disabled class="form-control" name="choice_row" placeholder="<?= $choice; ?>">
                </div>
                <div class="form-check col-sm-1">
                    <input class="form-check-input" type="checkbox" name="answers" value="<?= $choice; ?>">
                </div>
            </div>
        <?php $i++;
        endforeach; ?>
    </div>
    <div class="options"></div>
    <div id="targeted_time">Targeted Time: </div>
    <div>
        <p id="duration">
        </p>
        <div class="progress col-sm-6 p-0">
        </div>
    </div>
    <div>
        <p>Number of Responses: <span id="num_response">0</span></p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" id="stat">Stats</button>
    </div>
</div>

<!-- Load d3.js -->
<script src="https://d3js.org/d3.v4.min.js"></script>

<h3><?= $title; ?></h3>
<!-- Create a div where the graph will take place -->
<div id="chart"></div>

<script>
    $(document).ready(() => {
        $("#stat").click(function() {
            $('html, body').animate({
                scrollTop: $("#chart").offset().top
            }, 2000);
        });

        let action = "start";
        let websocket, init_progress, msg, timer_type;
        //initialize dataset array(associative)
        let arr_dataset = new Object();
        let frequency = new Object();
        let arr_student_answer = new Object();
        get_session().then((user) => {
            user = JSON.parse(user);
            let url_params = get_url_params(window.location.href);
            let quiz_id = url_params[url_params.length - 2];
            get_all_students(quiz_id).then((list_of_students) => {
                list_of_students = JSON.parse(list_of_students);
                for (let student of Object.entries(list_of_students)) {
                    arr_student_answer[student[1]] = "";
                }
                if (window.WebSocket) {
                    websocket = new WebSocket(wsurl);
                    websocket.onopen = function(evevt) {
                        msg = {
                            'cmd': "connect",
                            'from_id': user.id,
                            'username': user.username,
                            'role': 'summary',
                            'quiz_id': quiz_id
                        };
                        websocket.send(JSON.stringify(msg));
                        console.log("Connected to WebSocket server.");

                        $.ajax({
                            url: `${root_url}/questions/get_question_for_student`,
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                'question_index': <?php echo $question_id; ?>
                            },
                            success: function(response) {
                                if (response.result != null) {
                                    $('#content').val(response.result.content);
                                    $('#editor').html(response.result.content);
                                    timer_type = response.result.timer_type;
                                    choices = response.result.choices;
                                    duration = response.result.duration;
                                    action = "start";
                                    if (timer_type == "timedown") {
                                        $(`#duration`).html(`Remaining Time: ${duration} seconds`);
                                        $(`.progress`).html(`<div class="progress-bar" id="progress_bar" role="progressbar" style="width:100%" aria-valuenow="${duration}" aria-valuemin="0" aria-valuemax="${duration}"></div>`);
                                        init_progress = duration;
                                        animate_time_down(duration, duration, $(`#progress_bar`))
                                    } else if (timer_type == "timeup") {
                                        $(`#duration`).html(`Time: 0 seconds`);
                                        $(`.progress`).html(`<div class="progress-bar" id="progress_bar" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="${duration}"></div>`);
                                        init_progress = 0;
                                        animate_time_up(0, duration, $(`#progress_bar`))
                                    }

                                    $('#targeted_time').html(`Targeted Time: ${duration} s`)
                                    // update question choices
                                    // arr_choices = response.result.choices.split(",");
                                    var arr = JSON.parse("[" + response.result.choices + "]")[0];
                                    for (i = 0; i < arr.length; i++) {
                                        newContent = `<div class="form-group row choice_row">
                                                    <div class="col-sm-6">
                                                        <button type="button" class="btn btn-outline-secondary col-sm-12" name=choice id=choice_${i}>${arr[i]}</button>
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
                        });
                        console.log(websocket);
                    }
                    websocket.onerror = function(event) {
                        console.log("Connected to WebSocket server error");
                    }
                    websocket.onclose = function(event) {
                        console.log('websocket Connection Closed. ', event);
                    };
                    //receive message
                    websocket.onmessage = function(event) {
                        msg = JSON.parse(event.data);
                        // console.log(msg);
                        remaining_time = msg.remaining_time;
                        let type = msg.cmd; //cmd ie. start/pause/resume/close/timeout
                        let num_clients = msg.num_online_students;
                        let num_responses = 0;

                        //student submits an answer
                        if (msg.cmd == "submit") {
                            $('#chart').empty();
                            let student_id = msg.from_id;
                            let student_answers = msg.answers.split('"').join("").split(',').join(",");
                            let answer_exist = arr_dataset[student_answers];
                            console.log(answer_exist);
                            if (answer_exist === undefined) { //new answer, initialize frequncy of the answer
                                arr_dataset[student_answers] = student_answers;
                                if(frequency[arr_student_answer[student_id]] !== undefined) {//previous answer
                                    frequency[arr_student_answer[student_id]]--;
                                    console.log(`previous answer: ${arr_student_answer[student_id]}`);
                                }
                                frequency[student_answers] = 1;//new answer
                            } else {//existed answer, increment the frequency of the answer by one, decrement the previous answer's frequency
                                frequency[arr_dataset[student_answers]]++;
                                frequency[arr_student_answer[student_id]]--;
                                console.log(`previous answer: ${arr_student_answer[student_id]}`);
                            }
                            // update student answer
                            arr_student_answer[student_id] = student_answers;

                            console.log(arr_student_answer);
                            console.log(arr_dataset);
                            console.log(frequency);

                            //placeholder for d3
                            //********************************************* */


                        } else if (msg.cmd == "close" || msg.cmd == "closing_connection") { //remove question contents
                            websocket.close();
                        } else if (msg.cmd == "pause") {
                            action = "pause";
                            init_progress = msg.remaining_time;
                            console.log(`remaining time: ${msg.remaining_time}`)
                            if (timer_type == "timeup") {
                                $(`#progress_bar`).parent().prev().first().html(`Time: ${init_progress} seconds`);

                            } else if (timer_type == "timedown") {
                                $(`#progress_bar`).parent().prev().first().html(`Remaining Time: ${init_progress} seconds`);
                            }
                            if (msg.question_status == "pause_answerable") {
                                // do nothing
                                $('#status').html(`Status: Pause(Answerable)`);
                            } else if (msg.question_status == "pause_disable") {
                                $('#status').html(`Status: Pause(Disabled)`);
                                $('.submit').prop('disabled', true);
                                $('button[name=choice]').prop('disabled', true);
                            }
                        } else if (msg.cmd == "resume") {
                            action = "resume";
                            $('#status').html(`Status: Running`);
                            $('.submit').prop('disabled', false);
                            $('button[name=choice]').prop('disabled', false);
                            init_progress = msg.remaining_time;
                            if (timer_type == "timeup") {
                                $(`#progress_bar`).parent().prev().first().html(`Time: ${init_progress} seconds`);

                            } else if (timer_type == "timedown") {
                                $(`#progress_bar`).parent().prev().first().html(`Remaining Time: ${init_progress} seconds`);
                            }
                        } else if (msg.cmd == "display_answer") {
                            $('#status').html(`Status: Displaying Answer`);
                            $('.submit').prop('disabled', true);
                            $('button[name=choice]').prop('disabled', true);
                            let answers = msg.answers;
                            arr_answers = answers.split(",");
                            for (i = 0; i < arr_answers.length; i++) {
                                arr_answers[i] = arr_answers[i].replace("[", "").replace("]", "").replace('"', "").replace('\"', "")
                            }

                            i = 0;
                            $(`button[name=choice]`).each(function() {
                                let content = $(this).html();
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
                        } else if (msg.cmd == "hide_answer") {
                            $('#status').html(`Status: Running`);
                            $('.submit').prop('disabled', false);
                            $('button[name=choice]').prop('disabled', false);
                            $(`button[name=choice]`).each(function() {
                                $(this).removeClass('bg-success').removeClass('bg-danger') //negate display_answer
                            });
                        } else if (msg.cmd == "update_remaining_time" && msg.remaining_time != null) {
                            init_progress = msg.remaining_time;
                            if (timer_type == "timeup") {
                                $(`#progress_bar`).parent().prev().first().html(`Time: ${init_progress} seconds`);

                            } else if (timer_type == "timedown") {
                                $(`#progress_bar`).parent().prev().first().html(`Remaining Time: ${init_progress} seconds`);
                            }
                        }
                    }
                }
            });
        });

        function animate_time_down(init_progress, max_progress, $element) {
            setTimeout(function() {
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
                        animate_time_down(init_progress, max_progress, $element);
                    } else {
                        $element.removeClass('bg-danger');
                        return;
                    }
                } else if (action == "close") {
                    $element.removeClass('bg-danger');
                    return;
                } else if (action == "pause") {
                    return;
                }
            }, 1000);
        };

        function animate_time_up(init_progress, max_progress, $element) {
            setTimeout(function() {
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
                    animate_time_up(init_progress, max_progress, $element);
                } else if (action == "close") {
                    $element.removeClass('bg-danger');
                    return;
                } else if (action == "pause") {
                    return;
                }
            }, 1000);
        };
    });
</script>