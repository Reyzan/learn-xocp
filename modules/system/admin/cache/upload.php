<?php
//--------------------------------------------------------------------//
// Filename : modules/system/admin/cache/upload.php                   //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-20                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('SYSTEM_UPLOADCACHE_DEFINED') ) {
   define('SYSTEM_UPLOADCACHE_DEFINED', TRUE);

class _system_UploadCache extends XocpBlock {

   function main() {
      switch($this->catch) {
         default : 
            $arr = XocpLists::getFileListAsArray(XOCP_DOC_ROOT."/cache/pages/");
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
      
   }
}

} // SYSTEM_UPLOADCACHE_DEFINED
?>