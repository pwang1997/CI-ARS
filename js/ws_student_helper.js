// function onStart(id, username, role, action) {

// }

function onTimeout() {
    //disable the interface
    console.log('timeout')
    $('.submit').addClass('disabled');
    $('button[name=choice]').addClass('disabled');
}

function onClose() {
    $('.question_on').removeClass("visible").addClass("invisible");
    $('.question_off').addClass("visible").removeClass("invisible");
    $('.options').empty(); //remove options
    $('.progress').empty(); //remove timer progress bar
    $('.choice_row').parent().empty(); //remove choices
}

function sendAnswers(id, username, role) {
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
            'student_id': id,
            'answer': JSON.stringify(answers),
            'question_instance_id': question_instance_id
        },
        success: function(response) {
            if (response.success) {
                console.log(response);
                msg = {
                    "cmd": response.cmd,
                    "answers": response.msg,
                    "username": username,
                    "role": role,
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

function onSubmit(id, username, role) {
    $('.submit').click(function(e) {
        e.preventDefault();
        sendAnswers(id, username, role);
    });
}

function animate_time_up(init_progress, max_progress, $element, id, username, role, action) {
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
            animate_time_up(init_progress, max_progress, $element, id, username, role, action);
        } else if (action == "close") {
            $element.removeClass('bg-danger');
            return false;
        } else {
            animate_time_up(init_progress, max_progress, $element, id, username, role, action);
        }
    }, 1000);
};

function animate_time_down(init_progress, max_progress, $element, id, username, role, action) {
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
                animate_time_down(init_progress, max_progress, $element, id, username, role, action);
            } else {
                msg = {
                    "cmd": "timeout",
                    "username": username,
                    "role": role
                }
                websocket.send(JSON.stringify(msg));
                sendAnswers(id, username, role);
                $element.removeClass('bg-danger');
                return false;
            }
        } else if (action == "close") {
            return false;
        } else if (action == "pause") {
            console.log("quiz has been paused")
            animate_time_down(init_progress, max_progress, $element, id, username, role, action);
        }
    }, 1000);
}

function toggleActive() {
    $('button[name=choice]').each(function() {
        btn_id = $(this)[0].id;
        $(`#${btn_id}`).on('click', function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active')
            } else {
                $(this).addClass('active')
            }
        })
    })
}