# <img valign='top' src="https://what3words.com/assets/images/w3w_square_red.png" width="64" height="64" alt="what3words">&nbsp;w3w-php-wrapper

A PHP library to use the [what3words REST API](https://docs.what3words.com/api/v3/).

# Overview

The what3words PHP wrapper gives you programmatic access to 

* convert a 3 word address to coordinates 
* convert coordinates to a 3 word address
* autosuggest functionality which takes a slightly incorrect 3 word address, and suggests a list of valid 3 word addresses
* obtain a section of the 3m x 3m what3words grid for a bounding box.
* determine the currently support 3 word address languages.

## Authentication

To use this library you’ll need a what3words API key, which can be signed up for [here](https://accounts.what3words.com/register?dev=true).

# Installation

#### Composer 

To use this library in your project, place the following line in your composer.json:

```php
"what3words/w3w-php-wrapper": "3.*"
```

#### Manual

To manually include this file, place [Geocoder.php](https://github.com/what3words/w3w-php-wrapper/blob/master/src/Geocoder.php) into your project, and <code>require_once("Geocoder.php")</code>

### Initialise

```php
use What3words\Geocoder\Geocoder;
use What3words\Geocoder\AutoSuggestOption;

$api = new Geocoder("<Secret API Key>");
```


## Convert To Coordinates
Convert a 3 word address to a position, expressed as coordinates of latitude and longitude.

This function takes the words parameter as a string of 3 words `'table.book.chair'`

The returned payload from the `convertToCoordinates` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v3/#convert-to-coordinates).

#### Code Example
```php
$result = $api->convertToCoordinates("index.home.raft");
print_r($result);
```

## Convert To 3 Word Address

Convert coordinates, expressed as latitude and longitude to a 3 word address.

The returned payload from the `convertTo3wa` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v3/#convert-to-3wa).

#### Code Example
```php
$result = $api->convertTo3wa(51.432393,-0.348023);
print_r($result);
```

## Available Languages

This function returns the currently supported languages.  It will return the two letter code ([ISO 639](https://en.wikipedia.org/wiki/ISO_639)), and the name of the language both in that language and in English.

The returned payload from the `convertTo3wa` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v3/#available-languages)

#### Code Example
```php
$result = $api->availableLanguages();
print_r($result);
```

## Grid Section

Returns a section of the 3m x 3m what3words grid for a given area. The requested box must not exceed 4km from corner to corner, or a BadBoundingBoxTooBig error will be returned. Latitudes must be >= -90 and <= 90, but longitudes are allowed to wrap around 180. To specify a bounding-box that crosses the anti-meridian, use longitude greater than 180. Example value: 50.0, 179.995, 50.01, 180.0005. 

The returned payload from the `gridSection` function  is described in the [what3words REST API documentation](https://docs.what3words.com/api/v3/#grid-section)

#### Code Example
```php
$result = $api->gridSection(39.903795, 116.384550, 39.902718, 116.383122);
print_r($result);
```

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

We provide various `clip` policies to allow you to specify a geographic area that is used to exclude results that are not likely to be relevant to your users. We recommend that you use the clipping to give a more targeted, shorter set of results to your user. If you know your user’s current location, we also strongly recommend that you use the `focus` to return results which are likely to be more relevant.

In summary, the clip policy is used to optionally restrict the list of candidate AutoSuggest results, after which, if focus has been supplied, this will be used to rank the results in order of relevancy to the focus.

The returned payload from the `autosuggest` method is described in the [what3words REST API documentation](https://docs.what3words.com/api/v2/#autosuggest-result).

### Usage

The first parameter is the partial three words, or voice data.  It is followed by an array of AutoSuggestOption objects.  The last parameter is the completion block.  The AutoSuggestOption objects in the array are created using static convenience functions of the form: 

```php
AutoSuggestOption::fallbackLanguage("de"); 
AutoSuggestOption::focus($latitude, $longitude);
```

#### Code Example
```php
$result = $api->autosuggest("fun.with.code");
print_r($result);
```

#### Code Example Two
Focus on (51.4243877,-0.34745) and look for 6 results.

```php
$result = $api->autosuggest("fun.with.code", [AutoSuggestOption::focus(51.4243877,-0.34745), AutoSuggestOption::numberResults(6)]);
print_r($result);
```

## Handling Errors

All the functions will return an stdClass Object containing the requested data, or on failure, they will return `false`.  If `false` is returned, call `get_error()`, and it will return an associative array containing a `code` value and a `message` value.

```php
print_r($api->getError());
```

Error values are listed in the [what3words REST API documentation](https://docs.what3words.com/api/v3/#error-handling). 

