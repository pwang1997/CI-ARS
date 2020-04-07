</div>
<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
<div class="tab-content" id="nav-tabContent">
    <!-- student list  -->
    <?php $quiz_instance_counter = 1; ?>
    <?php foreach ($quiz_instance_list as $quiz_instance) : ?>
        <?php $quiz_instance_id = $quiz_instance['id']; ?>

        <div class="tab-pane fade <?php if ($quiz_instance_counter == 1) echo "active show"; ?>" id="quiz-<?= $quiz_instance_id; ?>" role="tabpanel" aria-labelledby="list-quiz-<?= $quiz_instance_id; ?>">
            <?php if (isset($question_instance_list[$quiz_instance_id])) : ?>
                <?php $question_counter = 1; ?>
                <?php foreach ($question_instance_list[$quiz_instance_id] as $question_instance) : ?>

                    <?php $quiz_instance_counter++; ?>
                    <div class="table-responsive">
                        <h3>Question <?= $question_counter++; ?></h3>
                        <hr>
                        <?php if (!empty($student_response_list[$question_instance['id']])) : ?>
                            <table class="table table-striped table-fixed">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Student Answer</th>
                                        <th scope="col">Answer</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $student_response_counter = 1; ?>
                                    <?php foreach ($student_list as $student) : ?>
                                        <?php $student_id = $student['student_id']; ?>
                                        <?php $student_responses = $student_response_list[$question_instance['id']]; ?>
                                        <?php $question_id = $question_instance['question_meta_id']; ?>
                                        <tr>
                                            <th><?php echo $student_response_counter++ ?></th>
                                            <th><?php echo $student['username']; ?></th>
                                            <?php if (isset($student_responses[$student_id])) : ?>
                                                <th><?php echo $student_responses[$student_id]['answer']; ?></th>
                                                <th><?php echo ($question_list[$question_id]['answer']); ?></th>
                                                <th>
                                                    <?php if (strcmp($student_responses[$student_id]['answer'], $question_list[$question_id]['answer']) == 0) : ?>
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    <?php else : ?>
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    <?php endif; ?>
                                                </th>
                                            <?php else : ?>
                                                <th>N/A</th>
                                                <th><?php echo ($question_list[$question_id]['answer']); ?></th>
                                                <th><i class="fa fa-times" aria-hidden="true"></i></th>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <table class="table table-striped table-fixed">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Student Answer</th>
                                        <th scope="col">Answer</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $student_response_counter = 1; ?>
                                    <?php foreach ($student_list as $student) : ?>
                                        <?php $student_id = $student['student_id']; ?>
                                        <?php $question_id = $question_instance['question_meta_id']; ?>
                                        <tr>
                                            <th><?php echo $student_response_counter++ ?></th>
                                            <th><?php echo $student['username']; ?></th>
                                            <th>N/A</th>
                                            <th><?php echo ($question_list[$question_id]['answer']); ?></th>
                                            <th><i class="fa fa-times" aria-hidden="true"></i></th>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>