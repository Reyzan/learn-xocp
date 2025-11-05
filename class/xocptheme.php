<?php
/* version: SE 1.0 build 20011202 */
//--------------------------------------------------------------------//
// Filename : class/xocptheme.php                                     //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-17                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('XOCP_THEME_DEFINED') ) {
   define('XOCP_THEME__DEFINED', TRUE);

class XocpTheme {

   function openPage() {
      $ret = "\n<!-- OpenPage --><table border=0 width=100% cellpadding=0 cellspacing=0>";
      return $ret;
   }

   function closePage() {
      $ret = "\n<!-- ClosePage --></table>\n";
      return $ret;
   }

   function openBlock() {
      $ret = "\n<!-- OpenBlock --><table border='0' cellspacing='0' cellpadding='0'><tr><td>";
      $ret .= "<table width=100% border='0' cellspacing='2' cellpadding='4' ><tr><td class='block' >\n";
      return $ret;
   }

   function closeBlock() {
      $ret = "\n<!-- CloseBlock --></td></tr></table></td></tr></table>\n";
      return $ret;
   }

   function openForm() {
      $ret = "\n<table border='1' cellspacing='0' cellpadding='3' class='frm'>\n";
      return $ret;
   }

   function closeForm() {
      $ret = "</table>\n";
      return $ret;
   }

   function openTable($width=NULL) {
      if(!$width) {
         $width = "100%";
      }
      $ret = "\n<!-- OpenTable --><table width='".$width."' border='0' cellspacing='0' cellpadding='2' ><tr><td class='tbl1'>\n"; 
      return $ret;
   }

   function closeTable() {
      $ret = "\n<!-- CloseTable --></td></tr></table>\n";
      return $ret;
   }

   function themeHeader() {
      $ret  = "\n<!-- OpenTheme --><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
      $ret .= "<tr><td valign='top'>\n";
      return $ret;
   }


   function themeFooter() {
      $ret = "\n<!-- CloseTheme --></td></tr></table>\n";
      return $ret;
   }

}

} // XOCP_THEME_DEFINED
?>