<?php if (strcmp($this->session->role, 'teacher') == 0) : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
<link rel="stylesheet" href="../../css/spinner.css">
<script src="<?= base_url(); ?>js/questions/student.js"></script>

<div class="question_off visible">
    <div class="d-flex flex-column align-items-center justify-content-center">
        <div class="row">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="row">
            <strong>Please prepare for quiz</strong>
        </div>
    </div>
</div>

<div class="question_on invisible">
    <!-- content + buttons  -->
    <div class="row">
        <div class="col-8">
            <br>
            <div class="form-group row" style="position:relative;">
                <div class="col-sm-8" id="scrolling-container" style="height:425px; min-width:100%; min-height:100%">
                    <div id="editor" style="min-height:100%; height:auto;"></div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <br>
            <div class="d-flex flex-column">
                <div class="p-2" id="status">Status:</div>
                <div class="p-2" id="targeted_time">Targeted Time: </div>
                <div class="p-2">
                    <p id="duration">
                    </p>
                    <div class="progress">
                    </div>
                </div>
                <div class="p-2">
                    <button type="button" class="btn btn-primary submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- answer/choices -->
    <div>
        <?php $choices = (isset($this->session->choices)) ? json_decode($this->session->choices) : [];
        $i = 1;
        foreach ($choices as $choice) : ?>
            <div class="form-group row choice_row">
                <label for="choice<?= $i; ?>" class="col-sm-2 col-form-label">:Choice <?= $i; ?></label>
                <div class="col-sm-6">
                    <input type="text" disabled class="form-control choice_row" name="choice_row" placeholder="<?= $choice; ?>">
                </div>
                <div class="form-check col-sm-1">
                    <input class="form-check-input answers" type="checkbox" name="answers" value="<?= $choice; ?>">
                </div>
            </div>
            <?php $i++; ?>
        <?php
        endforeach;
        ?>
    </div>
    <div class="options"></div>
</div>
