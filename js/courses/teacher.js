$(document).ready(() => {
  classroom_id = $("#classroom_id");
  $('#btn_add_student').click((e) => {
    e.preventDefault();
    sname = $("#username");
    last_student = $("#list_of_students > tbody").length + 1;

    $.ajax({
      url: `${base_url}/courses/add_student_from_classroom`,
      type: "POST",
      dataType: "JSON",
      data: {
        "username": sname.val(),
        "classroom_id": classroom_id.val()
      },
      success: function (response) {
        if (response.success) {
          $(".modal").modal('hide');
          $("#username").val("");
          $("#list_of_students > tbody").append('<tr class="table-light">' +
            '<th scrope="row">' + response.username + '</th>' + '<th><button type="button" class="btn btn-outline-primary btn_remove_student" id=btn_' + response.username + '>Remove</button>' +
            '<button type="button" class="btn btn-outline-primary">Edit</button></th>' + '</tr>');
        } else {
          alert('Student does not exitst');
        }
      },
      fail: function () {
        alert("failed");
      }
    });
  });

  $('.btn_remove_student').click((e) => {
    e.preventDefault();
    var target = e.target.id;
    // alert(target);
    sname = $('#' + target).parent().prev().text();

    $.ajax({
      url: `${base_url}/courses/remove_student_from_classroom`,
      type: "POST",
      dataType: "JSON",
      data: {
        "username": sname,
        "classroom_id": classroom_id.val()
      },
      success: function (response) {
        if (response.success) {
          $("#" + target).parent().parent().remove();
        } else {
          alert('Student does not exitst');
        }
      },
      fail: function () {
        alert("failed");
      }
    });
  });

  $('#add_quiz').click((e) => {
    e.preventDefault();
    $.ajax({
      url: "<?php echo base_url(); ?>courses/add_quiz_from_classroom",
      type: "POST",
      dataType: "JSON",
      data: {
        "classroom_id": classroom_id.val()
      },
      success: function (response) {
        if (response.success) {
          quiz_index = response.quiz_index;
          location.replace(`${base_url}/questions/create/${quiz_index}`);
        } else {
          alert("failed ")
        }
      },
      fail: function () {
        alert("failed");
      }
    });
  });

  //jump to question(ongoing) view
  $('button').click(function () {
    quiz_index = this.id.substring(3);
    console.log(quiz_index)
    if ($(this).hasClass('start')) {
      head = `${base_url}/questions/ongoing_quiz_teacher/${quiz_index}`;
      location.replace(head);
    } else if ($(this).hasClass('remove')) {
      $.ajax({
        url: `${base_url}/courses/remove_quiz_from_classroom`,
        type: "POST",
        dataType: "JSON",
        data: {
          "quiz_id": quiz_index
        },
        success: function (response) {
          if (response.success) {
            // alert('success');
            $(`#card_${quiz_index}`).remove();
          } else {
            alert("failed ")
          }
        },
        fail: function () {
          alert("failed");
        }
      })
    }

  })
});