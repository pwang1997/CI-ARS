</div><!-- end of container -->

<div class="row">
    <div class="col-2">
        <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-course-detail" data-toggle="list" href="#list-course" role="tab" aria-controls="lab">Course Detail</a>
            <a class="list-group-item list-group-item-action" id="list-lab-list" data-toggle="list" href="#list-lab" role="tab" aria-controls="lab">Quizs</a>
            <a class="list-group-item list-group-item-action" id="list-grade-list" data-toggle="list" href="#list-grade" role="tab" aria-controls="grade">Grade</a>

        </div>
    </div>
    <div class="col-10">
        <div class="tab-content" id="nav-tabContent">
            <!-- course detail  -->
            <div class="tab-pane fade show active" id="list-course" role="tabpanel" aria-labelledby="list-course-detail">
                <h2><?= $title; ?></h2>
                <p><strong>Course Name: </strong> <?php echo $course_info['course_name']; ?></p>
                <p><strong>Course Code: </strong> <?php echo $course_info['course_code']; ?></p>
                <p><strong>Section Number: </strong> <?php echo $course_info['section_id']; ?></p>
                <p><strong>Description: </strong> <?php echo $course_info['description']; ?></p>
            </div>
            <!-- quiz list  -->
            <div class="tab-pane fade" id="list-lab" role="tabpanel" aria-labelledby="list-lab">
                <table class="table table-hover" id="list_of_students">
                    <thead>
                        <tr>
                            <th scope="col">Quiz</th>
                            <th scope="col">Number of Questions</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quizs as $quiz) : ?>
                            <tr class="table-light">
                                <th scope="row"><a href="<?php echo base_url(); ?>questions/student/<?php echo $quiz['quiz_index']; ?>">
                                        <?php echo $quiz['quiz_index']; ?></a></th>
                                <th>
                                    <?php echo $num_of_questions[$quiz['quiz_index']]['num_questions']; ?>
                                </th>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(() => {

        });
    </script>