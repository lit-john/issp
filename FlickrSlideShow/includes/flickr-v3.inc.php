<?php

/*
   This script uses the FLickr API to search for photo's on flickr that are tagged "clonmel" and display these photos. The 
   script uses the cURL library to issue the HTTP requests.
  
   Flickr supports various API's. This script uses the REST API. REST stands for Representational State Transfer. At it's most
   basic REST is:
  		- a way of calling "functions" on a remote server. So, for example, the Flickr API (Application Progamming Interface)
  		  has a "function" that allows clients search for photo's. Flickr client's are 3rd party (non-flickr) applications that
  		  use the flickr service. So this script can be described as a Flickr client application.
  		- Generally, to call a function using REST the client issues a HTTP request. For example, a REST call may look like
  			http://flickr.com/functions/search
  		  When this URL is called it is telling flickr to execute it's search function. This example, however, would require
  		  more information, like what it is your searching for. Such "function arguments" are also passed to the service via 
  		  the HTTP request. So the request might look like:
  			http://flickr.com/functions/search?tag=clonmel&number=10
  		  The above request passes two arguments to the search "function", the tag argument has a value of clonmel and the 
  		  number argument has a value of 10. So flickr could now search for photo's tagged clonmel and return you the first
  		  10 photos.
  		- REST services, like flickr, generally return you information in the form of XML. Usually when you call a URL the 
  		  web server returns you HTML, but in the case of REST the server will return you XML containing the "return value"
  		  of the "function" you just called.
 		- REST services remember nothing about you, that is, they are stateless. What this essentially means is that every time
  		  you issue a REST call you must provide the REST service all the information it requires in order to process the call.
  
   The flickr documentation states that when you issue a REST call it must be in this format:
  		http://api.flickr.com/services/rest/?method=[method_name]&[argument_name=argument_value]
   see: http://www.flickr.com/services/api/request.rest.html
  
   A look at the flickr API shows that it has a REST "function" (which are usually called REST methods) for searching. The details
   of the method are here : http://www.flickr.com/services/api/flickr.photos.search.html (a full list of flickr API methods are
   are here: http://www.flickr.com/services/api/). The search method is called flickr.photos.search, so to call this method using
   REST we would issues a HTTP request like:
  		http://api.flickr.com/services/rest/?method=flickr.photos.search
  
   However, the documentation for the method flickr.photos.search states that you must pass in your api key. The api key is to be
   passed in an argument called api_key. So now the HTTP
   request would look like:
  		http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=3b7e3bcf72eeff9614e6131de731369c
  
   We still haven't told flickr what it is we want to search for. Looking again at the documentation for flickr.photos.search we
   see that there is an optional argument called tags which you can contain "a comma-delimited list of tags. Photos with one 
   or more of the tags listed will be returned". So now our HTTP request will look like:
  		http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=3b7e3bcf72eeff9614e6131de731369c&tags=clonmel
  
   We're nearly there but before we issues the above request there are two more optional arguments to flickr.photos.search that
   I am going to provide:
  		media - Filter results by media type. Possible values are all (default), photos or videos. I just want photo's so I am 
  				going to set this to photos.
  		per_page - 	Number of photos to return per page. If this argument is omitted, it defaults to 100. The maximum allowed 
  					value is 500. I only want 10 photo's returned so I am going to set this to 10.
		tag_mode -  Either 'any' for an OR combination of tags, or 'all' for an AND combination. Defaults to 'any' if not 
					specified. I'm going to set it to all
  
   So my REST HTTP request will look like:
    http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=3b7e3bcf72eeff9614e6131de731369c&tags=clonmel&tag_mode=all&media=photos&per_page=10
  
   What type of XML can I expect to be returned back. Again the documentation states that the response will be in the following 
   format:
  
		<photos page="2" pages="89" perpage="10" total="881">
			<photo id="2636" owner="47058503995@N01" 
				secret="a123456" server="2" title="test_04"
				ispublic="1" isfriend="0" isfamily="0" />
			<photo id="2635" owner="47058503995@N01"
				secret="b123456" server="2" title="test_03"
				ispublic="0" isfriend="1" isfamily="1" />
			<photo id="2633" owner="47058503995@N01"
				secret="c123456" server="2" title="test_01"
				ispublic="1" isfriend="0" isfamily="0" />
			<photo id="2610" owner="12037949754@N01"
				secret="d123456" server="2" title="00_tall"
				ispublic="1" isfriend="0" isfamily="0" />
		</photos>
 
  Looking at the response I see that nowhere does it specify the URL of the photo's, just their id, title, etc. I need the URLs of
  the photo's if I want to display them. Looks like I am going to have to make a second REST call. 
  
  Having looked once again through the API documentation (http://www.flickr.com/services/api/) I see there is a method called
   flickr.photos.getSizes (http://www.flickr.com/services/api/flickr.photos.getSizes.html) which:
 
 	"Returns the available sizes for a photo. The calling user must have permission to view the photo."
	
  The method takes two agruments:
  	api_key (Required) - Your API application key. See here for more details.
	photo_id (Required) - The id of the photo to fetch size information for.
	
  The api_key I have and I also have the id's of the photo's I want (they were returned in the xml response from the 
  flickr.photos.search call. The response XML looks will look like:
  
  		<sizes>
			<size label="Square" width="75" height="75"
				  source="http://farm2.static.flickr.com/1103/567229075_2cf8456f01_s.jpg"
				  url="http://www.flickr.com/photos/stewart/567229075/sizes/sq/"/>
			<size label="Thumbnail" width="100" height="75"
				  source="http://farm2.static.flickr.com/1103/567229075_2cf8456f01_t.jpg"
				  url="http://www.flickr.com/photos/stewart/567229075/sizes/t/"/>
			<size label="Small" width="240" height="180"
				  source="http://farm2.static.flickr.com/1103/567229075_2cf8456f01_m.jpg"
				  url="http://www.flickr.com/photos/stewart/567229075/sizes/s/"/>
			<size label="Medium" width="500" height="375"
				  source="http://farm2.static.flickr.com/1103/567229075_2cf8456f01.jpg"
				  url="http://www.flickr.com/photos/stewart/567229075/sizes/m/"/>
			<size label="Original" width="640" height="480"
				  source="http://farm2.static.flickr.com/1103/567229075_6dc09dc6da_o.jpg"
				  url="http://www.flickr.com/photos/stewart/567229075/sizes/o/"/>
		</sizes>
		
  As you can see it returns all the available sizes for the photo whose id you specified in the photo_id argument. Each size also
  has an attribute called source which contains the URL of the image (which is what I am after).
  
  So I have everything I need, this is what I am going to do:
  	1. Call the flickr method flickr.photos.search and search for 10 photo's that are tagged clonmel. 
	2. I am going to loop through all the <photo>'s in the response and on each iteration of the loop I am going to:
		a. Get the id of the <photo>
		b. Call the flickr.photos.getSizes method passing the id of the photo from (a) as an argument
		c. Parse the response XML and get the source attribute of the "Medium" size photo.
	
 */
 
