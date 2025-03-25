<?php

/**
 * w3w-php-wrapper - A PHP library to use the what3words RESTful API
 *
 * @author Gary Gale <gary@what3words.com>
 * @author Dave Duprey <support@what3words.com>
 * @author Frederick lee <support@what3words.com>
 * @copyright 2016, 2017, 2024 what3words Ltd
 * @link http://developer.what3words.com
 * @license MIT
 * @version 3.5.1
 * @package What3words\Geocoder
 */

namespace What3words\Geocoder;

class Geocoder
{
  private $version = "3.5.1";  // if changing this, remember to change the comment block at the top, and match everything with the git tag
  private $apiKey = "";
  private $error = [];
  private $baseUrl = "https://api.what3words.com/v3/";
  private $wrapper = "what3words-PHP/x.x.x (PHP x.x.x; OS x.x.x)";
  private $referer = null;
  private $headers = [];

  // To construct this you need API key.  You can get one here: https://accounts.what3words.com/en/register/
  // - parameter apiKey: What3Words api key
  // - parameter options: Geocoder options
  public function __construct($apiKey, GeocoderOptions $options = null)
  {
    $this->apiKey = $apiKey;
    $this->wrapper = "what3words-PHP/" . $this->version . " (PHP " . phpversion() . "; " . php_uname("s") . " " . php_uname("r") . ")";
    if ($options) {
      $this->headers = $options->getHeaders();
      $this->referer = $options->getReferer() ?: $this->referer;
      $this->baseUrl = $options->getBaseUrl() ?: $this->baseUrl;
    }
  }


  // Call this to see the last error.  Errors are indicated when any call return 'false'
  public function getError()
  {
    return $this->error;
  }

  // Returns a three word address from a latitude and longitude
  // - parameters latitude, longitude: coordinates of the place in question
  // - parameter language: A supported 3 word address language as an ISO 639-1 2 letter code. Defaults to en
  // - parameter format: Return data format type; can be one of json (the default) or geojson
  public function convertTo3wa($latitude, $longitude, $language = "en", $format = "json")
  {
    return $this->performRequest("convert-to-3wa", ["coordinates" => "$latitude,$longitude", "language" => $language, "format" => $format]);
  }

  // Convert a 3 word address to a latitude and longitude.
  // - parameter words: A 3 word address as a string
  // - parameter format: Return data format type; can be one of json (the default) or geojson
  public function convertToCoordinates($words, $format = "json")
  {
    return $this->performRequest("convert-to-coordinates", ["words" => $words, "format" => $format]);
  }

  // Returns a section of the 3m x 3m what3words grid for a given area.
  // - parameters south_lat, west_lng, north_lat, east_lng, for which the grid should be returned. The requested box must not exceed 4km from corner to corner, or a BadBoundingBoxTooBig error will be returned. Latitudes must be >= -90 and <= 90, but longitudes are allowed to wrap around 180. To specify a bounding-box that crosses the anti-meridian, use longitude greater than 180. Example value: "50.0,179.995,50.01,180.0005" .
  // - parameter format: Return data format type; can be one of json (the default) or geojson Example value:format=Format.json
  public function gridSection($south_lat, $west_lng, $north_lat, $east_lng, $format = "json")
  {
    return $this->performRequest("grid-section", ["bounding-box" => "$south_lat,$west_lng,$north_lat,$east_lng", "format" => $format]);
  }

  // Retrieves a list all available 3 word address languages, including the ISO 639-1 2 letter code, english name and native name.
  public function availableLanguages()
  {
    return $this->performRequest("available-languages", []);
  }

