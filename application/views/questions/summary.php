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
        <div class="progress">
        </div>
    </div>
    <div>
        <p>Number of Responses: <span id="num_response">0</span></p>
        <p>Number of Online Students: <span id="num_online_students">0</span></p>
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
        $.ajax({
            url: `${root_url}/questions/get_question_for_student`,
            type: "POST",
            dataType: "JSON",
            data: {
                'question_index': <?php echo $question_id; ?>
            },
            success: function(response) {
                if (response.result != null) {
                    console.log(response);
                    $('#content').val(response.result.content);
                    $('#editor').html(response.result.content);
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
        })

        $("#stat").click(function() {
            $('html, body').animate({
                scrollTop: $("#chart").offset().top
            }, 2000);
        });

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
                        sendAnswers(question_instance_id);
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
                } else if (action == "pause") {
                    animate_time_up(init_progress, max_progress, $element);
                    // return;
                }
            }, 1000);
        };

        get_session().then((user) => {
            user = JSON.parse(user);
            let url_params = get_url_params(window.location.href);
            let quiz_id = url_params[url_params.length - 2];
            get_all_students(quiz_id).then((list_of_students) => {
                console.log(`list of students: ${list_of_students}`);
                let action = null;
                let init_progress = null;
                let websocket = null;
                let msg = null;

                if (window.WebSocket) {
                    websocket = new WebSocket(wsurl);

                    websocket.onopen = function(evevt) {
                        msg = {
                            'cmd': "connect",
                            'from_id': user.id,
                            'username': user.username,
                            'role': user.role,
                            'quiz_id': quiz_id
                        };
                        websocket.send(JSON.stringify(msg));
                        console.log("Connected to WebSocket server.");
                    }
                    websocket.onerror = function(event) {
                        console.log("Connected to WebSocket server error");
                    }

                    let close_connection = async function() {
                        msg = {
                            cmd: "closing_connection",
                            from_id: user.id,
                            role: user.role,
                            quiz_id: quiz_id
                        }
                        websocket.send(JSON.stringify(msg));

                        websocket.onclose = function(event) {
                            console.log('websocket Connection Closed. ', event);
                        }; // disable onclose handler first
                    };

                    window.onbeforeunload = function() {
                        close_connection();
                    };
                    //receive message
                    websocket.onmessage = function(event) {
                        msg = JSON.parse(event.data);

                        let type = msg.cmd; //cmd ie. start/pause/resume/close/timeout
                        let num_clients = msg.num_online_students;
                        let num_responses = 0;
                        console.log(msg);
                        if (type == "connect") { //update number of students in the class room
                            $('#num_online_students').html(num_clients - 1);
                        }
                        //initialize dataset array(associative)
                        let arr_dataset = [];
                        let frequency = [];
                        for (let i = 0; i < list_of_students.length; i++) {
                            arr_dataset[`${list_of_students[i]}`] = "";
                        }
                        //student submits an answer
                        if (msg.cmd == "submit") {
                            $('#chart').empty();

                            let student_id = msg.from_id;
                            let student_answer = msg.answer;
                            student_answer = student_answer.replace("[", "").replace("]", "").split("'").join("").split(",");
                            //a new student response
                            if (arr_dataset[`${student_id}`].length == 0) {
                                num_responses++;
                            }
                            arr_dataset[`${student_id}`] = student_answer;
                            if (frequency[`${student_answer}`] && frequency[`${student_answer}`].length > 0) {
                                frequency[`${student_answer}`]++;
                            } else {
                                frequency[`${student_answer}`] = 1;
                            }
                            console.log([arr_dataset, frequency]);
                        }
                    }
                }
            });
        });
    });
</script>

<script>
    $(document).ready(() => {
        var interval = 1000;

        function fetchData() {
            $.ajax({
                url: <?php echo "'" . base_url() . "questions/get_answered_question_instance/" . $question_instance_id . "'"; ?>,
                type: "POST",
                dataType: "JSON",
                data: {},
                success: function(response) {
                    // //get dataset from database
                    $('#chart').empty();
                    dataset = [];
                    frequency = [];
                    $('#num_response').html(response.dataset.length);
                    choices = <?php echo $question['choices']; ?>;
                    for (i = 0; i < choices.length; i++) {
                        frequency.push(0);
                    }
                    for (i = 0; i < response.dataset.length; i++) {
                        element = response.dataset[i]
                        element = element.replace("[", "").replace("]", "").split("'").join("").split(",")
                        dataset.push(element)
                    }
                    //get frequency of all choices
                    for (i = 0; i < dataset.length; i++) {
                        for (j = 0; j < dataset[i].length; j++) {
                            temp_index = choices.indexOf(dataset[i][j])
                            frequency[temp_index]++;
                        }
                    }
                    var url = <?php echo "'" . base_url() . "questions/get_answered_question_instance/" . $question_instance_id . "'"; ?>;
                    // console.log(`choices: ${choices}`)
                    // console.log(`frequency: ${frequency}`)
                    var data = [];
                    //building json 
                    for (var c in choices) {
                        temp_var = {};
                        temp_var['choice'] = choices[c]
                        temp_var['freq'] = frequency[c]

                        data.push(temp_var)

                    }
                    data1 = data;
                    data = JSON.stringify(data);
                    //graph bar chart
                    let margin = {
                            top: 35,
                            right: 145,
                            bottom: 35,
                            left: 45
                        },
                        width = 700 - margin.left - margin.right,
                        height = 400 - margin.top - margin.bottom;

                    // scale to ordinal because x axis is not numerical
                    var x = d3.scaleBand().rangeRound([0, width]).padding(0.1);

                    //scale to numerical value by height
                    var y = d3.scaleLinear().range([height, 0]);

                    var chart = d3.select("#chart").append("svg")
                        .attr("width", width + margin.left + margin.right)
                        .attr("height", height + margin.top + margin.bottom)
                        .append("g")
                        .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
                        .attr("fill", "#002145");
                    var xAxis = d3.axisBottom(x); //orient bottom because x-axis will appear below the bars

                    var yAxis = d3.axisLeft(y);

                    // d3.json("coucou.json", function(error, data1) {
                    x.domain(data1.map(function(d) {
                        return d.choice
                    }));
                    y.domain([0, d3.max(data1, function(d) {
                        // console.log(d.freq);
                        return d.freq
                    })]);

                    var bar = chart.selectAll("g")
                        .data(data1)
                        .enter();

                    bar.append("rect")
                        .attr("y", function(d) {
                            // console.log(d)
                            return y(d.freq);
                        })
                        .attr("x", function(d, i) {
                            return x(d.choice);
                        })
                        .attr("height", function(d) {
                            return height - y(d.freq);
                        })
                        .attr("width", x.bandwidth()); //set width base on range on ordinal data

                    bar.append("text")
                        .attr("y", function(d) {
                            return y(d.freq) - 15;
                        })
                        .attr("x", function(d, i) {
                            return x(d.choice);
                        })
                        .attr("dy", ".75em")
                        .text(function(d) {
                            return d.freq;
                        });

                    chart.append("g")
                        .attr("class", "x axis")
                        .attr("transform", "translate(0," + height + ")")
                        .call(xAxis);

                    chart.append("g")
                        .attr("class", "y axis")
                        .call(yAxis)
                        .append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", 6)
                        .attr("dy", ".71em")
                        .style("text-anchor", "end")
                        .text("responses");
                    // });
                },
                fail: function(response) {
                    alert("failed to fetch dataset")
                },
                complete: function() {
                    setTimeout(fetchData, interval);
                }
            }); //end of ajax
        }
        setTimeout(fetchData(), interval);

    })
</script>