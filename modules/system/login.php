<?php
//--------------------------------------------------------------------//
// Filename : modules/system/login.php                                //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-13                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('SYSTEM_LOGIN_DEFINED') ) {
   define('SYSTEM_LOGIN_DEFINED', TRUE);

session_register("login_c");
session_register("login_failed");
session_register("login_status");


class _system_Login extends XocpBlock {

   function main() {
      global $login_c,$login_failed;
      global $login,$login_status;

      $this->html->loadScript(XOCP_DOC_ROOT."/modules/system/include/login.js");
      $this->html->setBodyOnload(" onload=\"document.login_form.login.focus();\"");
      
      switch($this->catch) {
         default :
            $login_c = uniqid(rand());

            $loginname = new XocpFormText(_SYS_LG_LOGINNAME, "login", 15, 30, "$login");
            $password = new XocpFormPassword(_SYS_LG_PASSWORD, "passwd", 15, 30, "");
            $hidden = new XocpFormHidden("c", $login_c);
            $submit_button = new XocpFormButton("", "dologin", _SYS_LG_SUBMIT, "submit");
         
            $login_form = new XocpThemeForm(_SYS_LG_LOGINFORM, "login_form", "index.php","post");
            $login_form->setExtra("autocomplete=off onsubmit=\"return dosubmit(this,'".XOCP_URL."/login.php')\"");
         
            $login_form->addElement($loginname);
            $login_form->addElement($password);
            $login_form->addElement($hidden);
            $login_form->addElement($submit_button);
            
            if($login_failed>0) {
               switch($login_status) {
                  case XOCP_USERNOEXISTS  : $comment = _SYS_LG_USERNOEXISTS;
                                            break;
                  case XOCP_WRONGPASSWORD : $comment = _SYS_LG_WRONGPASSWORD;
                                            break;
                  case XOCP_USERINACTIVE  : $comment = _SYS_LG_USERINACTIVE;
                                            break;
               }
               $login_form->setComment(_SYS_LG_FAILED . " $login_failed : $comment");
            }
         
            return $login_form->render();
      }
   }

}


} // SYSTEM_LOGIN_DEFINED
?>