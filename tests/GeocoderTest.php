<?php

/**
 * w3w-php-wrapper - A PHP library to use the what3words RESTful API
 *
 * @author Gary Gale <gary@what3words.com>
 * @copyright 2016, 2017 what3words Ltd
 * @link http://developer.what3words.com
 * @license MIT
 * @version 3.5.0
 * @package What3words\Geocoder\Test
 */

namespace What3words\Geocoder\Test;

require_once "./Geocoder.php";

use What3words\Geocoder\Geocoder;
use What3words\Geocoder\AutoSuggestOption;
use PHPUnit\Framework\TestCase;

class GeocoderTest extends TestCase
{
    protected $geocoder;

    protected function setUp()
    {
        $this->geocoder = new Geocoder(getenv('W3W_API_KEY'));
    }

    public function testBuildGeocoder()
    {
        $this->assertObjectHasAttribute('apiKey', $this->geocoder);
    }

    public function testInvalidKey()
    {
        $geocoder = new Geocoder("BADKEY");

        $geocoder->convertToCoordinates("index.home.raft");
        $error = $geocoder->getError();

        $this->assertEquals("InvalidKey", $error["code"]);
    }

    public function testConvertToCoordinates()
    {
        $result = $this->geocoder->convertToCoordinates('index.home.raft');
        $this->assertEquals(51.521251, $result["coordinates"]["lat"]);
    }


    public function testConvertTo3wa()
    {
        $result = $this->geocoder->convertTo3wa(51.521251, -0.203586);
        $this->assertEquals("index.home.raft", $result["words"]);
    }

    public function testGrid()
    {
        $result = $this->geocoder->gridSection(39.903795, 116.384550, 39.902718, 116.383122);
        $this->assertEquals(80, count($result["lines"]));
    }

    public function testLanguages()
    {
        $result = $this->geocoder->availableLanguages();
        $this->assertTrue(count($result["languages"]) > 1);
    }


    public function testAutosuggest()
    {
        $result = $this->geocoder->autosuggest('index.home.raft');
        $this->assertEquals("index.home.raft", $result["suggestions"][0]["words"]);
    }

    public function testAutosuggestNonEnglish()
    {
        $result = $this->geocoder->autosuggest("有些.明.火");
        $this->assertEquals("有些.驰名.护耳", $result["suggestions"][0]["words"]);
    }

    public function testAutosuggestBoundingBox()
    {
        $result = $this->geocoder->autosuggest('geschaft.planter.carciofi', [AutoSuggestOption::clipToBoundingBox(51.521, -0.343, 52.6, 2.3324)]);
        $this->assertEquals("restate.piante.carciofo", $result["suggestions"][0]["words"]);
    }


    public function testAutosuggestFocus()
    {
        $result = $this->geocoder->autosuggest("geschaft.planter.carciofi", [AutoSuggestOption::focus(51.4243877, -0.34745)]);
        $this->assertEquals("restate.piante.carciofo", $result["suggestions"][0]["words"]);
    }


    public function testAutosuggestCountry()
    {
        $result = $this->geocoder->autosuggest("oui.oui.oui", [AutoSuggestOption::clipToCountry("fr")]);
        $this->assertEquals("oust.souk.souk", $result["suggestions"][0]["words"]);
    }


    public function testAutosuggestCircle()
    {
        $result = $this->geocoder->autosuggest("mitiger.tarir.prolong", [AutoSuggestOption::clipToCircle(51.521238, -0.203607, 1.0)]);
        $this->assertEquals("mitiger.tarir.prolonger", $result["suggestions"][0]["words"]);
    }

    public function testAutosuggestPolygon()
    {
        $result = $this->geocoder->autosuggest("scenes.irritated.sparkle", [AutoSuggestOption::clipToPolygon([51.0, 0.0, 51.0, 0.1, 51.1, 0.1, 51.1, 0.0, 51.0, 0.0])]);
        $this->assertEquals("scenes.irritated.sparkles", $result["suggestions"][0]["words"]);
    }

