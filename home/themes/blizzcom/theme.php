<?php

/************************/
/* Theme by NoriaC (elonbc.sytes.net)		*/
/************************/

$body_text = "#D7DCE9";
$body_bg = "#1b1b1b";
$theme_width = "957";
$theme_width_l = "170";
$theme_width_r = "170";

// Right Panels off in Forum Mode
$theme_panels_exclude = array("/forum/");





function render_header($header_content) {

global $theme_width,$settings;

	echo '<!--[if IE]>
  <link rel="stylesheet" type="text/css" href="ie.css" />
	<![endif]-->
	<div class="blizzardtop"></div><div class="blizzardleft"></div><div class="blizzardright"></div>
	';
	echo "<br><br><br><center><table class='bodyline' width='$theme_width' cellspacing='0' cellpadding='0' border='0'>";
	echo "<tr><td align='center' valign='top'>";
	echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'>";
	echo "<tr><td align='center' valign='top'>";
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
	echo "<td width='60%' background='".THEME."images/cellpic_bkg.jpg' height='110'><a href='".BASEDIR."index.php'><img src='".THEME."images/logo/logo2.jpg' align='center' alt='' title='".$settings[sitename]."'></td>";
	echo "</td></tr></table>";
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
	echo "<td><table width='100%' height='25' border='0' cellpadding='4' cellspacing='0'>";
	$result = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_position>='2' ORDER BY link_order");
		if (dbrows($result) != 0) {
	echo "<td class='cellpic'>\n";
		$i = 0;
	while($data = dbarray($result)) {
		if (checkgroup($data['link_visibility'])) {
		if ($data['link_url']!="---") {
		if ($i != 0) { echo "  <img src='".THEME."images/divider.gif' alt=''>  "; } else { echo "\n"; }
	$link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
		if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
	echo "<a href='".$data['link_url']."'".$link_target."'>".$data['link_name']."</a>";
	} else {
	echo "<a href='".BASEDIR.$data['link_url']."'".$link_target."'>".$data['link_name']."</a>";
}
	}
$i++;
		}
	}
}
	echo ($i == 0 ? " " : "")."</td>\n";
	echo "<td align='right' class='cellpic' nowrap><strong>".ucwords(showdate($settings['subheaderdate'], time()))."</strong>";
	echo "</td></tr></table></td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' align='center'>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' align='center'>";
	echo "<tr valign='top'>";
	echo "<td valign='middle' align='right'>";
	echo "<table width='100%' cellpadding='4' bgcolor='$body_bg' cellspacing='0' border='0'>";
}

function render_footer($license=false) {
	
global $theme_width,$settings,$locale;

	echo "</tr>\n</table>\n";
	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr>";
	echo "<td>".stripslashes($settings['footer'])."<br>";
	echo "<tr><td><table border='0' cellpadding='0' cellspacing='0' width='100%' height='26'>";
	echo "<tr><td class='footer' valign='middle' width='35%'>"; 
	echo "</td>";
	echo "<td class='footer' align='center' width='22%'>";
	echo "<span class='version'><br><br>Ascent CMS v.3 - Made by Bulqr4eto</span> </td>";
	echo "<td class='footer' align='right' width='33%'><span class='stats'><b><br><br>".$settings['counter']."</b></span> ".($settings['counter'] == 1 ? $locale['140']."\n" : $locale['141']."\n");
	echo "</td></tr></table></center></table></table></table>\n";
}


function render_news($subject, $news, $info) {
	
global $locale;
	
	echo "<table border='0' style='border: 1px solid #191928' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td background='".THEME."forum/f_head_cat.gif' height='24'><img src='".THEME."forum/folder.png' alt='' hspace='1'><font class='block-news'>$subject</font></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='1' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>$news</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>&nbsp;";
	echo "".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'><font color='00c0ff'><b>".$info['user_name']."</b></font></a> ";
	echo "".$locale['041'].showdate("longdate", $info['news_date'])." </td>";
	echo "<td height='24' align='right' class='news-footer'>";
	echo "".($info['news_ext'] == "y" ? "<a href='news.php?readmore=".$info['news_id']."'>".$locale['042']."</a> ·\n" : "")."";
	if ($info['news_allow_comments']) 
	echo "<a href='news.php?readmore=".$info['news_id']."'>".$info['news_comments'].$locale['043']."</a> · ";
	echo "</td></tr></table></td></tr></table>\n";
}

function render_article($subject, $article, $info) {
	
global $locale;
	
	echo "<table style='border: 1px solid #191928' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td background='".THEME."images/cellpic3.gif' height='24'><img src='".THEME."images/tri.gif' alt='' hspace='3'><font class='block-title'>$subject</font></td>";
	echo "</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='1' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."";
	echo "</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>";
	echo "".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> ";
	echo "".$locale['041'].showdate("longdate", $info['article_date'])."</td>";
	echo "<td height='24' align='right' class='news-footer'>";
	if ($info['article_allow_comments']) echo $info['article_comments'].$locale['043']." · ";
	echo "".$info['article_reads'].$locale['044']." ";
	echo "<a href='print.php?type=A&item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' alt='' style='vertical-align:middle;'></a>";
	echo "</td></tr></table></td></tr></table>\n";
}

// Open table begins
function opentable($title) {
	
	echo "<table width='100%' cellpadding='2' cellspacing='1' style='border: 1px solid #191928'>";
	echo "<tr><td background='".THEME."sub.jpg' bgcolor='#34353B' height='22'><font class='head-title'>&nbsp;$title</font></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td class='main-body'>\n";
}

// Close table end
function closetable() {
	echo "</td></tr></table>\n";
}

function openside($title) {

	echo "<table style='border: 1px solid #191928' cellspacing='1' width='100%' cellpadding='0'><tr>";
	echo "<td height='18' width='100%' background='".THEME."nav.jpg'>";
	echo "<img src='".THEME."images/tri.gif' alt='' hspace='3'><font class='block-title'>$title</font>";
	echo "</td></tr>";
	echo "<tr><td bgcolor='#191928' class='side-body' width='100%'>";
}

function closeside() {
	echo "</td></tr></table><table border='0' cellpadding='0' cellspacing='0' width='100%'>
  <tr>
    <td width='100%' background='".THEME."images/panfoot.jpg'>&nbsp;</td>
  </tr>
</table>";
	tablebreak();
}

function opensidex($title,$open="on") {

$boxname = str_replace(" ", "", $title);
$box_img = $open == "on" ? "off" : "on";

	echo "<table border='0' style='border: 1px solid #191928' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td height='24' width='100%' background='".THEME."images/cellpic3.gif'>";
	echo "<img align='right' src='".THEME."images/panel_$box_img.gif' name='b_$boxname' alt='' onclick=\"javascript:flipBox('$boxname')\"><font class='block-title'>$title</font>";
	echo "</td></tr>";
	echo "<tr><td bgcolor='#191928' class='side-body'width='100%' '".THEME."images/back1.gif'>";
	echo "<div id='box_$boxname'".($open=="off"?" style='display:none'":"").">\n";
}


function closesidex() {

	echo "</div></td></tr></table>";
	tablebreak();
}

// Table functions
function tablebreak() {
	echo "<table width='100%' cellspacing='0' cellpadding='0'><tr><td height='8'></td></tr></table>\n</center>";
}
?>