<?php
//--------------------------------------------------------------------//
// Filename : class/database/mysql.php                                //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-09                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('MYSQL_DATABASE_DEFINED') ) {
   define('MYSQL_DATABASE_DEFINED', TRUE);

include_once(XOCP_DOC_ROOT."/class/database/database.php");

class Database extends DBConnection {
   var $conn;

   function Database(){
   }

   function &getInstance(){
      if ( isset($GLOBALS['xocpDB']) && get_class($GLOBALS['xocpDB']) == "database" ){
         return $GLOBALS['xocpDB'];
      }
      return new Database();
   }
   
   function connect($host, $user, $password, $db, $persistent=1) {
      if ( $persistent ) {
         $this->conn = @mysql_pconnect($host, $user, $password);
      } else {
         $this->conn = @mysql_connect($host, $user, $password);
      }
      if ( !$this->conn ) {
         return false;
      }
      
      $selDB = mysql_select_db($db);
      if ( !$selDB ) {
         return false;
      }
      return true;
   }
      
   function genId($sequence) {
      return 0;
   }

   function fetchRow($result) {  
      return @mysql_fetch_row($result);
   }

   function fetchArray($result) {
      return @mysql_fetch_array($result,MYSQL_ASSOC);
   }

   function getInsertId() {
      return mysql_insert_id($this->conn);
   }

   function getRowsNum($result) {
      return @mysql_num_rows($result);
   }

   function close() {
      mysql_close($this->conn);
   }

   function freeRecordSet($result) {
      return mysql_free_result($result);
   }

   function error() {
      return @mysql_error();
   }

   function errno() {
      return @mysql_errno();
   }

   function &queryF($sql, $limit=0, $start=0) {
      if ( !empty($limit) ) {
         if (empty($start)) {
            $start = 0;
         }
         $sql = $sql. " LIMIT ".intval($start).",".intval($limit)."";
      }
      $result =& mysql_query($sql, $this->conn);
      if ( $result ) {
         return $result;
      } else {
         if ( $this->debug ) {
            $errorMsg = @mysql_error($this->conn);
            $errorNum = @mysql_errno($this->conn);
            print( "<b>MySQL Query Error</b>: " . htmlentities( $sql ) . "<br /><b> Error number:</b>" . $errorNum . "<br /><b> Error message:</b> ". $errorMsg ."<br />" );
         }
         return false;
      }
   }

   function &query($sql, $limit=0, $start=0) {
      if ( !empty($limit) ) {
         if ( empty($start) ) {
            $start = 0;
         }
         $sql = $sql. " LIMIT ".intval($start).",".intval($limit)."";
      }
      $result =& mysql_query($sql);
      if ( $result ) {
         return $result;
      } else {
         if ( $this->debug ) {
            $errorMsg = @mysql_error($this->conn);
            $errorNum = @mysql_errno($this->conn);
            print( "<b>MySQL Query Error</b>: " . htmlentities( $sql ) . "<br /><b> Error number:</b>" . $errorNum . "<br /><b> Error message:</b> ". $errorMsg ."<br />" );
         }
         return false;
      }
   }


   /**
   * Function from phpMyAdmin (http://phpwizard.net/projects/phpMyAdmin/)
   *
    * Removes comment and splits large sql files into individual queries
    *
   * Last revision: September 23, 2001 - gandon
    *
    * @param   array    the splitted sql commands
    * @param   string   the sql commands
    * @return  boolean  always true
    * @access  public
    */
   function splitSqlFile(&$ret, $sql){
          $sql               = trim($sql);
          $sql_len           = strlen($sql);
          $char              = '';
          $string_start      = '';
          $in_string         = FALSE;

          for ($i = 0; $i < $sql_len; ++$i) {
              $char = $sql[$i];

              // We are in a string, check for not escaped end of
         // strings except for backquotes that can't be escaped
              if ($in_string) {
                  for (;;) {
                      $i         = strpos($sql, $string_start, $i);
                      // No end of string found -> add the current
            // substring to the returned array
                      if (!$i) {
                             $ret[] = $sql;
                             return TRUE;
                      }
                      // Backquotes or no backslashes before 
            // quotes: it's indeed the end of the 
            // string -> exit the loop
                      else if ($string_start == '`' || $sql[$i-1] != '\\') {
                             $string_start      = '';
                            $in_string         = FALSE;
                             break;
                      }
                      // one or more Backslashes before the presumed 
            // end of string...
                      else {
                             // first checks for escaped backslashes
                             $j                     = 2;
                             $escaped_backslash     = FALSE;
                             while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                                 $escaped_backslash = !$escaped_backslash;
                                 $j++;
                             }
                             // ... if escaped backslashes: it's really the 
               // end of the string -> exit the loop
                             if ($escaped_backslash) {
                                 $string_start  = '';
                                 $in_string     = FALSE;
                                 break;
                             }
                             // ... else loop
                             else {
                                 $i++;
                             }
                      } // end if...elseif...else
                  } // end for
           } // end if (in string)
           // We are not in a string, first check for delimiter...
           else if ($char == ';') {
               // if delimiter found, add the parsed part to the returned array
                  $ret[]    = substr($sql, 0, $i);
                  $sql      = ltrim(substr($sql, min($i + 1, $sql_len)));
                 $sql_len  = strlen($sql);
                  if ($sql_len) {
                      $i      = -1;
                  } else {
                      // The submited statement(s) end(s) here
                      return TRUE;
                  }
           } // end else if (is delimiter)
           // ... then check for start of a string,...
           else if (($char == '"') || ($char == '\'') || ($char == '`')) {
                  $in_string    = TRUE;
                  $string_start = $char;
           } // end else if (is start of string)

           // for start of a comment (and remove this comment if found)...
           else if ($char == '#' || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
               // starting position of the comment depends on the comment type
                  $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
               // if no "\n" exits in the remaining string, checks for "\r"
               // (Mac eol style)
                  $end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
                              ? strpos(' ' . $sql, "\012", $i+2)
                              : strpos(' ' . $sql, "\015", $i+2);
                  if (!$end_of_comment) {
                // no eol found after '#', add the parsed part to the returned
                // array and exit
                      $ret[]   = trim(substr($sql, 0, $i-1));
                      return TRUE;
                  } else {
                      $sql     = substr($sql, 0, $start_of_comment) . ltrim(substr($sql, $end_of_comment));
                      $sql_len = strlen($sql);
                      $i--;
                  } // end if...else
               } // end else if (is comment)
               } // end for

             // add any rest to the returned array
             if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
              $ret[] = $sql;
             }
             return TRUE;
   }

   function prefixQuery($query){
      $pattern = "/^(INSERT INTO|CREATE TABLE)(\s)+([`]?)([^`\s]+)\\3(\s)+/siU";
      if ( preg_match($pattern, $query, $matches) ) {
         $replace = "\\1 ".$this->prefix()."_\\4\\5";
         $matches[0] = preg_replace($pattern, $replace, $query);
         return $matches;
      }
      return false;
   }
}

} // MYSQL_DATABASE_DEFINED
?>