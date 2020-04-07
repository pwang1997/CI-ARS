"use strict";

$(document).ready(() => {
    let arr_param = get_url_params(window.location.href);
    let question_index = arr_param[6];

    $.ajax({
        url: `${base_url}/get_question_for_student`,
        type: "POST",
        dataType: "JSON",
        data: {
            'question_index': question_index
        },
        success: function(response) {
            if (response.result != null) {
                console.log(response);
                $('#content').html(response.result.content)
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
                                                    <label for="choice${i}" class="col-sm-2 col-form-label">:Choice ${i+1}</label>
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
})