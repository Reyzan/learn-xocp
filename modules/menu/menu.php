<?php
//--------------------------------------------------------------------//
// Filename : modules/menu/menu.php                                   //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-20                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('MENU_DEFINED') ) {
   define('MENU_DEFINED', TRUE);

class _menu_Menu extends XocpBlock {

   function show() {
      return "Menu test<br>Menu test<br>Menu test<br>Menu test<br>Menu test<br>Menu test<br>Menu test<br>Menu test<br>Menu test<br>";
   }

}

} // MENU_DEFINED
?>