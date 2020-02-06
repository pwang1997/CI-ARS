<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
<h2><?php echo $title; ?></h2>

<div class="form_question">
    <?php echo form_open('questions/create'); ?>
    <input type="hidden" id="quiz_index" value=<?php echo $lab_index; ?>>
    <!-- timer type  -->
    <div class="form-group row">
        <label for="timerType" class="col-sm-2 col-form-label">Timer Type</label>
        <div class="col-sm-10">
            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="timerType">
                <label class="btn btn-outline-primary">
                    <input type="radio" name="timer_types" id="time_up" value="timeup" autocomplete="off"> Time Up
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" name="timer_types" id="time_down" value="timedown" autocomplete="off"> Time Down
                </label>
            </div>
        </div>
    </div>
    <!-- public access of the question -->
    <div class="form-group row">
        <label for="isPublic" class="col-sm-2 col-form-label">Access</label>
        <div class="col-sm-10">
            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="isPublic">
                <label class="btn btn-outline-primary">
                    <input type="radio" name="accesses"  value="false" autocomplete="off"> Private
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" name="accesses" value="true" autocomplete="off"> Public
                </label>
            </div>
        </div>
    </div>
    <!-- difficulty of the question  -->
    <div class="form-group row">
        <label for="difficulty" class="col-sm-2 col-form-label">Difficulty</label>
        <div class="col-sm-10">
            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="difficulty">
                <label class="btn btn-outline-primary">
                    <input type="radio" name="difficulties" id="easy" value="easy" autocomplete="off"> Easy
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" name="difficulties" id="medium" value="medium" autocomplete="off"> Medium
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" name="difficulties" id="hard" value="hard" autocomplete="off"> Hard
                </label>
            </div>
        </div>
    </div>
    <!-- category of the question -->
    <div class="form-group row">
        <label for="category" class="col-sm-2 col-form-label">Category</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="category" placeholder="category" autocomplete="on">
        </div>
    </div>
    <!-- duration of the question  -->
    <div class="form-group row">
        <label for="duration" class="col-sm-2 col-form-label">Duration</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="duration" placeholder="duration(in second)" autocomplete="off">
        </div>
    </div>
    <!-- Quill editor  -->
    <div class="row" style="position:relative;">
        <div class="col-sm-8" id="scrolling-container" style="height:10em; min-height:100%">
            <div id="editor" style="min-height:100%; height:auto;"></div>
        </div>
    </div>
    <!-- answer heading -->
    <div class="form-group row" style="margin-top:3em;">
        <div class="col-sm-2 offset-sm-8" style="padding-left: 0px;">Answer</div>
    </div>
    <!-- choice content + answer -->
    <?php for ($i = 1; $i <= 4; $i++) : ?>
        <div class="form-group row choice_row">
            <label for="choice<?= $i; ?>" class="col-sm-2 col-form-label">:Choice <?= $i; ?></label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="choice<?= $i; ?>" autocomplete="on">
            </div>
            <div class="form-check col-sm-1">
                <input class="form-check-input" type="checkbox" name="answers" value="correct">
            </div>
        </div>
    <?php endfor; ?>
    <div class="additional_choices"></div>
    <!-- add choices/ remove empty choices -->
    <div class="form-group row">
        <button type="button" class="btn btn-primary" id="add_more_choices">Add</button>
        <button type="button" class="btn btn-primary" id="remove_blanks">Remove</button>
    </div>

    <!-- submit -->
    <div class="row">
        <input type="submit" class="btn btn-outline-primary btn-block" value="Submit">
    </div>
    <?php echo form_close(); ?>
</div>

<script>
    $(document).ready(() => {
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
        
        //add n choices
        $('#add_more_choices').click(function() {
            var moreChoices = `<div class="form-group row choice_row">
            <label for="choice<?= $i; ?>" class="col-sm-2 col-form-label">:Choice <?php echo $i; ?></label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="choice<?php echo $i; ?>" autocomplete="on">
            </div>
            <div class="form-check col-sm-1">
                <input class="form-check-input" type="checkbox" name="answers" value="correct">
            </div>
        </div>`;
            <?php $i++; ?>
            $('.additional_choices').append(moreChoices);
        });

        $('#remove_blanks').click(function() {
            <?php $i--; ?>
            $('.additional_choices').children().last().remove();
        });

        //submit form
        $("input[type='submit']").click((e) => {
            e.preventDefault();

            choices = [];
            answers = [];
            //get all values of choices
            $('input[name="answers"]').each(function() {
                if ($(this).is(':checked')) {
                    answers.push($(this).parent().prev().children().first().val());
                } 
                    choices.push($(this).parent().prev().children().first().val());
            });

            choices = choices.filter(Boolean);

            $.ajax({
                url: "<?php echo base_url(); ?>questions/create_question",
                type: "POST",
                dataType: "JSON",
                data: {
                    'quiz_index': $('#quiz_index').val(),
                    'timer_type': $('input[name=timer_types]:checked').val(),
                    'duration': $('input[name=duration]').val(),
                    'content': quill.getContents()['ops'][0]['insert'],
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
</script>