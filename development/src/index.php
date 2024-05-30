<?php
require_once "./Geocoder.php";
use What3words\Geocoder\Geocoder;

$api = new Geocoder(getenv('W3W_API_KEY'));
$what3words = isset($_GET["what3words"]) ? $_GET["what3words"] : null;
$result = null;
if ($what3words) {
  $result = $api->convertToCoordinates($what3words);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>what3words</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      font-size: 1.2rem;
    }

    input {
      font-size: 1rem;
    }

    #invalid-what3words {
      color: red;
      font-weight: bold;
    }

    #nearest-place {
      font-weight: bold;
    }

    #coordinates {
      color: grey;
      font-weight: bold;
    }

    .label {
      font-size: 0.95rem;
      color: black;
      font-weight: normal;
    }
  </style>
</head>

<body>
  <form action="index.php" method="GET">
    what3words: <input type="text" name="what3words" value="<?php echo $what3words; ?>">
    <input type="submit">
  </form>
  <?php
  if ($result && $result['coordinates']) {
    echo '<div id="nearest-place"><span class="label">Nearest Place: </span>' . $result['nearestPlace'] . ', ' . $result['country'] . '</div>';
    echo '<div id="coordinates"><span class="label">Coordinates: </span>' . $result['coordinates']['lat'] . ', ' . $result['coordinates']['lng'] . '</div>';
  }
  if ($what3words && (!$result || !$result['coordinates'])) {
    echo '<div id="invalid-what3words"><span class="label">Error: </span>Invalid what3words</div>';
  }
  ?>
</body>

</html>