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

namespace What3words\Geocoder;

use What3words\Geocoder\AbstractWrapper;

class Geocoder extends AbstractWrapper {
    const USER_AGENT = 'what3words PHP wrapper v2.1.0';
    const AUTH_HEADER = 'X-Api-Key';

    const METHOD_FORWARD = 'forward';
    const METHOD_REVERSE = 'reverse';
    const METHOD_AUTOSUGGEST = 'autosuggest';
    const METHOD_AUTOSUGGEST_ML = 'autosuggest-ml';
    const METHOD_STANDARDBLEND = 'standardblend';
    const METHOD_STANDARDBLEND_ML = 'standardblend-ml';
    const METHOD_GRID = 'grid';
    const METHOD_LANGUAGES = 'languages';

    private static $default_params = [
        self::METHOD_FORWARD => [
            'lang' => 'en',
            'display' => 'full',
            'format' => 'json'
        ],
        self::METHOD_REVERSE => [
            'lang' => 'en',
            'display' => 'full',
            'format' => 'json'
        ],
        self::METHOD_AUTOSUGGEST => [
            'lang' => 'en',
            'format' => 'json',
            'clip' => [
                'type' => 'none'
            ]
        ],
        self::METHOD_AUTOSUGGEST_ML => [
            'lang' => 'en',
            'format' => 'json'
        ],
        self::METHOD_STANDARDBLEND => [
            'lang' => 'en',
            'format' => 'json'
        ],
        self::METHOD_STANDARDBLEND_ML => [
            'lang' => 'en',
            'format' => 'json'
        ],
        self::METHOD_GRID => [
            'format' => 'json'
        ],
        self::METHOD_LANGUAGES => [
            'format' => 'json'
        ]
    ];

    // $params = [
    //     'lang' => 'en',
    //     'display' => 'full',
    //     'format' => 'json'
    // ];
    public function forwardGeocode($threeWordAddr, $params=[]) {
        $params['addr'] = $threeWordAddr;
        $params = $this->buildParams(self::METHOD_FORWARD, $params);
        $uri = $this->buildUri(self::METHOD_FORWARD, $params);
        return $this->getResponse($uri);
    }

    // $params = [
    //     'lang' => 'en',
    //     'display' => 'full',
    //     'format' => 'json'
    // ];
    public function reverseGeocode($coords, $params=[]) {
        $params['coords'] = $coords;
        $params = $this->buildParams(self::METHOD_REVERSE, $params);
        $uri = $this->buildUri(self::METHOD_REVERSE, $params);
        return $this->getResponse($uri);
    }

    // $params = [
    //     'lang' => 'en',
    //     'format' => 'json'
    //     'focus' => [
    //         'lat' => 0,
    //         'lng' => 0
    //     ],
    //     'clip' => [
    //         'type' => 'none'
    //     ],
    //     'clip' => [
    //         'type' => 'radius',
    //         'coords' => [
    //             'lat' => 0,
    //             'lng' => 0
    //         ]
    //         'distance' => 0
    //     ],
    //     'clip' => [
    //         'type' => 'focus',
    //         'distance' => 0
    //     ],
    //     'clip' => [
    //         'type' => 'bbox',
    //         'bbox' => [
    //             'ne' => [
    //                 'lat' => 0,
    //                 'lng' => 0
    //             ],
    //             'sw' => [
    //                 'lat' => 0,
    //                 'lng' => 0
    //             ]
    //         ]
    //     ]
    // ];
    public function autoSuggest($threeWordAddr, $params=[]) {
        $params['addr'] = $threeWordAddr;
        $params = $this->buildParams(self::METHOD_AUTOSUGGEST, $params);
        $uri = $this->buildUri(self::METHOD_AUTOSUGGEST, $params);
        return $this->getResponse($uri);
    }

    // $params = [
    //     'lang' => 'en',
    //     'format' => 'json'
    //     'focus' => [
    //         'lat' => 0,
    //         'lng' => 0
    //     ],
    //     'clip' => [
    //         'type' => 'none'
    //     ],
    //     'clip' => [
    //         'type' => 'radius',
    //         'coords' => [
    //             'lat' => 0,
    //             'lng' => 0
    //         ]
    //         'distance' => 0
    //     ],
    //     'clip' => [
    //         'type' => 'focus',
    //         'distance' => 0
    //     ],
    //     'clip' => [
    //         'type' => 'bbox',
    //         'bbox' => [
    //             'ne' => [
    //                 'lat' => 0,
    //                 'lng' => 0
    //             ],
    //             'sw' => [
    //                 'lat' => 0,
    //                 'lng' => 0
    //             ]
    //         ]
    //     ]
    // ];
    public function autoSuggestML($threeWordAddr, $params=[]) {
        $params['addr'] = $threeWordAddr;
        $params = $this->buildParams(self::METHOD_AUTOSUGGEST_ML, $params);
        $uri = $this->buildUri(self::METHOD_AUTOSUGGEST_ML, $params);
        return $this->getResponse($uri);
    }

    // $params = [
    //     'lang' => 'en',
    //     'format' => 'json'
    //     'focus' => [
    //         'lat' => 0,
    //         'lng' => 0
    //     ]
    // ];
    public function standardblend($threeWordAddr, $params=[]) {
        $params['addr'] = $threeWordAddr;
        $params = $this->buildParams(self::METHOD_AUTOSUGGEST, $params);
        $uri = $this->buildUri(self::METHOD_AUTOSUGGEST, $params);
        return $this->getResponse($uri);
    }

