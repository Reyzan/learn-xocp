<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
// Author of File: 							     //
// Kazumi Ono (http://www.mywebaddons.com/ , http://www.myweb.ne.jp/)        //
// ------------------------------------------------------------------------- //

include_once(XOOPS_DOC_ROOT."/class/database/database.php");

class Database extends AbsDatabase{

var $conn;
var $curr_row;

	function Database(){
	}

	function getInstance(){
		if ( isset($GLOBALS['xoopsDB']) && get_class($GLOBALS['xoopsDB']) == "Database" ) {
			return $GLOBALS['xoopsDB'];
		}
		return new Database();
	}

	function connect($host, $user, $password, $db, $persistent=1){
		if($persistent){
			$this->conn = @pg_pconnect("host=$host user=$user, password=$password dbname=$db");
		}else{
			$this->conn = @pg_connect("host=$host user=$user, password=$password dbname=$db");
		}
		if(!$this->conn){
			print("<b>Error Connecting DB</b>: ".$this->error($this->conn).":".$this->errno($this->conn));
		}
	}

    	function &query($sql, $debug=false, $limit=0, $start=0){
		if (!empty($limit)) {
			if (empty($start)){
				$start = 0;
			}
			$sql = $sql. " LIMIT ".$limit." OFFSET ".$start."";
		}
        	$result =& @pg_exec($this->conn, $sql);

        	$errorMsg = @pg_errormessage($this->conn);

        	if ($result){
            		return $result;
        	} else {
            		if ($debug){
                		print( "<b>postgreSQL Query Error</b>: " . htmlentities( $sql ) . "<br><b> Error message:</b> ". $errorMsg ."<br>" );
            		}
            		return false;
        	}
		$this->curr_row=0;
        	@pg_freeresult($result);
    	}
    

    	function genId($sequence){

		$res = $this->query("SELECT nextval('$sequence')");
		if ($res) {
			$row = pg_fetch_row($res);
			$nextid = $row[0];
		} else {
			$res = $this->query("CREATE SEQUENCE $sequence");
			if ($res) {
				$nextid = $this->genId($sequence);
			} else {
				$nextid = 0;
			}
		}
		return $nextid;
    	}

	function fetchRow($result){
		$row = @pg_fetch_row($result,$this->curr_row);
		$this->curr_row++;
		return $row;
	}

	function fetchArray($result){
		$row = @pg_fetch_array($result,$this->curr_row,PGSQL_ASSOC);
		$this->curr_row++;
		return $row;
	}

	// This will not be used in postgreSQL, I think
    	function getIsertId(){
            	return false;
    	}

	function getRowsNum($result){
		return @pg_numrows($result);
	}

    	function close(){
        	pg_close($this->conn);
    	}

	function freeRecordSet($result){
		return pg_freeresult($result);
	}

	function error(){
		return @pg_errormessage();
	}

	function errno(){
		return false;
	}

}


?>