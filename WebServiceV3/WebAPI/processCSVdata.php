<?php 
			  // adapted example from php.net manual with example from class
			 //http://php.net/manual/en/function.fopen.php
			 
			 // save the file into a stream
			 $handle = fopen("data/smartBuildingApartment.csv", "r");
			 $r_handle=fopen("data/smartBuildingRooms.csv", "r");// handle for the opening of the room csv file
			 // call out the database connection file
			 include_once("model.inc");
			 // apartment
             if ($handle) {
				  // get the contents of the file with a file pointer, 4096 refers to the number of lines to read from the file
                 while (($buffer = fgets($handle, 4096)) !== false) {
					     $i=0;     
                         // Place each line read from the file into an array, and explode it out into a list of variables						 
				         $out[$i] = array($buffer);
			        	 list($apartmentId, $apartmentName, $electricity, $gas, $hotWater, $coldWater) = explode(",",$out[$i][0]);
						 echo "Inserting CSV data in Apartment....";
						 record_apartment($apartmentId, $apartmentName, $electricity, $gas, $hotWater, $coldWater);
						 echo "<br/>";
										
						 // increment for each line
				         $i++;
                      }
                    if (!feof($handle)) {
                          echo "Error: unexpected fgets() fail\n";
                         }
                          fclose($handle);
              } 
			  // room readings			
			  if ($r_handle) {
                 while (($r_buffer = fgets($r_handle, 4096)) !== false) {
					     $j=0;                         
				         $out[$j] = array($r_buffer);
						 list($roomID, $roomType,$rhumidity,$rTemperature, $rco2level, $smoke, $apartmentId) = explode(",",$out[$j][0]);
						 echo "Record room readings ...";
						 record_room($roomID, $roomType,$rhumidity,$rTemperature, $rco2level, $smoke, $apartmentId);
						 echo "<br/>";
						  
						  // increment for each line
				         $i++;
                      }
                    if (!feof($r_handle)) {
                          echo "Error: unexpected fgets() fail\n";
                         }
                          fclose($r_handle);
              } 
			  
			 ?>
	</body>	     
</html>