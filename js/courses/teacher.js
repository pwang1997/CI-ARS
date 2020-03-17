"use strict";

$(document).ready(() => {
    let classroom_id = $("#classroom_id");

    $(document).on('click', '#btn_add_student', (e) => {
        e.preventDefault();
        let sname = $("#username");
        $.ajax({
            url: `${base_url}/add_student_from_classroom`,
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
                    $("tbody").
                        append(`<tr class="table-light" id=${response.username}>
                              <th>${$("tbody").length}</th>
                              <th scrope="row">${response.username}</th>
                              <th>
                                <button style="width:100%" type="button" class="btn btn-outline-danger btn_remove_student" id=btn_${response.username}>Remove</button>
                                <button style="width:100%" type="button" class="btn btn-outline-primary btn_modify_student">Edit</button>
                              </th>
                            </tr>`);
                } else {
                    alert('Student does not exitst');
                }
            },
            fail: function () {
                alert("failed");
            }
        });
    });

    $(document).on('click', '.btn_remove_student', (e) => {
        e.preventDefault();
        let target = e.target.id;
        let sname = target.split('_')[1];
        $.ajax({
            url: `${base_url}/remove_student_from_classroom`,
            type: "POST",
            dataType: "JSON",
            data: {
                "username": sname,
                "classroom_id": classroom_id.val()
            },
            success: function (response) {
                if (response.success) {
                    $(`#${sname}`).remove();
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
            url: `${base_url}/add_quiz_from_classroom`,
            type: "POST",
            dataType: "JSON",
            data: {
                "classroom_id": classroom_id.val()
            },
            success: function (response) {
                if (response.success) {
                    quiz_index = response.quiz_index;
                    location.replace(`${base_url}/../questions/create/${quiz_index}`);
                } else {
                    alert("failed ")
                }
            },
            fail: function () {
                alert("failed");
            }
        });
    });

    $('.export').click((e) => {
        e.preventDefault();
        let target = e.target.id;
        let quiz_id = target.split('_')[1];

        $.ajax({
            url: `${base_url}/export_student_stat`,
            type: "POST",
            dataType: "JSON",
            data: {
                "quiz_id": quiz_id
            },
            success: function (response) {
                let questions = response.result.question;
                let arr_questions = [];
                //export question
                for (let question of questions) {
                    let choices = question.choices.split('"').join("").split(',').join(";");
                    let answer = question.answer.split('"').join("").split(',').join(";");;
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
                    content: "content",
                    choices: "choices",
                    answer: "answer",
                    time_created: "time created"
                };

                //export student record
                let students = response.result.student;
                let arr_students = [];
                console.log(arr_questions)
                for (let i = 0; i < arr_questions.length; i++) {
                    let student = students[arr_questions[i].question_id][0];
                    console.log(student);
                    if (student != null) {
                        let answer = student.answer.split('"').join("").split(',').join(";");
                        arr_students.push({
                            question_id: student.question_instance_id,
                            student_name: student.username,
                            answer: answer,
                            time_answered: student.time_answered
                        });
                    }
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
            fail: function () {
                alert("failed");
            }
        });
    });
    //jump to question(ongoing) view
    $('button').click(function () {
        let quiz_index = this.id.substring(3);
        if ($(this).hasClass('start')) {
            let head = `${base_url}/../questions/ongoing_quiz_teacher/${quiz_index}`;
            location.replace(head);
        } else if ($(this).hasClass('remove')) {
            $.ajax({
                url: `${base_url}/remove_quiz_from_classroom`,
                type: "POST",
                dataType: "JSON",
                data: {
                    "quiz_id": quiz_index
                },
                success: function (response) {
                    if (response.success) {
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

    $('#export_class_history').click((e) => {

        $.ajax({
            url: `${base_url}/export_classroom_history`,
            type: "POST",
            dataType: "JSON",
            data: {
                classroom_id: classroom_id.val()
            },
            success: function (response) {
                let questions = response.result.questions;
                let student_responses = response.result.student_responses;
                let question_instances = response.result.question_instances;
                // console.log(question_instances);
                //arr_questions[0]:list of question info, arr_questions[1]: list of question instances
                let arr_questions = [[],[]];
                //export question
                for (let question of questions) {
                    for (let q of question) {
                        //get questoin info
                        let choices = q.choices.split('"').join("").split(',').join(";");
                        let answer = q.answer.split('"').join("").split(',').join(";");;
                        arr_questions[0].push({
                            question_id: q.id,
                            quiz_id: q.quiz_id,
                            content: q.content,
                            choices: choices,
                            answer: answer
                        });
                        //get question instances
                        for(let question_instance of question_instances) {
                            for(let q_i of question_instance) {
                                if(arr_questions[1].indexOf(q_i.id) == -1 && q_i.question_meta_id == q.id) {
                                    arr_questions[1].push({
                                        question_id: q.id,
                                        question_instance_id: q_i.id,
                                        time_created: q_i.time_created
                                    });
                                }
                            }
                        }
                    }
                }

                // let object_question = JSON.stringify(arr_questions);
                let headers_question = {
                    question_instance: "question instance id",
                    quiz_id: "quiz id",
                    content: "content",
                    choices: "choices",
                    answer: "answer"
                };

                //export student record
                let students = student_responses;
                let arr_students = [];
                console.log(students);
                console.log(arr_questions);

                for(let i = 0; i < arr_questions[1].length; i++) {
                    let question_instance_id = arr_questions[1][i].question_instance_id;
                    let student = students[question_instance_id];
                    if(student.length != 0) {
                        student = student[0];
                        let answer = student.answer.split('"').join("").split(',').join(";");
                        arr_students.push({
                            question_instance_id: student.question_instance_id,
                            student_name: student.username,
                            answer: answer,
                            time_answered: student.time_answered
                        });
                    }
                }

                let object_student = JSON.stringify(arr_students);
                let headers_student = {
                    question_id: "question id",
                    student_name: "username",
                    answer: "answer",
                    time_answered: "time answered"
                };


                let headers_question_instances = {
                    quiz_id: "quiz id",
                    question_instance: "question instance id",
                    time_answered: "time answered"
                };
                
                export_csv_file(headers_question, arr_questions[0], `questions-classroom-${classroom_id.val()}`);
                export_csv_file(headers_question_instances, arr_questions[1], `question-instances-classroom-${classroom_id.val()}`);
                export_csv_file(headers_student, arr_students, `student-response-classroom-${classroom_id.val()}`);
                
            },
            fail: function () {
                alert("failed");
            }
        });
    })
});

function json_to_csv(objArray) {
    let array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
    let str = '';

    for (let i = 0; i < array.length; i++) {
        let line = '';
        for (let index in array[i]) {
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
    let jsonObject = JSON.stringify(items);

    let csv = json_to_csv(jsonObject);

    let exportedFilenmae = fileTitle + '.csv' || 'export.csv';

    let blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, exportedFilenmae);
    } else {
        let link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            let url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", exportedFilenmae);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}