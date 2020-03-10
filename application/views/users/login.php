<?php echo form_open('users/login'); ?>

<h1 class="text-center" style="margin-top:175px"><?php echo $title; ?></h1>
<div class="row">
  <div class="form-group col-md-6 offset-md-3">
    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
  </div>
</div>
<div class="row">
  <div class="form-group col-md-6 offset-md-3">
    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
  </div>
</div>
<div class="row">
  <div class="col-md-6 offset-md-3">
    <button type=" submit" class="btn btn-primary btn-block">Submit</button>
  </div>
</div>

<?php echo form_close(); ?>