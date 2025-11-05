<?php
//--------------------------------------------------------------------//
// Filename : logout.php                                              //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-20                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

include_once('config.php');

session_destroy();

header("Location: ".XOCP_URL);

?>