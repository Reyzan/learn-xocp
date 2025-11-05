<?php
$mod_conf['module_nm'] = "Login";
$mod_conf['version'] = 1.01;
$mod_conf['description'] = _LOGIN_MODULE_DESC;
$mod_conf['credits'] = "X Open Community Portal";
$mod_conf['license'] = "Public Domain";
$mod_conf['image'] = "";

$mod_conf['dir_nm'] = "login";
$mod_conf['del_ind'] = 'n';

// Dependencies
$mod_conf['depend'][0] = "";

// All tables should not have any prefix!
//$mod_conf['sqlfile']['mysql'] = "sql/mysql.sql";
//$mod_conf['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Tables created by sql file (without prefix!)
$mod_conf['tables'][0] = "comments";
$mod_conf['tables'][1] = "stories";
$mod_conf['tables'][2] = "topics";

// Blocks
$mod_conf['blocks'][1]['file_nm'] = "login_form.php";
$mod_conf['blocks'][1]['func_nm'] = "show_login_form";
$mod_conf['blocks'][1]['pos_allow'] = "A|B|C|D|BC|CD|ABC|BCD|ABCD";


?>