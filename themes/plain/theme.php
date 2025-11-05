<?php
//--------------------------------------------------------------------//
// Filename : themes/plain/theme.php                                  //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-17                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

// To create theme, just define class named _theme that extends XocpTheme.
// Overwrite some function if necessary. For documentation, please read
// xocptheme.php and xocphtml.php in class directory.

if ( !defined('THEME_X_DEFINED') ) {
   define('THEME_X_DEFINED', TRUE);

include_once(XOCP_DOC_ROOT."/class/xocptheme.php");

class _theme extends XocpTheme {
   
}

} // THEME_X_DEFINED
?>