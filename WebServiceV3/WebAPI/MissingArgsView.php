<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Missing Args</title>
  <meta name="description" content="Description of Services">
  <meta name="author" content="">
</head>
<h1>Missing or Incorrect arguments</h1>
<div>
<p>To test the API, please add the following parameters to the API link: </p>
<li>action = get_apartment | get_room</li>
<li>intv = all| 1 day | 1 week | 1 month</li>
<li>apartment_id = 1 | 2</li>
<li>readings = all | eg | hcw</li>
<li>room = apartment_id followed by 00 | 01 | 02 | 03 </li>
<li> values = all | ht | sco </li>
</div>
<div>
 <p>The order is : </p>
 <li> For Apartment Readings : action | intv | apartment | readings </li>
 <li>For Room Readings: action | intv | apartment | room | values </li>
</div>
<body>

</body>
</html>