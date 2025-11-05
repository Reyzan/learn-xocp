<?php
//--------------------------------------------------------------------//
// Filename : modules/system/logout.php                               //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-20                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('SYSTEM_LOGOUT_DEFINED') ) {
   define('SYSTEM_LOGOUT_DEFINED', TRUE);

class _system_Logout extends XocpBlock {

   function show() {
      global $xocp_user;

      $ret = _theme::openTable();
      $ret .= "<a href=".XOCP_URL."/logout.php>"._SYS_LOGOUT."</a>";
      $ret .= _theme::closeTable();
      return $ret;
   }

}

} // SYSTEM_LOGOUT_DEFINED
?>