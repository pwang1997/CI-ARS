<?php
//echo validation_errors(); 
?>
<?php echo form_open('create_student'); ?>
<fieldset>
  <legend>Signup Sheet(Student)</legend>
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" id="username" placeholder="Username">
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="fullname">Full Name</label>
    <input type="text" class="form-control" id="fullname" placeholder="Fullname">
  </div>
  <div class="form-group">
    <label for="age">Age</label>
    <select class="form-control" id="age">
      <?php
      for ($i = 3; $i < 100; $i++) {
        echo "<option>" . $i . "</option>";
      }
      ?>
    </select>
  </div>
  <div class="form-group">
    <label for="schoolFullname">School Full Name</label>
    <input type="text" class="form-control" id="schoolFullname" placeholder="School Full Name">
  </div>
  <div class="form-group">
    <label for="schoolAddress">School Address</label>
    <input type="text" class="form-control" id="schoolAddress" placeholder="School Address">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</fieldset>
</form>