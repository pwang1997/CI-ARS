<?php if (strcmp($this->session->role, 'teacher') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
<link rel="stylesheet" href="../../css/spinner.css">
<script src="<?= base_url(); ?>js/questions/student.js"></script>

<div class="question_off visible">
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
</div>

<div class="question_on invisible">
    <!-- content + buttons  -->
    <div class="row">
        <div class="p-2 col-sm-3" id="status">Status:</div>
        <div class="p-2 col-sm-3" id="targeted_time">Targeted Time: </div>
    </div>
    <div class="row">
        <div class="p-2 col-sm-12">
            <p id="duration">
            </p>
            <div class="progress">
            </div>
        </div>
    </div>
    <h6 class="ml-2" id="content"></h6>
    <!-- answer/choices -->
    <div class="options"></div>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <button style="width:100%" type="button" class="btn btn-primary submit">Submit</button>
        </div>
    </div>
</div>