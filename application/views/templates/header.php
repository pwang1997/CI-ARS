<html>

<head>
  <title>ARS</title>
  <link rel="stylesheet" href="https://bootswatch.com/4/litera/bootstrap.min.css">
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crossfilter/1.3.12/crossfilter.min.js" integrity="sha256-T9tvV3x+/vCnCoFciKNZwbaJ46q9lh6iZjD0ZjD95lE=" crossorigin="anonymous"></script>
  <!-- Main Quill library -->
  <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
  <!-- Theme included stylesheets -->
  <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <!-- Customized js and css  -->
  <script src="<?= base_url(); ?>js/global.js"></script>
  <script src="<?= base_url(); ?>js/get_session.js"></script>
  <link href="<?= base_url(); ?>/css/overlay.css" rel="stylesheet">
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="./">Navbar</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarColor03">
        <ul class="navbar-nav  mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="<?php echo base_url(); ?>">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>about">About</a>
          </li>
          <!-- teacher's header-->
          <?php if ($this->session->role == "teacher") : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>users/teacher">Classrooms</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>questions/question_base">Question Base</a>
            </li>
          <?php elseif ($this->session->role == "student") : ?>
            <!-- student's header -->
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>users/student">Student</a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>analysis/category_cloud">Category Cloud</a>
          </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <?php if ($this->session->userdata('logged_in')) : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?><?php if ($this->session->role === 'teacher') : ?><?php echo 'users/teacher'; ?>
              <?php else : ?><?php echo 'users/student'; ?><?php endif; ?>">
                <?php echo $this->session->username; ?>
              </a>
            </li>
            <li class=" nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>users/logout">Logout</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>users/register">Register</a>
            </li>
            <li class="nav-item">
              <?php echo '<a class="nav-link" href="' . base_url() . 'users/login">Login</a>'; ?>
            </li>
          <?php endif; ?>

        </ul>
      </div>
    </nav>
  </header>

  <div id="overlay" style="display:none;" onclick="off()">
    <div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center">
      <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="mr-auto">Notification</strong>
          <small>n time ago</small>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="toast-body">
          Body
        </div>
      </div>
    </div>
  </div>


  <div class="container">
    <!-- Flash message -->
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
      <!-- <script src="<?= base_url(); ?>js/ws_student.js"></script> -->
    <?php elseif (isset($this->session->role) && $this->session->role == "teacher") : ?>
      <!-- <script src="<?= base_url(); ?>js/ws_teacher_helper.js"></script> -->
      <!-- <script src="<?= base_url(); ?>js/ws_teacher.js"></script> -->
    <?php endif; ?>