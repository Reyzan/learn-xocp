<?php
//--------------------------------------------------------------------//
// Filename : modules/system/uploadcache.php                          //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-20                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('SYSTEM_WHOAMI_DEFINED') ) {
   define('SYSTEM_WHOAMI_DEFINED', TRUE);

class _system_Whoami extends XocpBlock {

   function show() {
      global $xocp_user;

      $ret = _theme::openTable();
      $ret .= "<b>"._SYS_WELCOME." ".$xocp_user->getVar("user_nm")."</b>";
      $ret .= _theme::closeTable();
      return $ret;
   }

}

} // SYSTEM_WHOAMI_DEFINED
?>