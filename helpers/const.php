<?php

//=====DB CONNECT=====
define('LOCALHOST', 'localhost');
define('DBNAME', 'basephp');
define('USERNAME', 'root');
define('PASSWORD', '');

define('ACTIVED', 0);
define('DELETED', 1);
define('ROLE_TYPE_ADMIN', 2);
define('ROLE_TYPE_SUPERADMIN', 1);
define('DEL_FLAG_0', 0);
define('DEL_FLAG_1', 1);

//=====PAGGING=====
define("RECORD_PER_PAGE", 5);

//=====URL UPLOAD=====
define('UPLOADS_ADMIN', 'public/uploads/admin/');
define('UPLOADS_USER', 'public/uploads/user/');