<?php
//--------------------------------------------------------------------//
// Filename : class/xocphtml.php                                      //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-15                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('XOCP_HTML_DEFINED') ) {
   define('XOCP_HTML_DEFINED', TRUE);

class XocpHTML {

   // private
   var $cookie = array();
   var $header = array();
   var $meta = array();
   var $stylesheet = "";
   var $script = "";
   var $bottomscript = "";
   var $title = "";
   var $body = "";
   var $bodyonload = "";
   var $redirect = "";
   
   function XocpHTML() {
      global $ss_timing_start_times;
      global $currentHTML;

      $themecss = getcss(getTheme());
      if ( $themecss ) {
         $this->addStyleSheet("<style type='text/css' media='all'><!-- @import url($themecss); --></style>");
      }
      
   }

   function setBodyOnload($text) {
      $this->bodyonload .= $text;
   }

   function addScript($text) {
      $this->script .= $text . "\n";
   }
   
   function loadScript($filename,$insidetag=TRUE) {
      if(file_exists($filename)) {
         $this->script .= fread(fopen($filename, 'rb'), filesize($filename)) . "\n";
      }
   }

   function addBottomScript($text) {
      $this->bottomscript .= $text . "\n";
   }
   
   function loadBottomScript($filename,$insidetag=TRUE) {
      if(file_exists($filename)) {
         $this->bottomscript .= fread(fopen($filename, 'rb'), filesize($filename)) . "\n";
      }
   }

   function addStyleSheet($text) {
      $this->stylesheet .= $text . "\n";
   }
   
   function loadStyleSheet($filename) {
      if(file_exists($filename)) {
         $this->stylesheet .= fread(fopen($filename, 'rb'), filesize($filename)) . "\n";
      }
   }

   // private
   function pageHeader() {
      global $xocpConfig;
      $myts =& MyTextSanitizer::getInstance();

      $meta = $xocpConfig['meta'];
      $meta = $myts->makeTareaData4InsideQuotes($meta);
      $ret  = "<html>\n";
      $ret .= "<head>\n";
      $ret .= "<meta http-equiv='Content-Type' content='text/html; charset="._CHARSET."' />\n";
      $ret .= "<meta name='author' content='".$xocpConfig['sitename']."' />\n";
      $ret .= "<meta name='copyright' content='Copyright (c) 2002 by ".$xocpConfig['sitename']."' />\n";
      $ret .= "<meta name='keywords' content='".$meta."' />\n";
      $ret .= "<meta name='description' content='".$xocpConfig['slogan']."' />\n";
      $ret .= "<meta name='generator' content='".XOCP_VERSION."' />\n\n";
      $ret .= "<title>".$xocpConfig['sitename']."</title>\n";
      // print scripts
      $ret .= $this->script;

      // print stylesheet
      $ret .= $this->stylesheet;
      $ret .= "\n</head>\n\n<body ".$this->bodyonload.">\n";
      
      return $ret;
   }

   // private
   function pageFooter(){
      return "\n</body>\n</html>\n";
   }
   
   // public
   function addCookie($name,$value="",$expire=0,$path="",$domain="",$secure=0) {
      $cookie[] = array("name"=>$name,"value"=>$value,"expire"=>$expire,"path"=>$path,"domain"=>$domain,"secure"=>$secure);
   }
   
   // public
   function addBody($text) {
      $this->body .= $text;
   }
   
   //private
   function createBlock($attr) {
      global $xocpConfig, $xocp_user;

      if($attr['rowspan']>1) {
         $rowspan = " rowspan='".$attr['rowspan']."'";
      } else {
         $rowspan = "";
      }
      
      if($attr['align'] != '') {
         $align = " align='".$attr['align']."'";
         if(strtolower($attr['align']) == "center") {
            $width = "";
         }
      } else {
         $align = "";
      }
      if($attr['valign'] != '') {
         $valign = " valign='".$attr['valign']."'";
      } else {
         $valign = "";
      }
      
      
      $block = "";
      if($attr['file_nm'] != "" && file_exists(XOCP_DOC_ROOT."/modules/".$attr['module_id']."/".$attr['file_nm'])) {
         
         // load the language first, before the module's files
         if(file_exists(XOCP_DOC_ROOT."/modules/".$attr['module_id']."/language/".$xocp_user->getVar("language").".php")) {
            include_once(XOCP_DOC_ROOT."/modules/".$attr['module_id']."/language/".$xocp_user->getVar("language").".php");
         } elseif (file_exists(XOCP_DOC_ROOT."/modules/".$attr['module_id']."/language/".$xocpConfig['language'].".php")) {
            include_once(XOCP_DOC_ROOT."/modules/".$attr['module_id']."/language/".$xocpConfig['language'].".php");
         }
         
         include_once(XOCP_DOC_ROOT."/modules/".$attr['module_id']."/".$attr['file_nm']);
         
         $class_nm = $attr['class_nm'];
         if(class_exists($class_nm)) {
            $var = catchVar($attr['module_id']);
            $obj = new $class_nm($var);
            $obj->setHtmlObject($this);
            $block = $obj->show();
         }


      }
      
      $info = "<!-- BLOCK: ".$attr['weight']."-".$attr['side']." -->";

      switch($attr['side']) {

         case XOCP_SIDE_A    : if($block_count>0) { $this->addBody("</tr>"); }
                               $this->addBody("\n$info<tr><td width=".XOCP_SIDEWIDTH_A."%$align$valign$rowspan>$block</td>");
                               break;
         case XOCP_SIDE_B    : $this->addBody("\n$info<td width=".XOCP_SIDEWIDTH_B."%$align$valign$rowspan>$block</td>");
                               break;
         case XOCP_SIDE_C    : $this->addBody("\n$info<td width=".XOCP_SIDEWIDTH_C."%$align$valign$rowspan>$block</td>");
                               break;
         case XOCP_SIDE_D    : $this->addBody("\n$info<td width=".XOCP_SIDEWIDTH_D."%$align$valign$rowspan>$block</td></tr>");
                               break;
         case XOCP_SIDE_AB   : if($block_count>0) { $this->addBody("</tr>"); }
                               $this->addBody("\n$info<tr><td width=".XOCP_SIDEWIDTH_AB."% colspan=2$align$valign$rowspan>$block</td>");
                               break;
         case XOCP_SIDE_BC   : $this->addBody("\n$info<td width=".XOCP_SIDEWIDTH_BC."% colspan=2$align$valign$rowspan>$block</td>");
                               break;
         case XOCP_SIDE_CD   : $this->addBody("\n$info<td width=".XOCP_SIDEWIDTH_CD."% colspan=2$align$valign$rowspan>$block</td></tr>");
                               break;
         case XOCP_SIDE_ABC  : if($block_count>0) { $this->addBody("</tr>"); }
                               $this->addBody("\n$info<tr><td width=".XOCP_SIDEWIDTH_ABC."% colspan=3$align$valign$rowspan>$block</td>");
                               break;
         case XOCP_SIDE_BCD  : $this->addBody("\n$info<td width=".XOCP_SIDEWIDTH_BCD."% colspan=3$align$valign$rowspan>$block</td></tr>");
                               break;
         case XOCP_SIDE_ABCD : if($block_count>0) { $this->addBody("</tr>"); }
                               $this->addBody("\n$info<tr><td width=".XOCP_SIDEWIDTH_ABCD."% colspan=4$align$valign$rowspan>$block</td></tr>");
                               break;
      }

      $block_count++;
   
   }
   
