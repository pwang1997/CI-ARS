<h1 class="text-center" style="margin-top:100px"><?php echo $title; ?></h1>
<style>
  p {
    text-align: center !important;
  }
</style>
<?php echo validation_errors(); ?>

<?php echo form_open('users/register'); ?>
<div class="row">
  <div class="form-group col-md-6 offset-md-3">
    <label for="role">Role</label>
    <select class="form-control" id="role" name="role">
      <option>teacher</option>
      <option>student</option>
    </select>
  </div>
</div>
<div class="row">
  <div class="form-group col-md-6 offset-md-3">
    <label for=" username">Username</label>
    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
  </div>
</div>
<div class="row">
  <div class="form-group col-md-6 offset-md-3">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
  </div>
</div>
<div class="row">
  <div class="form-group col-md-6 offset-md-3">
    <label for="password2">Confirm Password</label>
    <input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm Password">
  </div>
</div>
<div class="row">
  <div class="col-md-6 offset-md-3">
    <button type="submit" class="btn btn-primary" style="width:100%">Submit</button>
  </div>
</div>
<?php echo form_close(); ?>