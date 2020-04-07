<script src="<?= base_url(); ?>js/users/dashboard.js"></script>

<?php if (strcmp($this->session->role, 'teacher') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>

<!-- <nav class="col-md-2 d-none d-md-block bg-light sidebar"> -->

<div class="tab-content" id="nav-tabContent">
    <!-- course detail  -->
    <div class="tab-pane fade show active" id="list-course" role="tabpanel" aria-labelledby="list-course-detail">
        <?php if (isset($course_info)) : ?>
            <p><strong>Course Name: </strong> <?php echo $course_info['course_name']; ?></p>
            <p><strong>Course Code: </strong> <?php echo $course_info['course_code']; ?></p>
            <p><strong>Section Number: </strong> <?php echo $course_info['section_id']; ?></p>
            <p><strong>Description: </strong> <?php echo $course_info['description']; ?></p>
        <?php endif; ?>
    </div>

    <div class="tab-pane fade" id="list-quiz" role="tabpanel" aria-labelledby="list-quiz-list">
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <?php
            for ($i = 0; $i < sizeof($quizs); $i++)
                addMeida($i + 1, $quizs[$i]['quiz_index']);
            ?>
        </div>
    </div>
    <div class="tab-pane fade" id="list-grade" role="tabpanel" aria-labelledby="list-grade-list">

    </div>
</div>

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