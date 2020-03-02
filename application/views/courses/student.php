</div><!-- end of container -->

<div class="row">
    <div class="col-2">
        <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-course-detail" data-toggle="list" href="#list-course" role="tab" aria-controls="lab">Course Detail</a>
            <a class="list-group-item list-group-item-action" id="list-quiz-list" data-toggle="list" href="#list-quiz" role="tab" aria-controls="quiz">Quizs</a>
            <a class="list-group-item list-group-item-action" id="list-grade-list" data-toggle="list" href="#list-grade" role="tab" aria-controls="grade">Grade</a>

        </div>
    </div>
    <div class="col-10">
        <div class="tab-content" id="nav-tabContent">
            <!-- course detail  -->
            <div class="tab-pane fade show active" id="list-course" role="tabpanel" aria-labelledby="list-course-detail">
                <h2><?= $title; ?></h2>
                <p><strong>Course Name: </strong> <?php echo $course_info['course_name']; ?></p>
                <p><strong>Course Code: </strong> <?php echo $course_info['course_code']; ?></p>
                <p><strong>Section Number: </strong> <?php echo $course_info['section_id']; ?></p>
                <p><strong>Description: </strong> <?php echo $course_info['description']; ?></p>
            </div>

            <div class="tab-pane fade" id="list-quiz" role="tabpanel" aria-labelledby="list-quiz-list">
                <div class="card-groups">
                    <?php
                    for ($i = 0; $i < sizeof($quizs); $i++) {
                        if ($i % 3 == 0) {
                    ?>
                            <div class="row">
                                <?php addedCard($i + 1, $quizs[$i]['quiz_index']); ?>
                                <div class="col-md-1"></div>
                            <?php } elseif ($i % 3 == 2) {
                            ?>
                                <?php addedCard($i + 1, $quizs[$i]['quiz_index']); ?>
                            </div>
                        <?php } else { ?>
                            <?php addedCard($i + 1, $quizs[$i]['quiz_index']); ?>
                            <div class="col-md-1"></div>
                    <?php }
                    }
                    ?>
                    <?php if (sizeof($quizs) % 3 != 0) : ?>
                    <?php endif; ?>
                    <?php
                    function addedCard($index, $quiz_id)
                    {
                        $base_url = base_url();
                        echo "<div class='card bg-outline-primary mb-3 col-md-3' id='card_$quiz_id'>
		                        <div class='card-body'>
		                            <h5 class='card-title'><a href=' {$base_url}questions/student/$quiz_id' class='text-secondary'>Quiz $index</a></h5>
		                        </div>
	                        </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>