   // private
   function endBlock($last_attr) {
         if($last_attr) {
            $cell = $last_attr['side'];
            switch($cell) {
               case XOCP_SIDE_A   : $this->addBody("\n<td colspan=3 width=75%></td></tr>");
                                    break;
               case XOCP_SIDE_B   : $this->addBody("\n<td colspan=2 width=45%></td></tr>");
                                    break;
               case XOCP_SIDE_C   : $this->addBody("\n<td width=20%></td></tr>");
                                    break;
               case XOCP_SIDE_AB  : $this->addBody("\n<td colspan=2 width=45%></td></tr>");
                                    break;
               case XOCP_SIDE_BC  : $this->addBody("\n<td width=20%></tr></tr>");
                                    break;
               case XOCP_SIDE_ABC : $this->addBody("\n<td width=20%></tr></tr>");
                                    break;
            }
         }
   
   }

   // public
   function pageFromFile($file) {
      include($file);
      $blocks = $xocpPage['block'];
      if(is_array($blocks)) {
         ksort($blocks);
         $c =  count($blocks);
         $this->addBody(_theme::openPage());
         $block_count = 0;
         $last_attr = NULL;;
         foreach($blocks as $block_no => $attr) {
            $this->createBlock($attr);
            $last_attr = $attr;
         }
         
         $this->endBlock($last_attr);
         
         $this->addBody(_theme::closePage());
      }
   }
   
   //public
   function pageFromDatabase($page) {

      $wherequery = "p.page_id = '$page'";

      $db =& Database::getInstance();
      $sql = "SELECT a.module_id, b.file_nm, a.class_nm, a.weight, a.side, a.rowspan, a.align, a.valign";
      $sql .= " FROM ".XOCP_PREFIX."pages p";
      $sql .= " LEFT JOIN ".XOCP_PREFIX."pages2blocks a USING(page_id)";
      $sql .= " LEFT JOIN ".XOCP_PREFIX."blocks b USING(module_id,class_nm)";
      $sql .= " WHERE $wherequery ORDER BY a.weight, a.side";
      $result = $db->query($sql);
      if ( $db->getRowsNum($result) ) {
         $this->addBody(_theme::openPage());
         $last_attr = NULL;
         while ( $attr = $db->fetchArray($result) ) {
            $this->createBlock($attr);
            $last_attr = $attr;
         }

         $this->endBlock($last_attr);

         $this->addBody(_theme::closePage());
      }
   }

   // public
   function redirect($url) {
      $this->redirect = $url;
   }
   
   // public
   function out() {
      global $ss_timing_start_times, $ss_timing_stop_times;

      // send cookies first
      if(count($this->cookie)>0) {
         reset($this->cookie);
         foreach ($this->cookie as $c) {
            setcookie($c['name'],$c['value'],$c['expire'],$c['path'],$c['domain'],$c['secure']);
         }
      }
      
      if(!empty($this->redirect)) {

         header("Location: ".$this->redirect);

      } else {

         if ( !headers_sent() ) {
            if ( $xocpConfig['gzip_compression'] ) {
               if (extension_loaded("zlib") ) {
                  if ( function_exists('version_compare') ) { // PHP 4.10+ has version_compare
                     if ( version_compare("4.0.5", phpversion(),'le') ) {
                        ob_start("ob_gzhandler");
                     } else {
                        ob_start();
                     }
                  } elseif ( preg_match("/[4-9]\.[0-9]\.[5-9].*/", phpversion()) )  {                     
                     ob_start("ob_gzhandler");
                  } else {
                     ob_start();
                  }
               } else {
                  ob_start();
               }
            } else {
               ob_start();
            }
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Pragma: no-cache");
         }


         echo $this->pageHeader();
         
         echo _theme::themeHeader();
         echo $this->body;

         // print bottomscripts
         echo $this->bottomscript;
         
         echo _theme::themeFooter();
         echo $this->pageFooter();
         echo ss_timing_result();
         ob_end_flush();
      }
   }

}

} // XOCP_HTML_DEFINED
?>