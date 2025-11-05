<?php
//--------------------------------------------------------------------//
// Filename : formselectgroup.php                                     //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-09                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('FORM_SELECTGROUP_DEFINED') ) {
   define('FORM_SELECTGROUP_DEFINED', TRUE);

include_once(XOCP_DOC_ROOT."/class/xocplist.php");
include_once(XOCP_DOC_ROOT."/class/form/xocpform.php");

class XocpFormSelectGroup extends XocpFormSelect {
	 function XocpFormSelectGroup($caption, $name, $include_anon=false, $value="", $size=1, $multiple=false){
		$this->XocpFormSelect($caption, $name, $value, $size, $multiple);
		if ( !$include_anon ) {
			$this->addOptionArray(Xocplist::getAllGroupsList(array("type!='Anonymous'")));
		} else {
			$this->addOptionArray(Xocplist::getAllGroupsList());
		}
	}
}

} // FORM_SELECTGROUP_DEFINED
?>