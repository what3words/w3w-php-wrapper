<?php
require_once "./Geocoder.php";
use What3words\Geocoder\Geocoder;

$api = new Geocoder(getenv('W3W_API_KEY'));
$w3w = $_GET["what3words"];
$result = null;
if (isset($w3w)) {
  $result = $api->convertToCoordinates($w3w);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>what3words</title>
</head>

<body>
  <form action="index.php" method="GET">
    what3words: <input type="text" name="what3words" value="<?php echo $w3w; ?>">
    <input type="submit">
  </form>
  <div id="nearest-place">
    <span>Nearest Place: </span>
    <?php
    if (isset($result)) {
      echo isset($result['nearestPlace']) ? $result['nearestPlace'] : 'unknown';
    }
    ?>
  </div>
  <div id="coordinates">
    <span>Coordinates:</span>
    <?php
    if (isset($result)) {
      $lat = isset($result['coordinates']) ? $result['coordinates']['lat'] : 'unknown';
      $lng = isset($result['coordinates']) ? $result['coordinates']['lng'] : 'unknown';
      echo $lat . ", " . $lng;
    }
    ?>
  </div>

</body>

</html>