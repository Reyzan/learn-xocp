<?php
//--------------------------------------------------------------------//
// Filename : formselectcountry.php                                   //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-09                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('FORM_SELECTCOUNTRY_DEFINED') ) {
   define('FORM_SELECTCOUNTRY_DEFINED', TRUE);

include_once(XOCP_DOC_ROOT."/class/xocplist.php");
include_once(XOCP_DOC_ROOT."/class/form/xocpform.php");

class XocpFormSelectCountry extends XocpFormSelect {
   function XocpFormSelectCountry($caption, $name, $value="", $size=1){
      $this->XocpFormSelect($caption, $name, $value, $size);
      $this->addOptionArray(XocpList::getCountryList());
   }
}

} // FORM_SELECTCOUNTRY_DEFINED
?>