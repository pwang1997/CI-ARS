<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php elseif ($hasQuestion == FALSE) : ?>
    <?php redirect('questions/create/' . $quiz_index); ?>
<?php endif; ?>

<?php foreach ($question_list as $question) : ?>
    <input type="hidden" id="quiz_index_<?php echo $question['id'];?>" value=<?php echo $quiz_index; ?>>
    <!-- content + buttons  -->
    <div class="row">
        <div class="col-8">
            <br>
            <div class="form-group">
                <textarea class="form-control" id="content_<?php echo $question['id'];?>" rows="18" style="resize:none" placeholder="<?php echo $question['content']; ?>"></textarea>
            </div>
        </div>
        <div class="col-4">
            <br>
            <div class="d-flex flex-column">
                <div class="p-2">
                    <div class="form-group">
                        <select class="form-control" id="timerType_<?php echo $question['id'];?>">
                            <option value="" diabled>Timer Type</option>
                            <option value="timeup" <?php if ($question['timer_type'] == "timeup") echo "selected"; ?>>timeup</option>
                            <option value="timedown" <?php if ($question['timer_type'] == "timedown") echo "selected"; ?>>timedown</option>
                        </select>
                        <small class="form-text text-muted">timer type</small>
                    </div>
                </div>
                <div class="p-2">
                    <div class="form-group">
                        <select class="form-control" id="isPublic_<?php echo $question['id'];?>">
                            <option value="" diabled>Access</option>
                            <option value="false" <?php if ($question['is_public'] == "false") echo "selected"; ?>>private</option>
                            <option value="true" <?php if ($question['is_public'] == "true") echo "selected"; ?>>public</option>
                        </select>
                        <small class="form-text text-muted">access</small>
                    </div>
                </div>
                <div class="p-2">
                    <div class="form-group">
                        <select class="form-control" id="difficulty_<?php echo $question['id'];?>">
                            <option value="" diabled>Difficulty</option>
                            <option value="easy" <?php if ($question['difficulty'] == "easy") echo "selected"; ?>>Easy</option>
                            <option value="medium" <?php if ($question['difficulty'] == "medium") echo "selected"; ?>>Medium</option>
                            <option value="hard" <?php if ($question['difficulty'] == "hard") echo "selected"; ?>>Hard</option>
                        </select>
                        <small class="form-text text-muted">difficulty</small>
                    </div>
                </div>
                <div class="p-2">
                    <div class="form-group">
                        <input type="text" class="form-control" id="category_<?php echo $question['id']; ; ?>" placeholder="<?php echo $question['category']; ?>">
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
    <!-- answer type -->
    <div class="row">
        <div class="col-md-12">
            <p>Answer Type</p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="answer_type_<?php echo $question['id']; ?>" value="true_or_false" <?php if ($question['question_type'] == "true_or_false") echo "checked"; ?>>
                <label class="form-check-label">True or False</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="answer_type_<?php echo $question['id']; ?>" value="multiple_answer" <?php if ($question['question_type'] == "multiple_answer") echo "checked"; ?>>
                <label class="form-check-label">Multiple Answers</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input " type="radio" name="answer_type_<?php echo $question['id']; ?>" value="single_answer" <?php if ($question['question_type'] == "single_answer") echo "checked"; ?>>
                <label class="form-check-label">Single Answer</label>
            </div>
        </div>
    </div>
    <!-- answer/choices -->
    <div class="row">
        <div class="float-left inline-block">
            <div class="col-md-12 choice_row" id="choice_row_<?php echo $question['id']; ?>">
                <p>Choices</p>
                <?php if ($question['question_type'] == "true_or_false") : ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="choice_row_<?php echo $question['id']; ?>" value="true" <?php if ($question['answer'] == "true") echo "checked"; ?>>
                        <label class="form-check-label">True</label></div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="choice_row_<?php echo $question['id']; ?>" value="false" <?php if ($question['answer'] == "false") echo "checked"; ?>>
                        <label class="form-check-label">False</label></div>
                <?php elseif ($question['question_type'] == "multiple_answer") : ?>
                    <?php $choices = (json_decode($question['choices']));
                    foreach ($choices as $choice) : ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="choice_row_<?php echo $question['id']; ?>" value="<?php echo $choice; ?>" <?php if ($choice == $question['answer']) echo "checked" ?>>
                            <label class="form-check-label" contenteditable="true"><?php echo $choice; ?></label>
                        </div>
                    <? endforeach; ?>
                <?php else : ?>
                    <?php $choices = (json_decode($question['choices']));
                    foreach ($choices as $choice) : ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="choice_row_<?php echo $question['id']; ?>" value="<?php echo $choice; ?>" <?php if ($choice == $question['answer']) echo "checked" ?>>
                            <label class="form-check-label" contenteditable="true"><?php echo $choice; ?></label>
                        </div>
                    <? endforeach; //end choices 
                    ?>

                <? endif; //end  question type
                ?>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-outline-primary offset-md-11 update_question" name="update_question_<?php echo $question['id']; ?>" id="<?php echo $question['id']; ?>">Update</button>
    <br><br>
    <div class="border-top my-3 d-block"></div>
<?php endforeach; //end question_list 
?>

<button type="button" class="btn btn-outline-primary" id="new_question">New Question</button>

<script>
    $(document).ready(() => {


        $('#new_question').click((e) => {
            location.replace(<?php echo "'" . base_url() . "questions/create/" . $quiz_index . "'"; ?>);
        })

        $("input[name^='answer_type_']").change(() => {
            console.log($("input[name^='answer_type_']:checked").attr('name'));
        }) //end of radio toggle

        $("button").click(function() {
            //update question
            if (this.id != "new_question") {
                // alert(this.id); // or alert($(this).attr('id'));
                content = ($('#content_'+this.id).val()=="") ? $('#content_'+this.id).attr('placeholder') : $('#content_'+this.id).val();
                category = ($('#category_'+this.id).val()=="") ? $('#category_'+this.id).attr('placeholder') : $('#category_'+this.id).val();
                duration = ($('#duration_'+this.id).val()=="") ? $('#duration_'+this.id).attr('placeholder') : $('#duration_'+this.id).val();
                // console.log($('#category_'+this.id).attr('placeholder'));
                $.ajax({
                    url: "<?php echo base_url(); ?>questions/update_question",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        'id' : this.id,
                        'quiz_index': $('#quiz_index_'+this.id).val(),
                        'timer_type': $('#timerType_'+this.id).val(),
                        'duration': duration,
                        'content': content,
                        'isPublic': $('#isPublic_'+this.id).val(),
                        'difficulty': $('#difficulty_'+this.id).val(),
                        'category': category
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
            }
        });
    })
</script>