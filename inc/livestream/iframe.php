<?php
$width = 640;
$height = 426;
if(!empty($_GET["width"]) && !empty($_GET["height"])) {
    $width = $_GET["width"];
    $height = $_GET["height"];
}
?>
<html>
<head>
    <title></title>
</head>
<body style="margin: 0;">
	<?php 
		if(strpos($_SERVER["HTTP_REFERER"], "keepthewebopen.com/sopa") !== false || strpos($_SERVER["HTTP_REFERER"], "keepthewebopen.com/open") !== false) : ?>

	<div id="hoclive"><a href="http://www.adobe.com/go/getflashplayer" target="_blank"><img src="images/flash.jpg" title="Install/Upgrade Adobe Flash Player" alt="Install/Upgrade Adobe Flash Player" border="0"/></a></div>
	<script type="text/javascript" src="mediaplayer-5.8-licensed/jwplayer.js"></script>
	<script type="text/javascript">
		jwplayer("hoclive").setup({
			flashplayer: "mediaplayer-5.8-licensed/player.swf",
			abouttext: "",
			aboutlink: "",
			width: <?php echo $width;?>,
			height: <?php echo $height;?>,
			skin: "skins/glow/glow.zip",
			streamer: "rtmp://imavex.fc.llnwd.net/imavexhoc",
			file: "hoc_input",
			"rtmp.subscribe": true,
			autostart: true,
			provider: "rtmp"
		});	
	</script>
    <?php endif; ?>
</body>
</html>
