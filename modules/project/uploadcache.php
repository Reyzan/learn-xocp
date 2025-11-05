<?php
//--------------------------------------------------------------------//
// Filename : class/xocpobject.php                                    //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-13                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('XOCPasdf_DEFINED') ) {
   define('XOCPasdf_DEFINED', TRUE);


global $xocpConfig;
include_once(XOCP_DOC_ROOT."/modules/system/language/".$xocpConfig['language'].".php");

function show_uploadform() {
   global $xocp_user,$HTTP_POST_VARS;
   
   if(catchVar("system")) {
      debugit("here");
   }
   
   $arr = XocpLists::getFileListAsArray(XOCP_DOC_ROOT."/cache/pages");


   $selectfile = new XocpFormSelect(_SYS_SELECTCACHEFILE, "system_selectfile", $system_selectfile);
   $selectfile->addOptionArray($arr);

   $hidden = new XocpFormHidden("X_system","1");

   $submit_button = new XocpFormButton("", "system_uploadcache", _SYS_UPLOADCACHE, "submit");

   $form = new XocpThemeForm(_SYS_UPCACHETITLE, "system_upcache", "index.php","post");

   $form->addElement($selectfile);
   $form->addElement($hidden);
   $form->addElement($submit_button);
   
   return $form->render();

}

} // ALKJDF
?>