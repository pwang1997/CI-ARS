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
      $this->user_model->register($enc_password, $this->input->post('role'));

      //set message
      $this->session->set_flashdata('user_registered', 'You are now registered and can log in');

      redirect('home');
    }
  }

  function check_username_exists($username)
  {
    $this->form_validation->set_message('check_username_exists', 'The username is taken, please choose a different one');

    if ($this->user_model->check_username_exists($username)) {
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
      $user_data = $this->user_model->login($username, $enc_password);
      print_r($user_data);
      if ($user_data['id'] === false || empty($user_data)) {
        $this->session->set_flashdata('login_failed', 'The password or username is incorrect');
        redirect('users/login');
      } else {
        $user_data['username'] = $username;
        $user_data['logged_in'] = true;

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
}
