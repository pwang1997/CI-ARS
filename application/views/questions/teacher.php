<script src="<?= base_url(); ?>js/questions/teacher.js"></script>
<link href="<?= base_url(); ?>/css/quill_editor_custom.css" rel="stylesheet">
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
    <div class="pb-2" id="question_<?= $question['id'] ?>">
        <div class="border-top my-3 d-block"></div>
        <h6>Question <?= $j++; ?></h6>
        <input type="hidden" id="quiz_index_<?php echo $question['id']; ?>" value=<?php echo $quiz_index; ?> />

        <!-- timer type  -->
        <div class="form-group row">
            <label for="timer_types_<?php echo $question['id']; ?>" class="col-sm-6 col-form-label">Timer Type</label>
            <div class="col-sm-10">
                <div class="btn-group btn-group-toggle" data-toggle="buttons" id="timer_types_<?php echo $question['id']; ?>">
                    <label class="btn btn-outline-primary <?php if ($question['timer_type'] == "timeup") echo " active"; ?>">
                        <input type="radio" name="timer_types_<?= $question['id']; ?>" value="timeup" autocomplete="off"> Time Up
                    </label>

                    <label class="btn btn-outline-primary <?php if ($question['timer_type'] == "timedown") echo " active"; ?>">
                        <input type="radio" name="timer_types_<?= $question['id']; ?>" value="timedown" autocomplete="off"> Time Down
                    </label>
                </div>
            </div>
        </div>

        <!-- public access of the question -->
        <div class="form-group row">
            <label for="accesses_<?php echo $question['id']; ?>" class="col-sm-6 col-form-label">Access</label>
            <div class="col-sm-10">
                <div class="btn-group btn-group-toggle" data-toggle="buttons" id="accesses_<?php echo $question['id']; ?>">
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
            <label for="difficulties_<?php echo $question['id']; ?>" class="col-sm-6 col-form-label">Difficulty</label>
            <div class="col-sm-10">
                <div class="btn-group btn-group-toggle" data-toggle="buttons" id="difficulties_<?php echo $question['id']; ?>">
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
                <div class="editor" id="editor_<?= $question['id']; ?>" style=" height: 350px; flex: 1; overflow-y: auto; width: 100%;"><?= $question['content']; ?></div>
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
                        <input type="checkbox" class="custom-control-input " id="customCheck_<?= $i; ?>_<?= $question['id']; ?>" name="choice_row_<?php echo $question['id']; ?>" value="<?php echo $choice ?>" <?php if (in_array($choice, $answers)) echo "checked"; ?>>
                        <label class="custom-control-label" for="customCheck_<?= $i; ?>_<?= $question['id']; ?>"></label>
                    </div>
                </div>
            <?php $i++;
            endforeach; ?>
        </div>
        <!-- add choices/ remove empty choices -->
        <div class="row">
            <div class="col-sm-2 pb-1">
                <button style="width:100%" type="button" class="btn btn-primary add" id="add_<?= $question['id'] ?>">Add</button>
            </div>
            <div class="col-sm-2 pb-1">
                <button style="width:100%" type="button" class="btn btn-primary remove" id="rmv_<?= $question['id'] ?>">Remove</button>
            </div>
            <div class="offset-sm-2 col-sm-2 pb-1">
                <button style="width:100%" type="button" class="btn btn-outline-primary offset-md-8 update" name="update_question_<?php echo $question['id']; ?>" id="<?php echo $question['id']; ?>">Update</button>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<div class="border-top my-3 d-block"></div>
<button type="button" class="btn btn-outline-primary" id="new_question">New Question</button>