    public function testAutosuggestFallBackLanguage()
    {
        $result = $this->geocoder->autosuggest("aaa.aaa.aaa", [AutoSuggestOption::fallbackLanguage("de")]);
        $this->assertEquals("saal.saal.saal", $result["suggestions"][0]["words"]);
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
        $this->assertEquals(10, count($result["suggestions"]));
    }

    public function testAutosuggestVoice()
    {
        $result = $this->geocoder->autosuggest('{"_isInGrammar":"yes","_isSpeech":"yes","_hypotheses":[{"_score":342516,"_startRule":"whatthreewordsgrammar#_main_","_conf":6546,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34225,"_orthography":"tend","_conf":6964,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":43800,"_orthography":"poached","_conf":6181,"_endTimeMs":4060,"_beginTimeMs":3220}]},{"_score":342631,"_startRule":"whatthreewordsgrammar#_main_","_conf":6498,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34340,"_orthography":"tent","_conf":6772,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":43800,"_orthography":"poached","_conf":6181,"_endTimeMs":4060,"_beginTimeMs":3220}]},{"_score":342668,"_startRule":"whatthreewordsgrammar#_main_","_conf":6474,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34225,"_orthography":"tend","_conf":6964,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":41696,"_orthography":"perch","_conf":5950,"_endTimeMs":4020,"_beginTimeMs":3220}]},{"_score":342670,"_startRule":"whatthreewordsgrammar#_main_","_conf":6474,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34379,"_orthography":"tinge","_conf":6705,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":43800,"_orthography":"poached","_conf":6181,"_endTimeMs":4060,"_beginTimeMs":3220}]},{"_score":342783,"_startRule":"whatthreewordsgrammar#_main_","_conf":6426,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34340,"_orthography":"tent","_conf":6772,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":41696,"_orthography":"perch","_conf":5950,"_endTimeMs":4020,"_beginTimeMs":3220}]},{"_score":342822,"_startRule":"whatthreewordsgrammar#_main_","_conf":6402,"_endTimeMs":6360,"_beginTimeMs":1570,"_lmScore":300,"_items":[{"_type":"terminal","_score":34379,"_orthography":"tinge","_conf":6705,"_endTimeMs":2250,"_beginTimeMs":1580},{"_type":"terminal","_score":47670,"_orthography":"artichokes","_conf":7176,"_endTimeMs":3180,"_beginTimeMs":2260},{"_type":"terminal","_score":41696,"_orthography":"perch","_conf":5950,"_endTimeMs":4020,"_beginTimeMs":3220}]}],"_resultType":"NBest"}', [AutoSuggestOption::inputType("vocon-hybrid"), AutoSuggestOption::fallbackLanguage("en")]);
        $this->assertEquals("tend.artichokes.perch", $result["suggestions"][0]["words"]);
    }

    public function testAutosuggestGenericVoice()
    {
        $result = $this->geocoder->autosuggest("filled count soap", [AutoSuggestOption::inputType("generic-voice"), AutoSuggestOption::fallbackLanguage("en")]);
        $this->assertEquals("filled.count.soap", $result["suggestions"][0]["words"]);
    }


    public function testAutosuggestPreferLand()
    {
        $options = [AutoSuggestOption::inputType("generic-voice"), AutoSuggestOption::fallbackLanguage("en"), AutoSuggestOption::preferLand(false)];
        $result = $this->geocoder->autosuggest("bisect.nourishment.genuineness", $options);
        $this->assertEquals('SD', $result["suggestions"][2]["country"]);
    }

    public function testAutosuggestIsPossible3wa()
    {
        $this->assertEquals(1, $this->geocoder->isPossible3wa("filled.count.soap"));
        $this->assertEquals(0, $this->geocoder->isPossible3wa("not a 3wa"));
        $this->assertEquals(0, $this->geocoder->isPossible3wa("not.3wa address"));
    }

    public function testAutosuggestFindPossible3wa()
    {
        $result = $this->geocoder->findPossible3wa('from "index.home.raft" to " "filled.count.soap"');
        $this->assertEquals(2, count($result));
    }

    public function testAutosuggestIsValid3wa()
    {
        $this->assertEquals(1, $this->geocoder->isValid3wa('filled.count.soap'));
    }




}