    // $params = [
    //     'lang' => 'en',
    //     'format' => 'json'
    //     'focus' => [
    //         'lat' => 0,
    //         'lng' => 0
    //     ]
    // ];
    public function standardblendML($threeWordAddr, $params=[]) {
        $params['addr'] = $threeWordAddr;
        $params = $this->buildParams(self::METHOD_AUTOSUGGEST_ML, $params);
        $uri = $this->buildUri(self::METHOD_AUTOSUGGEST_ML, $params);
        return $this->getResponse($uri);
    }

    // $params = [
    //     'format' => 'json'
    // ];
    public function grid($bbox, $params=[]) {
        $params['bbox'] = $bbox;
        $params = $this->buildParams(self::METHOD_GRID, $params);
        $uri = $this->buildUri(self::METHOD_GRID, $params);
        return $this->getResponse($uri);
    }

    public function languages($params=[]) {
        $params = $this->buildParams(self::METHOD_LANGUAGES, $params);
        $uri = $this->buildUri(self::METHOD_LANGUAGES, $params);
        return $this->getResponse($uri);
    }

    private function buildParams($method, $params) {
        if ($params === NULL || (gettype($params) === 'array') && empty($params)) {
            $params = [];
        }
        $params = array_replace_recursive(self::$default_params[$method], $params);

        switch ($method) {
            case self::METHOD_FORWARD:
                break;

            case self::METHOD_REVERSE:
                $params['coords'] = $this->buildCoords($params['coords']);
                break;

            case self::METHOD_AUTOSUGGEST:
                if ($this->hasParam($params, 'focus')) {
                    $params['focus'] = $this->buildCoords($params['focus']);
                }
                if ($this->hasParam($params, 'clip')) {
                    $params['clip'] = $this->buildClip($params['clip']);
                }
                break;

            case self::METHOD_AUTOSUGGEST_ML:
                if ($this->hasParam($params, 'focus')) {
                    $params['focus'] = $this->buildCoords($params['focus']);
                }
                if ($this->hasParam($params, 'clip')) {
                    $params['clip'] = $this->buildClip($params['clip']);
                }
                break;

            case self::METHOD_STANDARDBLEND:
                if ($this->hasParam($params, 'focus')) {
                    $params['focus'] = $this->buildCoords($params['focus']);
                }
                break;

            case self::METHOD_STANDARDBLEND_ML:
                if ($this->hasParam($params, 'focus')) {
                    $params['focus'] = $this->buildCoords($params['focus']);
                }
                break;

            case self::METHOD_GRID:
                $params['bbox'] = $this->buildBBox($params['bbox']);
                break;

            case self::METHOD_LANGUAGES:
            default:
                break;
        }

        return $params;
    }

    private function buildCoords($coords) {
        if ((gettype($coords) === 'array') && $this->hasParam($coords, 'lat') && $this->hasParam($coords, 'lng')) {
            return sprintf('%f,%f', $coords['lat'], $coords['lng']);
        }
        throw new \Exception('what3words: Invalid format coordinates');
    }

    private function buildBBox($bbox) {
        if ((gettype($bbox) === 'array') && $this->hasParam($bbox, 'ne') && $this->hasParam($bbox, 'sw')) {
            return sprintf('%s,%s', $this->buildCoords($bbox['ne']), $this->buildCoords($bbox['sw']));
        }
        throw new \Exception('what3words: Invalid format bbox');
    }

    private function buildClip($clip) {
        if (!$this->hasParam($clip, 'type')) {
            throw new \Exception('what3words: Invalid clip format');
        }

        $clip_str = '';
        switch ($clip['type']) {
            case 'none':
                $clip_str = $clip['type'];
                break;

            case 'focus':
                if ($this->hasParam($clip, 'distance')) {
                    $clip_str = sprintf('focus(%d)', $clip['distance']);
                }
                else {
                    throw new \Exception('what3words: Invalid clip focus format');
                }
                break;

            case 'radius':
                if ($this->hasParam($clip, 'coords') && $this->hasParam($clip, 'distance')) {
                    $clip_str = sprintf('radius(%s,%d)', $this->buildCoords($clip['coords']), $clip['distance']);
                }
                else {
                    throw new \Exception('what3words: Invalid clip radius format');
                }
                break;

            case 'bbox':
                if ($this->hasParam($clip, 'bbox') && $this->hasParam($clip['bbox'], 'ne') && $this->hasParam($clip['bbox'], 'sw')) {
                    $clip_str = sprintf('bbox(%s,%s)', $this->buildCoords($clip['bbox']['ne']), $this->buildCoords($clip['bbox']['sw']));
                }
                else {
                    throw new \Exception('what3words: Invalid clip bbox format');
                }
                break;
            default:
                break;
        }

        return $clip_str;
    }

    private function buildUri($method, $params) {
        $query = http_build_query($params);
        return sprintf('%s/%s?%s', $this->endpoint, $method, $query);
    }

    private function hasParam($params, $key) {
        return isset($params[$key]) && !empty($params[$key]);
    }

    protected function getUserAgent() {
        return self::USER_AGENT;
    }

    protected function getHeaders() {
        return [
            sprintf('%s: %s', self::AUTH_HEADER, $this->key)
        ];
    }
}

?>
