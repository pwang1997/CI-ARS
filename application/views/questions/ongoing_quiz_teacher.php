<script src="<?= base_url(); ?>js/questions/ongoing_quiz_teacher.js"></script>
<?php if (strcmp($this->session->role, 'student') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
</div>
<!-- <h3>Number of Online Students: <span id="num_online_students">0</span></h3>
<h3>Number of Answered Students: <span id="num_students_answered" name="">0</span></h3> -->
<div class="container-fluid">
    <div class="row" style="height: 100%">
        <!-- sidebar -->
        <nav class="col-md-2 nav flex-column nav-pills bg-light  sidebar pr-0" style="min-height:100px" aria-orientation="vertical">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <?php $index = 1;
                    foreach ($question_list as $question) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($index == 1) echo 'active'; ?>" id="list-question_<?= $question['id']; ?>" data-toggle="list" href="#list-<?= $question['id']; ?>" role="tab" aria-controls="<?= $index; ?>">Question <?= $index++; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>
        <!-- main content -->
        <div class="col-md-10">
            <div class="tab-content" id="nav-tabContent">
                <?php $index = 1; ?>
                <?php foreach ($question_list as $question) : ?>
                    <div class="tab-pane fade <?php if ($index == 1) echo "show active"; ?>" id="list-<?= $question['id']; ?>" role="tabpanel" aria-labelledby="list-question_<?= $question['id']; ?>">
                        <h5>Question <?= $index; ?>:</h5>
                        <h6 class="ml-2" id="editor_<?= $question['id']; ?>"><?= $question['content']; ?></h6>
                        <div id="question_<?= $question['id'] ?>">
                            <input type="hidden" id="quiz_index_<?php echo $question['id']; ?>" value=<?php echo $quiz_index; ?> />
                            <!-- answer/choices -->
                            <div id="option_row<?= $question['id']; ?>">
                                <?php $choices = (json_decode($question['choices']));
                                $answers = json_decode($question['answer']);
                                $i = 1;
                                foreach ($choices as $choice) : ?>
                                    <div class="form-group row choice_row">
                                        <div class="custom-control custom-checkbox ml-4">
                                            <input type="checkbox" disabled="true" class="custom-control-input " id="customCheck_<?= $i; ?>_<?= $question['id']; ?>" name="choice_row_<?php echo $question['id']; ?>" value="<?php echo $choice ?>" <?php if (in_array($choice, $answers)) echo "checked"; ?>>
                                            <label class="custom-control-label" for="customCheck_<?= $i; ?>_<?= $question['id']; ?>"></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" disabled="true" class="form-control" name="choice<?= $i; ?>" id="<?php echo $question['id'] . "_" . $i ?>" autocomplete="on" value="<?php echo $choice; ?>">
                                        </div>
                                    </div>
                                <?php $i++;
                                endforeach; ?>
                            </div>

                            <!-- content + buttons  -->
                            <div class="d-flex flex-column">
                                <p id="timerType_<?php echo $question['id']; ?>">Timer Type: <span><?php echo $question['timer_type']; ?></span></p>

                                <p id="difficulty_<?php echo $question['id']; ?>">Difficulty: <span><?php echo $question['difficulty']; ?></span></p>

                                <p id="category_<?php echo $question['id']; ?>">Category: <span><?php echo $question['category']; ?></span></p>

                                <p id="duration_<?php echo $question['id']; ?>">
                                    <?php if ($question['timer_type'] == 'timedown') : ?>
                                        Remaining Time:<?php echo $question['duration']; ?> seconds
                                    <?php else : ?>
                                        Time:<?php echo $question['duration']; ?> seconds
                                    <?php endif; ?>
                                </p>

                                <div class="row">
                                    <div class="progress col-sm-6 p-0 ml-3">
                                        <?php if ($question['timer_type'] == 'timedown') : ?>
                                            <div class="progress-bar" id="progress_bar_<?= $question['id']; ?>" role="progressbar" style="width:100%" aria-valuenow="<?= $question['duration'] ?>" aria-valuemin="0" aria-valuemax="<?= $question['duration'] ?>"></div>
                                        <?php else : ?>
                                            <div class="progress-bar" id="progress_bar_<?= $question['id']; ?>" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="<?= $question['duration'] ?>"></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-sm-2 pb-2">
                                    <button style="width:100%" type="button" class="btn btn-primary start" id="start_<?php echo $question['id']; ?>">Start</button>
                                </div>

                                <div class="col-sm-2 pb-2">
                                    <div class="dropdown">
                                        <button style="width:100%" class="btn btn-primary pause dropdown-toggle" id="pause_<?php echo $question['id']; ?>" type="button" id="dropdown_pause" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Pause
                                        </button>
                                        <div class="dropdown-menu" id="dropdown_menu_<?php echo $question['id']; ?>" aria-labelledby="dropdown_pause">
                                            <button style="width:100%" class="dropdown-item pause_answerable" id="pause_answerable_<?php echo $question['id']; ?>">Answerable</button>
                                            <button style="width:100%" class="dropdown-item pause_disable" id="pause_disable_<?php echo $question['id']; ?>">Disable</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2 pb-2">
                                    <button style="width:100%" type="button" class="btn btn-primary btn-close" id="close_<?php echo $question['id']; ?>">Close</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 pb-2">
                                    <button style="width:100%" type="button" class="btn btn-primary btn-display_answer" id="display_answer_<?php echo $question['id']; ?>">Display Answer</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2 pb-2">
                                <button style="width:100%" class="btn btn-primary prev" <?php if ($index == 1) echo "disabled=true"; ?> type="button" id="prev_<?= $question['id']; ?>">Previous</button>
                            </div>
                            <div class="col-sm-2 pb-2">
                                <button style="width:100%" class="btn btn-primary next" <?php if ($index == count($question_list)) echo "disabled=true"; ?>type="button" id="next_<?= $question['id']; ?>">Next</button>
                            </div>
                            <div class="col-sm-2 pb-2">
                                <button style="width:100%" class="btn btn-danger exit" type="button" id="exit_<?= $question['id']; ?>">Exit</button>
                            </div>
                        </div>
                        <div class="border-top my-3 d-block"></div>
                        <?php $index++; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>