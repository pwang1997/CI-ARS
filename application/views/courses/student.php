<script src="<?= base_url(); ?>js/users/dashboard.js"></script>

<?php if (strcmp($this->session->role, 'teacher') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
</div><!-- end of container -->
<div class="container-fluid">
    <div class="row" style="height: 100%">
        <!-- <nav class="col-md-2 d-none d-md-block bg-light sidebar"> -->
        <nav class="col-md-2 nav flex-column nav-pills bg-light  sidebar pr-0" style="min-height:100px" aria-orientation="vertical">
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
                        <a class="nav-link" id="list-grade-list" data-toggle="list" href="#list-grade" role="tab" aria-controls="student">Grade
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
                    <div class="my-3 p-3 bg-white rounded shadow-sm">
                        <h6 class="border-bottom border-gray pb-2 mb-0">Quiz list</h6>
                        <?php
                        for ($i = 0; $i < sizeof($quizs); $i++)
                            addMeida($i + 1, $quizs[$i]['quiz_index']);
                        ?>
                    </div>
                    <?php
                    // for ($i = 0; $i < sizeof($quizs); $i++) {
                    //     if ($i % 3 == 0) :
                    //         echo '<div class="row">';
                    //         addedCard($i + 1, $quizs[$i]['quiz_index']);;
                    //     elseif ($i % 3 == 2) :
                    //         addedCard($i + 1, $quizs[$i]['quiz_index']);;
                    //         echo '</div>';
                    //     else :
                    //         addedCard($i + 1, $quizs[$i]['quiz_index']);;
                    //     endif;
                    // }
                    // if (sizeof($quizs) % 3 != 0) :
                    //     echo '</div>';
                    // endif;
                    ?>
                </div>
                <div class="tab-pane fade" id="list-grade" role="tabpanel" aria-labelledby="list-grade-list">
                    <h3 class=" border-gray pb-2 mb-0">Grade list</h3>
                    <hr>
                    <?php foreach ($questions as $question) : ?>
                        <?php $i = 1; ?>
                        <?php if (count($question) > 0) : ?>
                            <?php if (count($question[$i - 1]) > 0) : ?>
                                <div class="accordion" id="accordion_<?= $question[$i - 1]['id']; ?>">
                                    <div class="card">
                                        <div class="card-header" id="heading_<?= $question[$i - 1]['id']; ?>">
                                            <h6 class="mb-0 row">
                                                <button class="btn btn-primary col-md-3" type="button" data-toggle="collapse" data-target="#collapse_<?= $question[$i - 1]['id']; ?>" aria-expanded="true" aria-controls="collapse_<?= $question[$i - 1]['id']; ?>">
                                                    <?php echo "Date: {$question[$i - 1]['time_created']}" ?>
                                                </button>
                                                <p class="offset-md-5 col-md-4 mb-0">
                                                    Score:
                                                    <?php
                                                    $count = 0;
                                                    $total_question = count($question);
                                                    foreach ($question as $q) {
                                                        if ($student_responses[$q['id']][0]['answer'] === $q['answer']) {
                                                            $count++;
                                                        }
                                                    }
                                                    echo "{$count}/{$total_question}";
                                                    ?>
                                                </p>
                                            </h6>
                                        </div>
                                        <div id="collapse_<?= $question[$i - 1]['id']; ?>" class="collapse" aria-labelledby="heading_<?= $question[$i - 1]['id']; ?>" data-parent="#accordion_<?= $question[$i - 1]['id']; ?>">
                                            <div class="card-body table-responsive">
                                                <?php if (count($question) > 0) : ?>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Question</th>
                                                                <th scope="col">Content</th>
                                                                <th scope="col">Your Choice</th>
                                                                <th scope="col">Answer</th>
                                                                <th scope="col">Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($question as $q) : ?>
                                                                <tr class=<?php if ($student_responses[$q['id']][0]['answer'] !== $q['answer']) echo "table-danger";
                                                                            else echo "table-success"; ?>>
                                                                    <th scope="row" id="<?= $q['id']; ?>"><?= $i++; ?></th>
                                                                    <td><?= $q['content']; ?></td>
                                                                    <td><?= $student_responses[$q['id']][0]['answer']; ?></td>
                                                                    <td><?= $q['answer']; ?></td>
                                                                    <td><?= $q['time_created']; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
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

<?php
function addMeida($index, $quiz_id)
{
    $base_url = base_url();
    echo  " <div class='media text-muted pt-3'>
    <a href='{$base_url}questions/student/{$quiz_id}'><div class='square'></div></a>
    <p class='media-body pb-3 mb-0 small lh-125 border-bottom border-gray'>
      <a href='{$base_url}questions/student/{$quiz_id}'><strong class='h5 d-block text-gray-dark'>Quiz {$index}</strong></a>
    </p>
  </div>";
}
?>