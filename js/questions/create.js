"use strict";

$(document).ready(() => {
    let quill = new Quill('#editor', {
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

    //add n choices
    $('#add_more_choices').click(function() {
        let num_choices = $(`.option_row > .choice_row`).length + 1;
        let moreChoices = `<div class="form-group row choice_row">
                          <label for="choice<?= $i; ?>" class="col-sm-2 col-form-label">:Choice ${num_choices}</label>
                          <div class="col-sm-6">
                              <input type="text" class="form-control" id="choice${num_choices}" autocomplete="on">
                          </div>
                          <div class="custom-control custom-checkbox col-sm-1 ml-3">
                            <input type="checkbox" class="custom-control-input " id="customCheck_<?= $i; ?>" name="answers" value="correct">
                            <label class="custom-control-label" for="customCheck_<?= $i; ?>"></label>
                          </div>
                        </div>`;
        $('.option_row').append(moreChoices);
    });

    $('#remove_blanks').click(function() {

        $('.option_row').children().last().remove();
    });

    //submit form
    $("input[type='submit']").click((e) => {
        e.preventDefault();

        let choices = [];
        let answers = [];
        //get all values of choices
        $('input[name="answers"]').each(function() {
            if ($(this).is(':checked')) {
                answers.push($(this).parent().prev().children().first().val());
            }
            if ($(this).parent().prev().children().first().val() != "") {
                choices.push($(this).parent().prev().children().first().val());
            }
        });

        choices = choices.filter(Boolean);

        $.ajax({
            url: `${base_url}/create_question`,
            type: "POST",
            dataType: "JSON",
            data: {
                'quiz_index': $('#quiz_index').val(),
                'timer_type': $('input[name=timer_types]:checked').val(),
                'duration': $('input[name=duration]').val(),
                'content': quill.root.innerHTML.trim(),
                'isPublic': $('input[name=accesses]:checked').val(),
                'choices': JSON.stringify(choices),
                'answer': JSON.stringify(answers),
                'difficulty': $('input[name=difficulties]').val(),
                'category': $('input[name=category]').val()
            },
            success: function(response) {
                if (response.success) {
                    alert("success");
                    window.history.back();
                } else {
                    alert("failed to insert question1");
                }
            },
            fail: function() {
                alert("failed to insert question2");
            }
        })
    }); //end of submnit

});