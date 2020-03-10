$(document).ready(() => {
    let classroom_id = $("#classroom_id");

    $(document).on('click', '#btn_add_student', (e) => {
        e.preventDefault();
        let sname = $("#username");
        $.ajax({
            url: `${base_url}/courses/add_student_from_classroom`,
            type: "POST",
            dataType: "JSON",
            data: {
                "username": sname.val(),
                "classroom_id": classroom_id.val()
            },
            success: function(response) {
                if (response.success) {
                    $(".modal").modal('hide');
                    $("#username").val("");
                    $("tbody").
                    append(`<tr class="table-light" id=${response.username}>
                              <th>${$("tbody").length}</th>
                              <th scrope="row">${response.username}</th>
                              <th>
                                <button type="button" class="btn btn-outline-danger btn_remove_student" id=btn_${response.username}>Remove</button>
                                <button type="button" class="btn btn-outline-primary btn_modify_student">Edit</button>
                              </th>
                            </tr>`);
                } else {
                    alert('Student does not exitst');
                }
            },
            fail: function() {
                alert("failed");
            }
        });
    });

    $(document).on('click', '.btn_remove_student', (e) => {
        e.preventDefault();
        let target = e.target.id;
        sname = target.split('_')[1];
        $.ajax({
            url: `${base_url}/courses/remove_student_from_classroom`,
            type: "POST",
            dataType: "JSON",
            data: {
                "username": sname,
                "classroom_id": classroom_id.val()
            },
            success: function(response) {
                if (response.success) {
                    $(`#${sname}`).remove();
                } else {
                    alert('Student does not exitst');
                }
            },
            fail: function() {
                alert("failed");
            }
        });
    });

    $('#add_quiz').click((e) => {
        e.preventDefault();
        $.ajax({
            url: `${base_url}/courses/add_quiz_from_classroom`,
            type: "POST",
            dataType: "JSON",
            data: {
                "classroom_id": classroom_id.val()
            },
            success: function(response) {
                if (response.success) {
                    quiz_index = response.quiz_index;
                    location.replace(`${base_url}/questions/create/${quiz_index}`);
                } else {
                    alert("failed ")
                }
            },
            fail: function() {
                alert("failed");
            }
        });
    });

    $('.export').click((e) => {
        e.preventDefault();
        let target = e.target.id;
        let quiz_id = target.split('_')[1];

        $.ajax({
            url: `${base_url}/courses/export_student_stat`,
            type: "POST",
            dataType: "JSON",
            data: {
                "quiz_id": quiz_id
            },
            success: function(response) {
                questions = response.result.question;
                arr_questions = [];
                //export question
                for (let question of questions) {
                    choices = question.choices.split('"').join("").split(',').join(";");
                    answer = question.answer.split('"').join("").split(',').join(";");;
                    arr_questions.push({
                        question_id: question.id,
                        quiz_id: question.quiz_id,
                        content: question.content,
                        choices: choices,
                        answer: answer,
                        time_created: question.time_created
                    });
                }

                // let object_question = JSON.stringify(arr_questions);
                let headers_question = {
                    question_id: "question id",
                    quiz_id: "quiz id",
                    content: "contetn",
                    choices: "choices",
                    answer: "answer",
                    time_created: "time created"
                };

                //export student record
                students = response.result.student;
                arr_students = [];
                for (let i = 0; i < arr_questions.length; i++) {
                    student = students[arr_questions[i].question_id][0];
                    // console.log(student.id);

                    answer = student.answer.split('"').join("").split(',').join(";");;
                    arr_students.push({
                        question_id: student.question_instance_id,
                        student_name: student.username,
                        answer: answer,
                        time_answered: student.time_answered
                    });
                }
                // let object_student = JSON.stringify(arr_students);
                let headers_student = {
                    question_id: "question id",
                    student_name: "username",
                    answer: "answer",
                    time_answered: "time answered"
                };

                export_csv_file(headers_question, arr_questions, `questions-classroom-${classroom_id.val()}`);
                export_csv_file(headers_student, arr_students, `student-response-classroom-${classroom_id.val()}`);
            },
            fail: function() {
                alert("failed");
            }
        });
    });
    //jump to question(ongoing) view
    $('button').click(function() {
        quiz_index = this.id.substring(3);
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
                success: function(response) {
                    if (response.success) {
                        $(`#card_${quiz_index}`).remove();
                    } else {
                        alert("failed ")
                    }
                },
                fail: function() {
                    alert("failed");
                }
            })
        }
    })
});

function json_to_csv(objArray) {
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
    var str = '';

    for (var i = 0; i < array.length; i++) {
        var line = '';
        for (var index in array[i]) {
            if (line != '') line += ','

            line += array[i][index];
        }

        str += line + '\r\n';
    }

    return str;
}

function export_csv_file(headers, items, fileTitle) {
    if (headers) {
        items.unshift(headers);
    }

    // Convert Object to JSON
    var jsonObject = JSON.stringify(items);

    var csv = json_to_csv(jsonObject);

    var exportedFilenmae = fileTitle + '.csv' || 'export.csv';

    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, exportedFilenmae);
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", exportedFilenmae);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}