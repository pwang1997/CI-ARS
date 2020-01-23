</div><!-- end of container -->

<div class="row">
    <div class="col-2">
        <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action active" id="list-course-detail" data-toggle="list" href="#list-course" role="tab" aria-controls="lab">Course Detail</a>
            <a class="list-group-item list-group-item-action" id="list-lab-list" data-toggle="list" href="#list-lab" role="tab" aria-controls="lab">Labs</a>
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

            <div class="tab-pane fade" id="list-lab" role="tabpanel" aria-labelledby="list-lab">
                <table class="table table-hover" id="list_of_students">
                    <thead>
                        <tr>
                            <th scope="col">Lab</th>
                            <th scope="col">TA index</th>
                            <th scope="col">Action</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($labs as $lab) : ?>
                            <tr class="table-light">
                                <th scope="row"><a href="../../../quizs/student/<?php echo $lab['id']; ?>"><?php echo $lab['id']; ?></a></th>
                                <th><?php echo $lab['assistant_id']?></th>
                                <th><button type="button" class="btn btn-primary ">Remove</button></th>
                                <th><button type="button" class="btn btn-primary ">Modify</button></th>
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