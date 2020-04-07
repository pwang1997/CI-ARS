<script src="<?= base_url(); ?>js/courses/teacher.js"></script>
<?php if (strcmp($this->session->role, 'student') == 0) : ?>
	<?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
	<?php redirect('users/login'); ?>
<?php endif; ?>

<div class="tab-content" id="nav-tabContent">
	<!-- course detail -->
	<div class="tab-pane fade show active" id="list-course" role="tabpanel" aria-labelledby="list-course-detail">
		<h3><?= $title; ?></h3>
		<hr>
		<?php if (isset($course_info)) : ?>
			<p><strong>Course Name: </strong> <?php echo $course_info['course_name']; ?></p>
			<p><strong>Course Code: </strong> <?php echo $course_info['course_code']; ?></p>
			<p><strong>Section Number: </strong> <?php echo $course_info['section_id']; ?></p>
			<p><strong>Description: </strong> <?php echo $course_info['description']; ?></p>
		<?php endif; ?>
	</div>
	<!-- student list  -->
	<div class="tab-pane fade" id="list-student" role="tabpanel" aria-labelledby="list-student-list">
		<div class="table-responsive">
			<table class="table table-sm table-striped table-fixed" id="list_of_students">
				<thead class="">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Name</th>
						<!-- <th scope="col">Grade</th> -->
						<th scope="col"></th>
						<!-- <th scope="col">Detail</th> -->
					</tr>
				</thead>
				<tbody>
					<?php $j = 1; ?>
					<?php foreach ($enrolled_students as $student) : ?>
						<tr class="" id="<?php echo "{$student['username']}"; ?>">
							<th><?php echo $j++; ?></th>
							<th scope="row"><?php echo $student['username']; ?></th>
							<!-- <th><?= (isset($student['grade'])) ? $student['grade'] : "N/A"; ?></th> -->
							<th>
								<div class="row">
									<div class="col-md-6 pb-1">
										<button style="width:100%" type="button" class="btn btn-outline-danger btn_remove_student" id="<?php echo "btn_{$student['username']}"; ?>">Remove</button>
									</div>
								</div>
							</th>
							<!-- <th>
											<div class="row">
												<div class="col-md-6 pb-1">
													<a id="grade_detail_<?= $student['id']; ?>" href='' style="color: grey !important">
														<svg class="bi bi-box-arrow-down" font-size="1.25em" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
															<path fill-rule="evenodd" d="M4.646 11.646a.5.5 0 01.708 0L8 14.293l2.646-2.647a.5.5 0 01.708.708l-3 3a.5.5 0 01-.708 0l-3-3a.5.5 0 010-.708z" clip-rule="evenodd" />
															<path fill-rule="evenodd" d="M8 4.5a.5.5 0 01.5.5v9a.5.5 0 01-1 0V5a.5.5 0 01.5-.5z" clip-rule="evenodd" />
															<path fill-rule="evenodd" d="M2.5 2A1.5 1.5 0 014 .5h8A1.5 1.5 0 0113.5 2v7a1.5 1.5 0 01-1.5 1.5h-1.5a.5.5 0 010-1H12a.5.5 0 00.5-.5V2a.5.5 0 00-.5-.5H4a.5.5 0 00-.5.5v7a.5.5 0 00.5.5h1.5a.5.5 0 010 1H4A1.5 1.5 0 012.5 9V2z" clip-rule="evenodd" />
														</svg>
													</a>
												</div>
											</div>
										</th> -->
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

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
			<div class="col-md-3 col-lg-3 py-2 ml-2">

				<button style='width:100%' id="add_quiz" class="btn btn-primary btn-lg">
					New Quiz
					<svg class="bi bi-plus" font-size="1.25em" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd" />
						<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd" />
					</svg>
				</button>
			</div>

			<div class="col-md-3 col-lg-3 py-2 ml-2">
				<button style='width:100%' id="export_class_history" class="btn btn-primary btn-lg" name="<?php echo $classroom_id; ?>">Export
					<svg class="bi bi-download" font-size="1.25em" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M.5 8a.5.5 0 01.5.5V12a1 1 0 001 1h12a1 1 0 001-1V8.5a.5.5 0 011 0V12a2 2 0 01-2 2H2a2 2 0 01-2-2V8.5A.5.5 0 01.5 8z" clip-rule="evenodd" />
						<path fill-rule="evenodd" d="M5 7.5a.5.5 0 01.707 0L8 9.793 10.293 7.5a.5.5 0 11.707.707l-2.646 2.647a.5.5 0 01-.708 0L5 8.207A.5.5 0 015 7.5z" clip-rule="evenodd" />
						<path fill-rule="evenodd" d="M8 1a.5.5 0 01.5.5v8a.5.5 0 01-1 0v-8A.5.5 0 018 1z" clip-rule="evenodd" />
					</svg>
				</button>
			</div>
		</div>
	</div>
</div>
<?php
function addedCard($index, $quiz_id, $created_at, $num_questions)
{
	echo "
				<div class='col-lg-3 py-2 ml-2'>
					<div class='card text-white bg-dark mb-3' id='card_{$quiz_id}'>
						<div class='card-teacher-course'>
							<div class='card-body'>
								<h5 class='card-title'><a href='" . base_url() . "questions/teacher/{$quiz_id}' class='text-secondary'>Quiz {$index}</a></h5>
								<p class='card-text'><a href='" . base_url() . "questions/teacher/{$quiz_id}' class='text-secondary'>Question pool: ${num_questions}</a></p>
								<div class=row>
								<div class='col-sm-6 col-lg-12 pb-2'>
									<button style='width:100%' type='button' class='btn btn-primary btn-block start' id=st_{$quiz_id}>Start</button>
								</div>
								<div class='col-sm-6 col-lg-12 pb-2'>
									<button style='width:100%' type='button' class='btn btn-danger btn-block remove' id=rm_{$quiz_id}>Delete</button>
								</div>
							</div>
							<div class='row'>
								<div class='col-sm-6 col-lg-12 pb-2'>
									<button style='width:100%' type='button' class='btn btn-primary btn-block history' id=history_{$quiz_id}>Review History</button>
								</div>
							</div>
							<div class='row'>
								<div class='col-sm-6 col-lg-12 pb-2'>
									<button style='width:100%' type='button' class='btn btn-primary btn-block export' id=export_{$quiz_id}>Download History</button>
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>";
}
?>