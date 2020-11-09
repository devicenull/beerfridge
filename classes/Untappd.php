<?php
class Untappd
{
	const WANTED_BEER_FIELDS =  [
		'bid',
		'beer_name',
		'beer_label',
		'beer_label_hd',
		'beer_abv',
		'beer_ibu',
		'beer_style',
		'brewery',
	];

	public static function beerInfo($BID): ?array
	{
		$c = curl_init('https://api.untappd.com/v4/beer/info/'.intVal($BID).'?client_id='.UNTAPPD_CLIENT_ID.'&client_secret='.UNTAPPD_CLIENT_SECRET);
		curl_setopt_array($c, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT      => 'https://github.com/devicenull/beerfridge',
			CURLOPT_VERBOSE        => true,
		]);

//		$result = json_decode(curl_exec($c));
		$result = json_decode(file_get_contents(__DIR__.'/../beerinfo.txt'), true);

		if (empty($result['response'])) return null;

		$beer = $result['response']['beer'];
		return Untappd::parseBeer($beer);
	}

	public static function beerSearch($query): ?array
	{
		$c = curl_init('https://api.untappd.com/v4/search/beer?q='.urlencode($query).'&client_id='.UNTAPPD_CLIENT_ID.'&client_secret='.UNTAPPD_CLIENT_SECRET);
		curl_setopt_array($c, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT      => 'https://github.com/devicenull/beerfridge',
			CURLOPT_VERBOSE        => true,
		]);

		$result = json_decode(curl_exec($c), true);
		//$result = json_decode(file_get_contents(__DIR__.'/../beersearch.txt'), true);

		$return = [];
		foreach ($result['response']['beers']['items'] as $beer)
		{
			$newbeer =  Untappd::parseBeer($beer['beer']);
			$newbeer['brewery'] = $beer['brewery']['brewery_name'];
			$newbeer['brewery_id'] = $beer['brewery']['brewery_id'];

			$return[] = $newbeer;
		}
		return $return;
	}

	/**
	*	Untappd returns a bunch of stuff for each beer, like recent checkins and similar beers
	*	We don't really care about any of that, strip it out asap
	*/
	private static function parseBeer($input): array
	{
		$return = [];
		foreach (Untappd::WANTED_BEER_FIELDS as $key)
		{
			if (isset($input[$key]))
			{
				$return[$key] = $input[$key];
			}
		}
		return $return;
	}
}
