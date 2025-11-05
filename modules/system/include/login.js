
<script language="JavaScript" src="include/md5.js"></script>
<script language="Javascript">
 
function dosubmit(form,url) {

   if (url) {
      url += "?";
      var hash;
      if(form.passwd.value){
         hash = hex_md5(form.c.value+hex_md5(form.passwd.value));
      } else {
         hash = "";
      }

      for(i=0; i<form.elements.length; i++){
         if(form.elements[i].name == "dologin" ||
            form.elements[i].name.length <=0 ||
            form.elements[i].name == "c"){
            continue;
         }
         if(i > 0){
            url += "&";
         }
         url += form.elements[i].name;
         url += "=";
         if(form.elements[i].name == "passwd"){
            url += hash;
         } else {
            url += escape(form.elements[i].value);
         }
      }
      // indicate the password is hashed.
      url += "&.hash=1";
      location.href=url;
      // prevent from running this again. Allow the server response to submit the form directly
      form.onsubmit = null;
      form.c.value = null;
      //alert(url);

      // abort normal form submission
      return false;
   }
   // allow normal form submission
   return true;
}
</script>
