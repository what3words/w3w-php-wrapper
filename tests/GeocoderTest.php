<?php

/**
 * w3w-php-wrapper - A PHP library to use the what3words RESTful API
 *
 * @author Gary Gale <gary@what3words.com>
 * @copyright 2016, 2017 what3words Ltd
 * @link http://developer.what3words.com
 * @license MIT
 * @version 2.2.0
 * @package What3words\Geocoder
 */

namespace What3words\Geocoder\Test;

use What3words\Geocoder\Geocoder;

class GeocoderTest extends \PHPUnit_Framework_TestCase
{
    protected $geocoder;

    protected function setUp()
    {
        $options = [
            'key' => API_KEY
        ];
        $this->geocoder = new Geocoder($options);
    }

    public function testBuildGeocoder()
    {
        $this->assertObjectHasAttribute('key', $this->geocoder);
        $this->assertObjectHasAttribute('timeout', $this->geocoder);
    }

    public function testInvalidKey()
    {
        $options = [
            'key' => 'NOTWORKING'
        ];
        $geocoder = new Geocoder($options);
        $threeWordAddr = 'index.home.raft';
        $payload = $geocoder->forwardGeocode($threeWordAddr);
        $json = json_decode($payload, true);

        $expected = [
            'code' => 2
        ];
        $this->assertArraySubset($expected, $json);
    }

    public function testForwardGeocodeWithJson()
    {
        $threeWordAddr = 'index.home.raft';
        $payload = $this->geocoder->forwardGeocode($threeWordAddr);

        $json = json_decode($payload, true);
        $expected = [
            'status' => [
                'status' => 200,
                'reason' => 'OK'
            ],
            'words' => 'index.home.raft',
            'geometry' => [
                'lat' => 51.521251,
                'lng' => -0.203586
            ]
        ];
        $this->assertArraySubset($expected, $json);
    }

    public function testReverseGeocodeWithJson()
    {
        $coords = [
            'lat' => 51.521251,
            'lng' => -0.203586
        ];
        $payload = $this->geocoder->reverseGeocode($coords);
        $json = json_decode($payload, true);
        $expected = [
            'status' => [
                'status' => 200,
                'reason' => 'OK'
            ],
            'words' => 'index.home.raft',
            'geometry' => [
                'lat' => 51.521251,
                'lng' => -0.203586
            ]
        ];
        $this->assertArraySubset($expected, $json);
    }

    public function testAutosuggest()
    {
        $suggest = 'index.home.raft';
        $payload = $this->geocoder->autoSuggest($suggest);
        $json = json_decode($payload, true);
        $expected = [
            'status' => [
                'status' => 200,
                'reason' => 'OK'
            ],
            'suggestions' => [[
              'words' => 'index.home.raft',
              'geometry' => [
                  'lat' => 51.521251,
                  'lng' => -0.203586
              ]]
            ]
        ];
        $this->assertArraySubset($expected, $json);
    }

    public function testAutosuggest_ML()
    {
        $suggest = 'index.home.raft';
        $payload = $this->geocoder->autoSuggestML($suggest);
        $json = json_decode($payload, true);
        $expected = [
            'status' => [
                'status' => 200,
                'reason' => 'OK'
            ],
            'suggestions' => [[
              'words' => 'index.home.raft',
              'geometry' => [
                  'lat' => 51.521251,
                  'lng' => -0.203586
              ]]
            ]
        ];
        $this->assertArraySubset($expected, $json);
    }

    public function testStandardBlend()
    {
        $suggest = 'index.home.raft';
        $payload = $this->geocoder->standardblend($suggest);
        $json = json_decode($payload, true);
        $expected = [
            'status' => [
                'status' => 200,
                'reason' => 'OK'
            ],
            'suggestions' => [[
              'words' => 'index.home.raft',
              'geometry' => [
                  'lat' => 51.521251,
                  'lng' => -0.203586
              ]]
            ]
        ];
        $this->assertArraySubset($expected, $json);
    }

    public function testStandardBlend_ML()
    {
        $suggest = 'index.home.raft';
        $payload = $this->geocoder->standardblendML($suggest);
        $json = json_decode($payload, true);
        $expected = [
            'status' => [
                'status' => 200,
                'reason' => 'OK'
            ],
            'suggestions' => [[
              'words' => 'index.home.raft',
              'geometry' => [
                  'lat' => 51.521251,
                  'lng' => -0.203586
              ]]
            ]
        ];
        $this->assertArraySubset($expected, $json);
    }

    public function testGrid()
    {
        $bbox = [                   // Bounding box, specified by the northeast
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
            'format' => 'json'
        ];
        $payload = $this->geocoder->grid($bbox, $params);
        $json = json_decode($payload, true);
        // print_r($json);
        $expected = [
            'status' => [
                'status' => 200,
                'reason' => 'OK'
              ],
        ];
        $this->assertArraySubset($expected, $json);
    }

    public function testLanguages()
    {
        $params = [
            'format' => 'json'
        ];
        $payload = $this->geocoder->languages($params);
        $json = json_decode($payload, true);
      // print_r($json);
      $expected = [
          'status' => [
              'status' => 200,
              'reason' => 'OK'
          ],
          // 'languages' => [[
          //     'code' => 'en',
          //     'name' => 'English',
          //     'native_name' => 'English'
          // ]]
        ];
        $this->assertArraySubset($expected, $json);
    }
}
