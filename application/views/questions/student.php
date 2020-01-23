<?php if (strcmp($this->session->role, 'teacher') == 0) : ?>
	<?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
	<?php redirect('users/login'); ?>
<?php endif; ?>
</div><!-- end of container -->

<div class="row">
	<div class="col-2">
		<div class="list-group" id="list-tab" role="tablist">
			<a class="list-group-item list-group-item-action active" id="list-course-detail" data-toggle="list" href="#list-course" role="tab" aria-controls="lab">Quiz Details</a>
			<!-- add class 'disabled' to disable question toggle  -->
			<?php for ($i = 1; $i <= count($question_list); $i++) : ?>
				<a class="list-group-item list-group-item-action" id="list-question-list<?php echo '-' . $i; ?>" data-toggle="list" href="#list-question<?php echo '-' . $i; ?>" role="tab" aria-controls="question">Question <?php echo $i; ?></a>
			<?php endfor; ?>
		</div>
	</div>
	<div class="col-8">
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active" id="list-course" role="tabpanel" aria-labelledby="list-course-detail">
				<h2><?= $title; ?></h2>
			</div>
			<?php for ($i = 1; $i <= count($question_list); $i++) : ?>
				<div class="tab-pane fade" id="list-question<?php echo '-' . $i; ?>" role="tabpanel" aria-labelledby="list-question-list<?php echo '-' . $i; ?>">
					<h2>Question <? echo $i; ?></h2>
					<p id="timer"></p>
					<?php echo form_open('questions/student/'.$lab_index); ?>
					<div class="form-group">
						<label for="content">Question: </label>
						<textarea class="form-control" id="content<?php echo '-' . $i; ?>" name="content" rows="3" readonly placeholder="<?php echo $question_list[$i - 1]['content']; ?>"></textarea>
					</div>

					<div class="form-group" id="choices">
						<label for="choices">Answer</label>
						<div class="true_or_false">
							<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="choices" value="True">
								<label class="form-check-label" for="choices">True</label></div>
							<div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="choices" value="False">
								<label class="form-check-label" for="choices">False</label></div>
						</div>
					</div>
					<input type="hidden" id="<?php echo 'question_instance_id_'.$question_list[$i-1]['id']; ?>" value="<?php echo $question_list[$i-1]['id']; ?>">
					<input type="submit" class="btn btn-primary submit" value="Submit">
					<?php echo form_close(); ?>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</div>

<script>
	$(document).ready(() => {

		$(".submit").click((e) => {
			e.preventDefault();

			// student_id = <?php //echo $this->session->id; ?>;
			//question_instance_id = $("input[type=hidden]").val();
			// console.log(question_instance_id)
			answer = $("input[name=choices]:checked").val();
			// 'question_instance_id' : question_instance_id,
			$.ajax({
				url: "<?php echo base_url(); ?>questions/student_response",
				type: "POST",
				dataType: "JSON",
				data: {
					'student_id': <?php echo $this->session->id; ?>,
					
					'answer' : answer
				},
				success: function(response) {
					if (response.success) {
						alert("success");
					} else {
						alert("failed to insert question1");
					}
				},
				fail: function() {
					alert("failed to insert question2");
				}
			})
		});
	})
</script>