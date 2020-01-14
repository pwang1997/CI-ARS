<?php
defined('BASEPATH') or exit('No direct script access allowed');


$route['users/teacher'] = 'users/teacher';
$route['users/student'] = 'users/student';
$route['users/login'] = 'users/login';
$route['users/register'] = 'users/register';

$route['default_controller'] = 'pages/view';

$route['(:any)'] = 'pages/view/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
