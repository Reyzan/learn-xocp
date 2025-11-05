<?php
//--------------------------------------------------------------------//
// Filename : xocplist.php                                            //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-09                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined("XOCP_LISTS_INCLUDED") ) {
   define("XOCP_LISTS_INCLUDED",TRUE);
   
   class XocpLists {
      function getTimeZoneList() {
         $time_zone_list = array ("-12" => _GMTM12, "-11" => _GMTM11, "-10" => _GMTM10, "-9" => _GMTM9, "-8" => _GMTM8, "-7" => _GMTM7, "-6" => _GMTM6, "-5" => _GMTM5, "-4" => _GMTM4, "-3.5" => _GMTM35, "-3" => _GMTM3, "-2" => _GMTM2, "-1" => _GMTM1, "0" => _GMT0, "1" => _GMTP1, "2" => _GMTP2, "3" => _GMTP3, "3.5" => _GMTP35, "4" => _GMTP4, "4.5" => _GMTP45, "5" => _GMTP5, "5.5" => _GMTP55, "6" => _GMTP6, "7" => _GMTP7, "8" => _GMTP8, "9" => _GMTP9, "9.5" => _GMTP95, "10" => _GMTP10, "11" => _GMTP11, "12" => _GMTP12);
         return $time_zone_list;
      }

      /*
       * gets list of themes folder from themes directory
       */
      function getThemeList() {
         $themes_list = array();
         $themes_list = XocpLists::getDirListAsArray(XOCP_DOC_ROOT."/themes/");
         return $themes_list;
      }

      /*
       * gets list of name of directories inside a directory
       */
      function getDirListAsArray($dirname) {
         $dirlist = array();
         //$dirname = str_replace(".", "", $dirname);
         $handle=@opendir($dirname);
         while ( ($file = readdir($handle)) ) {
            if ( !ereg("^[.]{1,2}$",$file) ) {
               if ( is_dir($dirname.$file) ) {
                  $dirlist[$file]=$file;
               }
            }
         }
         closedir($handle);
         asort($dirlist);
         reset($dirlist);
         return $dirlist;
      }

      /*
       * gets list of name of directories inside a directory
       */
      function getFileListAsArray($dirname) {
         $filelist = array();
         //$dirname = str_replace(".", "", $dirname);
         $handle=@opendir($dirname);
         while ( ($file = readdir($handle)) ) {
            if ( !ereg("^[.]{1,2}$",$file) ) {
               if ( !is_dir($dirname.$file) ) {
                  $filelist[$file]=$file;
               }
            }
         }
         closedir($handle);
         asort($filelist);
         reset($filelist);
         return $filelist;
      }

      /*
       *  gets list of image file names in a directory
       */
      function getImgListAsArray($dirname, $prefix="") {
         $filelist = array();
         //$dirname = str_replace(".", "", $dirname);
         $handle=@opendir($dirname);
         while ( ($file = readdir($handle)) ) {
            if ( !ereg("^[.]{1,2}$",$file) && ereg(".gif|.jpg|.png",$file) ) {
               $file = $prefix.$file;
               $filelist[$file]=$file;
            }
         }
         closedir($handle);
         asort($filelist);
         reset($filelist);
         return $filelist;
      }

      /*
       *  gets list of avatar file names in a certain directory
       *  if directory is not specified, default directory will be searched
       */
      function getAvatarsList($avatar_dir="") {
         $avatars = array();
         if ( $avatar_dir != "" ) {
            $avatars = XocpLists::getImgListAsArray(XOCP_DOC_ROOT."/images/avatar/".$avatar_dir."/", $avatar_dir."/");
         } else {
            $avatars = XocpLists::getImgListAsArray(XOCP_DOC_ROOT."/images/avatar/");
         }
         return $avatars;
      }

      /*
       *  gets list of all avatar image files inside default avatars directory
       */
      function getAllAvatarsList() {
         $avatars = array();
         $dirlist = array();
         $dirlist = XocpLists::getDirListAsArray(XOCP_DOC_ROOT."/images/avatar/");
         if ( count($dirlist) > 0 ) {
            foreach ( $dirlist as $dir ) {
               $avatars[$dir] = XocpLists::getImgListAsArray(XOCP_DOC_ROOT."/images/avatar/".$dir."/", $dir."/");
            }
         } else {
            return false;
         }
         return $avatars;
      }

      /*
      *  gets list of subject icon image file names in a certain directory
      *  if directory is not specified, default directory will be searched
      */
      function getSubjectsList($sub_dir=""){
         $subjects = array();
         if($subject_dir != ""){
            $subjects = XocpLists::getImgListAsArray(XOCP_DOC_ROOT."/images/subject/".$sub_dir, $sub_dir."/");
         } else {
            $subjects = XocpLists::getImgListAsArray(XOCP_DOC_ROOT."/images/subject/");
         }
         return $subjects;
      }



      /*
       * gets list of language folders inside default language directory
       */
      function getLangList() {
         $lang_list = array();
         $lang_list = XocpLists::getDirListAsArray(XOCP_DOC_ROOT."/language/");
         return $lang_list;
      }

      function getCountryList() {
         $country_list = array (
         ""    => "-",
         "AFG" => "Afghanistan",
         "ALB" => "Albania, People's Socialist Republic of",
         "DZA" => "Algeria, People's Democratic Republic of",
         "ASM" => "American Samoa",
         "AND" => "Andorra, Principality of",
         "AGO" => "Angola, Republic of",
         "AIA" => "Anguilla",
         "ATA" => "Antarctica (the territory South of 60 deg S)",
         "ATG" => "Antigua and Barbuda",
         "ARG" => "Argentina, Argentine Republic",
         "ARM" => "Armenia",
         "ABW" => "Aruba",
         "AUS" => "Australia, Commonwealth of",
         "AUT" => "Austria, Republic of",
         "AZE" => "Azerbaijan, Republic of",
         "BHS" => "Bahamas, Commonwealth of the",
         "BHR" => "Bahrain, Kingdom of",
         "BGD" => "Bangladesh, People's Republic of",
         "BRB" => "Barbados",
         "BLR" => "Belarus",
         "BEL" => "Belgium, Kingdom of",
         "BLZ" => "Belize",
         "BEN" => "Benin, People's Republic of",
         "BMU" => "Bermuda",
         "BTN" => "Bhutan, Kingdom of",
         "BOL" => "Bolivia, Republic of",
         "BIH" => "Bosnia and Herzegovina",
         "BWA" => "Botswana, Republic of",
         "BVT" => "Bouvet Island (Bouvetoya)",
         "BRA" => "Brazil, Federative Republic of",
         "IOT" => "British Indian Ocean Territory (Chagos Archipelago)",
         "VGB" => "British Virgin Islands",
         "BRN" => "Brunei Darussalam",
         "BGR" => "Bulgaria, People's Republic of",
         "BFA" => "Burkina Faso",
         "BDI" => "Burundi, Republic of",
         "KHM" => "Cambodia, Kingdom of",
         "CMR" => "Cameroon, United Republic of",
         "CAN" => "Canada",
         "CPV" => "Cape Verde, Republic of",
         "CYM" => "Cayman Islands",
         "CAF" => "Central African Republic",
         "TCD" => "Chad, Republic of",
         "CHL" => "Chile, Republic of",
         "CHN" => "China, People's Republic of",
         "CXR" => "Christmas Island",
         "CCK" => "Cocos (Keeling) Islands",
         "COL" => "Colombia, Republic of",
         "COM" => "Comoros, Federal and Islamic Republic of",
         "COD" => "Congo, Democratic Republic of",
         "COG" => "Congo, People's Republic of",
         "COK" => "Cook Islands",
         "CRI" => "Costa Rica, Republic of",
         "CIV" => "Cote D'Ivoire, Ivory Coast, Republic of the",
         "CUB" => "Cuba, Republic of",
         "CYP" => "Cyprus, Republic of",
         "CZE" => "Czech Republic",
         "DNK" => "Denmark, Kingdom of",
         "DJI" => "Djibouti, Republic of",
         "DMA" => "Dominica, Commonwealth of",
         "DOM" => "Dominican Republic",
         "TLS" => "East Timor, Democratic Republic of",
         "ECU" => "Ecuador, Republic of",
         "EGY" => "Egypt, Arab Republic of",
         "SLV" => "El Salvador, Republic of",
         "GNQ" => "Equatorial Guinea, Republic of",
         "ERI" => "Eritrea",
         "EST" => "Estonia",
         "ETH" => "Ethiopia",
         "FRO" => "Faeroe Islands",
         "FLK" => "Falkland Islands (Malvinas)",
         "FJI" => "Fiji, Republic of the Fiji Islands",
         "FIN" => "Finland, Republic of",
         "FRA" => "France, French Republic",
         "GUF" => "French Guiana",
         "PYF" => "French Polynesia",
         "ATF" => "French Southern Territories",
         "GAB" => "Gabon, Gabonese Republic",
         "GMB" => "Gambia, Republic of the",
         "GEO" => "Georgia",
         "DEU" => "Germany",
         "GHA" => "Ghana, Republic of",
         "GIB" => "Gibraltar",
         "GRC" => "Greece, Hellenic Republic",
         "GRL" => "Greenland",
         "GRD" => "Grenada",
         "GLP" => "Guadaloupe",
         "GUM" => "Guam",
         "GTM" => "Guatemala, Republic of",
         "GIN" => "Guinea, Revolutionary People's Rep'c of",
         "GNB" => "Guinea-Bissau, Republic of",
         "GUY" => "Guyana, Republic of",
         "HTI" => "Haiti, Republic of",
         "HMD" => "Heard and McDonald Islands",
         "VAT" => "Holy See (Vatican City State)",
         "HND" => "Honduras, Republic of",
         "HKG" => "Hong Kong, Special Administrative Region of China",
         "HRV" => "Hrvatska (Croatia)",
         "HUN" => "Hungary, Hungarian People's Republic",
         "ISL" => "Iceland, Republic of",
         "IND" => "India, Republic of",
         "IDN" => "Indonesia, Republic of",
         "IRN" => "Iran, Islamic Republic of",
         "IRQ" => "Iraq, Republic of",
         "IRL" => "Ireland",
         "ISR" => "Israel, State of",
         "ITA" => "Italy, Italian Republic",
         "JAM" => "Jamaica",
         "JPN" => "Japan",
         "JOR" => "Jordan, Hashemite Kingdom of",
         "KAZ" => "Kazakhstan, Republic of",
         "KEN" => "Kenya, Republic of",
         "KIR" => "Kiribati, Republic of",
         "PRK" => "Korea, Democratic People's Republic of",
         "KOR" => "Korea, Republic of",
         "KWT" => "Kuwait, State of",
         "KGZ" => "Kyrgyz Republic",
         "LAO" => "Lao People's Democratic Republic",
         "LVA" => "Latvia",
         "LBN" => "Lebanon, Lebanese Republic",
         "LSO" => "Lesotho, Kingdom of",
         "LBR" => "Liberia, Republic of",
         "LBY" => "Libyan Arab Jamahiriya",
         "LIE" => "Liechtenstein, Principality of",
         "LTU" => "Lithuania",
         "LUX" => "Luxembourg, Grand Duchy of",
         "MAC" => "Macao, Special Administrative Region of China",
         "MKD" => "Macedonia, the former Yugoslav Republic of",
         "MDG" => "Madagascar, Republic of",
         "MWI" => "Malawi, Republic of",
         "MYS" => "Malaysia",
         "MDV" => "Maldives, Republic of",
         "MLI" => "Mali, Republic of",
         "MLT" => "Malta, Republic of",
         "MHL" => "Marshall Islands",
         "MTQ" => "Martinique",
         "MRT" => "Mauritania, Islamic Republic of",
         "MUS" => "Mauritius",
         "MYT" => "Mayotte",
         "MEX" => "Mexico, United Mexican States",
         "FSM" => "Micronesia, Federated States of",
         "MDA" => "Moldova, Republic of",
         "MCO" => "Monaco, Principality of",
         "MNG" => "Mongolia, Mongolian People's Republic",
         "MSR" => "Montserrat",
         "MAR" => "Morocco, Kingdom of",
         "MOZ" => "Mozambique, People's Republic of",
         "MMR" => "Myanmar",
         "NAM" => "Namibia",
         "NRU" => "Nauru, Republic of",
         "NPL" => "Nepal, Kingdom of",
         "ANT" => "Netherlands Antilles",
         "NLD" => "Netherlands, Kingdom of the",
         "NCL" => "New Caledonia",
         "NZL" => "New Zealand",
         "NIC" => "Nicaragua, Republic of",
         "NER" => "Niger, Republic of the",
         "NGA" => "Nigeria, Federal Republic of",
         "NIU" => "Niue, Republic of",
         "NFK" => "Norfolk Island",
         "MNP" => "Northern Mariana Islands",
         "NOR" => "Norway, Kingdom of",
         "OMN" => "Oman, Sultanate of",
         "PAK" => "Pakistan, Islamic Republic of",
         "PLW" => "Palau",
         "PSE" => "Palestinian Territory, Occupied",
         "PAN" => "Panama, Republic of",
         "PNG" => "Papua New Guinea",
         "PRY" => "Paraguay, Republic of",
         "PER" => "Peru, Republic of",
         "PHL" => "Philippines, Republic of the",
         "PCN" => "Pitcairn Island",
         "POL" => "Poland, Polish People's Republic",
         "PRT" => "Portugal, Portuguese Republic",
         "PRI" => "Puerto Rico",
         "QAT" => "Qatar, State of",
         "REU" => "Reunion",
         "ROU" => "Romania, Socialist Republic of",
         "RUS" => "Russian Federation",
         "RWA" => "Rwanda, Rwandese Republic",
         "SHN" => "St. Helena",
         "KNA" => "St. Kitts and Nevis",
         "LCA" => "St. Lucia",
         "SPM" => "St. Pierre and Miquelon",
         "VCT" => "St. Vincent and the Grenadines",
         "WSM" => "Samoa, Independent State of",
         "SMR" => "San Marino, Republic of",
         "STP" => "Sao Tome and Principe, Democratic Republic of",
         "SAU" => "Saudi Arabia, Kingdom of",
         "SEN" => "Senegal, Republic of",
         "SYC" => "Seychelles, Republic of",
         "SLE" => "Sierra Leone, Republic of",
         "SGP" => "Singapore, Republic of",
         "SVK" => "Slovakia (Slovak Republic)",
         "SVN" => "Slovenia",
         "SLB" => "Solomon Islands",
         "SOM" => "Somalia, Somali Republic",
         "ZAF" => "South Africa, Republic of",
         "SGS" => "South Georgia and the South Sandwich Islands",
         "ESP" => "Spain, Spanish State",
         "LKA" => "Sri Lanka, Democratic Socialist Republic of",
         "SDN" => "Sudan, Democratic Republic of the",
         "SUR" => "Suriname, Republic of",
         "SJM" => "Svalbard & Jan Mayen Islands",
         "SWZ" => "Swaziland, Kingdom of",
         "SWE" => "Sweden, Kingdom of",
         "CHE" => "Switzerland, Swiss Confederation",
         "SYR" => "Syrian Arab Republic",
         "TWN" => "Taiwan, Province of China",
         "TJK" => "Tajikistan",
         "TZA" => "Tanzania, United Republic of",
         "THA" => "Thailand, Kingdom of",
         "TGO" => "Togo, Togolese Republic",
         "TKL" => "Tokelau (Tokelau Islands)",
         "TON" => "Tonga, Kingdom of",
         "TTO" => "Trinidad and Tobago, Republic of",
         "TUN" => "Tunisia, Republic of",
         "TUR" => "Turkey, Republic of",
         "TKM" => "Turkmenistan",
         "TCA" => "Turks and Caicos Islands",
         "TUV" => "Tuvalu",
         "VIR" => "US Virgin Islands",
         "UGA" => "Uganda, Republic of",
         "UKR" => "Ukraine",
         "ARE" => "United Arab Emirates",
         "GBR" => "United Kingdom of Great Britain & N. Ireland",
         "UMI" => "United States Minor Outlying Islands",
         "USA" => "United States of America",
         "URY" => "Uruguay, Eastern Republic of",
         "UZB" => "Uzbekistan",
         "VUT" => "Vanuatu",
         "VEN" => "Venezuela, Bolivarian Republic of",
         "VNM" => "Viet Nam, Socialist Republic of",
         "WLF" => "Wallis and Futuna Islands",
         "ESH" => "Western Sahara",
         "YEM" => "Yemen",
         "YUG" => "Yugoslavia, Socialist Federal Republic of",
         "ZMB" => "Zambia, Republic of",
         "ZWE" => "Zimbabwe");
   
         asort($country_list);
         reset($country_list);
         return $country_list;
      
      }


      function getHtmlList() {
         $html_list = array (
            "a" => "&lt;a&gt;",
            "abbr" => "&lt;abbr&gt;",
            "acronym" => "&lt;acronym&gt;",
            "address" => "&lt;address&gt;",
            "b" => "&lt;b&gt;",
            "bdo" => "&lt;bdo&gt;",
            "big" => "&lt;big&gt;",
            "blockquote" => "&lt;blockquote&gt;",
            "caption" => "&lt;caption&gt;",
            "cite" => "&lt;cite&gt;",
            "code" => "&lt;code&gt;",
            "col" => "&lt;col&gt;",
            "colgroup" => "&lt;colgroup&gt;",
            "dd" => "&lt;dd&gt;",
            "del" => "&lt;del&gt;",
            "dfn" => "&lt;dfn&gt;",
            "div" => "&lt;div&gt;",
            "dl" => "&lt;dl&gt;",
            "dt" => "&lt;dt&gt;",
            "em" => "&lt;em&gt;",
            "font" => "&lt;font&gt;",
            "h1" => "&lt;h1&gt;",
            "h2" => "&lt;h2&gt;",
            "h3" => "&lt;h3&gt;",
            "h4" => "&lt;h4&gt;",
            "h5" => "&lt;h5&gt;",
            "h6" => "&lt;h6&gt;",
            "hr" => "&lt;hr&gt;",
            "i" => "&lt;i&gt;",
            "img" => "&lt;img&gt;",
            "ins" => "&lt;ins&gt;",
            "kbd" => "&lt;kbd&gt;",
            "li" => "&lt;li&gt;",
            "map" => "&lt;map&gt;",
            "object" => "&lt;object&gt;",
            "ol" => "&lt;ol&gt;",
            "samp" => "&lt;samp&gt;",
            "small" => "&lt;small&gt;",
            "strong" => "&lt;strong&gt;",
            "sub" => "&lt;sub&gt;",
            "sup" => "&lt;sup&gt;",
            "table" => "&lt;table&gt;",
            "tbody" => "&lt;tbody&gt;",
            "td" => "&lt;td&gt;",
            "tfoot" => "&lt;tfoot&gt;",
            "th" => "&lt;th&gt;",
            "thead" => "&lt;thead&gt;",
            "tr" => "&lt;tr&gt;",
            "tt" => "&lt;tt&gt;",
            "ul" => "&lt;ul&gt;",
            "var" => "&lt;var&gt;"
         );
         asort($html_list);
         reset($html_list);
         return $html_list;
      }

      function getUserRankList(){
         $db =& Database::getInstance();
         $myts =& MyTextSanitizer::getInstance();
         $sql = "SELECT rank_id, rank_title FROM ".$db->prefix("ranks")." WHERE rank_special = 1";
         $ret = array();
         $result = $db->query($sql);
         while ( $myrow = $db->fetchArray($result) ) {
            $ret[$myrow['rank_id']] = $myts->makeTboxData4Show($myrow['rank_title']);
         }
         return $ret;
      }


   }
}
?>