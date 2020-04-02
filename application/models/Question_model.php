<?php

class Question_model extends CI_Model
{
  public function __construct()
  {
    $this->load->database();
  }

  public function create($quiz_index)
  {
    $quiz_id = $this->getQuiz($quiz_index);
    if (!empty($quiz_id)) {
      $quiz_id = $quiz_id[0]['id'];
    } else {
      return false;
    }
    $data = array(
      'quiz_id' => $quiz_id,
      'duration' => $this->input->post('duration'),
      'content' => $this->input->post('content'),
      'answer' => $this->input->post('answer'),
      'choices' => $this->input->post('choices'),
      'category' => $this->input->post('category'),
      'difficulty' => $this->input->post('difficulty'),
      'timer_type' => $this->input->post('timer_type'),
      'is_public' => $this->input->post('isPublic')
    );
    return $this->db->insert('questions', $data);
  }

  public function has_question_in_quiz($quiz_index)
  {
    return $this->db->select('COUNT(id) as counter')->get_where('questions', array('quiz_id' => $quiz_index))->result_array()[0]['counter'] != 0;
  }

  public function getQuiz($quiz_index)
  {
    return $result = $this->db->get_where('quizs', array('id' => $quiz_index))->result_array();
  }

  public function getQuestions($quiz_index)
  {
    $quiz_id = $this->getQuiz($quiz_index)[0]['id'];
    return $this->db->get_where('questions', array('quiz_id' => $quiz_id))->result_array();
  }

  public function student_response()
  {
    $data = array(
      'student_id' => $this->input->post('student_id'),
      // 'question_instance_id' => $this->input->post('question_instance_id'),
      'answer' => $this->input->post('answer')
    );

    return $this->db->insert('student_responses', $data);
  }

