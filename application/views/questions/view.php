<link href="<?= base_url(); ?>/css/quill_editor_custom.css" rel="stylesheet">
<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
<div class="pb-2" id="question_<?= $question['id'] ?>">
    <!-- timer type  -->
    <div class="row">
        <div class="form-group">
            <label for="timerType_<?php echo $question['id']; ?>" class="col-sm-6 col-form-label">Timer Type</label>
            <div class="col-sm-10">
                <div class="btn-group btn-group-toggle" data-toggle="buttons" id="timerType_<?php echo $question['id']; ?>">
                    <label class="btn btn-outline-primary <?php if ($question['timer_type'] == "timeup") echo " active"; ?>">
                        <input type="radio" name="timer_types_<?= $question['id']; ?>" value="timeup" autocomplete="off"> Time Up
                    </label>

                    <label class="btn btn-outline-primary <?php if ($question['timer_type'] == "timedown") echo " active"; ?>">
                        <input type="radio" name="timer_types_<?= $question['id']; ?>" value="timedown" autocomplete="off"> Time Down
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- public access of the question -->
    <div class="form-group row">
        <label for="isPublic_<?php echo $question['id']; ?>" class="col-sm-6 col-form-label">Access</label>
        <div class="col-sm-10">
            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="isPublic">
                <label class="btn btn-outline-primary <?php if ($question['is_public'] == "false") echo " active"; ?>">
                    <input type="radio" name="accesses_<?= $question['id']; ?>" value="false" autocomplete="off"> Private
                </label>
                <label class="btn btn-outline-primary <?php if ($question['is_public'] == "true") echo " active"; ?>">
                    <input type="radio" name="accesses_<?= $question['id']; ?>" value="true" autocomplete="off"> Public
                </label>
            </div>
        </div>
    </div>

    <!-- difficulty of the question  -->
    <div class="form-group row">
        <label for="difficulty_<?php echo $question['id']; ?>" class="col-sm-6 col-form-label">Difficulty</label>
        <div class="col-sm-10">
            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="difficulty">
                <label class="btn btn-outline-primary <?php if ($question['difficulty'] == "easy") echo " active"; ?>">
                    <input type="radio" name="difficulties_<?= $question['id']; ?>" value="easy" autocomplete="off"> Easy
                </label>
                <label class="btn btn-outline-primary  <?php if ($question['difficulty'] == "medium") echo " active"; ?>">
                    <input type="radio" name="difficulties_<?= $question['id']; ?>" value="medium" autocomplete="off"> Medium
                </label>
                <label class="btn btn-outline-primary  <?php if ($question['difficulty'] == "hard") echo " active"; ?>">
                    <input type="radio" name="difficulties_<?= $question['id']; ?>" value="hard" autocomplete="off"> Hard
                </label>
            </div>
        </div>
    </div>

    <!-- category of the question -->
    <div class="form-group row">
        <label for="category" class="col-sm-2 col-form-label">Category</label>
        <div class="col-sm-6">
            <input type="text" id="category_<?php echo $question['id']; ?>" class="form-control" name="category_<?= $question['id']; ?>" value="<?php echo $question['category']; ?>" autocomplete="on">
        </div>
    </div>

    <!-- duration of the question  -->
    <div class="form-group row">
        <label for="duration" class="col-sm-2 col-form-label">Duration</label>
        <div class="col-sm-6">
            <input type="text" id="duration_<?php echo $question['id']; ?>" class="form-control" name="duration_<?= $question['id']; ?>" placeholder="duration(in second)" value="<?php echo $question['duration']; ?> s" autocomplete="off">
        </div>
    </div>

    <div class="row editor-container">
        <div class="col-sm-8" id="scrolling-container">
            <div class="editor" id="editor" style=" height: 350px; flex: 1; overflow-y: auto; width: 100%;"><?= $question['content']; ?></div>
        </div>
    </div>

    <!-- answer heading -->
    <div class="row">
        <div class="col-sm-2 offset-sm-8">
            <h6>Answer</h6>
        </div>
    </div>

    <!-- answer/choices -->
    <div id="option_row<?= $question['id']; ?>">
        <?php $choices = (json_decode($question['choices']));
        $answers = json_decode($question['answer']);
        $i = 1;
        foreach ($choices as $choice) : ?>
            <div class="form-group row choice_row">
                <label for="choice<?= $i; ?>" class="col-sm-12 col-md-2 col-form-label">:Choice <?= $i; ?></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="choice<?= $i; ?>" id="<?php echo $question['id'] . "_" . $i ?>" autocomplete="on" value="<?php echo $choice; ?>">
                </div>

                <div class="custom-control custom-checkbox col-sm-1 ml-3">
                    <input type="checkbox" class="custom-control-input  " id="customCheck_<?= $i; ?>_<?= $question['id']; ?>" name="choice_row_<?php echo $question['id']; ?>" value="<?php echo $choice ?>" <?php if (in_array($choice, $answers)) echo "checked"; ?>>
                    <label class="custom-control-label" for="customCheck_<?= $i; ?>_<?= $question['id']; ?>"></label>
                </div>
            </div>
        <?php $i++;
        endforeach; ?>
    </div>
</div>

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
                    <option value="" selected>Course</option>
                    <?php foreach ($courses as $course) : ?>
                        <option value="<?php echo $course['course_name']; ?>"><?php echo $course['course_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="border-top my-3 d-block"></div>

                <!-- classroom selection  -->
                <select class="form-control" id="classroom">
                    <option value="" selected>Classroom</option>
                </select>
                <div class="border-top my-3 d-block"></div>

                <!-- quiz selection  -->
                <select class="form-control" id="quiz">
                    <option value="" selected>Quiz</option>
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

        var quill = new Quill('#editor', {
            modules: {
                'toolbar': false
            },
            scrollingContainer: '#scrolling-container',
            placeholder: 'Question Content',
            theme: 'snow' // or 'bubble'
        });
        quill.enable(false);

    })
</script>