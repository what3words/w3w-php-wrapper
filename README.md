# <img src="https://what3words.com/assets/w3w_square_red.png" width="64" height="64" alt="what3words">&nbsp;w3w-php-wrapper

A PHP library to use the [what3words REST API](https://docs.what3words.com/api/v2/).

# Overview

The what3words PHP library gives you programmatic access to convert a 3 word address to coordinates (_forward geocoding_), to convert coordinates to a 3 word address (_reverse geocoding_), to obtain suggestions based on a full or partial 3 word address (_AutoSuggest_) and to determine the currently support 3 word address languages.

## Authentication

To use this library you’ll need a what3words API key, which can be signed up for [here](https://map.what3words.com/register?dev=true).

# Installation

## With Composer

The recommended - and easiest way - to install is via [Composer](https://getcomposer.org/). Require the library in your project's `composer.json` file.

```
$ composer require what3words/geocoder
```

Import the what3words `Geocoder` class.

```
require "vendor/autoload.php";
use What3words\Geocoder\Geocoder;
```

Start geocoding with 3 word addresses.

```
$options = [
    'key' => 'your-key-here'
];
try {
    $geocoder = new Geocoder($options);
    $payload = geocoder->forwardGeocode('index.home.raft');
}
catch (Exception $e) {
    // exception handling code
}
```

## The old fashioned way

Download the library's [latest release](https://github.com/what3words/w3w-php-wrapper/releases) and unpack. Then require the autoloader that will in turn load the library's `Geocoder` class automagically.

```
require "w3w-php-wrapper/autoload.php";
use What3words\Geocoder\Geocoder;
```

Start geocoding with 3 word addresses.

```
$options = [
    'key' => 'your-key-here'
];
try {
    $geocoder = new Geocoder($options);
    $payload = geocoder->forwardGeocode('index.home.raft');
}
catch (Exception $e) {
    // exception handling code
}
```

# Usage

## Errors and exceptions

The library will throw an exception under certain conditions, including:
* Not providing an API key during library instantiation
* Providing invalid format options to a library method

The library will also return a set of [status and error codes](https://docs.what3words.com/api/v2/#errors) that are returned from the what3word REST API.

## Initialisation

```
use What3words\Geocoder\Geocoder;

$options = [
    'key' => 'your-key-here',   // mandatory
    'timeout' => 10             // default: 10 secs
];
$geocoder = new Geocoder($options);
```

## Forward geocoding

```
$params = [
    'lang' => 'en',         // ISO 639-1 2 letter code; default: en
    'display' => 'full',    // full or terse, default: full
    'format' => 'json'      // json, geojson or xml, default: json
];
$threeWordAddr = 'index.home.raft';
$payload = $what3words->forwardGeocode($threeWordAddr, $params);
```

Forward geocodes a 3 word address to a position, expressed as coordinates of latitude and longitude.

The returned payload from the `forwardGeocode` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v2/#forward-result).

## Reverse geocoding

Reverse geocodes coordinates, expressed as latitude and longitude to a 3 word address.

```
$params = [
    'lang' => 'en',         // ISO 639-1 2 letter code; default: en
    'display' => 'full',    // full or terse, default: full
    'format' => 'json'      // json, geojson or xml, default: json
];
$coords = [
    'lat' => 51.521251,
    'lng' => -0.203586
];
$payload = $what3words->reverseGeocode($coords, $params);
```

The returned payload from the `reverseGeocode` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v2/#reverse-result).

## AutoSuggest

Returns a list of 3 word addresses based on user input and other parameters.

This method provides corrections for the following types of input error:
* typing errors
* spelling errors
* misremembered words (e.g. singular vs. plural)
* words in the wrong order

The `autoSuggest` method determines possible corrections to the supplied 3 word address string based on the probability of the input errors listed above and returns a ranked list of suggestions. This method can also take into consideration the geographic proximity of possible corrections to a given location to further improve the suggestions returned.

### Input 3 word address

You will only receive results back if the partial 3 word address string you submit contains the first two words and at least the first character of the third word; otherwise an error message will be returned.

### Clipping and Focus

We provide various `clip` policies to allow you to specify a geographic area that is used to exclude results that are not likely to be relevant to your users. We recommend that you use the `clip` parameter to give a more targeted, shorter set of results to your user. If you know your user’s current location, we also strongly recommend that you use the `focus` to return results which are likely to be more relevant.

In summary, the `clip` policy is used to optionally restrict the list of candidate AutoSuggest results, after which, if `focus` has been supplied, this will be used to rank the results in order of relevancy to the focus.
```

$params = [
    'lang' => 'en',         // ISO 639-1 2 letter code
    'display' => 'full',    // full or terse; default: full
    'format' => 'json',      // json or xml, default: json
    'focus' => [
        'lat' => 51.521251,
        'lng' => -0.203586
    ],
    // Specify one clip policy only
    'clip' => [
        'type' => 'none'
    ],
    'clip' => [
        'type' => 'radius',
        'coords' => [
            'lat' => 51.521251,
            'lng' => -0.203586
        ]
        'distance' => 10
    ],
    'clip' => [
        'type' => 'focus',
        'distance' => 10
    ],
    'clip' => [
        'type' => 'bbox',
        'bbox' => [
            'ne' => [
                'lat' => 54,
                'lng' => 2
            ],
            'sw' => [
                'lat' => 50,
                'lng' => -4
            ]
        ]
    ]
];

$partialAddr = 'index.home.r';
$payload = $what3words->autoSuggest($partialAddr);
```

The returned payload from the `autoSuggest` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v2/#autosuggest-result).

## Get Languages

Retrieves a list of the currently loaded and available 3 word address languages.

```
$params = [
    'format' => 'json'      // json, or xml, default: json
];
$payload = $what3words->languages($coords);
```

The returned payload from the `languages` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v2/#lang-result).

# Revision History

* `v2.0.0` 12/05/16 - Complete rewrite supporting v2. of the what3words REST API
* `v1.0.5` 23/11/15 - Add composer support. Make API key a constructor parameter. Minor code tweaks and doc updates
* `v1.0.4` 6/3/15 - Normalise class name across what3words wrappers
* `v1.0.3` 18/1/15 - Remove hard-coded API key
* `v1.0.2` 7/1/15 - More `README.md` updates
* `v1.0.1` 22/12/14 - Documentation updates to `README.md`
* `v1.0.0` 8/12/14 - Initial release
