<script src='<?= base_url(); ?>js/users/dashboard.js'></script>

<?php if (strcmp($this->session->role, "teacher") == 0) : ?>
  <?php redirect("home"); ?>
<?php elseif (empty($this->session->username)) : ?>
  <?php redirect("users/login"); ?>
<?php endif; ?>
<h3 class='border-bottom pb-2 pt-2 mt-2'>Dashboard </h3>

<br>
<?php
// for ($i = 0; $i < sizeof($course_list); $i++) {
//   if ($i % 3 == 0) :
//     echo "<div class='row hidden-md-up'>";
//     addedCard($course_list[$i]["course_name"], $course_list[$i]["course_id"], $course_list[$i]["classroom_id"]);
//   elseif ($i % 3 == 2) :
//     addedCard($course_list[$i]["course_name"], $course_list[$i]["course_id"], $course_list[$i]["classroom_id"]);
//     echo "</div>";
//   else :
//     addedCard($course_list[$i]["course_name"], $course_list[$i]["course_id"], $course_list[$i]["classroom_id"]);
//   endif;
// } 
?>

<?php //if (sizeof($course_list) % 3 != 0) : ?>
  <!-- </div> -->
<?php //endif; ?>
<?php
function addedCard($course_name, $course_id, $classroom_id)
{
  // <img src='...' class='card-img-top' alt='...'>
  echo "<div class='col-md-3 py-2 ml-2'>
          <div class='card'>
          <div class='card-student-course'>
            <div class='card-block'>
             <a href='../courses/student/" . $course_id . "/" . $classroom_id . "'>
              <h6 class='card-text text-center pt-5'>" . $course_name . "<br>Section" . $course_id . "</h6>
            </a>
            </div>
          </div>
          </div>
        </div>";
}
?>

<div class='my-3 p-3 bg-white rounded shadow-sm'>
  <h6 class='border-bottom border-gray pb-2 mb-0'>Classroom list</h6>
  <?php
  for ($i = 0; $i < sizeof($course_list); $i++)
    addMeida($course_list[$i]["course_name"], $course_list[$i]["course_id"], $course_list[$i]["classroom_id"], $course_list[$i]["section_id"]);
  ?>  
</div>

<?php
  function addMeida($course_name, $course_id, $classroom_id, $section)
  {
    echo  " <div class='media text-muted pt-3'>
    <a href='../courses/student/{$course_id}/{$classroom_id}'><div class='square'></div></a>
    <p class='media-body pb-3 mb-0 small lh-125 border-bottom border-gray'>
      <a href='../courses/student/{$course_id}/{$classroom_id}'><strong class='d-block text-gray-dark'>$course_name</strong>
      Section $section</a>
    </p>
  </div>";
  }
  ?>