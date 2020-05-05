************************
Audience Response System
************************
The system is a practice of WebSocket with audience response system(ARS). Its goal is to enable students' engagement 
and maximize instructors' freedom in using educational software during quizzes and lecturing. 

*******************
Living Demo
*******************
- aws ec2: <http://ec2-54-183-88-168.us-west-1.compute.amazonaws.com>

*******************
Server Requirements
*******************

-  PHP version 7.2 or newer.
-  Composer version 1.9 or newer

************
Deployment
************

-  Download Apache LAMP Stack "sudo apt update & sudo apt install apache2"
-  Append apache2.config with  
    ``<Directory 'your deploy directory'> 
        Options Indexes FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>``

-  Clone the repository ``git clone https://github.com/pwang1997/CI-ARS.git``
-  Download Composer packages ``composer i``
-  Change Database Config under ``application/config/database.php`` to your database config
-  Change Routing base url under ``application/config/config.php $config[base_url]`` to your deployment
-  Change JavaScript base url under ``js/global.js`` to your deployment