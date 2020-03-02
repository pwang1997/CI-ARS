$(document).ready(() => {
    ids = $("[id^=question_]");
    arr_ids = [];
    for (i = 0; i < ids.length; i++) {
        arr_ids.push((ids[i]).id.substring(9));
    };

    quills = [];
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
    console.log(arr_param)
        // $('#new_question').click((e) => {
        //     location.replace(base_url + "questions/create/".$quiz_index);
        // })

    // $("input[name^='answer_type_']").change(() => {
    //         console.log($("input[name^='answer_type_']:checked").attr('name'));
    //     }) //end of radio toggle

    // $("button").click(function() {
    //     //update question
    //     if ($(this).hasClass('update')) {
    //         //($('#content_' + this.id).val() == "") ? $('#content_' + this.id).attr('placeholder') : $('#content_' + this.id).val();
    //         content = quill.root.innerHTML.trim();
    //         category = ($('#category_' + this.id).val() == "") ? $('#category_' + this.id).attr('placeholder') : $('#category_' + this.id).val();
    //         duration = ($('#duration_' + this.id).val() == "") ? $('#duration_' + this.id).attr('placeholder') : $('#duration_' + this.id).val();
    //         console.log(content);
    //         choices = [];
    //         answers = [];
    //         //get all values of choices
    //         $(`input[name=choice_row_${this.id}]`).each(function() {
    //             temp = $(this).parent().prev().children().first();
    //             temp.val() == "" ? temp = temp.attr('placeholder') : temp = temp.val();
    //             if ($(this).is(':checked')) {
    //                 answers.push(temp);
    //             }
    //             choices.push(temp);
    //         });

    //         choices = choices.filter(Boolean);
    //         $.ajax({
    //             url: "<?php echo base_url(); ?>questions/update_question",
    //             type: "POST",
    //             dataType: "JSON",
    //             data: {
    //                 'id': this.id,
    //                 'quiz_index': $('#quiz_index_' + this.id).val(),
    //                 'timer_type': $('#timerType_' + this.id).val(),
    //                 'duration': duration,
    //                 'content': content,
    //                 'isPublic': $('#isPublic_' + this.id).val(),
    //                 'difficulty': $('#difficulty_' + this.id).val(),
    //                 'category': category,
    //                 'choices': JSON.stringify(choices),
    //                 'answer': JSON.stringify(answers)
    //             },
    //             success: function(response) {
    //                 if (response.success) {
    //                     alert("success");
    //                 } else {
    //                     alert("failed to insert question1");
    //                 }
    //             },
    //             fail: function() {
    //                 alert("failed to insert question2");
    //             }
    //         })
    //     } else if ($(this).hasClass('add')) {
    //         question_index = this.id.substring(4);
    //         //number of current options in the question
    //         num_choices = $(`#option_row${question_index} > .choice_row`).length + 1;
    //         //content
    //         var moreChoices = `<div class="form-group row choice_row">
    //                             <label for="choice${num_choices}" class="col-sm-2 col-form-label">:Choice ${num_choices}</label>
    //                             <div class="col-sm-6">
    //                                 <input type="text" class="form-control" name="choice${num_choices}" id="${question_index}_${num_choices}" autocomplete="on">
    //                             </div>
    //                             <div class="form-check col-sm-1">
    //                                 <input class="form-check-input" type="checkbox" name="choice_row_${question_index}" value="">
    //                             </div>
    //                         </div>`;
    //         $(`#option_row${question_index}`).append(moreChoices);
    //     } else if ($(this).hasClass('remove')) {
    //         question_index = this.id.substring(4);
    //         $(`#option_row${question_index}`).children().last().remove();
    //     }
    // });
})