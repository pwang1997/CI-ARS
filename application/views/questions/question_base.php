<?php if ($this->session->role != "teacher") : ?>
    <?php redirect('home'); ?>
<?php elseif (empty($this->session->username)) : ?>
    <?php redirect('users/login'); ?>
<?php endif; ?>
<script src="../js/ddtf.js"></script>

<h2>Question Base</h2>
<br>
<table id="question_base_table" class="table table-bordered">
    <thead>
        <tr>
            <th>Index</th>
            <th>Course Name</th>
            <th>Course Code</th>
            <th>Section</th>
            <th>Teacher's Name</th>
            <th>Question Index</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php foreach ($result as $row) : ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $row['course_name']; ?></td>
                <td><?php echo $row['course_code']; ?></td>
                <td><?php echo $row['section']; ?></td>
                <td><?php echo $row['teacher_name']; ?></td>
                <td>
                    <?php if ($row['is_public'] == "true") : ?>
                        <a href="<?php echo base_url(); ?>questions/view/<?php echo $row['question_index']; ?>">
                            <?php echo $row['question_index']; ?>
                        </a>
                    <? else : ?>
                        <?php echo $row['question_index']; ?>
                    <? endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<script>
    $(document).ready(function() {
        $('#question_base_table').ddTableFilter();
    });
</script>