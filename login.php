<?php
//--------------------------------------------------------------------//
// Filename : login.php                                               //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-19                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

include_once('config.php');

$login_status = $xocp_user->login($login,$passwd,$HTTP_SESSION_VARS["login_c"]);

if($login_status != XOCP_USEROK) {
   $login_failed++;
   $xocp_user->load(0);
   header("Location: ".XOCP_URL."?login=".urlencode($login));
   exit(0);
}

$login_failed = 0;
header("Location: ".XOCP_URL."?login=".urlencode($login));

?>