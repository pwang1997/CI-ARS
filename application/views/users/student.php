<?php if (strcmp($this->session->role, 'teacher') == 0) : ?>
  <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
  <?php redirect('users/login'); ?>
<?php endif; ?>
<h3 class="border-bottom pb-2 pt-2 mt-2">Dashboard </h3>

<br>
<?php
for ($i = 0; $i < sizeof($course_list); $i++) {
  if ($i % 3 == 0) :
    echo '<div class="card-group">';
    addedCard($course_list[$i]['course_name'], $course_list[$i]['course_id'], $course_list[$i]['classroom_id']);
  elseif ($i % 3 == 2) :
    addedCard($course_list[$i]['course_name'], $course_list[$i]['course_id'], $course_list[$i]['classroom_id']);
    echo '</div>';
  else :
    addedCard($course_list[$i]['course_name'], $course_list[$i]['course_id'], $course_list[$i]['classroom_id']);
  endif;
} ?>

<?php if (sizeof($course_list) % 3 != 0) : ?>
  </div>
<?php endif; ?>
<?php
function addedCard($course_name, $course_id, $classroom_id)
{
  // <img src="..." class="card-img-top" alt="...">
  echo '
  <div class="card card-student-course col-md-3 ml-4 mr-4 mb-4">
    <div class="card-body">
    <a href="../courses/student/' . $course_id . '/' . $classroom_id . '">
      <h6 class="card-text text-center pt-5">' . $course_name . '<br>Section' . $course_id . '</h6>
      </a>
    </div>
  </div>';
}
?>