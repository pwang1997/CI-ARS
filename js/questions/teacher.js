$(document).ready(() => {
    ids = $("[id^=question_]");
    arr_ids = [];
    for (i = 0; i < ids.length; i++) {
        arr_ids.push((ids[i]).id.substring(9));
    };

    for (i = 0; i < ids.length; i++) {
        id = "#editor_" + arr_ids[i];
        // console.log(id);
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
    }

    arr_param = get_url_params(window.location.href)
    $('#new_question').click((e) => {
        location.replace(`${base_url}/questions/create/${arr_param[arr_param.length - 1]}`);
    })

    $("button").click(function() {
        //update question
        if ($(this).hasClass('update')) {
            choices = [];
            answers = [];
            //get all values of choices
            $(`input[name=choice_row_${this.id}]`).each(function() {
                temp = $(this).parent().prev().children().first().val();
                if ($(this).is(':checked')) {
                    answers.push(temp);
                }
                choices.push(temp);
            });

            choices = choices.filter(Boolean);

            $.ajax({
                url: `${base_url}/questions/update_question`,
                type: "POST",
                dataType: "JSON",
                data: {
                    'id': this.id,
                    'quiz_index': $(`#quiz_index_${this.id}`).val(),
                    'timer_type': $(`input[name=timer_types_${this.id}]:checked`).val(),
                    'duration': $(`input[name=duration_${this.id}]`).val().split(' ')[0],
                    'content': quill.root.innerHTML.trim(),
                    'isPublic': $(`input[name=accesses_${this.id}]:checked`).val(),
                    'difficulty': $(`input[name=difficulties_${this.id}]:checked`).val(),
                    'category': $(`input[name=category_${this.id}]`).val(),
                    'choices': JSON.stringify(choices),
                    'answer': JSON.stringify(answers)
                },
                success: function(response) {
                    if (response.success) {
                        alert("success");
                    } else {
                        alert("failed to insert question1");
                    }
                },
                fail: function() {
                    alert("failed to insert question2");
                }
            });
        } else if ($(this).hasClass('add')) {
            question_index = this.id.substring(4);
            //number of current options in the question
            num_choices = $(`#option_row${question_index} > .choice_row`).length + 1;
            //content
            var moreChoices = `<div class="form-group row choice_row">
                    <label for="choice${num_choices}" class="col-sm-12 col-md-2 col-form-label">:Choice ${num_choices}</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="choice${num_choices}" id="${question_index}_${num_choices}" autocomplete="on" >
                    </div>
                    <div class="custom-control custom-checkbox col-sm-1 ml-3">
                            <input type="checkbox" class="custom-control-input " id="customCheck_${num_choices}_${question_index}" name="choice_row_${question_index}">
                            <label class="custom-control-label" for="customCheck_${num_choices}_${question_index}"></label>
                        </div>
                </div>`;

            $(`#option_row${question_index}`).append(moreChoices);
        } else if ($(this).hasClass('remove')) {
            question_index = this.id.substring(4);
            $(`#option_row${question_index}`).children().last().remove();
        }
    });
})