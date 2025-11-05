<?php
//--------------------------------------------------------------------//
// Filename : class/xocpblock.php                                     //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-20                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('XOCP_BLOCK_DEFINED') ) {
   define('XOCP_BLOCK_DEFINED', TRUE);

class XocpBlock {
   
   var $catch;
   var $align = "left";
   var $html = NULL;
   
   function XocpBlock($catch=NULL) {
      $this->catch = $catch;
   }
   
   function setHTMLObject(&$obj) {
      if(is_object($obj) && get_class($obj) == "xocphtml") {
         $this->html = &$obj;
      }
   }
   
   function main() {
      return "";
   }
   
   function show() {
      return _theme::openBlock() . $this->main() . _theme::closeBlock();
   }

}

} // XOCP_BLOCK_DEFINED
?>