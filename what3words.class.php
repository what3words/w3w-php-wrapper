<?php

class what3words
{
	private $apiKey;
	private $language;

	/**
	 * @param string $apiKey
	 * @param string $language
	 */
	public function __construct($apiKey, $language = 'en')
	{
		$this->apiKey = $apiKey;
		$this->setLanguage($language);
	}

	/**
	 * Send a POST request to the API
	 *
	 * @param string $url
	 * @param array $data
	 * @return array
	 */
	private function postRequest($url, $data = array())
	{
		$ch = curl_init('https://api.what3words.com/' . $url);
		$data['key'] = $this->apiKey;
		$data['lang'] = $this->language;

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$return = curl_exec($ch);
		$return = json_decode($return, true);

		curl_close($ch);
		return $return;
	}

	/**
	 * Sets the language in which to return words
	 *
	 * @param string $language
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
	 *
	 * @param string|array $words
	 * @return array
	 * @throws Exception
	 */
	public function wordsToPosition($words)
	{
		if (is_array($words)) {
			$words = implode('.', $words);
		}
		elseif (!is_string($words)) {
			throw new Exception('Invalid words passed');
		}

		$data = array('string' => $words);
		$return = $this->postRequest('w3w', $data);
		return $return['position'];
	}

	/**
	 * Convert a position into 3 words
	 * Takes position either as string, or array of 2 values
	 * Returns array of [word1, word2, word3]
	 *
	 * @param string|array $position
	 * @return array
	 * @throws Exception
	 */
	public function positionToWords($position)
	{
		if (is_array($position)) {
			$position = implode(',', $position);
		}
		elseif (!is_string($position)) {
			throw new Exception('Invalid position passed');
		}

		$data = array('position' => $position);
		$return = $this->postRequest('position', $data);
		return $return['words'];
	}

}