  // AutoSuggest can take a slightly incorrect 3 word address, and suggest a list of valid 3 word addresses.
  // - parameter input: The full or partial 3 word address to obtain suggestions for. At minimum this must be the first two complete words plus at least one character from the third word.
  // - options are taken as an array of arrays, each subarray made using the static helper functions in AutoSuggestOption.  Eg:
  //      -  autosuggest(input: "filled.count.soap", options: [AutoSuggestOption::fallback_language("de"), AutoSuggestOption::clip_to_country("GB") ]);
  //
  // - option AutoSuggestOption::number_results(): The number of AutoSuggest results to return. A maximum of 100 results can be specified, if a number greater than this is requested, this will be truncated to the maximum. The default is 3
  // - option AutoSuggestOption::focus(): This is a location, specified as a latitude (often where the user making the query is). If specified, the results will be weighted to give preference to those near the focus. For convenience, longitude is allowed to wrap around the 180 line, so 361 is equivalent to 1.
  // - option AutoSuggestOption::number_focus_results(): Specifies the number of results (must be <= n-results) within the results set which will have a focus. Defaults to n-results. This allows you to run autosuggest with a mix of focussed and unfocussed results, to give you a "blend" of the two. This is exactly what the old V2 standarblend did, and standardblend behaviour can easily be replicated by passing n-focus-results=1, which will return just one focussed result and the rest unfocussed.
  // - option AutoSuggestOption::bounding_country(): Restricts autosuggest to only return results inside the countries specified by comma-separated list of uppercase ISO 3166-1 alpha-2 country codes (for example, to restrict to Belgium and the UK, use clip-to-country=GB,BE). Clip-to-country will also accept lowercase country codes. Entries must be two a-z letters. WARNING: If the two-letter code does not correspond to a country, there is no error: API simply returns no results. eg: "NZ,AU"
  // - option AutoSuggestOption::bounding_box(south_lat:Double, west_lng:Double, north_lat: Double, east_lng:Double): Restrict autosuggest results to a bounding box, specified by coordinates. Such as south_lat,west_lng,north_lat,east_lng, where: south_lat <= north_lat west_lng <= east_lng In other words, latitudes and longitudes should be specified order of increasing size. Lng is allowed to wrap, so that you can specify bounding boxes which cross the ante-meridian: -4,178.2,22,195.4 Example value: "51.521,-0.343,52.6,2.3324"
  // - option AutoSuggestOption::bounding_circle(lat:Double, lng:Double, kilometres:Double): Restrict autosuggest results to a circle, specified by lat,lng,kilometres. For convenience, longitude is allowed to wrap around 180 degrees. For example 181 is equivalent to -179. Example value: "51.521,-0.343,142"
  // - option AutoSuggestOption::bounding_polygon(array(lat,lng, lat,lng, ...)): Restrict autosuggest results to a polygon, specified by a comma-separated list of lat,lng pairs. The polygon should be closed, i.e. the first element should be repeated as the last element; also the list should contain at least 4 entries. The API is currently limited to accepting up to 25 pairs. Example value: "51.521,-0.343,52.6,2.3324,54.234,8.343,51.521,-0.343"
  // - option AutoSuggestOption::input_type(): For power users, used to specify voice input mode. Can be text (default), vocon-hybrid, nmdp-asr or generic-voice. See voice recognition section for more details.
  // - option AutoSuggestOption::fallback_language(): For normal text input, specifies a fallback language, which will help guide AutoSuggest if the input is particularly messy. If specified, this parameter must be a supported 3 word address language as an ISO 639-1 2 letter code. For voice input (see voice section), language must always be specified.
  public function autosuggest($input, $options = [])
  {
    $parameters = ["input" => $input];

    foreach ($options as $option) {
      $parameters = array_merge($parameters, $option);
    }

    return $this->performRequest("autosuggest", $parameters);
  }

  // Determines if the string passed in is the form of a three word address.
  // This does not validate whether it is a real address as it returns 1 for x.x.x
  public function isPossible3wa($input)
  {
    $regex = "/^\/*(?:[^0-9`~!@#$%^&*()+\-_=[{\]}\\|'<,.>?\/\";:£§º©®\s]+[.｡。･・︒។։။۔።।][^0-9`~!@#$%^&*()+\-_=[{\]}\\|'<,.>?\/\";:£§º©®\s]+[.｡。･・︒។։။۔።।][^0-9`~!@#$%^&*()+\-_=[{\]}\\|'<,.>?\/\";:£§º©®\s]+|'<,.>?\/\";:£§º©®\s]+[.｡。･・︒។։။۔።।][^0-9`~!@#$%^&*()+\-_=[{\]}\\|'<,.>?\/\";:£§º©®\s]+|[^0-9`~!@#$%^&*()+\-_=[{\]}\\|'<,.>?\/\";:£§º©®\s]+([\x{0020}\x{00A0}][^0-9`~!@#$%^&*()+\-_=[{\]}\\|'<,.>?\/\";:£§º©®\s]+){1,3}[.｡。･・︒។։။۔።।][^0-9`~!@#$%^&*()+\-_=[{\]}\\|'<,.>?\/\";:£§º©®\s]+([\x{0020}\x{00A0}][^0-9`~!@#$%^&*()+\-_=[{\]}\\|'<,.>?\/\";:£§º©®\s]+){1,3}[.｡。･・︒។։။۔።।][^0-9`~!@#$%^&*()+\-_=[{\]}\\|'<,.>?\/\";:£§º©®\s]+([\x{0020}\x{00A0}][^0-9`~!@#$%^&*()+\-_=[{\]}\\|'<,.>?\/\";:£§º©®\s]+){1,3})$/";
    return preg_match($regex, $input);
  }

