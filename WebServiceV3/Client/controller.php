<?php  
// set the common path to the api
$url="http://localhost/WebServiceV3/WebAPI/controller.php?";
// declare an empty variable for the remaining part of URL
$partUrl="";
if(isset($_POST['format'])) // check if one of the submit buttons has been pressed
{    // make sure the first 4 variables are not empty
    if (!empty($_POST['action'])&&!empty($_POST['intv']) && !empty($_POST['apartment_id'])&&!empty($_POST['format'])) 
	{   // use urlencode to remove any white space
		$action = urlencode($_POST['action']);
        $intv = urlencode($_POST['intv']);
	    $apid = urlencode($_POST['apartment_id']);
		$format=urlencode($_POST['format']);	
	    
		//check the value of the action variable
		if ( filter_input(INPUT_POST, "action") == "get_apartment")
		{
			if (!empty($_POST['readings'])) $readings = urlencode($_POST['readings']);
			// form the second part of the URL to api resource
			$partUrl="action=get_apartment&intv=$intv&apartment_id=$apid&readings=$readings&format=$format";
		}
		else if ( filter_input(INPUT_POST, "action") == "get_room")
		{
			if (!empty($_POST['room_id']) &&!empty($_POST['values']))
			{
				$roomid = urlencode($_POST['room_id']);
				$values = urlencode($_POST['values']);
				// form the second part of the URL to api resource
				$partUrl="action=get_room&intv=$intv&apartment_id=$apid&room_id=$roomid&values=$values&format=$format";
			}			
		}
		else
		{
			$action ="get_apartment"; // set a default			
		}
	}
	else
	{   // some crude error checking
		echo "Not all variables have been set"; 
	}
	// form the full URL
	$urlFull=$url.$partUrl;
	// initialize the cURL and give it the link where the HTTP request needs to be sent to
	$wclient = curl_init($urlFull);
	//set the cURL options
	curl_setopt($wclient, CURLOPT_RETURNTRANSFER, 1);  // CURLOPT_RETURNTRANSFER, means to return a string and not output the response to the screen
	//call the curl_exec to send the HTTP request and save the response in a variable
	$response = curl_exec($wclient);
	// Check which format has been selected for outputting data
	if(filter_input(INPUT_POST, "format") == "json")
	{
		$temp_data=json_decode($response); // decode the data from json format, data is returned as an array
		$edata = $temp_data->{'data'}; // access the data part of the response
		if(!empty($edata))
		{
			$newdata=implode("",$edata); // break up the array and use a space as delimiter between items, required for the Google charts formatting
		}
		else 
		{
			// add logic to fill in $newdata with empty table for charts and a message so it does not crash
		}
		include_once('templateCharts.php'); // show the charts page
	}
	else if(filter_input(INPUT_POST, "format") == "xml")
	{
		include_once("xmlClient.php");	// add a view for the XML content
	    echo $response;
	}	
}
					
?>