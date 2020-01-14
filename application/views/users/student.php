<?php if (strcmp($this->session->role, 'student') != 0) : ?>
  <?php //redirect('users/login'); 
  ?>
<?php endif; ?>

<h2>Welcome back <?php echo $this->session->username . '!'; ?></h2>


<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Course Name</th>
      <th scope="col">Section Number</th>
      <th scope="col">Teacher</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($course_list as $course) : ?>
      <tr class="table-light">
        <th scope="row"><a href=""><?php echo $course['course_name']; ?></a></th>
        <td><?php echo $course['id']; ?></td>
        <td><?php echo $course['username']; ?></td>
        <td>TBD</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>