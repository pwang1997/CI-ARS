<script src="<?= base_url(); ?>js/courses/teacher.js"></script>
<?php if (strcmp($this->session->role, 'student') == 0) : ?>
	<?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
	<?php redirect('users/login'); ?>
<?php endif; ?>
</div><!-- end of container -->

<div class="container-fluid">
	<div class="row" style="height: 100%">
		<!-- d-none d-md-block bg-light sidebar -->
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
						<a class="nav-link" id="list-student-list" data-toggle="list" href="#list-student" role="tab" aria-controls="student">Student
						</a>
					</li>
				</ul>
			</div>
		</nav>

		<div class="col-md-10">
			<div class="tab-content" id="nav-tabContent">
				<!-- course detail -->
				<div class="tab-pane fade show active" id="list-course" role="tabpanel" aria-labelledby="list-course-detail">
					<h3><?= $title; ?></h3>
					<hr>
					<p><strong>Course Name: </strong> <?php echo $course_info['course_name']; ?></p>
					<p><strong>Course Code: </strong> <?php echo $course_info['course_code']; ?></p>
					<p><strong>Section Number: </strong> <?php echo $course_info['section_id']; ?></p>
					<p><strong>Description: </strong> <?php echo $course_info['description']; ?></p>
				</div>
				<!-- student list  -->
				<div class="tab-pane fade" id="list-student" role="tabpanel" aria-labelledby="list-student-list">
					<h3>Student List</h3>
					<table class="table table-hover" id="list_of_students">
						<thead>
							<tr>
								<th scope="col">Index</th>
								<th scope="col">Student Name</th>
								<th scope="col">Action</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							<?php $j = 1; ?>
							<?php foreach ($enrolledStudents as $student) : ?>
								<tr class="table-light">
									<th><?php echo $j++; ?></th>
									<th scope="row"><?php echo $student['username']; ?></th>
									<th>
										<div class="row">
											<div class="col-md-6 pb-1">
												<button style="width:100%" type="button" class="btn btn-outline-danger btn_remove_student" id="<?php echo "btn_" . $student['username']; ?>">Remove</button>
											</div>
											<div class="col-md-6">
												<button style="width:100%" type="button" class="btn btn-outline-primary btn_modify_student" id="<?php echo "btn_" . $student['username'] . "2"; ?>">Edit</button>
											</div>
										</div>
									</th>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_add_student">
						New Student
					</button>
					<div class="modal fade" id="modal_add_student" tabindex="-1" role="dialog" aria-labelledby="label_add_student" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="label_add_student">Add Student</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<?php echo form_open('courses/teacher'); ?>
									<input type="hidden" id="classroom_id" value="<?php echo $course_info['id']; ?>">
									<div class="form-group">
										<input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
									</div>
									<?php echo form_close(); ?>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="button" id="btn_add_student" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- quizs  -->
				<div class="tab-pane fade" id="list-quiz" role="tabpanel" aria-labelledby="list-quiz-list">
					<h3>Quiz List</h3>
					<hr>
					<?php
					for ($i = 0; $i < sizeof($quizs); $i++) {
						if ($i % 3 == 0) :
							echo '<div class="row">';
							addedCard($i + 1, $quizs[$i]['id'], $quizs[$i]['created_at'], $num_questions[$quizs[$i]['id']]);
						elseif ($i % 3 == 2) :
							addedCard($i + 1, $quizs[$i]['id'], $quizs[$i]['created_at'], $num_questions[$quizs[$i]['id']]);
							echo '</div>';
						else :
							addedCard($i + 1, $quizs[$i]['id'], $quizs[$i]['created_at'], $num_questions[$quizs[$i]['id']]);
						endif;
					}
					if (sizeof($quizs) % 3 != 0) :
						echo '</div>';
					endif;
					?>
					<!-- add new quiz -->
					<div class="row">
						<div class="col-lg-3 py-2 ml-2">
							<div class='card bg-outline-primary'>
								<div class='card-teacher-course'>
									<div class='card-body'>
										<h5 class='card-title'><a href='#' id="add_quiz" class='text-secondary'>New Quiz</a></h5>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
function addedCard($index, $quiz_id, $created_at, $num_questions)
{
	echo "
				<div class='col-lg-3 py-2 ml-2'>
					<div class='card bg-outline-primary mb-3' id='card_{$quiz_id}'>
						<div class='card-teacher-course'>
							<div class='card-body'>
								<h5 class='card-title'><a href='" . base_url() . "questions/teacher/{$quiz_id}' class='text-secondary'>Quiz {$index}</a></h5>
								<p class='card-text'><a href='" . base_url() . "questions/teacher/{$quiz_id}' class='text-secondary'>Question pool: ${num_questions}</a></p>
								<small class='text-muted'>created at ${created_at}</small>
							</div>
							<div class='card-footer'>
								<div class=row>
									<div class='col-sm-6 pb-2'	>
										<button type='button' class='btn btn-primary btn-block start' id=st_{$quiz_id}>start</button>
									</div>
									<div class=col-sm-6>
										<button type='button' class='btn btn-danger btn-block remove' id=rm_{$quiz_id}>remove</button>
									</div>
								</div>
							</div>
							</div>
					</div>
				</div>";
}
?>