************************
Audience Response System
************************
The system is a practice of WebSocket with audience response system(ARS). Its goal is to enable students' engagement 
and maximize instructors' freedom in using educational software during quizzes and lecturing. 

*******************
Server Requirements
*******************

- PHP version 7.2 or newer.
- Composer version 1.3 or newer

************
Deployment
************

-  Download Apache LAMP Stack "sudo apt update & sudo apt install apache2"
-  Clone the repository "git clone https://github.com/pwang1997/CI-ARS.git"
-  Download Composer packages "composer i"
-  Change Database Config under application/config/database.php to your database config
-  Change Routing base url under application/config/config.php $config[base_url] to your deployment
-  Change JavaScript base url under js/global.js to your deployment

*******
License
*******

Please see the `license
agreement <https://github.com/bcit-ci/CodeIgniter/blob/develop/user_guide_src/source/license.rst>`_.


***************
Acknowledgement
***************

The CodeIgniter team would like to thank EllisLab, all the
contributors to the CodeIgniter project and you, the CodeIgniter user.
