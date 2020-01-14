<?php
class User_model extends CI_Model
{
  public function __construct()
  {
    $this->load->database();
  }
  // Register user 
  public function register($enc_password, $role)
  {
    // User data array
    $data = array(
      'username' => $this->input->post('username'),
      'password' => $enc_password,
      'role' => $role
    );
    // Insert user
    return $this->db->insert('users', $data);
  }

  // Check username exists
  public function check_username_exists($username)
  {
    $query = $this->db->get_where('users', array('username' => $username));
    if (empty($query->row_array())) {
      return true;
    } else {
      return false;
    }
  }
  // Log user in
  public function login($username, $password)
  {
    $this->db->select('id, role');
    $this->db->where('username', $username);
    $this->db->where('password', $password);
    $result = $this->db->get('users');
    if ($result->num_rows() == 1) {
      return array('id' => $result->row(0)->id, 'role' => $result->row(0)->role);
    } else {
      return false;
    }
  }
}
