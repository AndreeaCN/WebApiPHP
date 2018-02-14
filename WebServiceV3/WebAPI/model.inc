<?php
/*----------------------------------------------------------------
function connect_db()
creates the connection to the database using the more secure PDO
returns a database handle $db
------------------------------------------------------------------*/
function connect_db()
{
	// the host name for the mysql database
	$hostname="localhost";
	//username for the database
	$username="root";
	//password for the database
	$password = "Jeremy1980";
	
	try
	{
		//create a connection to the smarthouse database and save it as a variable
		$db=new PDO("mysql:host=localhost;port=3306;dbname=smartbuilding",$username, $password);
	}
	catch(PDOException $e)
	{
		$db = -1;
		echo "Could not connect to SmartHouse database:".$e->getMessage();		
	}
	return $db;	
}
/*------------------------------------------------------------------
function: record_apartment
data: $apartmentID - the apartment id, 
      $apartmentName - the name of the apartment, 
	  $apartmentElec - reading for electricity,
      $apartmentGas - reading for gas, 
	  $apartmentHotWater - reading for hot water, 
	  $apartmentColdWater - reading for cold water
Responsible for inserting readings for each Apartment into the apartment table
returns: n/a
--------------------------------------------------------------------*/
function record_apartment($apartmentID, $apartmentName, $apartmentElec, $apartmentGas, $apartmentHotWater, $apartmentColdWater)
{
       //call the database connection and save it as variable
	$db = connect_db();
	if($db)
	{
	     // Save the Insert SQL statement as a variable 
		// and pass in the data readings received as parameters
		$sql = "INSERT INTO `apartment`(`apartment_id`, `apartment_name`, `apartment_electricity`, `apartment_gas`, `apartment_hotwater`, `apartment_coldwater`)
					VALUES ($apartmentID, \"$apartmentName\", $apartmentElec, $apartmentGas, $apartmentHotWater, $apartmentColdWater)";
		try
		{
			// run query to insert data in the table
			$r = $db->query($sql);
			//echo "<p>".$sql."</p>";
		}
		catch(PDOException $ex)
		{
			echo "An error occured writing to database:".$ex->getMessage();
		}
	}	
}

/*--------------------------------------------------------------------------------------------------
function: record_room
data:  $roomID - the room id, 
       $roomType - the type of room, 
	   $roomSmoke-  the smoke detected sensor,
	   $roomHum - the humidity level
	   $roomTemp - the room temperature
	   $roomCo2 - the room CO2 level
	   $roomSmoke - sensor from smoke detector(yes/no)
	   $apartmentID - the apartment ID
	   
Responsible for inserting the room sensors data into the database room table
returns: n/a
---------------------------------------------------------------------------------------------------*/
function record_room($roomID, $roomType, $roomHum, $roomTemp, $roomCo2, $roomSmoke, $apartmentID )
{
    // create connection to the database
	$db = connect_db();
	if($db)
	{
	    // save the SQL statement as variable
		$sql = "INSERT INTO `room`(`room_id`, `room_type`, `room_humidity`, `room_temp`, `room_co2`, `room_smoke_det`, `apartment_id`) 
		VALUES($roomID, \"$roomType\", $roomHum, $roomTemp, $roomCo2, \"$roomSmoke\", $apartmentID)";
		try
		{
			// run query to insert data in the table
			$r = $db->query($sql);
			//echo "<p>".$sql."</p>";
		}
		catch(PDOException $ex)
		{
			echo "An error occured writing to database:".$ex->getMessage();
		}
	}
}

/*---------------------------------------------------------------------------------------------
FUNCTIONS TO VIEW JSON DATA
-----------------------------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------------------------
function get_apartment_intv()
data: requires the Apartment ID , the time Interval and the format of the response (json or xml)
returns: All the main apartment readings 
-----------------------------------------------------------------------------------------------*/

function get_apartment($apid, $intv, $format)
{
   // $wap_data = 0;
    // create database connection
    $db = connect_db();
	if($db)
	{
	   if($intv=="all") {
	        $sql = "SELECT `apartment_electricity`, `apartment_gas`, `apartment_hotwater`, `apartment_coldwater`, `apartment_timestamp` 
		        FROM `apartment` 
				WHERE `apartment_id`= $apid
                ORDER BY `apartment_timestamp`";			   
		} 
		else {       
				// form the sql query
			   $sql = "SELECT `apartment_electricity`, `apartment_gas`, `apartment_hotwater`, `apartment_coldwater`, `apartment_timestamp` 
						FROM `apartment` 
						WHERE `apartment_id`= $apid
						AND `apartment_timestamp`<NOW()
						AND `apartment_timestamp` > NOW()-INTERVAL $intv
						ORDER BY `apartment_timestamp`";	
		}
		try{
		// attempt to run the query
			$query= $db->prepare($sql);
			$query->execute();		
			$results=$query->fetchAll( PDO::FETCH_ASSOC); // save the results of the query as an array
			if($results) 
			{
			    if($format=="json")
				{
				    $wap_data[] ="{cols: [{label: 'Date', type: 'string'},{label: 'Electricity', type: 'number'},{label: 'Gas', type: 'number'},{label: 'Hot Water', type: 'number'},{label: 'Cold Water', type: 'number'}],rows:[";
					foreach($results as $row)
					{
						// form an array with the values brough from the database
						// and create the format needed for the Google Charts
						$test_data[] = "{c:[{v: '".$row['apartment_timestamp']."'},{v: ".$row['apartment_electricity']."},{v: ".$row['apartment_gas']."},{v: ".$row['apartment_hotwater']."},{v: ".$row['apartment_coldwater']."}]}";
					}
					if($test_data)
					{
					    $cdata=join(",",$test_data);
	                 	array_push($wap_data, $cdata,"]}");
					}
				}
				else if($format=="xml")
				{
				    $wap_data=0;
				    $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><apartment></apartment>');
                    foreach($results as $row)
					{
					    // Recreate the XML structure adding each reading from the database as a child to a holder that is child to the xml root
                        $data=$xml->addChild('readings'); // add a holder for each group of readings
						$data->addChild('electricity',$row['apartment_electricity']);
						$data->addChild('gas',$row['apartment_gas']);
						$data->addChild('hot_water',$row['apartment_hotwater']);	
						$data->addChild('cold_water',$row['apartment_coldwater']);
						$data->addChild('timestamp',$row['apartment_timestamp']);
					}	
					$wap_data=$xml->asXML();	// store the XMLObject as a string			
				}						
			} 
			else
			{
				$wap_data = 0;
			}
			
		}
		catch(PDOException $ex)
		{
			echo "An error occured writing to the database".$ex->getMessage();
		}	
	return $wap_data;  // bring out the data from the database
	}
}




/*---------------------------------------------------------------------------------------------------------------------------------
function get_elec_gas()
data: requires apartment id, interval and format
Returns: The electricity and gas reading for an interval
-----------------------------------------------------------------------------------------------------------------------------------*/

function get_elec_gas($apid,$intv,$format)
{
    $db = connect_db();
	if($db)
	{
	   if($intv=="all")
	   {
	            $sql = "SELECT `apartment_electricity`, `apartment_gas`,`apartment_timestamp` 
						FROM `apartment` 
						WHERE `apartment_id`= $apid
						ORDER BY `apartment_timestamp`";	   	   
		} else
		{
		     $sql = "SELECT `apartment_electricity`, `apartment_gas`,`apartment_timestamp` 
		        FROM `apartment` 
				WHERE `apartment_id`= $apid
                AND `apartment_timestamp`<NOW()
                AND `apartment_timestamp` > NOW()-INTERVAL $intv
				ORDER BY `apartment_timestamp`";
		}
	    try{						
			$query= $db->prepare($sql);
			$query->execute();		
			$results=$query->fetchAll( PDO::FETCH_ASSOC);
			if($results)
			{
			   if($format=="json")
			   {
			        $eg_data[]="{cols: [{label: 'Date', type: 'string'},{label: 'Electricity', type: 'number'},{label: 'Gas', type: 'number'}],rows:[";
			        foreach($results as $row)
					{
					  $test_data[] = "{c:[{v: '".$row['apartment_timestamp']."'},{v: ".$row['apartment_electricity']."},{v: ".$row['apartment_gas']."}]}";
					}
					if($test_data)
					{
					    $cdata=join(",",$test_data);
					    array_push($eg_data, $cdata,"]}");
					}							
			    }
				else if($format=="xml")
				{
				    $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><apartment></apartment>');	
				    foreach($results as $row)
					{
					         // Recreate the XML structure adding each reading from the database as a child to a holder that is child to the xml root
							$data=$xml->addChild('readings'); // add a holder for each group of readings
							$data->addChild('electricity',$row['apartment_electricity']);
							$data->addChild('gas',$row['apartment_gas']);
							$data->addChild('timestamp',$row['apartment_timestamp']);
					}
					$eg_data=$xml->asXML();	// store the XMLObject as a string		
				}
			   				
			} else
			{
			        $eg_data=0;
			}		
		}
		catch(PDOException $ex)
		{
			echo "An error occured writing to the database".$ex->getMessage();
		}
		return $eg_data;
	}
	
}


/*---------------------------------------------------------------------------------------------------------------------------------
function get_water()
data: requires the apartment id, interval and format
Returns: All the hot and cold water readings by time stamp
-----------------------------------------------------------------------------------------------------------------------------------*/
function get_water($apid, $intv, $format)
{
    $db = connect_db();
	if($db)
	{
	    if($intv=="all")
		{
		         $sql = "SELECT `apartment_hotwater`, `apartment_coldwater`, `apartment_timestamp` 
					  FROM `apartment` 
					  WHERE `apartment_id`= $apid 
					  ORDER BY `apartment_timestamp`";					
         } else
		 {
				$sql = "SELECT `apartment_hotwater`, `apartment_coldwater`, `apartment_timestamp` 
					  FROM `apartment` 
					  WHERE `apartment_id`= $apid 
					  AND `apartment_timestamp`<NOW()
					  AND `apartment_timestamp` > NOW()-INTERVAL $intv
					  ORDER BY `apartment_timestamp`";	
		 }			  
	    try{	
			$query= $db->prepare($sql);
			$query->execute();		
			$results=$query->fetchAll( PDO::FETCH_ASSOC);
			if($results)
			{
			    if($format=="json")
				{
				     $iw_data[]="{cols: [{label: 'Date', type: 'string'},{label: 'Hot Water', type: 'number'},{label: 'Cold Water', type: 'number'}],rows:[";
					 foreach($results as $row)
					{
							$test_data[] = "{c:[{v: '".$row['apartment_timestamp']."'},{v: ".$row['apartment_hotwater']."},{v: ".$row['apartment_coldwater']."}]}";
					}
					if($test_data)
					{
					    $cdata=join(",",$test_data);
						array_push($iw_data, $cdata,"]}");
					}						
				} 
				else if($format=="xml")
				{
				     $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><apartment></apartment>');
                     foreach($results as $row)
					 {
						 // Recreate the XML structure adding each reading from the database as a child to a holder that is child to the xml root
							$data=$xml->addChild('readings'); // add a holder for each group of readings
							$data->addChild('hot_water',$row['apartment_hotwater']);	
							$data->addChild('cold_water',$row['apartment_coldwater']);
							$data->addChild('timestamp',$row['apartment_timestamp']);
					 }	
                     $iw_data=$xml->asXML();
				}
			   
			} else 
			{
			   $iw_data = 0;
			}
		
		}
		catch(PDOException $ex)
		{
			echo "An error occured writing to the database".$ex->getMessage();
		}
		return $iw_data;
	}
	
}

/*--------------------------------------------------------------------------------------------------------------------------------
function get_room()
data: requires the apartment id,room id, time interval and format
returns: All the readings for a room by an interval: 1 day, 1 week, 1 month
---------------------------------------------------------------------------------------------------------------------------------*/
function get_room($apid, $roomid, $intv, $format)
{
    $db = connect_db();
	if($db)
	{
	   if($intv == "all")
	   {
	       $sql = "SELECT `room_humidity`, `room_temp`, `room_co2`,`room_timestamp` 
             FROM `room` 
			 WHERE `room_id`=$roomid 
			 AND `apartment_id`=$apid
			 ORDER BY `room_timestamp`";
	   }
	   else
	   {     			 
			 $sql = "SELECT `room_humidity`, `room_temp`, `room_co2`, `room_timestamp` 
				 FROM `room` 
				 WHERE `room_id`= $roomid 
				 AND `apartment_id`=$apid
				 AND `room_timestamp` <NOW()
				 AND `room_timestamp`>NOW()- INTERVAL $intv
				 ORDER BY `room_timestamp`";	
	   }
	    		
	    try{
			$query= $db->prepare($sql);
			$query->execute();		
			$results=$query->fetchAll( PDO::FETCH_ASSOC);
			if($results)
			{
			    if($format=="json")
				{
				     $r_data[]="{cols: [{label: 'Date', type: 'string'},{label: 'Humidity', type: 'number'},{label: 'Temperature', type: 'number'},{label: 'CO2', type: 'number'}],rows:[";
				     foreach($results as $row)
				     {
				    	$test_data[] = "{c:[{v: '".$row['room_timestamp']."'},{v: ".$row['room_humidity']."},{v: ".$row['room_temp']."},{v: ".$row['room_co2']."}]}";
				     }
				     if($test_data)
					 {
					     $cdata=join(",",$test_data);
						 array_push($r_data, $cdata,"]}");
					 }						
				}
				else if($format=="xml")
				{
				      $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><apartment></apartment>');
                       foreach($results as $row)
						{
							$data=$xml->addChild('room');
							$data->addChild('humidity',$row['room_humidity']);
							$data->addChild('temperature',$row['room_temp']);
							$data->addChild('co2level',$row['room_co2']);
							$data->addChild('smoke_detected',$row['room_smoke_det']);
							$data->addChild('timestamp',$row['room_timestamp']);						
						}	
						$r_data=$xml->asXML();
				}
			    	
			} else
			{
			    $r_data = 0;
			}
		}
		catch(PDOException $ex)
		{
			echo "An error occured writing to the database".$ex->getMessage();
		}
		return $r_data;
	}	
}

/*--------------------------------------------------------------------------------------------------------------------------------
function get_ht_room()
data: requires the apartment id , room id, interval and format
returns: the humidity and temperature readings - by interval
---------------------------------------------------------------------------------------------------------------------------------*/
function get_ht_room($apid, $roomid, $intv, $format)
{
    $db = connect_db();
    if($db)
	{
	   if($intv == "all")
	   {
	        $sql = "SELECT `room_humidity`, `room_temp`,`room_timestamp` 
				 FROM `room` 
				 WHERE `room_id`=$roomid 
				 AND `apartment_id`=$apid
				 ORDER BY `room_timestamp`";
	   }
	   else
	   {       			 
			 $sql = "SELECT `room_humidity`, `room_temp`,`room_timestamp` 
				 FROM `room` 
				 WHERE `room_id`= $roomid
				 AND `apartment_id`= $apid
				 AND `room_timestamp` <NOW()
				 AND `room_timestamp`>NOW()- INTERVAL $intv
				 ORDER BY `room_timestamp`";
	   }
	   
	   try{
	        $query= $db->prepare($sql);
			$query->execute();		
			$results=$query->fetchAll( PDO::FETCH_ASSOC);
			if($results)
			{
			    if($format=="json")
				{
				    $htr_data[]="{cols: [{label: 'Date', type: 'string'},{label: 'Humidity', type: 'number'},{label: 'Temperature', type: 'number'}],rows:[";
				    foreach($results as $row)
					{
						 $test_data[] = "{c:[{v: '".$row['room_timestamp']."'},{v: ".$row['room_humidity']."},{v: ".$row['room_temp']."}]}";
					}
					if($test_data)
					{
					      $cdata=join(",",$test_data);
						  array_push($htr_data, $cdata,"]}");
					}							
				}
				else if($format=="xml")
				{
				    $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><apartment></apartment>');	 
					foreach($results as $row)
					{
					    $data=$xml->addChild('room');
						$data->addChild('humidity',$row['room_humidity']);
						$data->addChild('temperature',$row['room_temp']);
						$data->addChild('timestamp',$row['room_timestamp']);						
					}
					$htr_data=$xml->asXML();
				}
			    
			} else
			{
			     $htr_data= 0;
			}
		}
		catch(PDOException $ex)
		{
			echo "An error occured writing to the database".$ex->getMessage();
		}
		return $htr_data;
	}
}

/*--------------------------------------------------------------------------------------------------------------------------------
function get_sco_room()
data:apartment id, room id, interval and format
returns: the CO2 readings and smoke detection - by interval
---------------------------------------------------------------------------------------------------------------------------------*/
function get_sco_room($apid, $roomid, $intv, $format)
{
    $db = connect_db();
	if($db)
	{
	    if($intv=="all")
		{ 
		     $sql = "SELECT `room_co2`,`room_timestamp`
				 FROM `room` 
				 WHERE `room_id`= $roomid 
				 AND `apartment_id`= $apid
				 ORDER BY `room_timestamp`";
		}
		else
		{		     
			 $sql = "SELECT `room_co2`,`room_smoke_det`,`room_timestamp`
				 FROM `room` 
				 WHERE `room_id`= $roomid
				 AND `apartment_id`= $apid
				 AND `room_timestamp` <NOW()
				 AND `room_timestamp`>NOW()- INTERVAL $intv
				 ORDER BY `room_timestamp`";	

		}
	    try{
		    $query= $db->prepare($sql);
		    $query->execute();
			$results=$query->fetchAll( PDO::FETCH_ASSOC);
			if($results)
			{
			    if($format=="json")
				{
				     $r_data[]="{cols: [{label: 'Date', type: 'string'},{label: 'CO2', type: 'number'}],rows:[";
				     foreach($results as $row)
						{
								$test_data[] = "{c:[{v: '".$row['room_timestamp']."'},{v: ".$row['room_co2']."}]}";
						}
					if($test_data)
					{
					    $cdata=join(",",$test_data);
					    array_push($r_data, $cdata,"]}");
					}					   
				}
				else if($format=="xml")
				{
				    $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><apartment></apartment>');	
                    foreach($results as $row)
						{
							$data=$xml->addChild('room');
							$data->addChild('co2level',$row['room_co2']);
							$data->addChild('smoke_detected',$row['room_smoke_det']);
							$data->addChild('timestamp',$row['room_timestamp']);						
						}	
                    $r_data=$xml->asXML();					
				}			    
			} else
			{
			    	$r_data =0;
			}
		}
		catch(PDOException $ex)
		{
			echo "An error occured writing to the database".$ex->getMessage();
		}
		return $r_data;
	}		
}
?>