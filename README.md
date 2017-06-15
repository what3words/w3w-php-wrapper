<img src="https://what3words.com/assets/images/w3w_square_red.png" width="32" height="32" alt="what3words">&nbsp;w3w-php-wrapper [![Build Status](https://travis-ci.org/what3words/w3w-php-wrapper.svg?branch=master)](https://travis-ci.org/what3words/w3w-php-wrapper)
================================================================================================================================

A PHP library to use the [what3words REST API](https://docs.what3words.com/api/v2/).

Overview
========

The what3words PHP library gives you programmatic access to convert a 3 word address to coordinates (*forward geocoding*), to convert coordinates to a 3 word address (*reverse geocoding*), to obtain suggestions based on a full or partial 3 word address (*AutoSuggest*) and to determine the currently support 3 word address languages.

Authentication
--------------

To use this library you’ll need a what3words API key, which can be signed up for [here](https://map.what3words.com/register?dev=true).

Installation
============

With Composer
-------------

The recommended - and easiest way - to install is via [Composer](https://getcomposer.org/). Require the library in your project's `composer.json` file.

```shell
$ composer require what3words/w3w-php-wrapper
```

Import the what3words `Geocoder` class.

```php
require "vendor/autoload.php";
```

Start geocoding with 3 word addresses.

```php
$options = [
    'key' => 'your-key-here'
];
try {
    $geocoder = new \What3words\Geocoder\Geocoder($options);
    $payload = $geocoder->forwardGeocode('index.home.raft');
}
catch (Exception $e) {
    // exception handling code
}
```

The old fashioned way
---------------------

Download the library's [latest release](https://github.com/what3words/w3w-php-wrapper/releases) and unpack. Then require the autoloader that will in turn load the library's `Geocoder` class automagically.

```php
require "w3w-php-wrapper/autoload.php";
use What3words\Geocoder\Geocoder;
```

Start geocoding with 3 word addresses.

```php
$options = [
    'key' => 'your-key-here'
];
try {
    $geocoder = new Geocoder($options);
    $payload = $geocoder->forwardGeocode('index.home.raft');
}
catch (Exception $e) {
    // exception handling code
}
```

Usage
=====

Errors and exceptions
---------------------

The library will throw an exception under certain conditions, including:* Not providing an API key during library instantiation* Providing invalid format options to a library method

The library will also return a set of [status and error codes](https://docs.what3words.com/api/v2/#errors) that are returned from the what3word REST API.

Initialisation
--------------

```php
use What3words\Geocoder\Geocoder;

$options = [
    'key' => 'your-key-here',   // mandatory
    'timeout' => 10             // default: 10 secs
];
$geocoder = new Geocoder($options);
```

Forward geocoding
-----------------

```php
$params = [
    'lang' => 'en',         // ISO 639-1 2 letter code; default: en
    'display' => 'full',    // full or terse, default: full
    'format' => 'json'      // json, geojson or xml, default: json
];
$threeWordAddr = 'index.home.raft';
$payload = $geocoder->forwardGeocode($threeWordAddr, $params);
```

Forward geocodes a 3 word address to a position, expressed as coordinates of latitude and longitude.

The returned payload from the `forwardGeocode` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v2/#forward-result).

Reverse geocoding
-----------------

Reverse geocodes coordinates, expressed as latitude and longitude to a 3 word address.

```php
$params = [
    'lang' => 'en',         // ISO 639-1 2 letter code; default: en
    'display' => 'full',    // full or terse, default: full
    'format' => 'json'      // json, geojson or xml, default: json
];
$coords = [
    'lat' => 51.521251,
    'lng' => -0.203586
];
$payload = $geocoder->reverseGeocode($coords, $params);
```

The returned payload from the `reverseGeocode` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v2/#reverse-result).

AutoSuggest
-----------

Returns a list of 3 word addresses based on user input and other parameters.

This method provides corrections for the following types of input error:* typing errors* spelling errors* misremembered words (e.g. singular vs. plural)* words in the wrong order

The `autoSuggest` method determines possible corrections to the supplied 3 word address string based on the probability of the input errors listed above and returns a ranked list of suggestions. This method can also take into consideration the geographic proximity of possible corrections to a given location to further improve the suggestions returned.

### Single and Multilingual Variants
AutoSuggest is provided via 2 variant resources; single language and multilingual.

The single language autosuggest resource requires a language to be specified. The input full or partial 3 word address will be interpreted as being in the specified language and all suggestions will be in this language. We recommend that you set this according to the language of your user interface, or the browser/device language of your user. If your software or app displays 3 word addresses to users (in addition to accepting 3 words as a search/input) then we recommend you set the language parameter for this resource to the same language that 3 word addresses are displayed to your users.

The multilingual `autoSuggestML` resource can accept an optional language. If specified, this will ensure that the `autoSuggestML` resource will look for suggestions in this language, in addition to any other languages that yield relevant suggestions.

### Input 3 word address

You will only receive results back if the partial 3 word address string you submit contains the first two words and at least the first character of the third word; otherwise an error message will be returned.

### Clipping and Focus

We provide various `clip` policies to allow you to specify a geographic area that is used to exclude results that are not likely to be relevant to your users. We recommend that you use the `clip` parameter to give a more targeted, shorter set of results to your user. If you know your user’s current location, we also strongly recommend that you use the `focus` to return results which are likely to be more relevant.

In summary, the `clip` policy is used to optionally restrict the list of candidate AutoSuggest results, after which, if `focus` has been supplied, this will be used to rank the results in order of relevancy to the focus.

```php
$params = [
    'lang' => 'en',         // ISO 639-1 2 letter code
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
$payload = $geocoder->autoSuggest($partialAddr, $params);

$payload = $geocoder->autoSuggestML($partialAddr, $params);
```

The returned payload from the `autoSuggest` and `autoSuggestML` methods are described in the [what3words REST API documentation](https://docs.what3words.com/api/v2/#autosuggest-result).

Standardblend
-------------

Returns a blend of the three most relevant 3 word address candidates for a given location, based on a full or partial 3 word address.

The specified 3 word address may either be a full 3 word address or a partial 3 word address containing the first 2 words in full and at least 1 character of the 3rd word. The standardblend resource provides the search logic that powers the search box on map.what3words.com and in the what3words mobile apps.

*Single and Multilingual Variants*

AutoSuggest is provided via 2 variant resources; single language and multilingual.

The single language `standardblend` method  requires a language to be specified.

The multilingual `standardblendML`  method  requires a language to be specified. This will ensure that the standardblend-ml resource will look for suggestions in this language, in addition to any other languages that yield relevant suggestions.

```php
$params = [
    'lang' => 'en',         // ISO 639-1 2 letter code
    'format' => 'json',      // json or xml, default: json
    'focus' => [
        'lat' => 51.521251,
        'lng' => -0.203586
    ]
];

$partialAddr = 'index.home.r';
$payload = $geocoder->standardblend($partialAddr, $params);

$payload = $geocoder->standardblendML($partialAddr, $params);
```

The returned payload from the `standardblend` and `standardblendML` methods are described in the [what3words REST API documentation](https://docs.what3words.com/api/v2/#standardblend-result).


Grid
----

Returns a section of the 3m x 3m what3words grid for a given area.

```
$bbox= [                      // Bounding box, specified by the northeast
    'ne' => [                 // and southwest corner coordinates,
        'lat' => 52.208867,   // for which the grid should be returned.
        'lng' => 0.117540
    ],
    'sw' => [
        'lat' => 52.207988,
        'lng' => 0.116126
    ]
];
$params = [
    'format' => 'json'        // json, or xml, default: json
];
$payload = $geocoder->grid($bbox, $param);


Get Languages
-------------

Retrieves a list of the currently loaded and available 3 word address languages.

```
$params = [
    'format' => 'json'      // json, or xml, default: json
];
$payload = $geocoder->languages($params);
```

The returned payload from the `languages` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v2/#lang-result).

Issues
======

Find a bug or want to request a new feature? Please let us know by submitting an issue.

Contributing
============

Anyone and everyone is welcome to contribute.

1. Fork it (http://github.com/what3words/w3w-php-wrapper and click "Fork")
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

Revision History
================

- `v2.2.0` 22/05/17 - Add grid method
- `v2.1.0` 28/03/17 - Added multilingual version of `autosuggest` and `standardblend`
-	`v2.0.2` 15/02/17 - Remove manual autoloader in favour of Composer's
-	`v2.0.1` 05/09/16 - Updated README with correct composer package name. Added configuration options to override defaults
-	`v2.0.0` 12/05/16 - Complete rewrite supporting v2. of the what3words REST API
-	`v1.0.5` 23/11/15 - Add composer support. Make API key a constructor parameter. Minor code tweaks and doc updates
-	`v1.0.4` 6/3/15 - Normalise class name across what3words wrappers
-	`v1.0.3` 18/1/15 - Remove hard-coded API key
-	`v1.0.2` 7/1/15 - More `README.md` updates
-	`v1.0.1` 22/12/14 - Documentation updates to `README.md`
-	`v1.0.0` 8/12/14 - Initial release

Licensing
=========

The MIT License (MIT)

A copy of the license is available in the repository's [license](LICENSE) file.
