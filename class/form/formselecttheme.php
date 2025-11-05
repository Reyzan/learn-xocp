<?php
//--------------------------------------------------------------------//
// Filename : formselecttheme.php                                     //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-09                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('FORM_SELECTTHEME_DEFINED') ) {
   define('FORM_SELECTTHEME_DEFINED', TRUE);

include_once(XOCP_DOC_ROOT."/class/xocplist.php");
include_once(XOCP_DOC_ROOT."/class/form/xocpform.php");

class XocpFormSelectTheme extends XocpFormSelect {
	 function XocpFormSelectTheme($caption, $name, $value="", $size=1){
		$this->XocpFormSelect($caption, $name, $value, $size);
		$this->addOptionArray(Xocplist::getThemeList());
	}
}

} // FORM_SELECTTHEME_DEFINED
?>