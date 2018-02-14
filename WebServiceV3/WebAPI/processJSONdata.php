<?php 
// read the file content into a string
//http://php.net/manual/en/function.file-get-contents.php
$test = file_get_contents("data/smartBuilding.json");
if($test)
{ 
    // call the db_lib file that makes the connection to the database
	include_once("model.inc");	
    //convert the string into a variable http://php.net/manual/en/function.json-decode.php				
	$json = json_decode($test);
	// use 2 nested foreach loops to access the readings for each Apartment 
	foreach($json as $building)
	{
		foreach($building as $details)
		{
		  echo "Inserting Json data in Apartment table...";
		  record_apartment($details->APARTMENT_ID,$details->APARTMENT_NAME, $details->ELECTRICITY_READING, $details->GAS_READING, $details->HOT_WATER_READING,$details->COLD_WATER_READING);
		  echo "<br/>";
		  echo "inserting room 1 data..";
		  // go one level lower into the data to the room readings
		  record_room($details->ROOMS[0]->ROOM_ID,$details->ROOMS[0]->ROOM_TYPE, $details->ROOMS[0]->HUMIDITY, $details->ROOMS[0]->TEMPERATURE, $details->ROOMS[0]->CO2LEVEL, $details->ROOMS[0]->SMOKE_DETECTED, $details->APARTMENT_ID);
		  echo "<br/>";
		  echo "inserting room 2 data..";
		  record_room($details->ROOMS[1]->ROOM_ID,$details->ROOMS[1]->ROOM_TYPE, $details->ROOMS[1]->HUMIDITY, $details->ROOMS[1]->TEMPERATURE, $details->ROOMS[1]->CO2LEVEL, $details->ROOMS[1]->SMOKE_DETECTED, $details->APARTMENT_ID);
		
		  echo "<br/>";
		  echo "inserting room 3 data..";
		  record_room($details->ROOMS[2]->ROOM_ID,$details->ROOMS[2]->ROOM_TYPE, $details->ROOMS[2]->HUMIDITY, $details->ROOMS[2]->TEMPERATURE, $details->ROOMS[2]->CO2LEVEL, $details->ROOMS[2]->SMOKE_DETECTED, $details->APARTMENT_ID);
		  echo "<br/>";
		  echo "inserting room 4 data..";
		  record_room($details->ROOMS[3]->ROOM_ID,$details->ROOMS[3]->ROOM_TYPE, $details->ROOMS[3]->HUMIDITY, $details->ROOMS[3]->TEMPERATURE, $details->ROOMS[3]->CO2LEVEL, $details->ROOMS[3]->SMOKE_DETECTED, $details->APARTMENT_ID);
		  echo "<br/>";					
		}
	}		
} else
{
	echo "<p>Error - DOES NOT WORK</p>";
	exit;
}	
					

?>
