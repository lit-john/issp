<?php
	include('./includes/flickr-v3.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flickr Slide Show</title>
<link href="css/960_12_col.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/cycle.css" rel="stylesheet" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="http://cloud.github.com/downloads/malsup/cycle/jquery.cycle.all.latest.js"></script>

<script>
		$(document).ready(function() {
    $('.slideshow').cycle({
		fx: 'fade' // choose your transition type, ex: fade, scrollUp, shuffle, etc...
	});
});
	</script>
</head>

<body>
<div id="wrapper" class="container_12 n">
    <div class="grid_8 prefix_2">
    <div class="slideshow">
        	<?php
				$images = getFlickrPhotos(false);
				
				for ($i=0; $i < sizeof($images); $i++)
				{
					echo($images[$i]);
				}
			?>
            
    </div>
    </div>
</div>
</body>

</html>