<?php
//--------------------------------------------------------------------//
// Filename : language/english.php                                    //
// Software : XOCP - X Open Community Portal                          //
// Version  : 0.1                                                     //
// Date     : 2002-11-13                                              //
// Author   : (anybody)                                               //
// License  : Public Domain                                           //
//                                                                    //
// You may use and modify this software as you wish. Share and Enjoy! //
//--------------------------------------------------------------------//

if ( !defined('LANGUAGE_DEFINED') ) {
   define('LANGUAGE_DEFINED', TRUE);

define("_PLEASEWAIT","Please Wait");
define("_FETCHING","Loading...");
define("_TAKINGBACK","Taking you back to where you were....");
define("_YOURNAME","Your Name");
define("_LOGOUT","Logout");
define("_SUBJECT","Subject");
define("_MESSAGEICON","Message Icon");
define("_COMMENT","Comment");
define("_CHKMSGLENGTH","[check message length]");
define("_ALLOWEDHTML","Allowed HTML:");
define("_POSTANON","Post Anonymously");
define("_DISABLESMILEY","Disable Smiley");
define("_DISABLEHTML","Disable html");
define("_PREVIEW","Preview");
define("_POSTCOMMENT","Post Comment");
define("_GO","Go!");
define("_NOCOMMENTS","No Comments");
define("_FLAT","Flat");
define("_THREADED","Threaded");
define("_OLDESTFIRST","Oldest First");
define("_NEWESTFIRST","Newest First");
define("_REFRESH","Refresh");
define("_MORE","more...");
define("_IFNOTRELOAD","If the page does not automatically reload, please click <a href=%s>here</a>");
define("_WARNINSTALL2","WARNING: File %s exists on your server. Please remove this file for security reasons.");
define("_WARNINWRITEABLE","WARNING: File %s is writeable by the server. Please change the permission of this file for security reasons.");

//%%%%%%	File Name themeuserpost.php 	%%%%%
define("_THREAD","Thread");
define("_POSTER","Poster");
define("_JOINED","Joined: ");
define("_POSTS","Posts: ");
define("_FROM","From: ");
define("_POSTED","Posted: "); // Posted date
define("_PROFILE","Profile");
define("_VISITWEBSITE","Visit Website");
define("_SENDPMTO","Send Private Message to %s");
define("_SENDEMAILTO","Send Email to %s");
define("_ADDTOLIST","Add to Contact List");
define("_ADD","Add");
define("_REPLY","Reply");
define("_EDITTHISPOST","Edit this post");
define("_DELETETHISPOST","Delete this post");
define("_POSTEDBY","Posted by");
define("_DATE","Date");   // Posted date
define("_REPLIES","Replies");
define("_PARENT","Parent");
define("_TOP","Top");
define("_ONLINE","Online!");

define("_CANCEL","Cancel");


//%%%%%%	File Name admin_functions.php 	%%%%%
define("_ADMINMENU","Administration Menu");
define("_MAIN","Main");
define("_MANUAL","Manual");
define("_INFO","Info");
define("_CPHOME","Control Panel Home");
define("_YOURHOME","Your Home Page");

//%%%%%%	File Name misc.php (who's-online popup)	%%%%%
define("_WHOSONLINE","Who's Online"); 
define("_NOUSRONLINE","No users are currently online"); 
define("_CLOSE","Close");  // Close window

//%%%%%%	File Name misc.php (avatars popup)	%%%%%
define("_AVAVATARS","Available Avatars");
define("_SELECT","Select");
define("_UPLOADAVATAR", "Upload Avatar");

//%%%%%%	File Name misc.php (smiles popup)	%%%%%
define("_SMILIES","Smilies");
define("_CLICKASMILIE","Click a smilie to insert it into your message.");
define("_CODE","Code");
define("_EMOTION","Emotion");
define("_IMAGE","Image");

//%%%%%%	File Name misc.php (send-to-friend popup)	%%%%%
define("_YOURNAMEC","Your Name: ");
define("_YOUREMAILC","Your Email: ");
define("_FRIENDNAMEC","Friend Name: ");
define("_FRIENDEMAILC","Friend Email: ");
define("_SEND","Send");

define("_RECOMMENDSITE","Recommend this Site to a Friend");

// %s is your site name
define("_INTSITE","Interesting Site: %s");

// %s is the name of your friend
define("_HELLO","Hello %s,");

define("_YOURFRIEND","Your friend %s considered our site interesting and wanted to send it to you.");

define("_SITENAME","Site Name:");
define("_SITEURL","Site URL:");
define("_REFERENCESENT","The reference to our site has been sent to your friend. Thanks!");

define("_ENTERYNAME","Please enter your name");
define("_ENTERYMAIL","Please enteer your email address");
define("_ENTERFNAME","Please enter your friend's name");
define("_ENTERFMAIL","Please enter your friend's email address");
define("_NEEDINFO","You need to enter required info!");
define("_INVALIDEMAIL1","Email address you provided is not a valid address.");
define("_INVALIDEMAIL2","Please check the address and try again.");

//%%%%%%	File Name module.textsanitizer.php 	%%%%%
define("_QUOTEC","Quote:");

//%%%%%%	File Name admin.php 	%%%%%
define("_NOPERM","Sorry, you don't have the permission to access this area.");

//%%%%%		Common Phrases		%%%%%
define("_NO","No");
define("_YES","Yes");
define("_EDIT","Edit");
define("_DELETE","Delete");
define("_SUBMIT","Submit");
define("_MODULENOEXIST","Selected module does not exist!");
define("_LEFT","Left");
define("_CENTER","Center");
define("_RIGHT","Right");
define("_FORM_ENTER", "Please enter %s");
// %s represents file name
define("_MUSTWABLE","File %s must be writable by the server!");
define("_NGWRITE","Could not write to file %s");
// Module info
define("_VERSION", "Version");
define("_DESCRIPTION", "Description");
define("_ERRORS", "Errors");
define("_NONE", "None");

//%%%%%	File Name class/xoopsform/formmatchoption.php 	%%%%%
define("_STARTSWITH", "Starts with");
define("_ENDSWITH", "Ends with");
define("_MATCHES", "Matches");
define("_CONTAINS", "Contains");

//%%%%%%	File Name commentform.php 	%%%%%
define("_REGISTER","Register");

//%%%%%%	File Name xoopscodes.php 	%%%%%
define("_SIZE","SIZE");  // font size
define("_FONT","FONT");  // font family
define("_COLOR","COLOR");  // font color
define("_EXAMPLE","SAMPLE");
define("_ENTERURL","Enter the URL of the link you want to add:");
define("_ENTERWEBTITLE","Enter the web site title:");
define("_ENTERIMGURL","Enter the URL of the image you want to add.");
define("_ENTERIMGPOS","Now, enter the position of the image.");
define("_IMGPOSRORL","'R' or 'r' for right, 'L' or 'l' for left, or leave it blank.");
define("_ERRORIMGPOS","ERROR! Enter the position of the image.");
define("_ENTEREMAIL","Enter the email address you want to add.");
define("_ENTERQUOTE","Enter the text that you want to be quoted.");
define("_ENTERTEXTBOX","Please input text into the textbox.");
define("_ALLOWEDCHAR","Allowed max chars length: ");
define("_CURRCHAR","Current chars length: ");
define("_PLZCOMPLETE","Please complete the subject and message fields.");
define("_MESSAGETOOLONG","Your message is too long.");

//%%%%%		Time Zone	%%%%
define("_GMTM12", "(GMT-12:00) Eniwetok, Kwajalein");
define("_GMTM11", "(GMT-11:00) Midway Island, Samoa");
define("_GMTM10", "(GMT-10:00) Hawaii");
define("_GMTM9", "(GMT-9:00) Alaska");
define("_GMTM8", "(GMT-8:00) Pacific Time (US & Canada)");
define("_GMTM7", "(GMT-7:00) Mountain Time (US & Canada)");
define("_GMTM6", "(GMT-6:00) Central Time (US & Canada), Mexico City");
define("_GMTM5", "(GMT-5:00) Eastern Time (US & Canada), Bogota, Lima, Quito");
define("_GMTM4", "(GMT-4:00) Atlantic Time (Canada), Caracas, La Paz");
define("_GMTM35", "(GMT-3:30) Newfoundland");
define("_GMTM3", "(GMT-3:00) Brasilia, Buenos Aires, Georgetown");
define("_GMTM2", "(GMT-2:00) Mid-Atlantic");
define("_GMTM1", "(GMT-1:00) Azores, Cape Verde Islands");
define("_GMT0", "(GMT) Greenwich Mean Time, London, Dublin, Lisbon, Casablanca, Monrovia");
define("_GMTP1", "(GMT+1:00) Amsterdam, Berlin, Rome, Copenhagen, Brussels, Madrid, Paris");
define("_GMTP2", "(GMT+2:00) Athens, Istanbul, Minsk, Helsinki, Jerusalem, South Africa");
define("_GMTP3", "(GMT+3:00) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg");
define("_GMTP35", "(GMT+3:30) Tehran");
define("_GMTP4", "(GMT+4:00) Abu Dhabi, Muscat, Baku, Tbilisi");
define("_GMTP45", "(GMT+4:30) Kabul");
define("_GMTP5", "(GMT+5:00) Ekaterinburg, Islamabad, Karachi, Tashkent");
define("_GMTP55", "(GMT+5:30) Bombay, Calcutta, Madras, New Delhi");
define("_GMTP6", "(GMT+6:00) Almaty, Dhaka, Colombo");
define("_GMTP7", "(GMT+7:00) Bangkok, Hanoi, Jakarta");
define("_GMTP8", "(GMT+8:00) Beijing, Perth, Singapore, Hong Kong, Urumqi, Taipei");
define("_GMTP9", "(GMT+9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk");
define("_GMTP95", "(GMT+9:30) Adelaide, Darwin");
define("_GMTP10", "(GMT+10:00) Brisbane, Canberra, Melbourne, Sydney, Guam,Vlasdiostok");
define("_GMTP11", "(GMT+11:00) Magadan, Solomon Islands, New Caledonia");
define("_GMTP12", "(GMT+12:00) Auckland, Wellington, Fiji, Kamchatka, Marshall Island");

//%%%%%		TIME FORMAT SETTINGS   %%%%%

define("_DATESTRING","Y/n/j G:i:s");
define("_MEDIUMDATESTRING","Y/n/j G:i");
define("_SHORTDATESTRING","Y/n/j");
/*
The following characters are recognized in the format string: 
a - "am" or "pm" 
A - "AM" or "PM" 
d - day of the month, 2 digits with leading zeros; i.e. "01" to "31" 
D - day of the week, textual, 3 letters; i.e. "Fri" 
F - month, textual, long; i.e. "January" 
h - hour, 12-hour format; i.e. "01" to "12" 
H - hour, 24-hour format; i.e. "00" to "23" 
g - hour, 12-hour format without leading zeros; i.e. "1" to "12" 
G - hour, 24-hour format without leading zeros; i.e. "0" to "23" 
i - minutes; i.e. "00" to "59" 
j - day of the month without leading zeros; i.e. "1" to "31" 
l (lowercase 'L') - day of the week, textual, long; i.e. "Friday" 
L - boolean for whether it is a leap year; i.e. "0" or "1" 
m - month; i.e. "01" to "12" 
n - month without leading zeros; i.e. "1" to "12" 
M - month, textual, 3 letters; i.e. "Jan" 
s - seconds; i.e. "00" to "59" 
S - English ordinal suffix, textual, 2 characters; i.e. "th", "nd" 
t - number of days in the given month; i.e. "28" to "31" 
T - Timezone setting of this machine; i.e. "MDT" 
U - seconds since the epoch 
w - day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday) 
Y - year, 4 digits; i.e. "1999" 
y - year, 2 digits; i.e. "99" 
z - day of the year; i.e. "0" to "365" 
Z - timezone offset in seconds (i.e. "-43200" to "43200") 
*/


//%%%%%		LANGUAGE SPECIFIC SETTINGS   %%%%%
define("_CHARSET", "ISO-8859-1");

// change 0 to 1 if this language is a multi-bytes language
define("XOOPS_USE_MULTIBYTES", "0");

} // LANGUAGE_DEFINED
?>