<?php if (strcmp($this->session->role, 'student') == 0) : ?>
	<?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
	<?php redirect('users/login'); ?>
<?php endif; ?>
</div><!-- end of container -->

<div class="row">
	<div class="col-2">
		<div class="list-group" id="list-tab" role="tablist">
			<a class="list-group-item list-group-item-action active" id="list-course-detail" data-toggle="list" href="#list-course" role="tab" aria-controls="course">Course Detail</a>
			<a class="list-group-item list-group-item-action" id="list-quiz-list" data-toggle="list" href="#list-quiz" role="tab" aria-controls="quiz">Quizs</a>
			<a class="list-group-item list-group-item-action" id="list-student-list" data-toggle="list" href="#list-student" role="tab" aria-controls="student">Students</a>
		</div>
	</div>
	<div class="col-10">
		<div class="tab-content" id="nav-tabContent">
			<!-- course detail -->
			<div class="tab-pane fade show active" id="list-course" role="tabpanel" aria-labelledby="list-course-detail">
				<h2><?= $title; ?></h2>
				<p><strong>Course Name: </strong> <?php echo $course_info['course_name']; ?></p>
				<p><strong>Course Code: </strong> <?php echo $course_info['course_code']; ?></p>
				<p><strong>Section Number: </strong> <?php echo $course_info['section_id']; ?></p>
				<p><strong>Description: </strong> <?php echo $course_info['description']; ?></p>
			</div>
			<!-- student list  -->
			<div class="tab-pane fade" id="list-student" role="tabpanel" aria-labelledby="list-student-list">
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
									<button type="button" class="btn btn-outline-primary btn_remove_student" id="<?php echo "btn_" . $student['username']; ?>">Remove</button>
									<button type="button" class="btn btn-outline-primary btn_modify_student" id="<?php echo "btn_" . $student['username'] . "2"; ?>">Edit</button>
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
			<div class="card-groups">
				<?php
				for ($i = 0; $i < sizeof($quizs); $i++) {
					if ($i % 3 == 0) {
				?>
						<div class="row">
							<?php addedCard($i, $quizs[$i]['id'], $quizs[$i]['created_at'], $num_questions[$quizs[$i]['id']]); ?>
							<div class="col-md-1"></div>
						<?php } elseif ($i % 3 == 2) {
						?>
							<?php addedCard($i, $quizs[$i]['id'], $quizs[$i]['created_at'], $num_questions[$quizs[$i]['id']]); ?>
						</div>
					<?php } else { ?>
						<?php addedCard($i, $quizs[$i]['id'], $quizs[$i]['created_at'], $num_questions[$quizs[$i]['id']]); ?>
						<div class="col-md-1"></div>
				<?php }
				}
				?>
				<?php if (sizeof($quizs) % 3 != 0) : ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class='card bg-outline-primary mb-3 col-md-3'>
				<div class='card-body'>
					<h5 class='card-title'><a href='#' id="add_quiz" class='text-secondary'>New Quiz</a></h5>
				</div>
			</div>
		</div>
		</div>

		<?php
		function addedCard($index, $quiz_id, $created_at, $num_questions)
		{
			echo "<div class='card bg-outline-primary mb-3 col-md-3' id='card_{$quiz_id}'>
		<div class='card-body'>
		  <h5 class='card-title'><a href='" . base_url() . "/questions/teacher/{$quiz_id}' class='text-secondary'>Quiz {$index}</a></h5>
		  <p class='card-text'><a href='" . base_url() . "/questions/teacher/{$quiz_id}' class='text-secondary'>Question pool: ${num_questions}</a></p>
		</div>
		<div class='card-footer'>
		  <small class='text-muted'>created at ${created_at}</small>
		  <button type='button' class='btn btn-outline-primary start' id=st_{$quiz_id}>start</button>
		  <button type='button' class='btn btn-outline-danger remove' id=rm_{$quiz_id}>remove</button>
		</div>
	  </div>";
		}
		?>
	</div>
</div>
<script>
	$(document).ready(() => {
		classroom_id = $("#classroom_id");
		$('#btn_add_student').click((e) => {
			e.preventDefault();
			sname = $("#username");
			last_student = $("#list_of_students > tbody").length + 1;

			$.ajax({
				url: "<?php echo base_url(); ?>courses/add_student_from_classroom",
				type: "POST",
				dataType: "JSON",
				data: {
					"username": sname.val(),
					"classroom_id": classroom_id.val()
				},
				success: function(response) {
					if (response.success) {
						$(".modal").modal('hide');
						$("#username").val("");
						$("#list_of_students > tbody").append('<tr class="table-light">' +
							'<th scrope="row">' + response.username + '</th>' + '<th><button type="button" class="btn btn-outline-primary btn_remove_student" id=btn_' + response.username + '>Remove</button>' +
							'<button type="button" class="btn btn-outline-primary">Edit</button></th>' + '</tr>');
					} else {
						alert('Student does not exitst');
					}
				},
				fail: function() {
					alert("failed");
				}
			});
		});

		$('.btn_remove_student').click((e) => {
			e.preventDefault();
			var target = e.target.id;
			// alert(target);
			sname = $('#' + target).parent().prev().text();

			$.ajax({
				url: "<?php echo base_url(); ?>courses/remove_student_from_classroom",
				type: "POST",
				dataType: "JSON",
				data: {
					"username": sname,
					"classroom_id": classroom_id.val()
				},
				success: function(response) {
					if (response.success) {
						$("#" + target).parent().parent().remove();
					} else {
						alert('Student does not exitst');
					}
				},
				fail: function() {
					alert("failed");
				}
			});
		});

		$('#add_quiz').click((e) => {
			e.preventDefault();
			$.ajax({
				url: "<?php echo base_url(); ?>courses/add_quiz_from_classroom",
				type: "POST",
				dataType: "JSON",
				data: {
					"classroom_id": classroom_id.val()
				},
				success: function(response) {
					if (response.success) {
						quiz_index = response.quiz_index;
						location.replace("<?php echo (base_url() . "questions/create/"); ?>" + quiz_index);
					} else {
						alert("failed ")
					}
				},
				fail: function() {
					alert("failed");
				}
			});
		});

		//jump to question(ongoing) view
		$('button').click(function() {
			quiz_index = this.id.substring(3);
			console.log(quiz_index)
			if ($(this).hasClass('start')) {
				head = <?php echo ("'" . base_url() . "questions/ongoing_quiz_teacher/'"); ?> + `${quiz_index}`;
				location.replace(head);
			} else if ($(this).hasClass('remove')) {
				$.ajax({
					url: "<?php echo base_url(); ?>courses/remove_quiz_from_classroom",
					type: "POST",
					dataType: "JSON",
					data: {
						"quiz_id": quiz_index
					},
					success: function(response) {
						if (response.success) {
							alert('success');
							$(`#card_${quiz_index}`).remove();
						} else {
							alert("failed ")
						}
					},
					fail: function() {
						alert("failed");
					}
				})
			}

		})
	});
</script>