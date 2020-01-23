<?php if (strcmp($this->session->role, 'student') == 0) : ?>
	<?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
	<?php redirect('users/login'); ?>
<?php endif; ?>
</div><!-- end of container -->

<div class="row">
	<div class="col-2">
		<div class="list-group" id="list-tab" role="tablist">
			<a class="list-group-item list-group-item-action active" id="list-course-detail" data-toggle="list" href="#list-course" role="tab" aria-controls="lab">Course Detail</a>
			<a class="list-group-item list-group-item-action" id="list-lab-list" data-toggle="list" href="#list-lab" role="tab" aria-controls="lab">Labs</a>
			<a class="list-group-item list-group-item-action" id="list-student-list" data-toggle="list" href="#list-student" role="tab" aria-controls="student">Students</a>

		</div>
	</div>
	<div class="col-10">
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active" id="list-course" role="tabpanel" aria-labelledby="list-course-detail">
				<h2><?= $title; ?></h2>
				<p><strong>Course Name: </strong> <?php echo $course_info['course_name']; ?></p>
				<p><strong>Course Code: </strong> <?php echo $course_info['course_code']; ?></p>
				<p><strong>Section Number: </strong> <?php echo $course_info['section_id']; ?></p>
				<p><strong>Description: </strong> <?php echo $course_info['description']; ?></p>
			</div>
			<div class="tab-pane fade" id="list-student" role="tabpanel" aria-labelledby="list-student-list">
				<table class="table table-hover" id="list_of_students">
					<thead>
						<tr>
							<th scope="col">Student Name</th>
							<th scope="col">Action</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($enrolledStudents as $student) : ?>
							<tr class="table-light">
								<th scope="row"><?php echo $student['username']; ?></th>
								<th><button type="button" class="btn btn-primary btn_remove_student" id="<?php echo "btn_" . $student['username']; ?>">Remove</button></th>
								<th><button type="button" class="btn btn-primary btn_modify_student" id="<?php echo "btn_" . $student['username'] . "2"; ?>">Modify</button></th>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_add_student">
					Add Student
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

			<div class="tab-pane fade" id="list-lab" role="tabpanel" aria-labelledby="list-lab">
				<table class="table table-hover" id="list_of_lab">
					<thead>
						<tr>
							<th scope="col">Lab</th>
							<th scope="col">Teaching Assistant</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($labs as $lab): ?>
							<tr class="table-light">
								<th scope="row"><?php echo $lab['id']; ?></th>
								<th><?php echo $lab['assistant_id']; ?></th>
								<th><button type="button" class="btn btn-primary" id="<?php echo "btn_" . $student['username']; ?>">Remove</button></th>
								<th><button type="button" class="btn btn-primary" id="<?php echo "btn_" . $student['username'] . "2"; ?>">Modify</button></th>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_add_lab">
					Add Lab
				</button>
				<div class="modal fade" id="modal_add_lab" tabindex="-1" role="dialog" aria-labelledby="label_add_lab" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="label_add_lab">Add Lab Section</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<?php echo form_open('courses/teacher'); ?>
								<div class="form-group">
									<input type="text" id="ta_username" name="ta_username" class="form-control" placeholder="TA Username" required autofocus>
								</div>
								<div class="form-group">
									<select class="form-control" id="student_username" name="student_username">
										<?php foreach ($enrolledStudents as $student) : ?>
											<option><?php echo $student['username']; ?></option>
										<? endforeach; ?>
									</select>
								</div>
								<?php echo form_close(); ?>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="button" id="btn_add_lab" class="btn btn-primary">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
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
								'<th scrope="row">' + response.username + '</th>' + '<th><button type="button" class="btn btn-primary btn_remove_student" id=btn_' + response.username + '>Remove</button></th>' +
								'<th><button type="button" class="btn btn-primary">Modify</button></th>' + '</tr>');
						} else {
							alert('Student does not exitst');
						}
					},
					fail: function() {
						alert("failed");
					}
				});
			});

			$('#btn_add_lab').click((e) => {
				e.preventDefault();
				ta_username = $('#ta_username').val();
				student_username = $('#student_username option:selected').text();
				$.ajax({
					url: "<?php echo base_url(); ?>courses/add_lab_from_classroom",
					type: "POST",
					dataType: "JSON",
					data: {
						"student_username": student_username,
						"ta_username": ta_username,
						"classroom_id" : classroom_id.val()
					},
					success: function(response) {
						if (response.success) {
							$(".modal").modal('hide');
							$('#ta_username').val("");
							$("#list_of_lab > tbody").append('<tr class="table-light">' +
								'<th scrope="row">' + response.id + '</th>' + '<th><button type="button" class="btn btn-primary" id=btn_' + response.username + '>Remove</button></th>' +
								'<th><button type="button" class="btn btn-primary">Modify</button></th>' + '</tr>');
						} else {
							alert('Student does not exitst');
						}
					},
					fail: function() {
						alert("failed");
					},
					always: function() {
						alert();
					}
				});
			});

			$('.btn_remove_student').click((e) => {
				e.preventDefault();
				var target = e.target.id;
				alert(target);
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

		});
	</script>