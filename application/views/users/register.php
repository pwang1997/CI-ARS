<div class="mx-auto">
  <h1 class="text-center"><?php echo $title; ?></h1>
</div>
<?php echo validation_errors(); ?>

<?php echo form_open('users/register'); ?>
<div class="mx-auto" style="width: 400px;">
  <div class="form-group">
    <label for="role">Role</label>
    <select class="form-control" id="role" name="role">
      <option>teacher</option>
      <option>student</option>
    </select>
  </div>
  <div class="form-group">
    <label for=" username">Username</label>
    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="password2">Confirm Password</label>
    <input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm Password">
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</div>
<?php echo form_close(); ?>