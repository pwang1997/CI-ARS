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
    if($this->hasQuestion($lab_index)) {
        $quiz_id = $this->getQuiz($lab_index);
        if(!empty($quiz_id)) {
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

  public function hasQuestion($lab_index) {
    $result = $this->db->get_where('quizs', array('lab_id' => $lab_index))->result_array();
    return !empty($result);
  }

  public function getQuiz($lab_index) {
      return $result = $this->db->get_where('quizs', array('lab_id' => $lab_index))->result_array();
  }
}