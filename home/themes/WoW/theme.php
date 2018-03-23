<?php
/*--------------------------------------------+
| PHP-Fusion v6 - Content Management System   |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
/*--------------------------------------------+
|      Fusion 6 Theme for PHP-Fusion v6       |
|---------------------------------------------|
| author: PHP-Fusion Themes - Shedrock © 2005 |
| web: http://phpfusion.org                   |
| email: webmaster@phpfusion.org              |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/

// theme settings
$body_text = "#000000";
$body_bg = "#000000";
$theme_width = "1000";
$theme_width_l = "200";
$theme_width_r = "200";

function render_header($header_content) {

global $theme_width,$settings;

echo "<center><table width=\"1000\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td>
    <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
        <td height=\"269\" align=\"center\" valign=\"bottom\" background=\"".THEME."header/wow_head.gif\">
         <div align='right'>".ucwords(showdate($settings['subheaderdate'], time()))."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
         <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
            <td>";

$result = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_visibility<='".iUSER."' AND link_position>='2' ORDER BY link_order");
if (dbrows($result) != 0) {
        $i = 1;
        while($data = dbarray($result)) {
                if ($data['link_url']!="---") {
                        $link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
                        if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
                                echo "<td  width=\"100\" height=\"32\" align=\"center\" background=\"".THEME."images/button.jpg\"><a href='".$data['link_url']."'".$link_target."><h3>".$data['link_name']."</h3></a></td>";
                        } else {
                                echo "<td  width=\"100\" height=\"32\" align=\"center\" background=\"".THEME."images/button.jpg\"><a href='".BASEDIR.$data['link_url']."'".$link_target."><h3>".$data['link_name']."</h3></a></td>";
                        }
                }
                if ($i != dbrows($result)) { echo "<td  width=\"17\" height=\"32\" align=\"center\" background=\"".THEME."images/buttonb.gif\"></td>"; } else { echo "\n"; } $i++;
        }
}
            echo "</td>
          </tr>
        </table>

        </td>
      </tr>
    </table>
      <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <tr>
          <td width=\"16\" background=\"".THEME."panels/leftborder.gif\">&nbsp;</td>
          <td>\n";
}

function render_footer($license=false) {

global $theme_width,$locale,$settings;

echo "</td>
          <td width=\"16\" background=\"".THEME."panels/rightborder.gif\">&nbsp;</td>
        </tr>
      </table>
      <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <tr>
        <td width=\"16\" background=\"".THEME."panels/leftborder.gif\">&nbsp;</td>
        <td align='center'>".stripslashes($settings['footer'])."</td>
      <td width=\"16\" background=\"".THEME."panels/rightborder.gif\">&nbsp;</td>
      </tr>
    </table>
      <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
<td>
<img name=\"wow_foot1\" src=\"".THEME."footer/wow_foot.gif\" width=\"1000\" height=\"150\" border=\"0\" usemap=\"#m_wow_foot1\" alt=\"\">
<map name=\"m_wow_foot1\">
<area shape=\"rect\" coords=\"559,77,784,90\" href=\"mailto:bfs2home@hot.ee\" target=\"_blank\" title=\"DX-Portal\" alt=\"DX-Portal\" >
<area shape=\"rect\" coords=\"94,76,345,90\" href=\"http://www.php-fusion.co.uk/\" target=\"_blank\" title=\"PHP-Fusion\" alt=\"PHP-Fusion\" >
</map>
</td>
      </tr>
    </table>
    </td>
  </tr>
</table>\n";
}

function render_news($subject, $news, $info) {

global $locale;

echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
              <td><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tr>
                  <td width=\"57\" height=\"47\" background=\"".THEME."panels/ctl.gif\">&nbsp;</td>
                  <td background=\"".THEME."panels/ctm.gif\">&nbsp;</td>
                  <td width=\"116\" height=\"47\" background=\"".THEME."panels/ctr.gif\">&nbsp;</td>
                </tr>
              </table>
                <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"15\" background=\"".THEME."panels/cborderl.gif\">&nbsp;</td>
                    <td bgcolor=\"#212723\">
                    <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                      <tr>
                        <td align=\"center\" class='center-caption'>$subject</td>
                      </tr>
                      <tr>
                        <td class='main-body'><hr>$news</td>
                       <tr>
                       <td align='center'><hr>".$locale['040']." <a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> ".$locale['041'].showdate("longdate", $info['news_date'])." --- ";

        echo "".($info['news_ext'] == "y" ? "<a href='news.php?readmore=".$info['news_id']."'>".$locale['042']."</a> <b>·</b>\n" : "")."";
        if ($info['news_allow_comments'])
        echo "<a href='news.php?readmore=".$info['news_id']."'>".$info['news_comments'].$locale['043']."</a> <b>·</b> ";
        echo "".$info['news_reads'].$locale['044']." <b>-</b> ";
        echo "<a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>";
        echo "</td>";

                       echo "</tr>
                      </tr>
                    </table>
                    </td>
                    <td width=\"35\" background=\"".THEME."panels/cborderr.gif\">&nbsp;</td>
                  </tr>
                </table>
                <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"57\" height=\"97\" background=\"".THEME."panels/cbl.gif\">&nbsp;</td>
                    <td height=\"97\" background=\"".THEME."panels/cbm.gif\">&nbsp;</td>
                    <td width=\"116\" height=\"97\" background=\"".THEME."panels/cbr.gif\">&nbsp;</td>
                  </tr>
                </table>
                </td>
            </tr>
          </table><br>\n";
}

function render_article($subject, $article, $info) {

global $locale;

echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
              <td><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tr>
                  <td width=\"57\" height=\"47\" background=\"".THEME."panels/ctl.gif\">&nbsp;</td>
                  <td background=\"".THEME."panels/ctm.gif\">&nbsp;</td>
                  <td width=\"116\" height=\"47\" background=\"".THEME."panels/ctr.gif\">&nbsp;</td>
                </tr>
              </table>
                <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"15\" background=\"".THEME."panels/cborderl.gif\">&nbsp;</td>
                    <td bgcolor=\"#212723\">
                    <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                      <tr>
                        <td align=\"center\" class='center-caption'>$subject</td>
                      </tr>
                      <tr>
                        <td class='main-body'><hr><div style='width:100%;vertical-align:top;'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</div><hr>


       <div align='center'>".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a>
        ".$locale['041'].showdate("longdate", $info['article_date'])."";
        if ($info['article_allow_comments'])
        echo $info['article_comments'].$locale['043']." <b>·</b> ";
        echo "".$info['article_reads'].$locale['044']." <b>-</b> ";
        echo "<a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>";
        echo "</div></td>";

                    echo "  </tr>
                    </table></td>
                    <td width=\"35\" background=\"".THEME."panels/cborderr.gif\">&nbsp;</td>
                  </tr>
                </table>
                <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"57\" height=\"97\" background=\"".THEME."panels/cbl.gif\">&nbsp;</td>
                    <td height=\"97\" background=\"".THEME."panels/cbm.gif\">&nbsp;</td>
                    <td width=\"116\" height=\"97\" background=\"".THEME."panels/cbr.gif\">&nbsp;</td>
                  </tr>
                </table>
                </td>
            </tr>
          </table><br>\n";
}

function opentable($title) {

echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
              <td><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tr>
                  <td width=\"57\" height=\"47\" background=\"".THEME."panels/ctl.gif\">&nbsp;</td>
                  <td background=\"".THEME."panels/ctm.gif\">&nbsp;</td>
                  <td width=\"116\" height=\"47\" background=\"".THEME."panels/ctr.gif\">&nbsp;</td>
                </tr>
              </table>
                <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"15\" background=\"".THEME."panels/cborderl.gif\">&nbsp;</td>
                    <td bgcolor=\"#212723\">
                    <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                      <tr>
                        <td align=\"center\" class='center-caption'>$title</td>
                      </tr>
                      <tr>
                        <td class='main-body'>\n";
}

function closetable() {

echo "</td>
                      </tr>
                    </table></td>
                    <td width=\"35\" background=\"".THEME."panels/cborderr.gif\">&nbsp;</td>
                  </tr>
                </table>
                <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"57\" height=\"97\" background=\"".THEME."panels/cbl.gif\">&nbsp;</td>
                    <td height=\"97\" background=\"".THEME."panels/cbm.gif\">&nbsp;</td>
                    <td width=\"116\" height=\"97\" background=\"".THEME."panels/cbr.gif\">&nbsp;</td>
                  </tr>
                </table>
                </td>
            </tr>
          </table><br>\n";
}

function opentablex($title,$open="on") {

        $boxname = str_replace(" ", "", $title);
        $box_img = $open == "on" ? "off" : "on";

echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
              <td><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tr>
                  <td width=\"57\" height=\"47\" background=\"".THEME."panels/ctl.gif\">&nbsp;</td>
                  <td background=\"".THEME."panels/ctm.gif\">&nbsp;</td>
                  <td width=\"116\" height=\"47\" background=\"".THEME."panels/ctr.gif\">&nbsp;</td>
                </tr>
              </table>
                <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"15\" background=\"".THEME."panels/cborderl.gif\">&nbsp;</td>
                    <td bgcolor=\"#212723\">
                    <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                      <tr>
                        <td align=\"center\" class='center-caption'>$title</td>
                        <td align='right' width='17' class='panel-main'><img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' alt='' onclick=\"javascript:flipBox('$boxname')\"></td>
                      </tr>
                      <tr>
                        <td class='main-body'>
                        <div id='box_$boxname'".($open=="off" ? "style='display:none'" : "").">\n";
}

function closetablex() {

echo "</div></td>
                      </tr>
                    </table></td>
                    <td width=\"35\" background=\"".THEME."panels/cborderr.gif\">&nbsp;</td>
                  </tr>
                </table>
                <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                  <tr>
                    <td width=\"57\" height=\"97\" background=\"".THEME."panels/cbl.gif\">&nbsp;</td>
                    <td height=\"97\" background=\"".THEME."panels/cbm.gif\">&nbsp;</td>
                    <td width=\"116\" height=\"97\" background=\"".THEME."panels/cbr.gif\">&nbsp;</td>
                  </tr>
                </table>
                </td>
            </tr>
          </table><br>\n";
}

function openside($title) {

echo "<table width=\"200\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
              <td><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tr>
                  <td height=\"47\" align=\"right\" valign=\"bottom\" background=\"".THEME."panels/pt.gif\">
                  <table width=\"73%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                      <tr>
                        <td class='side-caption'>$title</td>
                      </tr>
                      <tr>
                        <td height=\"20\"></td>
                      </tr>
                  </table>
                  </td>
                </tr>
                <tr>
                  <td background=\"".THEME."panels/pm.gif\">
                  <table  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                      <tr>
                        <td width=\"36\">&nbsp;</td>
                        <td width=\"142\" class='side-body'>\n";
}

function closeside() {

echo "</td>
               </tr>
                  </table>
                  </td>
                </tr>
                <tr>
                  <td height=\"8\" background=\"".THEME."panels/pb.gif\"></td>
                </tr>
              </table>
              </td>
            </tr>
          </table><br>\n";
}

function opensidex($title,$open="on") {

        $boxname = str_replace(" ", "", $title);
        $box_img = $open == "on" ? "off" : "on";

echo "<table width=\"200\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
              <td><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tr>
                  <td height=\"47\" align=\"right\" valign=\"bottom\" background=\"".THEME."panels/pt.gif\">
                  <table width=\"73%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                      <tr>
                        <td class='side-caption'>$title</td>
                        <td align='center' class='panel-main'><img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' alt='' onclick=\"javascript:flipBox('$boxname')\"></td>
                      </tr>
                      <tr>
                        <td height=\"20\"></td>
                      </tr>
                  </table>
                  </td>
                </tr>
                <tr>
                  <td background=\"".THEME."panels/pm.gif\">
                  <table  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                      <tr>
                        <td width=\"36\">&nbsp;</td>
                        <td width=\"142\" class='side-body'>
                        <div id='box_$boxname'".($open=="off" ? "style='display:none'" : "").">\n";
}

function closesidex() {

echo "</div></td>
               </tr>
                  </table>
                  </td>
                </tr>
                <tr>
                  <td height=\"8\" background=\"".THEME."panels/pb.gif\"></td>
                </tr>
              </table>
              </td>
            </tr>
          </table><br>\n";
}

function tablebreak() {

        echo "<table width='100%' cellspacing='0' cellpadding='0'><tr><td height='5'>";
        echo "</td></tr></table>\n";
}
?>