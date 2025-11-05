<?php
//--------------------------------------------------------------------//
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-09                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('DHTML_TEXTAREA_DEFINED') ) {
   define('DHTML_TEXTAREA_DEFINED', TRUE);

include_once(XOCP_DOC_ROOT."/class/form/xocpform.php");

// Make sure you have included /include/xocpcodes.php, otherwise DHTML will not work properly!

class XocpFormDhtmlTextArea extends XocpFormTextArea {

	function XocpFormDhtmlTextArea($caption, $name, $rows=5, $cols=50){
		$this->XocpFormTextArea($caption, $name, $rows, $cols);
	}

	function render(){
		$ret = "<input type='button' value='URL' onclick='xocpCodeUrl(\"".$this->getName()."\");' /><input type='button' value='IMG' onclick='xocpCodeImg(\"".$this->getName()."\");' /><input type='button' value='EMAIL' onclick='xocpCodeEmail(\"".$this->getName()."\");' /><input type='button' value='QUOTE' onclick='xocpCodeQuote(\"".$this->getName()."\");' /><br />\n";

		$sizearray = array("xx-small", "x-small", "small", "medium", "large", "x-large", "xx-large");
		$ret .= "<select id='".$this->getName()."Size' onchange='setVisible(\"hiddenText\");setElementSize(\"hiddenText\",this.options[this.selectedIndex].value);'>\n";
		$ret .= "<option value='SIZE'>"._SIZE."</option>\n";
		foreach ( $sizearray as $size ) {
			$ret .=  "<option value='$size'>$size</option>\n";
		}
		$ret .= "</select>\n";
		$fontarray = array("Arial", "Courier", "Georgia", "Helvetica", "Impact", "Verdana");
		$ret .= "<select id='".$this->getName()."Font' onchange='setVisible(\"hiddenText\");setElementFont(\"hiddenText\",this.options[this.selectedIndex].value);'>\n";
		$ret .= "<option value='FONT'>"._FONT."</option>\n";
		foreach ( $fontarray as $font ) {
			$ret .= "<option value='$font'>$font</option>\n";
		}
		$ret .= "</select>\n";
		$colorarray = array("00", "33", "66", "99", "CC", "FF");
		$ret .= "<select id='".$this->getName()."Color' onchange='setVisible(\"hiddenText\");setElementColor(\"hiddenText\",this.options[this.selectedIndex].value);'>\n";
		$ret .= "<option value='COLOR'>"._COLOR."</option>\n";
		foreach ( $colorarray as $color1 ) {
			foreach ( $colorarray as $color2 ) {
				foreach ( $colorarray as $color3 ) {
					$ret .= "<option value='".$color1.$color2.$color3."' style='background-color:#".$color1.$color2.$color3.";color:#".$color1.$color2.$color3.";'>#".$color1.$color2.$color3."</option>\n";
				}
			}
		}
		$ret .= "</select><span id='hiddenText'>"._EXAMPLE."</span>\n";
		$ret .= "<br />\n";
		$ret .= "<input type='checkbox' id='".$this->getName()."Bold' onclick='setVisible(\"hiddenText\");makeBold(\"hiddenText\");' /><b>B</b>&nbsp;<input type='checkbox' id='".$this->getName()."Italic' onclick='setVisible(\"hiddenText\");makeItalic(\"hiddenText\");' /><i>I</i>&nbsp;<input type='checkbox' id='".$this->getName()."Underline' onclick='setVisible(\"hiddenText\");makeUnderline(\"hiddenText\");' /><u>U</u>&nbsp;&nbsp;<input type='textbox' id='".$this->getName()."Addtext' size='20' />&nbsp;<input type='button' onclick='xocpCodeText(\"".$this->getName()."\")' value='"._ADD."'><br /><br /><textarea id='".$this->getName()."' name='".$this->getName()."' wrap='virtual' cols='".$this->getCols()."' rows='".$this->getRows()."'".$this->getExtra().">".$this->getValue()."</textarea><br />\n";
		$ret .= $this->renderSmileys();
		return $ret;
	}

	function renderSmileys(){
		global $xocpConfig;
		$ret = "";
		$smileyPath = "images/smilies";
		$ret .= "<a href='javascript: justReturn()' onclick='xocpCodeSmilie(\"".$this->getName()."\", \" :-) \");'><img width='15' height='15' src='".XOOPS_URL."/".$smileyPath."/icon_smile.gif' border='0' alt=':-)' /></a>";
		$ret .= "<a href='javascript: justReturn()' onclick='xocpCodeSmilie(\"".$this->getName()."\", \" :-( \");'><img width='15' height='15' src='".XOOPS_URL."/".$smileyPath."/icon_frown.gif' border='0' alt=':-(' /></a>";
		$ret .= "<a href='javascript: justReturn()' onclick='xocpCodeSmilie(\"".$this->getName()."\", \" :-D \");'><img width='15' height='15' src='".XOOPS_URL."/".$smileyPath."/icon_biggrin.gif' border='0' alt=':-D' /></a>";
		$ret .= "<a href='javascript: justReturn()' onclick='xocpCodeSmilie(\"".$this->getName()."\", \" ;-) \");'><img width='15' height='15' src='".XOOPS_URL."/".$smileyPath."/icon_wink.gif' border='0' alt=';-)' /></a>";
		$ret .= "<a href='javascript: justReturn()' onclick='xocpCodeSmilie(\"".$this->getName()."\", \" :-o \");'><img width='15' height='15' src='".XOOPS_URL."/".$smileyPath."/icon_eek.gif' border='0' alt=':-o' /></a>";
		$ret .= "<a href='javascript: justReturn()' onclick='xocpCodeSmilie(\"".$this->getName()."\", \" 8-) \");'><img width='15' height='15' src='".XOOPS_URL."/".$smileyPath."/icon_cool.gif' border='0' alt='8-)' /></a>";
		$ret .= "<a href='javascript: justReturn()' onclick='xocpCodeSmilie(\"".$this->getName()."\", \" :-? \");'><img width='15' height='22' src='".XOOPS_URL."/".$smileyPath."/icon_confused.gif' border='0' alt=':-?' /></a>";
		$ret .= "<a href='javascript: justReturn()' onclick='xocpCodeSmilie(\"".$this->getName()."\", \" :-P \");'><img width='15' height='15' src='".XOOPS_URL."/".$smileyPath."/icon_razz.gif' border='0' alt=':-P' /></a>";
		$ret .= "<a href='javascript: justReturn()' onclick='xocpCodeSmilie(\"".$this->getName()."\", \" :-x \");'><img width='15' height='15' src='".XOOPS_URL."/".$smileyPath."/icon_mad.gif' border='0' alt=':-x' /></a>";
		$ret .= "&nbsp;[<a href='javascript:openWithSelfMain(\"".XOOPS_URL."/misc.php?action=showpopups&amp;type=smilies&amp;target=".$this->getName()."\",\"smilies\",300,430);'>"._MORE."</a>]";
		return $ret;
	}
}

} // DHTML_TEXTAREA_DEFINED
?>