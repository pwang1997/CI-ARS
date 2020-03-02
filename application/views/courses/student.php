</div><!-- end of container -->
<div class="container-fluid">
    <div class="row" style="height: 100%">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" id="list-course-detail" data-toggle="list" href="#list-course" role="tab" aria-controls="course">Course Detail
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="list-quiz-list" data-toggle="list" href="#list-quiz" role="tab" aria-controls="quiz">Quizs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="list-student-list" data-toggle="list" href="#list-student" role="tab" aria-controls="student">Grade
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="col-10">
            <div class="tab-content" id="nav-tabContent">
                <!-- course detail  -->
                <div class="tab-pane fade show active" id="list-course" role="tabpanel" aria-labelledby="list-course-detail">
                    <h3><?= $title; ?></h3>
                    <hr>
                    <p><strong>Course Name: </strong> <?php echo $course_info['course_name']; ?></p>
                    <p><strong>Course Code: </strong> <?php echo $course_info['course_code']; ?></p>
                    <p><strong>Section Number: </strong> <?php echo $course_info['section_id']; ?></p>
                    <p><strong>Description: </strong> <?php echo $course_info['description']; ?></p>
                </div>

                <div class="tab-pane fade" id="list-quiz" role="tabpanel" aria-labelledby="list-quiz-list">
                    <h3>Quiz List</h3>
                    <hr>

                    <?php
                    for ($i = 0; $i < sizeof($quizs); $i++) {
                        if ($i % 3 == 0) :
                            echo '<div class="row">';
                            addedCard($i + 1, $quizs[$i]['quiz_index']);;
                        elseif ($i % 3 == 2) :
                            addedCard($i + 1, $quizs[$i]['quiz_index']);;
                            echo '</div>';
                        else :
                            addedCard($i + 1, $quizs[$i]['quiz_index']);;
                        endif;
                    }
                    if (sizeof($quizs) % 3 != 0) :
                        echo '</div>';
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
function addedCard($index, $quiz_id)
{
    $base_url = base_url();
    echo "
    <div class='col-sm-3 py-2 ml-2'>
        <div class='card bg-outline-primary mb-3' id='card_$quiz_id'>
            <div class='card-student-course'>
                <div class='card-body'>
                    <h6 class='card-text text-center pt-5'><a href=' {$base_url}questions/student/$quiz_id' class='text-secondary'>Quiz $index</a></h6>
                </div>
            </div>
        </div>
    </div>";
}
?>