define('MY_PROXY', 'litstaffprxy.litdom.lit.ie:80');
 
function getFlickrPhotos($useProxy)
{
	// As stared earlier I am using cURL, the following variable indicates whether or not I am behind the TI proxy
	$usingProxy = $useProxy;
	 
	/*
	 * I am going to use the following API key
	 *		Account: Details for multimedia.tipp@yahoo.com
	 *		key: 3b7e3bcf72eeff9614e6131de731369c
	 */
	 
	// Set up some variables to store the values of arguments
	$api_key = "3b7e3bcf72eeff9614e6131de731369c";
	$tags = urlencode("clonmel , flood");
	$tag_mode = "all";
	$per_page = 10;
	$media = "photos";
	
	/*
		Notice above that I have specified two tags "clonmel" and "flood". The API documentation specified that if you are specifying 
		more that one tag thay have to be seperated by comma's. You should always url encode this as the urlencode function will replace
		characters like ' ' (spaces) with a '+' and ',' (comma's) with '%2C'. All HTTP requests should be URL encoded.
	*/
	
	// Set up a variable to store the REST URL
	$flickr_search = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=$api_key&tags=$tags&tag_mode=$tag_mode&media=$media&per_page=$per_page";
	
	
	/*
		Note: In the above URL
			http://api.flickr.com/services/rest/ - is referred to as the endpoint
			?method=flickr.photos.search - is the method
			&api_key=$api_key&tags=$tags&media=$media&per_page=£per_page - is the arguments
	 */
	
	// Call the function (see below) called getXML which will return a SimpleXMLElement
	$rsp = getXML($flickr_search, $usingProxy);
	
	// Loop through all <photo> elements in the response
	foreach($rsp->photos->photo as $photo)
	{
		// Get the id attribute of the photo
		$photo_id = $photo['id'];
		
		// Build the URL for the flickr.photos.getSizes method call
		$flickr_getSizes = "http://api.flickr.com/services/rest/?method=flickr.photos.getSizes&api_key=$api_key&photo_id=$photo_id";
		
		// Call the getXML function
		$sizes_rsp = getXML($flickr_getSizes,$usingProxy);
		
		// Loop through all <size> elements in the response
		foreach($sizes_rsp->sizes->size as $size)
		{
			// Check to see if the "label" attribute of this <size> is equal to "Medium"
			if ($size['label'] == "Medium")
			{
				if ($size['width'] == 500)
				{
					$imgArray[] = '<img src="' . $size['source'] .'"' . ' width="' .$size['width'] . '" height="' . $size['height'] . '" />';
				}
			}
		}
		
	}
	
	return $imgArray;
}

