<?php
	// I am including version one of my flickr script
	include('./includes/flickr-v1.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flickr Slide Show</title>
<link href="css/960_12_col.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="wrapper" class="container_12 n">
    <div class="grid_12">
        <div id="slideshow">
        	<?php
				getFlickrPhotos(false, "dublin, flood");
			?>
        </div>
    </div>
</div>
</body>

</html>