<?php

class Question_model extends CI_Model
{
  public function __construct()
  {
    $this->load->database();
  }

  public function create($lab_index)
  {
    $duration = $this->input->post('duration');
    $content = $this->input->post('content');
    $answer = $this->input->post('answer');
    $question_type = $this->input->post('question_type');
    $choices = $this->input->post('choices');

    //quiz exists for the lab
    if ($this->has_question_in_quiz($lab_index)) {
      $quiz_id = $this->getQuiz($lab_index);
      if (!empty($quiz_id)) {
        $quiz_id = $quiz_id[0]['id'];
      } else {
        return false;
      }
      $data = array(
        'quiz_id' => $quiz_id,
        'duration' => $duration,
        'content' => $content,
        'answer' => $answer,
        'question_type' => $question_type,
        'choices' => $choices
      );
      $this->db->insert('questions', $data);
    } else {
      $query = $this->db->insert('quizs', array('lab_id' => $lab_index));
      $quiz_id = $this->db->insert_id();
      $data = array(
        'quiz_id' => $quiz_id,
        'duration' => $duration,
        'content' => $content,
        'answer' => $answer,
        'question_type' => $question_type,
        'choices' => $choices
      );
      $this->db->insert('questions', $data);
    }
    return  $this->db->affected_rows() > 0;
  }

  public function has_question_in_quiz($lab_index)
  {
    $result = $this->db->get_where('quizs', array('lab_id' => $lab_index))->result_array();
    return !empty($result);
  }

  public function getQuiz($lab_index)
  {
    return $result = $this->db->get_where('quizs', array('lab_id' => $lab_index))->result_array();
  }

  public function getQuestions($lab_index)
  {
    $quiz_id = $this->getQuiz($lab_index)[0]['id'];
    return $this->db->get_where('questions',array('quiz_id'=>$quiz_id))->result_array();
  }

  public function student_response() {
    $data = array(
      'student_id' => $this->input->post('student_id'),
      // 'question_instance_id' => $this->input->post('question_instance_id'),
      'answer' => $this->input->post('answer')
    );

    return $this->db->insert('studentResponse', $data);
  }

  public function get_question_base() {
    $teacher_id = $this->session->id;
    if($this->session->role != "teacher") return;
    else {
      $this->db->distinct();
      $this->db->select("courses.course_name as course_name, courses.course_code as course_code,
      users.username as teacher_name, classrooms.section_id as section, questions.id as question_index, 
      questions.is_public as is_public");
      $this->db->from("users");
      $this->db->where('users.id', $teacher_id);
      $this->db->join('classrooms', 'users.id = classrooms.taught_by');
      $this->db->join('courses', 'classrooms.course_id = courses.id');
      $this->db->join('quizs', 'quizs.classroom_id = classrooms.id');
      $this->db->join('questions', 'quizs.id = questions.quiz_id');
      return $this->db->get()->result_array();
    }
  }
}
