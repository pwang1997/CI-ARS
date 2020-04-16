<script src="<?= base_url(); ?>js/users/dashboard.js"></script>
<?php if (strcmp($this->session->role, 'student') == 0) : ?>
  <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
  <?php redirect('users/login'); ?>
<?php endif; ?>

<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
  <div class="lh-100">
    <h6 class="mb-0 text-dark lh-100">Classroom <span class="text-muted">List</span></h6>
  </div>
</div>

<div class="my-3 p-3 bg-white rounded shadow-sm">
  <?php
  for ($i = 0; $i < sizeof($course_list); $i++)
    addMeida($course_list[$i]['course_name'], $course_list[$i]['course_id'], $course_list[$i]['classroom_id'], $section_list[$i]['section_id']);
  ?>

</div>

<?php
function addMeida($course_name, $course_id, $classroom_id, $section)
{
  echo  ' <div class="media text-muted pt-3">
    <a href="../courses/teacher/' . $course_id . '/' . $classroom_id . '"><div class="square"></div></a>
    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
      <a href="../courses/teacher/' . $course_id . '/' . $classroom_id . '"><strong class="d-block text-gray-dark">' . $course_name . '</strong>
      Section ' . $section . '</a>
    </p>
  </div>';
}
?>