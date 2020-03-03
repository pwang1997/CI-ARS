  <link href="<?= base_url(); ?>/css/quill_editor_custom.css" rel="stylesheet">
  <script src="<?= base_url(); ?>js/questions/create.js"></script>
  <?php if (strcmp($this->session->role, 'student') == 0) : ?>
      <?php redirect('home'); ?>
  <?php elseif (empty($this->session->username)) : ?>
      <?php redirect('users/login'); ?>
  <?php endif; ?>
  <h2>Create Question</h2>

  <div class="form_question">
      <?php echo form_open('questions/create'); ?>
      <input type="hidden" id="quiz_index" value=<?php echo $lab_index; ?>>
      <!-- timer type  -->
      <div class="form-group row">
          <label for="timerType" class="col-sm-2 col-form-label">Timer Type</label>
          <div class="col-sm-10">
              <div class="btn-group btn-group-toggle" data-toggle="buttons" id="timerType">
                  <label class="btn btn-outline-primary">
                      <input type="radio" name="timer_types" id="time_up" value="timeup" autocomplete="off"> Time Up
                  </label>
                  <label class="btn btn-outline-primary">
                      <input type="radio" name="timer_types" id="time_down" value="timedown" autocomplete="off"> Time Down
                  </label>
              </div>
          </div>
      </div>
      <!-- public access of the question -->
      <div class="form-group row">
          <label for="isPublic" class="col-sm-2 col-form-label">Access</label>
          <div class="col-sm-10">
              <div class="btn-group btn-group-toggle" data-toggle="buttons" id="isPublic">
                  <label class="btn btn-outline-primary">
                      <input type="radio" name="accesses" value="false" autocomplete="off"> Private
                  </label>
                  <label class="btn btn-outline-primary">
                      <input type="radio" name="accesses" value="true" autocomplete="off"> Public
                  </label>
              </div>
          </div>
      </div>
      <!-- difficulty of the question  -->
      <div class="form-group row">
          <label for="difficulty" class="col-sm-2 col-form-label">Difficulty</label>
          <div class="col-sm-10">
              <div class="btn-group btn-group-toggle" data-toggle="buttons" id="difficulty">
                  <label class="btn btn-outline-primary">
                      <input type="radio" name="difficulties" id="easy" value="easy" autocomplete="off"> Easy
                  </label>
                  <label class="btn btn-outline-primary">
                      <input type="radio" name="difficulties" id="medium" value="medium" autocomplete="off"> Medium
                  </label>
                  <label class="btn btn-outline-primary">
                      <input type="radio" name="difficulties" id="hard" value="hard" autocomplete="off"> Hard
                  </label>
              </div>
          </div>
      </div>
      <!-- category of the question -->
      <div class="form-group row">
          <label for="category" class="col-sm-2 col-form-label">Category</label>
          <div class="col-sm-6">
              <input type="text" class="form-control" name="category" placeholder="category" autocomplete="on">
          </div>
      </div>
      <!-- duration of the question  -->
      <div class="form-group row">
          <label for="duration" class="col-sm-2 col-form-label">Duration</label>
          <div class="col-sm-6">
              <input type="text" class="form-control" name="duration" placeholder="duration(in second)" autocomplete="off">
          </div>
      </div>
      <!-- Quill editor  -->
      <div class="row editor-container">
          <div class="col-sm-8" id="scrolling-container">
              <div id="editor"></div>
          </div>
      </div>
      <hr>
      <!-- answer heading -->
      <div class="row">
          <div class="col-sm-2 offset-sm-8">
              <h6>Answer</h6>
          </div>
      </div>
      <!-- choice content + answer -->
      <div class="option_row">
          <?php for ($i = 1; $i <= 4; $i++) : ?>
              <div class="form-group row choice_row">
                  <label for="choice<?= $i; ?>" class="col-sm-12 col-md-2 col-form-label">:Choice <?= $i; ?></label>
                  <div class="col-sm-6">
                      <input type="text" class="form-control" id="choice<?= $i; ?>" autocomplete="on">
                  </div>
                  <div class="custom-control custom-checkbox col-sm-1 ml-3">
                      <input type="checkbox" class="custom-control-input " id="customCheck_<?= $i; ?>" name="answers" value="correct">
                      <label class="custom-control-label" for="customCheck_<?= $i; ?>"></label>
                  </div>
                  <!-- <div class="form-check col-sm-1">
                        <input class="form-check-input" type="checkbox" name="answers" value="correct">
                    </div> -->
              </div>
          <?php endfor; ?>
      </div>
  </div>
  <!-- add choices/ remove empty choices -->
  <div class="form-group row">
      <div class="offset-sm-5 col-sm-2 pb-1">
          <button style="width:100%" type="button" class="btn btn-outline-primary mr-2" id="add_more_choices">Add</button>
      </div>
      <div class="col-sm-2">
          <button style="width:100%" type="button" class="btn btn-outline-primary" id="remove_blanks">Remove</button>
      </div>
  </div>

  <!-- submit -->
  <div class="row pb-4">
      <input type="submit" class="btn btn-outline-primary btn-block" value="Submit">
  </div>
  <?php echo form_close(); ?>
  </div>