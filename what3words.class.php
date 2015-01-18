<?php

class What3words
{
	const API_KEY = 'YOURAPIKEY'; 	// Change to your what3words API key
	private $language = 'en'; 	// Change to your default language

	// --

	public function __construct($language = null)
	{
		$this->setLanguage($language);
	}

	private function postRequest($url, $data = array())
	{
		$ch = curl_init('http://api.what3words.com/' . $url);
		$data['key'] = self::API_KEY;
		$data['lang'] = $this->language;

		curl_setopt($ch, CURLOPT_POST, count($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$return = curl_exec($ch);
		$return = json_decode($return, true);

		curl_close($ch);
		return $return;
	}

	// --

	/**
	 * Sets the language in which to return words
	 */
	public function setLanguage($language)
	{
		if (!empty($language))
			$this->language = $language;
	}

	/**
	 * Convert 3 words or OneWord into position
	 * Takes words either as string, or array of words
	 * Returns array of [lat, long]
	 */

	public function wordsToPosition($words)
	{
		if (is_array($words))
			$words = implode('.', $words);
		elseif (!is_string($words))
			throw new Exception('Invalid words passed');

		$data = array('string' => $words);
		$return = self::postRequest('w3w', $data);
		return $return['position'];
	}

	/**
	 * Convert a position into 3 words
	 * Takes position either as string, or array of 2 values
	 * Returns array of [word1, word2, word3]
	 */

	public function positionToWords($position)
	{
		if (is_array($position))
			$position = implode(',', $position);
		elseif (!is_string($position))
			throw new Exception('Invalid position passed');

		$data = array('position' => $position);
		$return = self::postRequest('position', $data);
		return $return['words'];
	}

}

