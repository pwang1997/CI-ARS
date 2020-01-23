<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>

</div>

<div class="row">
    <div class="col-2">
        <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-quiz-detail" data-toggle="list" href="#list-quiz" role="tab" aria-controls="quiz">Quiz</a>
            <a class="list-group-item list-group-item-action" id="list-add-question" data-toggle="list" href="#list-add" role="tab" aria-controls="add-question">Add Question</a>

        </div>
    </div>
    <div class="col-10">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-quiz" role="tabpanel" aria-labelledby="list-quiz-detail">
                <h2><?= $title; ?></h2>
                <p><strong>Lab Index: </strong><?php echo $lab_index; ?></p>
            </div>
            <div class="tab-pane fade" id="list-add" role="tabpanel" aria-labelledby="list-add-question">
                <?php echo form_open('questions/create'); ?>
                <div class="mx-auto">
                    <div class="form-group">
                        <label for="timer_type">Timer Type</label>
                        <select class="form-control" id="timer_type" name="timer_type">
                            <option>count down</option>
                            <option>count up</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question_type">Question Type</label>
                        <select class="form-control" id="question_type" name="question_type">
                            <option>True/False</option>
                            <option>Multiple Choice(Single Answer)</option>
                            <option>Multiple Choice(Multiple Answer)</option>
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <label for="num_choices">Number of Choices</label>
                        <select class="form-control" id="num_choices" name="num_choices">
                            <?php for ($i = 2; $i <= 10; $i++) : ?>
                                <option><?php //echo $i; 
                                        ?></option>
                            <? endfor; ?>
                        </select>
                    </div> -->
                    <div class="form-group">
                        <label for="duration">Duration(s)</label>
                        <input type="text" class="form-control" id="duration" name="duration" placeholder="Duration of the question">
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="3" spellcheck="true" placeholder="Enter Context of the Question"></textarea>
                    </div>

                    <div class="form-group" id="choices">
                        <label for="choices">Answer</label>
                        <div id="true_or_false">
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="choices" value="True">
                                <label class="form-check-label" for="choices">True</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="choices" value="False">
                                <label class="form-check-label" for="choices">False</label></div>
                        </div>
                    </div>
                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(() => {
        $("#question_type").change((e) => {
            // selected = $("#question_type option:selected").text();
            // num_choices = $("#num_choices option:selected").text();
            // target = $("#choices").children();
            // alert(selected);
            // if (selected == "True/False") {
            //     $("#true_or_false").show();
            //     $("#single_answer").hide();
            //     $("#multiple_answer").hide();
            // } else if (selected == "Multiple Choice(Single Answer)") {
            //     $("#true_or_false").hide();
            //     $("#single_answer").show();
            //     $("#multiple_answer").hide();
            // } else {
            //     $("#true_or_false").hide();
            //     $("#single_answer").hide();
            //     $("#multiple_answer").show();
            // }
        });

        $("#submit").click((e) => {
            e.preventDefault();
            timer_type = $("#timer_type option:selected").text();
            question_type = $("#question_type option:selected").text();
            // num_choices = $("#num_choices option:selected").text();
            duration = $("#duration").val();
            content = $("#content").val();

            choices = "true/false";
            answer = $("input[name=choices]:checked").val();;

            $.ajax({
                url: "<?php echo base_url(); ?>questions/create_question",
                type: "POST",
                dataType: "JSON",
                data: {
                    "timer_type" : timer_type,
                    "question_type" : question_type,
                    "duration" : duration,
                    "content" : content,
                    "answer" : answer,
                    "choices" : choices,
                    'lab_index' :<?php echo $lab_index; ?>
                },
                success: function(response) {
                    if(response.success) {
                        alert("success");
                    } else {
                        alert("failed to insert question1");
                    }
                },
                fail: function() {
                    alert("failed to insert question2");
                }
            })
        });

        // function true_or_false(element) {
        //     target.empty();
        //     target.append('<div class="form-check"><input class="form-check-input" type="radio" name="choices" value="True">' +
        //         '<label class="form-check-label" for="choices">True</label></div>');
        //     target.append('<div class="form-check"><input class="form-check-input" type="radio" name="choices" value="False">' +
        //         '<label class="form-check-label" for="choices">False</label></div>');
        // }

        // function multiple_choices_single_answer(element, num_choice) {
        //     element.empty();
        // }

        // function multiple_choices_multiple_answer(element, num_choice) {
        //     element.empty();
        // }
    });
</script>