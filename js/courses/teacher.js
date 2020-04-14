"use strict";

$(document).ready(() => {
    let classroom_id = $("#classroom_id");

    $(document).on('click', '#btn_add_student', (e) => {
        e.preventDefault();
        let sname = $("#username");
        $.ajax({
            url: `${root_url}/courses/add_student_from_classroom`,
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
                        append(`<tr id=${response.username}>
                              <th>${$("tbody").length}</th>
                              <th scrope="row">${response.username}</th>
                              <th>
                              <div class="row">
                                    <div class="col-md-6 pb-1">
                                        <button style="width:100%" type="button" class="btn btn-outline-danger btn_remove_student" id=btn_${response.username}>Remove</button>
                                    </div>
                                </div>
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
            url: `${root_url}/courses/remove_student_from_classroom`,
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
            url: `${root_url}/courses/add_quiz_from_classroom`,
            type: "POST",
            dataType: "JSON",
            data: {
                "classroom_id": classroom_id.val()
            },
            success: function (response) {
                if (response.success) {
                    location.replace(`${root_url}/questions/create/${response.quiz_index}`);
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
            url: `${root_url}/courses/export_student_stat`,
            type: "POST",
            dataType: "JSON",
            data: {
                "quiz_id": quiz_id
            },
            success: function (response) {
                let question_info = response.question_info;
                let quiz_info = response.quiz_info;
                let student_response = response.student_response;
                let student_list = response.student_list;

                let arr_question_info = [[], [], [], []];
                for (let a of Object.entries(question_info)) {
                    arr_question_info[0].push(a[1].id);
                    arr_question_info[1].push(a[1].content);
                    arr_question_info[2].push(a[1].choices);
                    arr_question_info[3].push(a[1].answer);
                }

                console.log(quiz_info);
                // console.log(question_info);
                console.log(student_response);
                // console.log(student_list);
                //export question
                let quiz_index = 1, question_index = 1;
                let arr_quiz = [];
                let arr_student_response = [];
                for (let quiz of Object.entries(quiz_info.quiz_instances)) {
                    quiz = quiz[1];
                    let created_at = quiz.create_at;
                    let quiz_id = quiz.id;
                    for (let question of Object.entries(quiz_info.question_instances[quiz_id])) {
                        let question_id = question[1].question_meta_id;
                        let question_instance_id = question[1].id;
                        let index = arr_question_info[0].indexOf(question_id);

                        let question_choices = arr_question_info[2][index].split('"').join("").split(',').join(";");
                        let question_answer = arr_question_info[3][index].split('"').join("").split(',').join(";");;

                        arr_quiz.push({
                            quiz_id: quiz_index,
                            question_id: question_index,
                            time_created: created_at,
                            content: arr_question_info[1][index],
                            choice: question_choices,
                            answer: question_answer
                        });
                        for (let s of Object.entries(student_list)) {
                            let student_id = s[1].student_id;
                            let student_name = s[1].username;

                            let student_response_list = student_response[question_instance_id];
                            let student_found = -1;
                            let c = 0;
                            for(let s_ of Object.entries(student_response_list)) {
                                if(s_[1].student_id == student_id) {
                                    student_found = c;
                                    break;
                                }
                                c++;
                            }
                            let student_answer = "";

                            if(student_found !== -1) {
                                student_answer = student_response_list[student_found].answer.split('"').join("").split(',').join(";");
                            }
                            arr_student_response.push({
                                quiz_id: quiz_index,
                                question_id: question_index,
                                student_id: student_id,
                                student_name: student_name,
                                student_answer: student_answer,
                                score: (student_answer == question_answer) ? 1 : 0
                            });
                        }

                        question_index++;
                    }
                    question_index = 1;
                    quiz_index++;
                }
                // console.log(arr_quiz);
                // console.log(arr_student_response);

                // let object_question = JSON.stringify(arr_questions);
                let headers_question = {
                    quiz_id: "Quiz Index",
                    question_id: "Question Index",
                    time_created: "Created At",
                    content: "Question Content",
                    choices: "Question Choices",
                    answer: "Question Answer"
                };

                let headers_student = {
                    quiz_id: "Quiz Index",
                    question_id: "Question Index",
                    student_id: "Student Id",
                    student_name: "Student Name",
                    answer: "Answer",
                    score: "Score"
                };
                
                export_csv_file(headers_question, arr_quiz, `questions-classroom-${classroom_id.val()}`);
                export_csv_file(headers_student, arr_student_response, `student-response-classroom-${classroom_id.val()}`);
            },
            fail: function () {
                alert("failed");
            }
        });
    });

    $('.history').click((e) => {
        e.preventDefault();
        let target = e.target.id;
        let quiz_id = target.split('_')[1];

        window.location.replace(`${root_url}/courses/review_history/${quiz_id}`);
    });

    //jump to question(ongoing) view
    $('button').click(function () {
        let quiz_index = this.id.substring(3);
        if ($(this).hasClass('start')) {
            let head = `${root_url}/questions/quiz/${quiz_index}`;
            location.replace(head);
        } else if ($(this).hasClass('remove')) {
            $.ajax({
                url: `${root_url}/courses/remove_quiz_from_classroom`,
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
            url: `${root_url}/courses/export_classroom_history`,
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
                let arr_questions = [[], []];
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
                        for (let question_instance of question_instances) {
                            for (let q_i of question_instance) {
                                if (arr_questions[1].indexOf(q_i.id) == -1 && q_i.question_meta_id == q.id) {
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

                for (let i = 0; i < arr_questions[1].length; i++) {
                    let question_instance_id = arr_questions[1][i].question_instance_id;
                    let student = students[question_instance_id];
                    if (student.length != 0) {
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