<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php elseif ($hasQuestion == FALSE) : ?>
    <?php redirect('questions/create/' . $quiz_index); ?>
<?php endif; ?>
<h3><?php echo ($title); ?></h3>
<span>Question pool: <?php echo ($num_questions['size']); ?></span>

<span class="float-right">Categories: <?php echo implode(',', $categories); ?></span>
<?php $j = 1; ?>
<?php foreach ($question_list as $question) : ?>
    <div id="question_<?= $question['id'] ?>">
        <div class="border-top my-3 d-block"></div>
        <p>Question <?= $j++; ?></p>
        <input type="hidden" id="quiz_index_<?php echo $question['id']; ?>" value=<?php echo $quiz_index; ?>>
        <!-- content + buttons  -->
        <div class="row">
            <div class="col-8">
                <br>
                <div class="form-group row" style="position:relative;">
                    <div class="col-sm-8" id="scrolling-container" style="height:425px; min-width:100%; min-height:100%">
                        <div class="editor" id="editor_<?= $question['id']; ?>" style="min-height:100%; height:auto;"><?= $question['content']; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <br>
                <div class="d-flex flex-column">
                    <div class="p-2">
                        <div class="form-group">
                            <select class="form-control" id="timerType_<?php echo $question['id']; ?>">
                                <option value="" disabled>Timer Type</option>
                                <option value="timeup" <?php if ($question['timer_type'] == "timeup") echo "selected"; ?>>timeup</option>
                                <option value="timedown" <?php if ($question['timer_type'] == "timedown") echo "selected"; ?>>timedown</option>
                            </select>
                            <small class="form-text text-muted">timer type</small>
                        </div>
                    </div>
                    <div class="p-2">
                        <div class="form-group">
                            <select class="form-control" id="isPublic_<?php echo $question['id']; ?>">
                                <option value="" disabled>Access</option>
                                <option value="false" <?php if ($question['is_public'] == "false") echo "selected"; ?>>private</option>
                                <option value="true" <?php if ($question['is_public'] == "true") echo "selected"; ?>>public</option>
                            </select>
                            <small class="form-text text-muted">access</small>
                        </div>
                    </div>
                    <div class="p-2">
                        <div class="form-group">
                            <select class="form-control" id="difficulty_<?php echo $question['id']; ?>">
                                <option value="" disabled>Difficulty</option>
                                <option value="easy" <?php if ($question['difficulty'] == "easy") echo "selected"; ?>>Easy</option>
                                <option value="medium" <?php if ($question['difficulty'] == "medium") echo "selected"; ?>>Medium</option>
                                <option value="hard" <?php if ($question['difficulty'] == "hard") echo "selected"; ?>>Hard</option>
                            </select>
                            <small class="form-text text-muted">difficulty</small>
                        </div>
                    </div>
                    <div class="p-2">
                        <div class="form-group">
                            <input type="text" class="form-control" id="category_<?php echo $question['id'];; ?>" placeholder="<?php echo $question['category']; ?>">
                            <small class="form-text text-muted">category</small>
                        </div>
                    </div>
                    <div class="p-2">
                        <div class="form-group">
                            <input type="text" class="form-control" id="duration_<?php echo $question['id']; ?>" placeholder="<?php echo $question['duration']; ?> s">
                            <small class="form-text text-muted">duration</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- answer heading -->
        <div class="form-group row">
            <div class="col-sm-2 offset-sm-8" style="padding-left: 0px;">Answer</div>
        </div>
        <!-- answer/choices -->
        <div id="option_row<?= $question['id']; ?>">
            <?php $choices = (json_decode($question['choices']));
            $answers = json_decode($question['answer']);
            $i = 1;
            foreach ($choices as $choice) : ?>
                <div class="form-group row choice_row">
                    <label for="choice<?= $i; ?>" class="col-sm-2 col-form-label">:Choice <?= $i; ?></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="choice<?= $i; ?>" autocomplete="on" placeholder="<?php echo $choice; ?>">
                    </div>
                    <div class="form-check col-sm-1">
                        <input class="form-check-input" type="checkbox" name="choice_row_<?php echo $question['id']; ?>" value="<?= $choice ?>" <?php if (in_array($choice, $answers)) echo "checked"; ?>>
                    </div>
                </div>
            <? $i++;
            endforeach; ?>
        </div>
        <!-- add choices/ remove empty choices -->
        <div class="row">
            <button type="button" class="btn btn-primary add" id="add_<?= $question['id'] ?>">Add</button>
            <button type="button" class="btn btn-primary remove" id="rmv_<?= $question['id'] ?>">Remove</button>
            <button type="button" class="btn btn-outline-primary offset-md-8 update" name="update_question_<?php echo $question['id']; ?>" id="<?php echo $question['id']; ?>">Update</button>
        </div>
        <br><br>
        <!-- <div class="border-top my-3 d-block"></div> -->
    <?php endforeach; //end question_list 
    ?>
    </div>
    <div class="border-top my-3 d-block"></div>
    <button type="button" class="btn btn-outline-primary" id="new_question">New Question</button>

    <script>
        $(document).ready(() => {
            
            ids = $("[id^=question_]");
            arr_ids = [];
            for(i = 0; i < ids.length; i++) {
                arr_ids.push((ids[i]).id.substring(9));
            };

            quills = [];
            for(i = 0; i < ids.length; i++) {
                id = "#editor_" + arr_ids[i];
                console.log(id);
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

            $('#new_question').click((e) => {
                location.replace(<?php echo "'" . base_url() . "questions/create/" . $quiz_index . "'"; ?>);
            })

            $("input[name^='answer_type_']").change(() => {
                console.log($("input[name^='answer_type_']:checked").attr('name'));
            }) //end of radio toggle

            $("button").click(function() {
                //update question
                if ($(this).hasClass('update')) {
                    //($('#content_' + this.id).val() == "") ? $('#content_' + this.id).attr('placeholder') : $('#content_' + this.id).val();
                    content = quill.root.innerHTML.trim();
                    category = ($('#category_' + this.id).val() == "") ? $('#category_' + this.id).attr('placeholder') : $('#category_' + this.id).val();
                    duration = ($('#duration_' + this.id).val() == "") ? $('#duration_' + this.id).attr('placeholder') : $('#duration_' + this.id).val();
                    console.log(content);
                    choices = [];
                    answers = [];
                    //get all values of choices
                    $(`input[name=choice_row_${this.id}]`).each(function() {
                        temp = $(this).parent().prev().children().first();
                        temp.val() == "" ? temp = temp.attr('placeholder') : temp = temp.val();
                        if ($(this).is(':checked')) {
                            answers.push(temp);
                        }
                        choices.push(temp);
                    });

                    choices = choices.filter(Boolean);
                    $.ajax({
                        url: "<?php echo base_url(); ?>questions/update_question",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'id': this.id,
                            'quiz_index': $('#quiz_index_' + this.id).val(),
                            'timer_type': $('#timerType_' + this.id).val(),
                            'duration': duration,
                            'content': content,
                            'isPublic': $('#isPublic_' + this.id).val(),
                            'difficulty': $('#difficulty_' + this.id).val(),
                            'category': category,
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
                    })
                } else if ($(this).hasClass('add')) {
                    question_index = this.id.substring(4);
                    //number of current options in the question
                    num_choices = $(`#option_row${question_index} > .choice_row`).length + 1;
                    //content
                    var moreChoices = `<div class="form-group row choice_row">
                                        <label for="choice${num_choices}" class="col-sm-2 col-form-label">:Choice ${num_choices}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="choice${num_choices}" autocomplete="on">
                                        </div>
                                        <div class="form-check col-sm-1">
                                            <input class="form-check-input" type="checkbox" name="choice_row_${question_index}" value="">
                                        </div>
                                    </div>`;
                    $(`#option_row${question_index}`).append(moreChoices);
                } else if ($(this).hasClass('remove')) {
                    question_index = this.id.substring(4);
                    $(`#option_row${question_index}`).children().last().remove();
                }
            });
        })
    </script>