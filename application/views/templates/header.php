<html>

<head>
  <title>ARS</title>
  <link rel="stylesheet" href="https://bootswatch.com/4/litera/bootstrap.min.css">
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
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>users/teacher">T</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>users/student">S</a>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <?php if ($this->session->flashdata('user_loggedin')) : ?>
            <li class="nav-item">
              <?php if (strcmp($this->session->role, 'teacher') == 0) : ?>
                <a class="nav-link" href="<?php echo base_url(); ?>users/teacher"><?php echo $this->session->username; ?></a>
              <?php elseif (strcmp($this->session->role, 'student') == 0) : ?>
                <a class="nav-link" href="<?php echo base_url(); ?>users/student"><?php echo $this->session->username; ?></a>
              <?php endif; ?>
            </li>
            <li class="nav-item">
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