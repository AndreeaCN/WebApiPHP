<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Welcome Message from Service Provider</title>
  <meta name="description" content="Description of Services">
  <meta name="author" content="">
</head>
<h1> Welcome Message from Service Provider</h1>
<div>
<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
</div>
<div>
<p>To test the API, please add parameters to the API link: domain-name.com/controller.php? </p>
<li>action = get_apartment | get_room</li>
<li>intv[optional] = 1 day | 1 week | 1 month</li>
<li>apartment_id = 1 | 2</li>
<li>readings = all | eg | hcw</li>
<li>room = apartment_id followed by 00 | 01 | 02 | 03 </li>
<li> values = all | ht | sco </li>
<li> format = json | xml </li>
</div>
<div>
 <p>The order is : </p>
 <li> For Apartment Readings : action | intv | apartment | readings | format </li>
 <li>For Room Readings: action | intv | apartment | room | values | format </li>
</div>
<body>

</body>
</html>