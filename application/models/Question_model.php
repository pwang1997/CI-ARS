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

  public function getQuiz($lab_index)
  {
    return $result = $this->db->get_where('quizs', array('id' => $lab_index))->result_array();
  }

  public function getQuestions($lab_index)
  {
    $quiz_id = $this->getQuiz($lab_index)[0]['id'];
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

  public function add_question_instance($question_index)
  {
    return array(
      'success' => $this->db->insert('question_instances', array('question_meta_id' => $question_index)),
      'question_instance_id' => $this->db->insert_id()
    );
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

  public function get_question_instance_list($question_list)
  {

    foreach ($question_list as $question) {
      $this->db->select("student_responses.question_instance_id, question_instances.time_created as time_created, student_responses.student_id as student_id, student_responses.answer as answer, student_responses.time_answered as time_answered")->from("student_responses");
      $this->db->join('question_instances', 'student_responses.question_instance_id = question_instances.id')->where(array('question_instances.question_meta_id' => $question['id']));
      $this->db->group_by('student_responses.question_instance_id')->order_by('time_answered', 'DESC');
      $result = $this->db->get()->result_array();
      $row[$question['id']] = $result;
    }
    return $row;
  }
}
