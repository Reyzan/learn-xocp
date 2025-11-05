<?php
//--------------------------------------------------------------------//
// Filename : config.php                                              //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-09                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined("XOCP_CONFIG_INCLUDED") ) {
   define("XOCP_CONFIG_INCLUDED",TRUE);

   // Portal ID
   define("XOCP_PORTAL_ID",1);
   
   // Version
   define("XOCP_VERSION","XOCP 1.0");
   
   // XOCP Physical Path
   // Physical path to your main XOCP directory WITHOUT trailing slash
   define("XOCP_DOC_ROOT","/var/www/html/xocp");

   // XOCP Virtual Path (URL)
   // Virtual path to your main XOCP directory WITHOUT trailing slash
   define("XOCP_URL", "http://adiet9000.x/xocp");

   // Database
   // Choose the database to be used
   $xocpConfig['database'] = "mysql";

   // Table Prefix
   // This prefix will be added to all new tables created to avoid name conflict in the database.
   define("XOCP_PREFIX", "");

   // Database Hostname
   // Hostname of the database server. If you are unsure, 'localhost' works in most cases.
   $xocpConfig['dbhost'] = "localhost";

   // Database Username
   // Your database user account on the host
   $xocpConfig['dbuname'] = "adiet";

   // Database Password
   // Password for your database user account
   $xocpConfig['dbpass'] = "";

   // Database Name
   // The name of database on the host. The installer will attempt to create the database if not exist
   $xocpConfig['dbname'] = "xocp";

   // Use persistent connection? (Yes=1 No=0)
   // Default is 'Yes'. Choose 'Yes' if you are unsure.
   $xocpConfig['db_pconnect'] = 1;

   // Site name
   $xocpConfig['sitename'] = "XOCP Site";

   // Slogan for your site
   $xocpConfig['slogan'] = "XOCP Site";

   // Admin mail address
   $xocpConfig['adminmail'] = "adiet@192.168.1.17";

   // Default language
   $xocpConfig['language'] = "english";

   // Module for your start page
   $xocpConfig['startpage'] = "guest.php";

   // Default theme
   $xocpConfig['default_theme'] = "plain";












   include(XOCP_DOC_ROOT."/include/common.php");
}

?>