<?php

global $xocpConfig;
include_once(XOCP_DOC_ROOT."/modules/system/language/".$xocpConfig['language'].".php");

function show_whoami() {
   global $xocp_user;

   $ret = _theme::openTable();
   $ret .= "<b>"._SYS_WELCOME." ".$xocp_user->getVar("user_nm")."</b>";
   $ret .= _theme::closeTable();
   return $ret;
   

}

?>