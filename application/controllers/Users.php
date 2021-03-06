<?php

class Users extends CI_Controller
{
  public function register()
  {
    $data['title'] = 'Sign Up';

    $this->form_validation->set_rules('username', 'Username', 'required|callback_check_username_exists');
    $this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('password2', 'ConfirmPassword', 'matches[password]');

    if ($this->form_validation->run() === FALSE) {
      $this->load->view('templates/header');
      $this->load->view('users/register', $data);
      $this->load->view('templates/footer');
    } else {
      //encrypt password
      $enc_password = md5($this->input->post('password'));
      $this->user->register($enc_password, $this->input->post('role'));

      //set message
      $this->session->set_flashdata('user_registered', 'You are now registered and can log in');

      redirect('users/login');
    }
  }

  function check_username_exists($username)
  {
    $this->form_validation->set_message('check_username_exists', 'The username is taken, please choose a different one');

    if ($this->user->check_username_exists($username)) {
      return true;
    } else {
      return false;
    }
  }

  public function login()
  {
    $data['title'] = 'Login';

    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if ($this->form_validation->run() === FALSE) {
      $this->load->view('templates/header');
      $this->load->view('users/login', $data);
      $this->load->view('templates/footer');
    } else {
      $username = $this->input->post('username');
      $enc_password = md5($this->input->post('password'));

      //login user
      $user_data = $this->user->login($username, $enc_password);
      print_r($user_data);
      if ($user_data['id'] === false || empty($user_data)) {
        $this->session->set_flashdata('login_failed', 'The password or username is incorrect');
        redirect('users/login');
      } else {
        $user_data['username'] = $username;
        $user_data['logged_in'] = true;
        print_r($user_data);
        $this->session->set_userdata($user_data);
        $this->session->set_flashdata('user_loggedin', 'You are now logged in');
        redirect('home');
      }
    }
  }

  public function logout()
  {
    //unset user data
    $this->session->unset_userdata('logged_in');
    $this->session->unset_userdata('user_id');
    $this->session->unset_userdata('username');
    $this->session->unset_userdata('role');
    $this->session->set_flashdata('user_loggedout', 'You are now logged out');

    redirect('users/login');
  }
  //teacher's user page
  public function teacher()
  {
    $data['title'] = 'Teacher\'s page';

    $course_list = $this->user->get_courses_for_teachers($this->session->id);
    $data['course_list'] = $course_list;
    $data['section_list'] = $this->user->get_section_list($course_list);
    // print_r($data['course_list']);

    $this->load->view('templates/header');
    $this->load->view('users/teacher', $data);
    $this->load->view('templates/footer');
  }
  //student's user page
  public function student()
  {
    $data['title'] = 'Student\'s page';

    $course_list = $this->user->get_courses_for_students($this->session->id);
    $data['course_list'] = $course_list;
    $data['teacher_list'] = $this->user->get_username($course_list);

    $this->load->view('templates/header');
    $this->load->view('users/student', $data);
    $this->load->view('templates/footer');
  }

  public function get_course_for_teacher()
  {
    $course_id = $this->uri->segment(3);
    $classroom_id = $this->uri->segment(4);

    $course_data = $this->user->get_course_data($course_id, $classroom_id);
    $data['course_data'] = $course_data;
    // print_r($data['course_list']);

    $this->load->view('templates/header');
    $this->load->view('../courses/teacher', $data);
    $this->load->view('templates/footer');
  }

  public function get_session()
  {
    $msg['id'] = $this->session->id;
    $msg['username'] = $this->session->username;
    $msg['role'] = $this->session->role;
    print_r(json_encode($msg));
  }
}
