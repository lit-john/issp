<?php
	include('./includes/flickr-v4.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flickr Slide Show</title>
<link href="css/960_12_col.css" rel="stylesheet" type="text/css" />
<link href="css/slides.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script src="js/slides.min.jquery.js"></script>

<script>
		$(function(){
			$('#slides').slides({
				preload: true,
				preloadImage: 'img/loading.gif',
				play: 5000,
				pause: 2500,
				hoverPause: true
			});
		});
</script>
</head>

<body>
<div id="wrapper" class="container_12">
    <div class="grid_8 prefix_2">
    <div id="slides">
    	<div class="slides_container">
        	<?php
				$images = getFlickrPhotos(false);
				
				for ($i=0; $i < sizeof($images); $i++)
				{
					echo('<a href="#">' . $images[$i] . '</a>');
				}
			?>
        </div>
        <a href="#" class="prev"><img src="img/arrow-prev.png" width="24" height="43" alt="Arrow Prev"></a>
		<a href="#" class="next"><img src="img/arrow-next.png" width="24" height="43" alt="Arrow Next"></a>    
    </div>
    <img src="img/example-frame.png" width="739" height="341" alt="Example Frame" id="frame">
    </div>
</div>
</body>

</html>