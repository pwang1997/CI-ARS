<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
<h2><?php echo $title; ?></h2>

<div class="form_question">
    <?php echo form_open('questions/create'); ?>
    <input type="hidden" id="quiz_index" value=<?php echo $lab_index; ?>>
    <!-- content + buttons  -->
    <div class="row">
        <div class="col-8">
            <br>
            <div class="form-group">
                <textarea class="form-control" id="content" rows="18" style="resize:none" placeholder="question content"></textarea>
            </div>
        </div>
        <div class="col-4">
            <br>
            <div class="d-flex flex-column">
                <div class="p-2">
                    <div class="form-group">
                        <select class="form-control" id="timerType">
                            <option value="" disabled selected>Timer Type</option>
                            <option value="timeup">timeup</option>
                            <option value="timedown">timedown</option>
                        </select>
                        <small class="form-text text-muted">timer type</small>
                    </div>
                </div>
                <div class="p-2">
                    <div class="form-group">
                        <select class="form-control" id="isPublic">
                            <option value="" disabled selected>Access</option>
                            <option value="false">private</option>
                            <option value="true">public</option>
                        </select>
                        <small class="form-text text-muted">access</small>
                    </div>
                </div>
                <div class="p-2">
                    <div class="form-group">
                        <select class="form-control" id="difficulty">
                            <option value="" disabled selected>Difficulty</option>
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                        <small class="form-text text-muted">difficulty</small>
                    </div>
                </div>
                <div class="p-2">
                    <div class="form-group">
                        <input type="text" class="form-control" id="category" placeholder="category">
                        <small class="form-text text-muted">category</small>
                    </div>
                </div>
                <div class="p-2">
                    <div class="form-group">
                        <input type="text" class="form-control" id="duration" placeholder="duration(in second)">
                        <small class="form-text text-muted">duration</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- answer type -->
    <div class="row">
        <div class="col-md-12">
            <p>Answer Type</p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="answer_type" id="answer_type1" value="true_or_false" data-toggle="collapse" href="#add_option" role="button" aria-expanded="false" aria-controls="add_option">
                <label class="form-check-label" for="answer_type1">True or False</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="answer_type" id="answer_type2" value="multiple_answer">

                <label class="form-check-label" for="answer_type2">Multiple Answers</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input " type="radio" name="answer_type" id="answer_type3" value="single_answer">
                <label class="form-check-label" for="answer_type3">Single Answer</label>
            </div>
        </div>
    </div>
    <!-- answer/choices -->
    <div class="row">
        <div class="col-md-12 choice_row">

        </div>
    </div>
    <!-- submit -->
    <div class="row">
        <div class="offset-md-11">
            <input type="submit" class="btn btn-outline-primary" value="Submit">
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script>
    $(document).ready(() => {
        //toggle on answer type
        $("input[type='radio']").change((e) => {
            selected_value = $("input[name='answer_type']:checked").val();
            $(".choice_row").empty();
            $(".choice_row").append("<p>Choices</p>");
            newContent = "";
            if (selected_value == "true_or_false") {
                newContent = '<div class="form-check">' +
                    '<input class="form-check-input" type="radio" name="choice_row" value="true">' +
                    '<label class="form-check-label">True</label></div>';
                newContent += '<div class="form-check">' +
                    '<input class="form-check-input" type="radio" name="choice_row" value="false">' +
                    '<label class="form-check-label">False</label></div>';
                $(".choice_row").append(newContent);
            } else if (selected_value == "multiple_answer") {
                i = 0;
                for (; i < 4; i++) {
                    newContent += '<div class="form-check">' +
                        `<input class="form-check-input" type="checkbox" name="choice_row"  value="${i}">` +
                        '<label class="form-check-label" contenteditable="true">' +
                        `${i}</label></div>`;
                }
                newContent += '<button type="button" class="btn btn-outline-primary" id="add_option"">Add option</button>';
                //append event listener
                $(".choice_row").first().append(newContent);
                $('#add_option').click((e) => {
                    temp = '<div class="form-check">' +
                        `<input class="form-check-input" type="checkbox" name="choice_row"  value="${i++}">` +
                        '<label class="form-check-label" contenteditable="true">' +
                        `placeholder</label></div>`
                    $('.choice_row div').last().after(temp);
                });

            } else {
                i = 0;
                for (; i < 4; i++) {
                    newContent += '<div class="form-check">' +
                        `<input class="form-check-input" type="radio" name="choice_row"  value="${i}">` +
                        '<label class="form-check-label" contenteditable="true">' +
                        `${i}</label></div>`;
                }
                newContent += '<button type="button" class="btn btn-outline-primary" id="add_option"">Add option</button>';
                //append event listener
                $(".choice_row").first().append(newContent);
                $('#add_option').click((e) => {
                    temp = '<div class="form-check">' +
                        `<input class="form-check-input" type="radio" name="choice_row"  value="${i++}">` +
                        '<label class="form-check-label" contenteditable="true">' +
                        `placeholder</label></div>`;
                    $('.choice_row div').last().after(temp);
                });

            }
        }) //end of radio toggle

        //submit form
        $("input[type='submit']").click((e) => {
            e.preventDefault();
            choices = [];
            //get all values of choices
            $('input[name="choice_row"]').each(function() {
                choices.push($(this).next().text());
            });

            $.ajax({
                url: "<?php echo base_url(); ?>questions/create_question",
                type: "POST",
                dataType: "JSON",
                data: {
                    'quiz_index': $('#quiz_index').val(),
                    'timer_type': $('#timerType').val(),
                    'duration': $('#duration').val(),
                    'content': $('#content').val(),
                    'isPublic': $('#isPublic').val(),
                    'question_type': $('input[name="answer_type"]:checked').val(),
                    'choices': JSON.stringify(choices),
                    'answer': $('input[name="choice_row"]:checked').next().text(),
                    'difficulty': $('#difficulty').val(),
                    'category': $('#category').val()
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
            })
        }); //end of submnit

    });
</script>