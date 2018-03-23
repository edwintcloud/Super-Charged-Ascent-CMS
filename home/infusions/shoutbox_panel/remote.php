<?php

require_once "../../maincore.php";
require_once "".THEME."theme.php";

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>".$settings['sitename']."</title>
<link rel='stylesheet' href='".THEME."styles.css' type='text/css'>

<script>
function insertText(elname, what) {
if (window.opener.document.forms['shoutform'].elements[elname].createTextRange) {
window.opener.document.forms['shoutform'].elements[elname].focus();
window.opener.document.selection.createRange().duplicate().text = what;
} else if ((typeof window.opener.document.forms['shoutform'].elements[elname].selectionStart) != 'undefined') { // for Mozilla
var tarea = window.opener.document.forms['shoutform'].elements[elname];
var selEnd = tarea.selectionEnd;
var txtLen = tarea.value.length;
var txtbefore = tarea.value.substring(0,selEnd);
var txtafter = tarea.value.substring(selEnd, txtLen);
var oldScrollTop = tarea.scrollTop;
tarea.value = txtbefore + what + txtafter;
tarea.selectionStart = txtbefore.length + what.length;
tarea.selectionEnd = txtbefore.length + what.length;
tarea.scrollTop = oldScrollTop;
tarea.focus();
} else {
window.opener.document.forms['shoutform'].elements[elname].value += what;
window.opener.document.forms['shoutform'].elements[elname].focus();
}
}
</script>

</head>
<body Onclick=\"window.close();\" bgcolor='$body_bg' text='$body_text'>\n";

opentable('Smileys');

echo"<center>".displaysmileys("shout_message")."</center>";
closetable();
?>