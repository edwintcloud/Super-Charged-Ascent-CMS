<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php $this->Title(); ?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<!-- Prepare to enter... DIV HELL!!!! -->
<body>
	<div id="topBar"></div>
	<div id="page">
		<div id="page_header">
			<div><div></div></div>
		</div>
		<div id="page_content">
			<div id="page_content_wrapper">
				<div id="page_content_content">
					<div id="page_content_padding">
						<div id="header_bar">
							<div>
								<div><table style="margin-left:35px; height:38px; vertical-align:middle;"><tr><td><?php $this->Title(); ?></td></tr></table></span>
								</div>
							</div>
						</div><br />

						<div id="article">
							<?php $this->Content(); ?>
						</div>
						<div id="article" style="min-height:0px;">
							<table style="margin-left:10px;">
								<tr>
									<td><a href="?act=vote">Vote Now</a></td>
									<td>&bull;</td>
									<td><a href="../">Go Back</a></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="page_footer">
			<div><div></div></div>
		</div>
	</div>
</body>
</html>
