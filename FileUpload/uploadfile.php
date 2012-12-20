<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Result</title>
</head>
<?php
	// I am including a file which has the functions I require
	require("myFunctions.php");
	/* I am going to setup some variables that I need and set them to "Not provided". Try comment
	 * out this line and add it instead within the if statement
	 */
	$name = $myFile = "Not provided";
		
	/* Next I am going to check if the submit button has
	 * been pressed. It is pointless doing this as this page would not executed
	 * if the button was not pressed.
	 */
	if(isset($_REQUEST["Submit"]))
	{	
		// Ok the form has been submitted, lets get the form info
		if ($_REQUEST["name"])
		{
			// Ok they filled in their name	
			$name = $_REQUEST["name"];
		}
		
		if (isset($_FILES['myFile']))
		{
			$myFile = uploadFile("uploads/", "myFile");
			
			if (strcmp($myFile,"error") != 0)
			{
				$myFile = '<a href="uploads/'.$myFile.'">'.$myFile.'</a>';
			}
		}

	}	// End of if(isset($_REQUEST["Submit"])) 
?>
<table border="1">
    <tr>
        <td>Name</td><td><?php echo $name?></td>
    </tr>
    <tr>
        <td>File</td><td><?php echo $myFile?></td>
    </tr>
 </table>
<body>
</body>
</html>
