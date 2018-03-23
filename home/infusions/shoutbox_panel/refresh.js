/*--------------------------------------------+
| PHP-Fusion 6 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) &#169; 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
|This program is released as free software    |
|under the |Affero GPL license. 	      |
|You can redistribute it and/or		      |
|modify it under the terms of this license    |
|which you |can read by viewing the included  |
|agpl.html or online			      |
|at www.gnu.org/licenses/agpl.html. 	      |
|Removal of this|copyright header is strictly |
|prohibited without |written permission from  |
|the original author(s).		      |
+---------------------------------------------+
|http://www.venue.nu			      |
+--------------------------------------------*/

var page = "infusions/shoutbox_panel/sbrefresh.php";
function ajax(url,target)
 {
    // native XMLHttpRequest object
   document.getElementById(target).innerHTML = 'sending...';
   if (window.XMLHttpRequest) {
       req = new XMLHttpRequest();
       req.onreadystatechange = function() {ajaxDone(target);};
       req.open("GET", url+"?sid="+Math.random(), true);
       req.send(null);
   // IE/Windows ActiveX version
   } else if (window.ActiveXObject) {
       req = new ActiveXObject("Microsoft.XMLHTTP");
       if (req) {
           req.onreadystatechange = function() {ajaxDone(target);};
	   req.open("GET", url+"?sid="+Math.random(), true);
           req.send();
       }
   }
		   setTimeout("ajax(page,'scriptoutput')", 40000);
}


function ajaxDone(target) {
   // only if req is "loaded"
   if (req.readyState == 4) {
       // only if "OK"
       if (req.status == 200 || req.status == 304) {
           results = req.responseText;
           document.getElementById(target).innerHTML = results;
       } else {
           document.getElementById(target).innerHTML="ajax error:\n" +
               req.statusText;
       }
   }
}
