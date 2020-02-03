<?php if (strcmp($this->session->role, 'teacher') == 0) : ?>
	<?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
	<?php redirect('users/login'); ?>
<?php endif; ?>
<link rel="stylesheet" href="../../css/spinner.css">
<?php if (true) : ?>
	<div class="d-flex flex-column align-items-center justify-content-center">
		<div class="row">
			<div class="spinner-border" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		</div>
		<div class="row">
			<strong>Please prepare for quiz</strong>
		</div>
	</div>
<?php else : ?>
	<div class="row">
		<div class="col-md-8">
			
		</div>
		<div class="col-md-4">
			
		</div>
	</div>
<?php endif; ?>