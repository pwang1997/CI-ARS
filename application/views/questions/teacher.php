<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php elseif ($hasQuestion ===FALSE) : ?>
    <?php redirect('questions/create/'.$lab_index); ?>
<?php endif; ?>

</div><!-- end of container -->

<div class="row">
    <div class="col-2">
        <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-course-detail" data-toggle="list" href="#list-course" role="tab" aria-controls="lab">Quiz Detail</a>
            <a class="list-group-item list-group-item-action" id="list-lab-list" data-toggle="list" href="#list-lab" role="tab" aria-controls="lab">Edit Question</a>
            <a class="list-group-item list-group-item-action" id="list-student-list" data-toggle="list" href="#list-student" role="tab" aria-controls="student">Open</a>

        </div>
    </div>
    <div class="col-10">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-course" role="tabpanel" aria-labelledby="list-course-detail">
                <h2><?= $title; ?></h2>
            </div>

            
        </div>
    </div>
</div>