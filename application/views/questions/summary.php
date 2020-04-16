<h3><?= $title; ?></h3>
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
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        Show Data
    </button>
</div>

<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<script src="https://d3js.org/d3.v5.min.js"></script>

<div class="collapse" id="collapseExample">
    <div class="card card-body">
        <div id='layout'>
            <!-- <h2>Bar chart example</h2> -->
            <div id='container'>
                <svg id="chart"></svg>
            </div>
        </div>
    </div>
</div>


<script>
    $("#stat").click(function() {
        $('html, body').animate({
            scrollTop: $("#chart").offset().top
        }, 2000);
    });
    let action = "start";
    let websocket, init_progress, msg, timer_type;
    //initialize dataset array(associative)
    let arr_dataset = new Object();
    let arr_student_answer = new Object();
    let arr_data = [];
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
                        let student_id = msg.from_id;
                        let student_answers = msg.answers.split('"').join("").split(',').join(",");
                        let answer_exist = arr_dataset[student_answers];
                        console.log(answer_exist);
                        if (answer_exist === undefined) { //new answer, initialize frequncy of the answer
                            arr_dataset[student_answers] = student_answers;
                            let prev_answers = arr_student_answer[student_id];
                            for (let i = 0; i < arr_data.length; ++i) { // decrement previous answer's frequency of the student
                                if (arr_data[i].answers === prev_answers) {
                                    arr_data[i].value--;
                                    break;
                                }
                            }
                            //add new answer to the dataset
                            arr_data.push({
                                answers: student_answers,
                                value: 1
                            });
                        } else { //existed answer, increment the frequency of the answer by one, decrement the previous answer's frequency
                            let prev_answers = arr_student_answer[student_id];
                            for (let i = 0; i < arr_data.length; ++i) {
                                if (arr_data[i].answers === student_answers) {
                                    arr_data[i].value++;
                                } else if (arr_data[i].answers === prev_answers) {
                                    arr_data[i].value--;
                                }
                            }
                        }
                        // update student answer
                        arr_student_answer[student_id] = student_answers;
                        console.log(arr_student_answer);
                        // console.log(arr_dataset);
                        // console.log(arr_data);
                        //placeholder for d3
                        //********************************************* */
                        $("#chart").empty();
                        demo(arr_data, Object.keys(arr_student_answer).length);

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

    function demo(sample, class_size) {
        const svg = d3.select('#chart');
        const svgContainer = d3.select('#container');

        const margin = 80;
        const width = 1000 - 2 * margin;
        const height = 600 - 2 * margin;

        const chart = svg.append('g')
            .attr('transform', `translate(${margin}, ${margin})`);

        const xScale = d3.scaleBand()
            .range([0, width])
            .domain(sample.map((s) => s.answers))
            .padding(0.4)

        const yScale = d3.scaleLinear()
            .range([height, 0])
            .domain([0, class_size]);

        const makeYLines = () => d3.axisLeft()
            .scale(yScale)

        chart.append('g')
            .attr('transform', `translate(0, ${height})`)
            .call(d3.axisBottom(xScale));

        chart.append('g')
            .call(d3.axisLeft(yScale));

        chart.append('g')
            .attr('class', 'grid')
            .call(makeYLines()
                .tickSize(-width, 0, 0)
                .tickFormat('')
            )

        const barGroups = chart.selectAll()
            .data(sample)
            .enter()
            .append('g')

        barGroups
            .append('rect')
            .attr('class', 'bar')
            .attr('x', (g) => xScale(g.answers))
            .attr('y', (g) => yScale(g.value))
            .attr('height', (g) => height - yScale(g.value))
            .attr('width', xScale.bandwidth())
            .on('mouseenter', function(actual, i) {
                d3.selectAll('.value')
                    .attr('opacity', 0)

                d3.select(this)
                    .transition()
                    .duration(300)
                    .attr('opacity', 0.6)
                    .attr('x', (a) => xScale(a.answers) - 5)
                    .attr('width', xScale.bandwidth() + 10)

                const y = yScale(actual.value)

                line = chart.append('line')
                    .attr('id', 'limit')
                    .attr('x1', 0)
                    .attr('y1', y)
                    .attr('x2', width)
                    .attr('y2', y)

                barGroups.append('text')
                    .attr('class', 'divergence')
                    .attr('x', (a) => xScale(a.answers) + xScale.bandwidth() / 2)
                    .attr('y', (a) => yScale(a.value) + 30)
                    .attr('fill', 'white')
                    .attr('text-anchor', 'middle')
                    .text((a, idx) => {
                        const divergence = (a.value - actual.value).toFixed(1)

                        let text = ''
                        if (divergence > 0) text += '+'
                        text += `${divergence}%`

                        return idx !== i ? text : '';
                    })

            })
            .on('mouseleave', function() {
                d3.selectAll('.value')
                    .attr('opacity', 1)

                d3.select(this)
                    .transition()
                    .duration(300)
                    .attr('opacity', 1)
                    .attr('x', (a) => xScale(a.answers))
                    .attr('width', xScale.bandwidth())

                chart.selectAll('#limit').remove()
                chart.selectAll('.divergence').remove()
            })

        barGroups
            .append('text')
            .attr('class', 'value')
            .attr('x', (a) => xScale(a.answers) + xScale.bandwidth() / 2)
            .attr('y', (a) => yScale(a.value) + 30)
            .attr('text-anchor', 'middle')
            .text((a) => `${a.value/class_size * 100}%`)

        svg
            .append('text')
            .attr('class', 'label')
            .attr('x', -(height / 2) - margin)
            .attr('y', margin / 2.4)
            .attr('transform', 'rotate(-90)')
            .attr('text-anchor', 'middle')
            .text('Frequency(%)')

        svg.append('text')
            .attr('class', 'label')
            .attr('x', width / 2 + margin)
            .attr('y', height + margin * 1.7)
            .attr('text-anchor', 'middle')
            .text('Student Answers')
    }
</script>