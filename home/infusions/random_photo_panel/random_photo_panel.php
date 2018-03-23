<?php
if (file_exists(INFUSIONS."random_photo_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."random_photo_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."random_photo_panel/locale/Dutch.php";
}

$result=dbquery(
	"SELECT photo_thumb1,ta.album_id,album_title,photo_id,photo_title FROM ".$db_prefix."photo_albums ta ".
	"INNER JOIN ".$db_prefix."photos USING (album_id) ORDER BY RAND() LIMIT 1"
);

if (dbrows($result) == 1) {
	@openside($locale['FOT_001']);
		$data=dbarray($result);
		$filename=PHOTOS.$data['photo_id'].'t.jpg';
		if (!file_exists($filename)) $filename=$image_url.'/imagenotfound.jpg';
		echo "<div style='text-align:center'>
		<a href='".BASEDIR."photogallery.php?photo_id=".$data['photo_id']."' class='gallery'>
		<img src='".PHOTOS."album_".$data['album_id']."/".$data['photo_thumb1']."' width='".$settings['thumb_image_w']."' height='".$settings['thumb_image_h']."'
		title='".$data['photo_title']."' alt='".$data['photo_title']."'>
		</a><br /><a href='".BASEDIR."photogallery.php?photo_id=".$data['photo_id']."' span class='photo'>".$data['photo_title']."</a>
		<br />
		".$data['album_title']."
		</div>";
	@closeside();
}
?>