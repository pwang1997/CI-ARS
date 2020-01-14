<?php echo form_open('users/login'); ?>
<div class="mx-auto" style="width: 400px;">

  <h1 class="text-center"><?php echo $title; ?></h1>

  <div class="form-group">
    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
  </div>
  <div class="form-group">
    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
  </div>

  <button type="submit" class="btn btn-primary btn-block">Submit</button>
</div>
<?php echo form_close(); ?>