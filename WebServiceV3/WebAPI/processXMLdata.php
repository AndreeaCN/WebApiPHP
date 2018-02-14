<?php 
// use the xml load file function to load up the contents into an object
//http://php.net/manual/en/function.simplexml-load-file.php
$xml = simplexml_load_file("data/smartBuilding.xml");
	// first check the document loaded ok:
if($xml)
{
	// include the db_lib file that makes the database connection
	include_once("model.inc");		    
	foreach($xml->APARTMENT[0]->DETAILS as $details)
	{
		// save the attribute apartment id as variable
		$theid = $xml->APARTMENT[0]["id"];
		echo "Inserting into Apartment table - Apartment 1 data ...";
		//call the record apartment function from db_lib and pass in the data as parameters
	    record_apartment($theid, $details->APARTMENT_NAME,$details->ELECTRICITY_READING, $details->GAS_READING, $details->HOT_WATER_READING,$details->COLD_WATER_READING);
		
		echo "<br/>";		  
	}
	foreach($xml->APARTMENT[0]->ROOMS->ROOM as $sensor)
	{
		$roomid = $sensor["id"];
		echo " Inserting room data ...";
		//call the record room function from db_lib and pass in the data as parameters
		record_room($roomid,$sensor->ROOM_TYPE,$sensor->HUMIDITY, $sensor->TEMPERATURE,$sensor->CO2LEVEL, $sensor->SMOKE_DETECTED, $theid);
		
		echo "<br/>";			 
	}
	// repeat for apartment 1
	foreach($xml->APARTMENT[1]->DETAILS as $details)
	{
		$theid = $xml->APARTMENT[1]["id"];
		echo " Inserting into Apartment table - Apartment 2 data ...."	;
        record_apartment($theid, $details->APARTMENT_NAME,$details->ELECTRICITY_READING, 
		                 $details->GAS_READING, $details->HOT_WATER_READING,$details->COLD_WATER_READING);		
		echo "<br/>";
	}
	foreach($xml->APARTMENT[1]->ROOMS->ROOM as $sensor)
	{
		   $roomid = $sensor["id"];
		   echo "Inserting room data ...";
		   record_room($roomid,$sensor->ROOM_TYPE,$sensor->HUMIDITY, $sensor->TEMPERATURE,$sensor->CO2LEVEL, $sensor->SMOKE_DETECTED, $theid);
		   echo "<br/>";
	}
} else
{
	echo "<p>Error - DOES NOT WORK</p>";
	exit;
}			
?>

	
