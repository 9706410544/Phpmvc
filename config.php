<?php
//////////////////////////////////////////////////////////////////////
//Paths can be defined here. Always add a trailing slash after path //
//////////////////////////////////////////////////////////////////////
define('URL','http://localhost/phpmvc/');
define('DS_LIBS','libs/');
define('DS_PUBLIC','public/');
define('NOTIFY_EMAIL','xxxxxxxxxx');
////////////////////////////////////////////
// Constants defined for hashing password //
////////////////////////////////////////////
define('HASH_PASS_KEY','./ACFGHIJKL*MNOPQRSTV@WXYZ#acfghijklmno%pqrstvwx&yz0123456789$');
define('HASH_GEN_KEY','./developer#$%&*!key$%#()here');
////////////////////////////
//Database configurations //
////////////////////////////
define('DB_TYPE','mysql');
define('DB_HOST','localhost');
define('DB_NAME','phpmvc');
define('DB_USER','root');
define('DB_PASS','');
////////////////////////////////////////////////////
//Define maximum results in a page for pagination //
////////////////////////////////////////////////////
define('DS_PAGE_MAX_RESULT',10);
/////////////////////////////////
//Define session remember time //
/////////////////////////////////
define('SESSION_REMEMBER','+1 hour');
?>