  public function get_question_base()
  {
    $teacher_id = $this->session->id;
    if ($this->session->role != "teacher") return;
    else {
      $this->db->select("courses.course_name as course_name, courses.course_code as course_code,
      users.username as teacher_name, classrooms.section_id as section, questions.id as question_index, 
      questions.is_public as is_public, questions.category as category, questions.difficulty as difficulty");
      $this->db->from("users");
      $this->db->where('questions.is_public', "true");
      $this->db->or_where('users.id', $teacher_id);
      $this->db->join('classrooms', 'users.id = classrooms.taught_by');
      $this->db->join('courses', 'classrooms.course_id = courses.id');
      $this->db->join('quizs', 'quizs.classroom_id = classrooms.id');
      $this->db->join('questions', 'quizs.id = questions.quiz_id');
      return $this->db->get()->result_array();
    }
  }

  public function update_question($quiz_index)
  {
    $quiz_id = $this->getQuiz($quiz_index);
    if (!empty($quiz_id)) {
      $quiz_id = $quiz_id[0]['id'];
    } else {
      return false;
    }
    $data = array(
      'quiz_id' => $quiz_id,
      'duration' => $this->input->post('duration'),
      'content' => $this->input->post('content'),
      'category' => $this->input->post('category'),
      'difficulty' => $this->input->post('difficulty'),
      'timer_type' => $this->input->post('timer_type'),
      'is_public' => $this->input->post('isPublic'),
      'choices' => $this->input->post('choices'),
      'answer' => $this->input->post('answer')
    );
    return $this->db->where('id', $this->input->post('id'))->update('questions', $data);
  }

  public function get_question($question_index)
  {
    return $this->db->get_where('questions', array('id' => $question_index))->result_array()[0];
  }

  public function get_all_courses()
  {
    return $this->db->select('*')->from('classrooms')->where('taught_by', $this->session->id)
      ->join('courses', 'courses.id = classrooms.course_id')->get()->result_array();
  }


  public function get_all_quizs()
  {
  }
  public function add_to_quiz()
  {
    $teacher_id = $this->session->id;
  }

  public function add_question_instance($question_index, $last_quiz_instance_id)
  {
    //get new or paused question instances
    $this->db->order_by('id', 'DESC')->limit(1);
    $result = $this->db->get_where('question_instances', array(
      'question_meta_id' => $question_index,
      'quiz_instance_id' => $last_quiz_instance_id, 'status !=' => 'complete'
    ))->result_array();
    $question_instance_id = null;

    if (!empty($result)) {
      // retrive last question instance 
      $question_instance_id = $result[0]['id'];
    } else {
      //create new question instance
      $data = [
        'question_meta_id' => $question_index,
        'quiz_instance_id' => $last_quiz_instance_id,
        'status' => 'new'
      ];
      $this->db->insert('question_instances', $data);
      $question_instance_id = $this->db->insert_id();
    }
    return ['success' => true, 'question_instance_id' => $question_instance_id];
  }

  public function pause_question_instance($question_index, $last_quiz_instance_id)
  {
    $data = [
      'status' => 'pause'
    ];
    $this->db->order_by('id', 'DESC')->limit(1);
    $this->db->set($data)->where(
      array(
        'question_meta_id' => $question_index,
        'quiz_instance_id' => $last_quiz_instance_id
      )
    )->update('question_instances');
  }

  public function resume_question_instance($question_index, $last_quiz_instance_id)
  {
    $data = [
      'status' => 'new'
    ];

    $this->db->order_by('id', 'DESC')->limit(1);
    $this->db->set($data)->where(
      array(
        'question_meta_id' => $question_index,
        'quiz_instance_id' => $last_quiz_instance_id
      )
    )->update('question_instances');
  }

  public function complete_question_instance($question_index, $last_quiz_instance_id)
  {
    $data = [
      'status' => 'complete'
    ];

    $this->db->order_by('id', 'DESC')->limit(1);
    $this->db->set($data)->where(
      array(
        'question_meta_id' => $question_index,
        'quiz_instance_id' => $last_quiz_instance_id
      )
    )->update('question_instances');
  }

  public function submit_student_response()
  {
    $data = array(
      'question_instance_id' => $this->input->post('question_instance_id'),
      'student_id' => $this->input->post('student_id'),
      'answer' => $this->input->post('answer')
    );
    return $this->db->insert('student_responses', $data);
  }

  public function get_num_question($quiz_index)
  {
    return $this->db->select('COUNT(id) as size')->from('questions')->where(array('quiz_id' => $quiz_index))->get()->result_array()[0];
  }

  public function get_categories($quiz_index)
  {
    $result = $this->db->select('category')->from('questions')->where(array('quiz_id' => $quiz_index))
      ->group_by('category')->get()->result_array();

    $row = array();
    foreach ($result as $key => $val) {
      $row[$key] = $val['category'];
    }
    return $row;
  }

  public function get_num_students_answered()
  {
    $question_instance_id = $this->input->post('question_instance_id');
    return $this->db->select('count(distinct student_id) as num')->from('student_responses')->where(array('question_instance_id' => $question_instance_id))
      ->group_by('student_id')->get()->result_array()[0]['num'];
  }

  public function get_answered_question_instance($question_instance_id)
  {
    $subquery = "SELECT MAX(id)
                FROM student_responses
                GROUP BY student_id";

    return $this->db->select("answer")->from("student_responses")->where(array('question_instance_id' => $question_instance_id))
      ->where("id IN ($subquery)", null, FALSE)->get()->result_array();
  }

  public function update_quiz_instance($quiz_meta_id, $teacher_id)
  {
    //get current status
    $result_quiz_instance = $this->db->select('id')->from('quiz_instances')
      ->where(array('quiz_meta_id' => $quiz_meta_id, 'teacher_id' => $teacher_id))
      ->order_by('id', 'DESC')->limit(1)->get()->result_array();
    if (!empty($result_quiz_instance)) {
      $result_quiz_instance = $result_quiz_instance[0];
      $quiz_instance_id = $result_quiz_instance['id'];

      //get number of questions in the quiz
      $result_num_of_questions = $this->db->select('COUNT(id) as num')->from('questions')->where(array('quiz_id' => $quiz_meta_id))
        ->get()->result_array()[0]['num'];

      //get the latest question instances associated with the quiz instance
      $data = ['quiz_instance_id' => $quiz_instance_id];
      $result_questions = $this->db->get_where('question_instances', $data)->result_array();
      //check for completeness of question instance status
      $is_all_complete = false;
      foreach ($result_questions as $question) {
        if ($question['status'] != "complete") {
          $is_all_complete = false;
        } else {
          $is_all_complete = true;
        }
      }
      if (count($result_questions) < $result_num_of_questions || $is_all_complete == false) {
        //   //update quiz instance status to pause
        $data = ['status' => 'pause'];
      } else {
        //   //update quiz instance status to complete
        $data = ['status' => 'complete'];
      }
      $this->db->order_by('id', 'DESC')->limit(1);
      return $this->db->set($data)->where(
        array(
          'teacher_id' => $teacher_id,
          'quiz_meta_id' => $quiz_meta_id
        )
      )->update('quiz_instances');
    } else {
      return false;
    }
  }

  public function get_all_students($quiz_index)
  {
    $row = [];

    $results = $this->db->select('users.id')->from('users')
      ->join('enrolled_students', 'enrolled_students.student_id=users.id')
      ->join('quizs', 'quizs.classroom_id=enrolled_students.classroom_id')
      ->where(array('quizs.id' => $quiz_index))->get()->result_array();

    foreach ($results as $result) {
      $row[$result['id']] = $result['id'];
    }
    return $row;
  }

  public function get_last_quiz_instance($quiz_meta_id, $teacher_id)
  {
    //get new or paused quiz instance
    $this->db->order_by('id', 'DESC')->limit(1);
    $result = $this->db->select('*')->from('quiz_instances')
      ->where(array('quiz_meta_id' => $quiz_meta_id, 'teacher_id' => $teacher_id, 'status !=' => 'complete'))
      ->get()->result_array();
    if (!empty($result)) {
      //continue the last quiz instance
      return $result[0]['id'];
    } else {
      //create new quiz instance
      $data = [
        'teacher_id' => $teacher_id,
        'quiz_meta_id' => $quiz_meta_id,
        'status' => 'new'
      ];
      $this->db->insert('quiz_instances', $data);
      return $this->db->insert_id();
    }
  }

  public function update_question_instance_status_tab_list($quiz_id, $teacher_id)
  {
    //get latest quiz instance
    $this->db->order_by('id', 'DESC')->limit(1);
    $where = [
      'quiz_meta_id' => $quiz_id, 'teacher_id' => $teacher_id,
      'status !=' => 'complete'
    ];
    $result_quiz_instance = $this->db->select('*')->from('quiz_instances')->where($where)->get()->result_array();
    if (empty($result_quiz_instance)) {
      return false;
    } else {
      $result_quiz_instance = $result_quiz_instance[0];
      $quiz_instance_id = $result_quiz_instance['id'];

      //get questions of current quiz instance
      $result_question_instances = $this->db->get_where('question_instances', array('quiz_instance_id' => $quiz_instance_id))
        ->result_array();
      if (empty($result_question_instances)) { //no question instance has been created
        return 'test';
      } else {
        $result = [];
        foreach ($result_question_instances as $question_instance) {
          $result[] = [
            'question_id' => $question_instance['question_meta_id'],
            'status' => $question_instance['status']
          ];
        }
        return $result;
      }
    }
  }


  public function get_quiz_instance_list($quiz_index, $teacher_id)
  {
    $result = $this->db->get_where('quiz_instances', array('quiz_meta_id' => $quiz_index, 'teacher_id' => $teacher_id))
      ->result_array();
    return $result;
  }

  public function get_question_instance_list($question_list)
  {
    $row = array();
    foreach ($question_list as $question) {
      $this->db->select('question_instances.id')
        ->from('questions')->join('question_instances', 'questions.id = question_instances.question_meta_id')
        ->join('quiz_instances', 'question_instances.quiz_instance_id = quiz_instances.id')
        ->where(['quiz_instance_id' => $question['id'], 'quiz_instances.status' => 'complete']);
      $result = $this->db->get()->result_array();
      if (!empty($result)) {
        $row[$question['id']] = $result;
      }
    }
    return $row;
  }

  public function get_student_response_list($question_instance_list)
  {
    $row = array();
    foreach ($question_instance_list as $question_instances) {
      foreach ($question_instances as $question_instance) {
        //get last record of each instance for each user
        $this->db->select('MAX(id) as id');
        $this->db->from('student_responses')
          ->where(['question_instance_id' => $question_instance['id']]);
        $this->db->group_by(['student_id']);
        $subquery = $this->db->get_compiled_select();


        $this->db->select('student_responses.id, users.username, student_responses.answer,student_responses.time_answered');
        $this->db->from('(' . $subquery . ') a');
        $this->db->join('student_responses', 'student_responses.id= a.id');
        $this->db->join('users', 'student_responses.student_id= users.id');
        $this->db->group_by('student_responses.student_id');

        $result = $this->db->get()->result_array();
        if (!empty($result)) {
          $row[$question_instance['id']] = $result;
        }
      }
    }
    return $row;
  }
  // public function get_question_instance_list($question_list)
  // {
  //   $row = array();
  //   foreach ($question_list as $question) {
  //     $this->db->select('time_created,quiz_instance_id, users.id as user_id, username, student_responses.answer as answer, student_responses.time_answered as time_answered')
  //     ->from('questions')->join('question_instances', 'questions.id = question_instances.question_meta_id')
  //     ->join('quiz_instances', 'question_instances.quiz_instance_id = quiz_instances.id')
  //     ->join('student_responses', 'question_instances.id = student_responses.question_instance_id')
  //     ->join('users', 'users.id = student_responses.student_id')
  //     ->where(['quiz_instance_id'=>$question['id'],'quiz_instances.status'=>'complete'])
  //     ->group_by('question_instance_id');
  //     $result = $this->db->get()->result_array();
  //     if(!empty($result)) {
  //       $row[$question['id']][] = $result;
  //     }
  //   }
  //   return $row;
  // }
}