  // Searches the string passed in for all substrings in the form of a three word address.
  // This does not validate whether it is a real address as it will return Array([0] => x.x.x) as a result
  public function findPossible3wa($input)
  {
    $regex = "/[^0-9`~!@#$%^&*()+\-_=[{\]}\|'<,.>?\/\";:£§º©®\s]+[.｡。･・︒។։။۔።।][^0-9`~!@#$%^&*()+\-_=[{\]}\|'<,.>?\/\";:£§º©®\s]+[.｡。･・︒។։။۔።।][^0-9`~!@#$%^&*()+\-_=[{\]}\|'<,.>?\/\";:£§º©®\s]+/";
    preg_match_all($regex, $input, $matches);
    if (count($matches) === 1) {
      return $matches[0];
    }
    return [];
  }

  // Determines if the string passed in is a real three word address.
  // It calls the API to verify it refers to an actual place on earth.
  // Returns 1 if valid, 0 if not
  public function isValid3wa($input)
  {
    switch ($this->isPossible3wa($input)) {
      case 1:
        $result = $this->autosuggest($input, [AutoSuggestOption::numberResults(1)]);
        if (count($result["suggestions"]) == 1 && $result["suggestions"][0]["words"] == $input) {
          return 1;
        }
        break;
      default:
        return 0;
    }
  }

  ////////////////////////////////////////////////////////////////

  // Prepare the call to the API server
  private function performRequest($command, $parameters)
  {
    // make an array out of the dictionary so that each element is a key value pair glued together with an '=', and urlencode the parameters
    $param_array = [];
    foreach ($parameters as $key => $value) {
      $param_array[] = "$key=" . urlencode($value);
    }

    // glue the array together unto a string with elements connected with '&'
    $params = implode("&", $param_array);

    // put the whole URL together now
    $url = "{$this->baseUrl}{$command}?{$params}";

    // call the server
    $data = $this->call($url);
    if (isset($data["error"])) {
      $this->error["code"] = $data["error"]["code"];
      $this->error["message"] = $data["error"]["message"];
      $data = false;
    }

    return $data;
  }


  // Make the call to the API server
  private function call($url)
  {
    $handle = curl_init();

    $headers = array_merge(["X-W3W-Wrapper: {$this->wrapper}", "X-API-Key: {$this->apiKey}"], $this->headers);
    // set the options
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_ENCODING, "");
    curl_setopt($handle, CURLOPT_MAXREDIRS, 10);
    curl_setopt($handle, CURLOPT_TIMEOUT, 30);
    if (defined('CURL_HTTP_VERSION_2_0')) {
      curl_setopt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
    } else {
      curl_setopt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    }
    curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "GET");
    if (!empty($this->referer)) {
      curl_setopt($handle, CURLOPT_REFERER, $this->referer);
    }
    // make the call
    $output = curl_exec($handle);
    if (!$output) {
      $this->error["code"] = "BadConnection";
      $this->error["message"] = curl_error($handle);
    }
    curl_close($handle);
    $json = json_decode($output, true);
    return $json ?: false;
  }
}

class GeocoderOptions
{
  private static $instance = null;
  private $baseUrl = null;
  private $referer = null;
  private $headers = [];

  private function __construct()
  {
  }
  private static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }
  public static function host($host)
  {
    $instance = self::getInstance();
    $instance->host = $host;
    return $instance;
  }
  public static function referer($referer)
  {
    $instance = self::getInstance();
    $instance->referer = $referer;
    return $instance;
  }
  public static function headers(array $headers)
  {
    $instance = self::getInstance();
    $instance->headers = $headers;
    return $instance;
  }
  public function getBaseUrl()
  {
    return $this->baseUrl;
  }
  public function getReferer()
  {
    return $this->referer;
  }
  public function getHeaders()
  {
    return $this->headers;
  }
}

// These are static helper functions that creates options (as array) to be passed into autosuggest
class AutoSuggestOption
{
  public static function fallbackLanguage($language)
  {
    return ["language" => $language];
  }

  public static function numberResults($number_of_results)
  {
    return ["n-results" => $number_of_results];
  }

  public static function focus($latitude, $longitude)
  {
    return ["focus" => "$latitude,$longitude"];
  }

  public static function numberFocusResults($number_focus_results)
  {
    return ["n-focus-results" => $number_focus_results];
  }

  public static function inputType($input_type)
  {
    return ["input-type" => $input_type];
  }

  public static function preferLand($land)
  {
    if ($land) {
      return ["prefer-land" => "true"];
    } else {
      return ["prefer-land" => "false"];
    }
  }

  public static function clipToCountry($country)
  {
    return ["clip-to-country" => $country];
  }

  public static function clipToCircle($latitude, $longitude, $radius)
  {
    return ["clip-to-circle" => "$latitude,$longitude,$radius"];
  }

  public static function clipToBoundingBox($south_lat, $west_lng, $north_lat, $east_lng)
  {
    return ["clip-to-bounding-box" => "$south_lat,$west_lng,$north_lat,$east_lng"];
  }

  public static function clipToPolygon($points = [])
  {
    return ["clip-to-polygon" => implode(",", $points)];
  }

}


