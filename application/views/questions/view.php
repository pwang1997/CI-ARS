<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>


<!-- content + buttons  -->
<div class="row">
    <div class="col-8">
        <br>
        <div class="form-group">
            <!-- <textarea disabled class="form-control" id="content" rows="18" style="resize:none" placeholder="<?php //echo $question['content']; 
                                                                                                                    ?>"></textarea> -->
            <div class="form-group row" style="position:relative;">
                <div class="col-sm-8" id="scrolling-container" style="height:425px; min-width:100%; min-height:100%">
                    <div id="editor" style="min-height:100%; height:auto;"><?= $question['content']; ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <br>
        <div class="d-flex flex-column">
            <div class="p-2">
                <div class="form-group">
                    <select disabled class="form-control" id="timerType">
                        <option value="" disabled>Timer Type</option>
                        <option value="timeup" <?php if ($question['timer_type'] == "timeup") echo "selected"; ?>>timeup</option>
                        <option value="timedown" <?php if ($question['timer_type'] == "timedown") echo "selected"; ?>>timedown</option>
                    </select>
                    <small class="form-text text-muted">timer type</small>
                </div>
            </div>
            <div class="p-2">
                <div class="form-group">
                    <select disabled class="form-control" id="isPublic">
                        <option value="" disabled>Access</option>
                        <option value="false" <?php if ($question['is_public'] == "false") echo "selected"; ?>>private</option>
                        <option value="true" <?php if ($question['is_public'] == "true") echo "selected"; ?>>public</option>
                    </select>
                    <small class="form-text text-muted">access</small>
                </div>
            </div>
            <div class="p-2">
                <div class="form-group">
                    <select disabled class="form-control" id="difficulty">
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
                    <input disabled type="text" class="form-control" id="category" placeholder="<?php echo $question['category']; ?>">
                    <small class="form-text text-muted">categroy</small>
                </div>
            </div>
            <div class="p-2">
                <div class="form-group">
                    <input disabled type="text" class="form-control" id="duration" placeholder="<?php echo $question['duration']; ?> s">
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
<?php $choices = (json_decode($question['choices']));
$answers = json_decode($question['answer']);
$i = 1;
foreach ($choices as $choice) : ?>
    <div class="form-group row choice_row">
        <label for="choice<?= $i; ?>" class="col-sm-2 col-form-label">:Choice <?= $i; ?></label>
        <div class="col-sm-6">
            <input type="text" disabled choice_row class="form-control" name="choice_row" autocomplete="on" placeholder="<?php echo $choice; ?>">
        </div>
        <div class="form-check col-sm-1">
            <input class="form-check-input" disabled type="checkbox" name="answers" value="correct" <?php if (in_array($choice, $answers)) echo "checked"; ?>>
        </div>
    </div>
<? $i++;
endforeach; ?>


<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal">
    Add to quiz
</button>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- course selection  -->
                <select class="form-control" id="course">
                    <option value="" disabled selected>Course</option>
                    <?php foreach ($courses as $course) : ?>
                        <option value="<?php echo $course['course_name']; ?>"><?php echo $course['course_name']; ?></option>
                    <? endforeach; ?>
                </select>
                <div class="border-top my-3 d-block"></div>

                <!-- classroom selection  -->
                <select class="form-control" id="classroom">
                    <option value="" disabled selected>Classroom</option>
                </select>
                <div class="border-top my-3 d-block"></div>

                <!-- quiz selection  -->
                <select class="form-control" id="quiz">
                    <option value="" disabled selected>Quiz</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add_to_quiz">Add To Quiz</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        $('#add_to_quiz').click(function() {

        })

        $('#course').change(function() {

        })

        var quill = new Quill('#editor', {
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
        quill.enable(false);
    })
</script>