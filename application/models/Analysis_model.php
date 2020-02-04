<?php

class Analysis_model extends CI_Model
{
  public function __construct()
  {
    $this->load->database();
  }

  public function get_unique_category()
  {
    return $this->db->distinct()->select('category')->from('questions')->get()->result_array();
  }

  public function get_category_count($category_list)
  {
    $result = array();
    foreach ($category_list as $category) {
      $result[$category['category']] = $this->db->select('count(id) as size')->from('questions')->where(array('category' => $category['category']))->get()->result_array()[0];
    }

    return $result;
  }
}
