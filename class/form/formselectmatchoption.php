<?php
//--------------------------------------------------------------------//
// Filename : formselectmatchoption.php                               //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-09                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('FORM_SELECTMATCH_DEFINED') ) {
   define('FORM_SELECTMATCH_DEFINED', TRUE);

include_once(XOCP_DOC_ROOT."/class/form/xocpform.php");

class XocpFormSelectMatchOption extends XocpFormSelect {
	 function XocpFormSelectMatchOption($caption, $name, $value="", $size=1){
		$this->XocpFormSelect($caption, $name, $value, $size, false);
		$this->addOption(XOCP_MATCH_START, _STARTSWITH);
		$this->addOption(XOCP_MATCH_END, _ENDSWITH);
		$this->addOption(XOCP_MATCH_EQUAL, _MATCHES);
		$this->addOption(XOCP_MATCH_CONTAIN, _CONTAINS);
	}
}

} // FORM_SELECTMATCH_DEFINED
?>