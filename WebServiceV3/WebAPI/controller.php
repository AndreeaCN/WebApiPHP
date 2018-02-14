<?php 
include_once("model.inc");            // call the database-logic file
//switch case inspired by http://blog.ijasoneverett.com/2013/02/rest-api-a-simple-php-tutorial/
$action=array("get_apartment", "get_room");    // save the 2 posibilities in an array and then check if the action has been set and if the value is one found in the array
//Also check if all the other required variables are not empty
if(isset($_GET['action']) && in_array($_GET['action'], $action)&& !empty($_GET['apartment_id']) &&!empty($_GET['intv']) &&!empty($_GET['format']))
{
	    $apid=urldecode($_GET['apartment_id']);
	    $intv =urldecode($_GET['intv']);
	    $format=urldecode($_GET['format']);
	
	 // construct switch case based on the action value
	switch($_GET['action'])
	{
		// if user only wants to view the main readings for the Apartment
		case "get_apartment":
		        // decode any %## from the URL encoded string
				 //http://php.net/manual/en/function.urldecode.php
				 
				 if(!empty($_GET['readings'])) // make sure the next variable is not empty
                 {					 
					 $readings =urldecode($_GET['readings']);					
				     if($readings == "all")
				     {					   	
						$data = get_apartment($apid, $intv, $format);		 // call the function from model.inc and pass in the variable values	
                                                                         // bring out a variable $data which now contains the data pulled from database						
				     }
				     else if($readings == "eg")
				     {
						$data = get_elec_gas($apid, $intv,$format);
					 } 		  
				     else if($readings == "hcw")
				     {
						$data = get_water($apid, $intv, $format);
					 }
				     else
				     {
					   $data = get_apartment($apid, $intv,$format);
				     }
				 } 
				 else // if variable is empty present custom HTTP reponse and info page
				 {
					 deliver_response(400, "Invalid request", NULL);
                     include_once('MissingArgsView.php');
				 }
			     
     	break;
		// if user wants to see the room readings
		case "get_room":
		        if (!empty($_GET['room_id']))   // make sure the next variable is not empty
					{
						  $roomid=urldecode($_GET['room_id']);
						  if(!empty($_GET['values'])) // make sure the next variable is not empty
						  {
							  $room_values =urldecode($_GET['values']);
								if($room_values == "all")
								{
									$data= get_room($apid, $roomid, $intv, $format);
								} 
								else if ($room_values == "ht")
								{
									$data= get_ht_room($apid, $roomid, $intv, $format);
								}
								else if($room_values == "sco")
								{
									$data= get_sco_room($apid, $roomid, $intv, $format);
								}
								else
								{
									   $data=get_room($apid, $roomid, $intv, $format);
								}
						  }	
                         else // if variable is empty present custom HTTP reponse and info page
						 {
							deliver_response(400, "Invalid request", NULL);
							 include_once('MissingArgsView.php');
						 }							  
					}
				else // if variable is empty present custom HTTP reponse and info page
					 {
						 deliver_response(400, "Invalid request", NULL);
						 include_once('MissingArgsView.php');
					 }
					  
		break;
	}
	// check if the variable data contains anything - this is in case there are no values saved in DB
	if (empty($data))
	   {
		   deliver_response(200, "Readins not found", NULL); // call the deliver response function
		   // this simulates the HTTP response codes
	   }
	else // if the $data is not empty choose a format for presenting data
	   {
		   if($format=="json")
		   {
			    header("Content-Type:application/json");   // set the content type as Json so the application knows what to do with it
		        deliver_response(200, "Readings found", $data);// deliver the data, formatted in a response
		   } 
		   else if($format="xml")
		   {
			   header("Content-Type:application/xml");   // set the content type as XML so the application knows what to do with it 
			   echo $data;// deliver the data, formatted in a response
		   }
		  
	   }
}
else // if any of the variables at the top are empty, present custom HTTP reponse and info page
{
deliver_response(400, "Invalid request", NULL);
include_once('MissingArgsView.php');
}    	  
	   


function deliver_response($status, $status_message, $data)
{
	// Format a Json response adding the status and message as well as the data brought up from the database
	// this could very well reconstruct the original file format 
	header("HTTP/1.1 $status $status_message");
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;	
	$json_response = json_encode($response);
	echo $json_response;
}

?>