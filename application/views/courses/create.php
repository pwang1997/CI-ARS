<?php echo form_open('courses/create'); ?>
<div class="mx-auto" style="width: 400px;">

  <h2 class="text-center"><?= $title ?></h2>
  <div class="form-group">
    <input type="text" name="courseName" class="form-control" placeholder="Course Name" required autofocus>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" id="courseCode" name="courseCode" placeholder="Course Code" required>
  </div>
  <div class="form-group">
    <textarea class="form-control" id="description" name="description" rows="3" spellcheck="true" placeholder="Description"></textarea>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" id="sectionId" name="sectionId" placeholder="Course Section" required>
  </div>

  <button type="submit" class="btn btn-primary btn-block">Submit</button>
</div>
<?php echo form_close(); ?>