/*
 * This function uses cURL to get the XML from a URL feed passed to it in the variable $theXMLFeed
 * and returns a SimpleXMLElement containing the XML. It will only use cURL if the $proxy variable 
 * is true (it is false by default)
 */
function getXML($theXMLFeed, $proxy=false)
{
	
	// First off we need to initialise a cURL session. We do so using the curl_init function which returns
	// a handle to the session i.e. $ch
	//
	//	see http://www.php.net/manual/en/function.curl-init.php
	$ch = curl_init();
	
	// Now we need to setup some options for the cURL session. There are a bunch of options that you can set
	// (see here http://www.php.net/manual/en/function.curl-setopt.php) and they are all set using the 
	// curl_setopt function. This function takes three arguments:
	//	1. A handle to the cURL session i.e. in our case $ch
	//	2. The option you want to set
	//	3. The value you want to set the option to
	
	// First I am going to set the URL that I want to connect to via cURL. In this case it is the URL of the
	// of the XML.
	curl_setopt($ch, CURLOPT_URL, $theXMLFeed);
		
	if ($proxy)
	{
		// Next I am going to set the ip address and port number of the proxy that cURL has to go through in order
		// to contact the above URL. Note that it is in the format ip-address:port-number
		// NOTE: I have set the proxy using the Tippinst settings
		curl_setopt($ch, CURLOPT_PROXY, MY_PROXY);
	}
		
	// Next, and probably most important, I have to set CURLOPT_RETURNTRANSFER to 1 i.e. true. Setting this
	// to true tells cURL to return the contents of the above URL as a string to this script and as opposed
	// to printing the contents to the browser directly
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	// Next I tell cURL to execute the cURL session. By default it will issue a HTTP GET request to the
	// sepcified URL. $data will contain a string containing the result of the request i.e. in this case a 
	// string containing the RSS XML.
	$data = curl_exec($ch);
	
	// Finally I close the cURL session
	curl_close($ch);

	// Now I create a SimpleXMLElement object passing it the string which contains the XML
	$xml = new SimpleXMLElement($data);
	
	return $xml;
}
?>
