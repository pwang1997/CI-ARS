<?php if (strcmp($this->session->role, 'student') == 0) : ?>
  <?php redirect('home'); ?>
  <?php elseif(empty($this->session->username)): ?>
    <?php redirect('users/login');?>
<?php endif; ?>

<h2>Welcome back <?php echo $this->session->username . '!'; ?></h2>

<?php
for ($i = 0; $i < sizeof($course_list); $i++) {
  if ($i % 3 == 0) {
?>
    <div class="row">
      <?php addedCard($course_list[$i]['course_name'], $course_list[$i]['course_id'], $course_list[$i]['classroom_id']); ?>
      <div class="col-md-1"></div>
    <?php } elseif ($i % 3 == 2) {
    ?>
      <?php addedCard($course_list[$i]['course_name'], $course_list[$i]['course_id'], $course_list[$i]['classroom_id']); ?>
    </div>
  <?php } else { ?>
    <?php addedCard($course_list[$i]['course_name'], $course_list[$i]['course_id'], $course_list[$i]['classroom_id']); ?>
    <div class="col-md-1"></div>
<?php }
}
?>
<?php if (sizeof($course_list) % 3 != 0) : ?>
  </div>
<?php endif; ?>
<div class="row">
  <div class="card text-white bg-primary mb-3 col-md-3">
    <div class="card-header">Add Course</div>
    <div class="card-body">
      <h4 class="card-title"><a href="../courses/create" class="text-secondary">New Course</a></h4>
    </div>
  </div>
</div>

<?php
function addedCard($course_name, $course_id, $classroom_id)
{
  echo ' <div class="card text-white bg-primary mb-3 col-md-3">
        <div class="card-header">' . $course_name . '</div>
        <div class="card-body">
          <h4 class="card-title"><a href="../courses/teacher/' . $course_id . '/'. $classroom_id.'" class="text-secondary">Section' . $course_id . '</a></h4>
        </div>
      </div>';
}
?>