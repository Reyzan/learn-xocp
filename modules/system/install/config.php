<?php
$mod_conf['module_id'] = "system";
$mod_conf['version'] = 1.01;
$mod_conf['description'] = _SYS_MODULE_DESC;
$mod_conf['credits'] = "X Open Community Portal";
$mod_conf['license'] = "Public Domain";

$mod_conf['del_ind'] = 'n';

// Dependencies
//$mod_conf['depend'][0] = "";

// All tables should not have any prefix!
//$mod_conf['sqlfile']['mysql'] = "sql/mysql.sql";
//$mod_conf['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Tables created by sql file (without prefix!)
//$mod_conf['tables'][0] = "comments";
//$mod_conf['tables'][1] = "stories";
//$mod_conf['tables'][2] = "topics";

// Blocks
$mod_conf['blocks'][1]['file_nm'] = "login.php";
$mod_conf['blocks'][1]['class_nm'] = "_system_Login";
$mod_conf['blocks'][1]['side_allow'] = "A|B|C|D|BC|CD|ABC|BCD|ABCD";

$mod_conf['blocks'][2]['file_nm'] = "whoami.php";
$mod_conf['blocks'][2]['class_nm'] = "_system_Whoami";
$mod_conf['blocks'][2]['side_allow'] = "A|B|C|D|BC|CD|ABC|BCD|ABCD";

$mod_conf['blocks'][3]['file_nm'] = "admin/cache/upload.php";
$mod_conf['blocks'][3]['class_nm'] = "_system_CacheUpload";
$mod_conf['blocks'][3]['side_allow'] = "BC|CD|ABC|BCD|ABCD";


?>