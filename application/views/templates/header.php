<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Honours-2020</title>
  <!-- Custom styles for this template -->
  <link href="<?= base_url(); ?>css/sidebar.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crossfilter/1.3.12/crossfilter.min.js" integrity="sha256-T9tvV3x+/vCnCoFciKNZwbaJ46q9lh6iZjD0ZjD95lE=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://bootswatch.com/4/litera/bootstrap.min.css">
  <script src="https://kit.fontawesome.com/0f9d378675.js" crossorigin="anonymous"></script>
  <!-- Theme included stylesheets -->
  <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <!-- Customized css  -->
  <link href="<?= base_url(); ?>/css/global.css" rel="stylesheet">
  <link href="<?= base_url(); ?>/css/overlay.css" rel="stylesheet">
  <!-- Customized js   -->
  <script src="<?= base_url(); ?>js/global.js"></script>
  <script src="<?= base_url(); ?>js/get_session.js"></script>
</head>

<body>

  <div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark border-right text-light" id="sidebar-wrapper">
      <div class="sidebar-heading bg-primary">Honours-Project-2020</div>
      <div class="list-group list-group-flush">
        <?php
        $current = basename($_SERVER['REQUEST_URI']);
        $arr_url =  explode('/', $_SERVER['REQUEST_URI']);
        ?>
        <a href="<?php echo base_url(); ?>" class="list-group-item list-group-item-action bg-dark text-light <?php if ($current === 'CI-ARS') echo "font-weight-bold"; ?>">Home</a>
        <div class="line"></div>
        <a href="<?php echo base_url(); ?>about" class="list-group-item list-group-item-action bg-dark text-light <?php if ($current === 'about') echo "font-weight-bold"; ?>">About</a>
        <div class="line"></div>
        <!-- teacher's header-->
        <?php if ($this->session->role == "teacher") : ?>
          <a href="#teacher" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle list-group-item list-group-item-action bg-dark text-light <?php if (in_array("teacher", $arr_url) && in_array("courses", $arr_url)) echo "font-weight-bold"; ?>">Classroom</a>
          <ul class="nav collapse list-unstyled" id="teacher">
            <?php if ((in_array("teacher", $arr_url) && in_array("courses", $arr_url))) : ?>
              <li>
                <a href="<?php echo base_url(); ?>users/teacher" class="list-group-item list-group-item-action bg-dark text-light <?php if (in_array("teacher", $arr_url) && !in_array("courses", $arr_url)) echo "font-weight-bold"; ?>">Manage Courses</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active list-group-item list-group-item-action bg-dark text-light" id="list-course-detail" data-toggle="list" href="#list-course" role="tab" aria-controls="course">Course Detail
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link list-group-item list-group-item-action bg-dark text-light" id="list-quiz-list" data-toggle="list" href="#list-quiz" role="tab" aria-controls="quiz">Quizs
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link list-group-item list-group-item-action bg-dark text-light" id="list-student-list" data-toggle="list" href="#list-student" role="tab" aria-controls="student">Students
                </a>
              </li>
            <?php elseif (in_array("teacher", $arr_url) && in_array("questions", $arr_url)) : ?>
              <li>
                <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="list-group-item list-group-item-action bg-dark text-light">Quizs</a>
              </li>
            <?php else : ?>
              <li>
                <a href="<?php echo base_url(); ?>users/teacher" class="list-group-item list-group-item-action bg-dark text-light <?php if (in_array("teacher", $arr_url) && !in_array("courses", $arr_url)) echo "font-weight-bold"; ?>">Manage Courses</a>
              </li>
              <li>
                <a href="<?php echo base_url(); ?>courses/create" class="list-group-item list-group-item-action bg-dark text-light <?php if (!in_array("teacher", $arr_url) && in_array("create", $arr_url)) echo "font-weight-bold"; ?>">Add Course</a>
              </li>
            <?php endif; ?>
          </ul>
          <a href="<?php echo base_url(); ?>questions/question_base" class="list-group-item list-group-item-action bg-dark text-light <?php if (in_array("question_base", $arr_url)) echo "font-weight-bold"; ?>">Quesion Base</a>
          <div class="line"></div>
          <?php if (in_array("quiz", $arr_url) && in_array("questions", $arr_url)) : ?>
            <a href="#quiz" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle list-group-item list-group-item-action bg-dark text-light">Quiz History</a>
            <ul class="nav collapse list-unstyled" id="quiz">
              <?php $index = 1;
              foreach ($question_list as $question) : ?>
                <li class="nav-item">
                  <a class="nav-link list-group-item list-group-item-action bg-dark text-light <?php if ($index == 1) echo 'active'; ?>" id="list-question_<?= $question['id']; ?>" data-toggle="list" href="#list-<?= $question['id']; ?>" role="tab" aria-controls="<?= $index; ?>">Question <?= $index++; ?></a>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>


          <div class="line"></div>
          <?php if (in_array("review_history", $arr_url) && in_array("courses", $arr_url)) : ?>
            <a href="#quiz_history" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle list-group-item list-group-item-action bg-dark text-light">Quiz History</a>
            <ul class="nav collapse list-unstyled" id="quiz_history">
              <?php if (isset($quiz_instance_list)) : ?>
                <?php $quiz_instance_counter = 1; ?>
                <?php foreach ($quiz_instance_list as $quiz_instance) : ?>
                  <?php $quiz_instance_id = $quiz_instance['id']; ?>
                  <li class="nav-item">
                    <a class="nav-link list-group-item list-group-item-action bg-dark text-light <?php if ($quiz_instance_counter == 1) echo "active"; ?>" id="list-quiz-<?= $quiz_instance_id; ?>" data-toggle="list" href="#quiz-<?= $quiz_instance_id; ?>" role="tab">
                      <?php
                      $date = new DateTime($quiz_instance['create_at']);
                      echo $date->format('Y-m-d');
                      $quiz_instance_counter++; ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              <?php endif; ?>
            </ul>
          <?php endif; ?>
        <?php elseif ($this->session->role == "student") : ?>
          <!-- course-instance info: course detail, quiz list, grade table -->
          <?php
          if (in_array("courses", $arr_url) && in_array("student", $arr_url)) :
          ?>
            <a href="#student" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle list-group-item list-group-item-action bg-dark text-light <?php if ($current === "student") echo "font-weight-bold"; ?>">Classroom</a>
            <ul class="collapse list-unstyled nav" id="student">
              <li class="nav-item">
                <a class="nav-link active list-group-item list-group-item-action bg-dark text-light" id="list-course-detail" data-toggle="list" href="#list-course" role="tab" aria-controls="course">Course Detail
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link list-group-item list-group-item-action bg-dark text-light" id="list-quiz-list" data-toggle="list" href="#list-quiz" role="tab" aria-controls="quiz">Quizs
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link list-group-item list-group-item-action bg-dark text-light" id="list-grade-list" data-toggle="list" href="#list-grade" role="tab" aria-controls="student">Grades
                </a>
              </li>
            </ul>
          <?php else : ?>
            <!-- student's header -->
            <a href="<?php echo base_url(); ?>users/student" class="list-group-item list-group-item-action bg-dark text-light <?php if ($current === "student") echo "font-weight-bold"; ?>">Classroom</a>
          <?php endif; ?>
          <div class="line"></div>
        <?php endif; ?>
        <div class="line"></div>
        <a href="<?php echo base_url(); ?>analysis/category_cloud" class="list-group-item list-group-item-action bg-dark text-light <?php if ($current === "category_cloud") echo "font-weight-bold"; ?>">Category Cloud</a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-primary border-bottom">
        <button class="btn btn-primary" id="menu-toggle"><i class="fas fa-bars fa-lg"></i></button>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <?php if ($this->session->userdata('logged_in')) : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?><?php if ($this->session->role === 'teacher') : ?><?php echo 'users/teacher'; ?>
              <?php else : ?><?php echo 'users/student'; ?><?php endif; ?>">
                  <i class="fas fa-user-circle fa-lg" id='user' data-toggle="tooltip" data-html="true" title="user account"></i>
                </a>
              </li>
              <li class=" nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>users/logout">
                  <i class="fas fa-sign-out-alt fa-lg" id='sign-out' data-toggle="tooltip" data-html="true" title="sign-out"></i>
                </a>
              </li>
            <?php else : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>users/register">
                  <i class="fas fa-user-plus fa-lg" id='sign-up' data-toggle="tooltip" data-html="true" title="sign-up"></i>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url(); ?>users/login">
                  <i class="fas fa-sign-in-alt fa-lg" id='sign-in' data-toggle="tooltip" data-html="true" title="sign-in"></i>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </nav>
      <!-- Flash message -->
      <div class="flash-message">
        <?php if ($this->session->flashdata('user_registered')) : ?>
          <?php echo '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $this->session->flashdata('user_registered') . '</div>'; ?>
        <?php endif; ?>

        <?php if ($this->session->flashdata('login_failed')) : ?>
          <?php echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $this->session->flashdata('login_failed') . '</div>'; ?>
        <?php endif; ?>

        <?php if ($this->session->flashdata('user_loggedin')) : ?>
          <?php echo '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $this->session->flashdata('user_loggedin') . '</div>'; ?>
        <?php endif; ?>

        <?php if ($this->session->flashdata('user_loggedout')) : ?>
          <?php echo '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $this->session->flashdata('user_loggedout') . '</div>'; ?>
        <?php endif; ?>

        <?php if (isset($this->session->role) && $this->session->role == "student") : ?>
          <!-- <script src="<?= base_url(); ?>js/ws_student_helper.js"></script> -->
          <script src="<?= base_url(); ?>js/ws_student.js"></script>
        <?php elseif (isset($this->session->role) && $this->session->role == "teacher") : ?>
          <!-- <script src="<?= base_url(); ?>js/ws_teacher_helper.js"></script> -->
          <!-- <script src="<?= base_url(); ?>js/ws_teacher.js"></script> -->
        <?php endif; ?>
      </div>
      <div class="container-fluid">