<?php

/**
 * w3w-php-wrapper - A PHP library to use the what3words RESTful API
 *
 * @author Gary Gale <gary@what3words.com>
 * @copyright 2016, 2017 what3words Ltd
 * @link http://developer.what3words.com
 * @license MIT
 * @version 3.3.0
 * @package What3words\Geocoder
 */

namespace What3words\Geocoder\Test;

use What3words\Geocoder\Geocoder;
use What3words\Geocoder\AutoSuggestOption;


class GeocoderTest extends \PHPUnit\Framework\TestCase
{
    protected $geocoder;

  protected function setUp()
    {
        $this->geocoder = new Geocoder(getenv('W3W_API_KEY'));
    }

    public function testBuildGeocoder()
    {
        $this->assertObjectHasAttribute('api_key', $this->geocoder);
    }

    public function testInvalidKey()
    {
        $geocoder = new Geocoder("BADKEY");

        $data  = $geocoder->convertToCoordinates('index.home.raft');
        $error = $geocoder->getError();

        $this->assertTrue($error["code"] == "InvalidKey");
    }

    public function testConvertToCoordinates()
    {
        $result = $this->geocoder->convertToCoordinates('index.home.raft');
        $this->assertTrue($result["coordinates"]["lat"] == 51.521251);
    }


    public function testConvertTo3wa()
    {
        $result = $this->geocoder->convertTo3wa(51.521251, -0.203586);
        $this->assertTrue($result["words"] == 'index.home.raft');
    }

    public function testGrid()
    {
        $result = $this->geocoder->gridSection(39.903795, 116.384550, 39.902718, 116.383122);
        $this->assertTrue(count($result["lines"]) == 80);
    }

    public function testLanguages()
    {
      $result = $this->geocoder->availableLanguages();
      $this->assertTrue(count($result["languages"]) > 1);
    }


    public function testAutosuggest()
    {
        $result = $this->geocoder->autosuggest('index.home.raft');
        $this->assertTrue($result["suggestions"][0]["words"] == 'index.home.raft');
    }

    public function testAutosuggestBoundingBox()
    {
        $result = $this->geocoder->autosuggest('geschaft.planter.carciofi', [AutoSuggestOption::clipToBoundingBox(51.521, -0.343, 52.6, 2.3324)]);
        $this->assertTrue($result["suggestions"][0]["words"] == 'restate.piante.carciofo');
    }


    public function testAutosuggestFocus()
    {
        $result = $this->geocoder->autosuggest('geschaft.planter.carciofi', [AutoSuggestOption::focus(51.4243877, -0.34745)]);
        $this->assertTrue($result["suggestions"][0]["words"] == 'restate.piante.carciofo');
    }


    public function testAutosuggestCountry()
    {
        $result = $this->geocoder->autosuggest('oui.oui.oui', [AutoSuggestOption::clipToCountry("fr")]);
        $this->assertTrue($result["suggestions"][0]["words"] == 'oust.souk.souk');
    }


    public function testAutosuggestCircle()
    {
        $result = $this->geocoder->autosuggest('mitiger.tarir.prolong', [AutoSuggestOption::clipToCircle(51.521238, -0.203607, 1.0)]);
        $this->assertTrue($result["suggestions"][0]["words"] == 'mitiger.tarir.prolonger');
    }

    public function testAutosuggestPolygon()
    {
        $result = $this->geocoder->autosuggest('scenes.irritated.sparkle', [AutoSuggestOption::clipToPolygon([51.0,0.0, 51.0,0.1, 51.1,0.1, 51.1,0.0, 51.0,0.0])]);
        $this->assertTrue($result["suggestions"][0]["words"] == 'scenes.irritated.sparkles');
    }

    public function testAutosuggestFallBackLanguage()
    {
        $result = $this->geocoder->autosuggest('aaa.aaa.aaa', [AutoSuggestOption::fallbackLanguage("de")]);
        $this->assertTrue($result["suggestions"][0]["words"] == 'saal.saal.saal');
    }


    public function testAutosuggestNumberFocusResults()
    {
        $result = $this->geocoder->autosuggest("geschaft.planter.carciofi", [AutoSuggestOption::focus(51.4243877, -0.34745), AutoSuggestOption::numberFocusResults(2)]);
        $this->assertTrue($result["suggestions"][0]["distanceToFocusKm"] < 100);
        $this->assertTrue($result["suggestions"][2]["distanceToFocusKm"] > 100);
    }


    public function testAutosuggestNumberResults()
    {
        $result = $this->geocoder->autosuggest("fun.with.code", [AutoSuggestOption::numberResults(10)]);
        $this->assertTrue(count($result["suggestions"]) == 10);
    }

  public function testAutosuggestVoice()
  {
    $result = $this->geocoder->autosuggest('{"_isInGrammar":"yes","_isSpeech":"yes","_hypotheses":[{"_score":342516,"_startRule":"whatthreewordsgrammar#_main_","_conf":6546,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34225,"_orthography":"tend","_conf":6964,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":43800,"_orthography":"poached","_conf":6181,"_endTimeMs":4060,"_beginTimeMs":3220}]},{"_score":342631,"_startRule":"whatthreewordsgrammar#_main_","_conf":6498,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34340,"_orthography":"tent","_conf":6772,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":43800,"_orthography":"poached","_conf":6181,"_endTimeMs":4060,"_beginTimeMs":3220}]},{"_score":342668,"_startRule":"whatthreewordsgrammar#_main_","_conf":6474,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34225,"_orthography":"tend","_conf":6964,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":41696,"_orthography":"perch","_conf":5950,"_endTimeMs":4020,"_beginTimeMs":3220}]},{"_score":342670,"_startRule":"whatthreewordsgrammar#_main_","_conf":6474,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34379,"_orthography":"tinge","_conf":6705,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":43800,"_orthography":"poached","_conf":6181,"_endTimeMs":4060,"_beginTimeMs":3220}]},{"_score":342783,"_startRule":"whatthreewordsgrammar#_main_","_conf":6426,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34340,"_orthography":"tent","_conf":6772,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":41696,"_orthography":"perch","_conf":5950,"_endTimeMs":4020,"_beginTimeMs":3220}]},{"_score":342822,"_startRule":"whatthreewordsgrammar#_main_","_conf":6402,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34379,"_orthography":"tinge","_conf":6705,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":41696,"_orthography":"perch","_conf":5950,"_endTimeMs":4020,"_beginTimeMs":3220}]}],"_resultType":"NBest"}', [AutoSuggestOption::inputType("vocon-hybrid"), AutoSuggestOption::fallbackLanguage("en")] );
    $this->assertTrue($result["suggestions"][0]["words"] == "tend.artichokes.perch");
  }
  
  public function testAutosuggestGenericVoice()
  {
    $result = $this->geocoder->autosuggest("filled count soap", [AutoSuggestOption::inputType("generic-voice"), AutoSuggestOption::fallbackLanguage("en")] );
    $this->assertTrue($result["suggestions"][0]["words"] == "filled.count.soap");
  }
  
  
  public function testAutosuggestPreferLand()
  {
    $options = [AutoSuggestOption::inputType("generic-voice"), AutoSuggestOption::fallbackLanguage("en"), AutoSuggestOption::preferLand(false)];
    $result = $this->geocoder->autosuggest("bisect.nourishment.genuineness", $options );
    $this->assertTrue($result["suggestions"][2]["country"] == 'ZZ');
  }
  
  

  
}


?>
