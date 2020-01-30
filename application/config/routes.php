<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['questions/question_base'] = 'questions/question_base';
$route['questions/student/:num'] = 'questions/student';
$route['questions/create/:num'] = 'questions/create';
$route['questions/teacher/:num'] = 'questions/teacher';

$route['courses/student/:num/:num'] = 'courses/student';
$route['courses/create'] = 'courses/create';
$route['courses/teacher/:num/:num'] = 'courses/teacher';
$route['courses/student'] = 'courses/student';

$route['users/student'] = 'users/student';
$route['users/teacher'] = 'users/teacher';
$route['users/login'] = 'users/login';
$route['users/register'] = 'users/register';

$route['default_controller'] = 'pages/view';

$route['(:any)'] = 'pages/view/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
