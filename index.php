<?php
//--------------------------------------------------------------------//
// Filename : index.php                                               //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-13                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

include_once('config.php');

if(!is_object($xocp_user)) {
   session_register("xocp_user");
   $xocp_user = new XocpUser(0); // set to guest user
   $xocp_user->setVar("startpage",$xocpConfig['startpage'],false);
}


$html = new XocpHTML();

if ( $xocp_user->getVar("startpage") != "") {
   if(!file_exists(XOCP_DOC_ROOT."/cache/pages/".$xocp_user->getVar("startpage"))) {
      $html->pageFromDatabase($xocp_user->getVar("startpage"));
   } else {
      $html->pageFromFile(XOCP_DOC_ROOT."/cache/pages/".$xocp_user->getVar("startpage"));
   }

} else {
   if(!file_exists(XOCP_DOC_ROOT."/cache/pages/".$xocpConfig['startpage'])) {
      $html->pageFromDatabase($xocpConfig['startpage']);
   } else {
      $html->pageFromFile(XOCP_DOC_ROOT."/cache/pages/".$xocpConfig['startpage']);
   }
}

$html->out();
//echo "<pre>";
//echo session_id();
//echo "</pre>";
?>