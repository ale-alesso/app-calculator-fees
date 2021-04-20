<?php

namespace App\Service\Converter\Exchange;

use App\Exception\MissedConversionRateException;

class ExchangeratesCurrencyExchange implements CurrencyExchangeInterface
{
    private string $baseCurrency;
    private string $apiUrl;
    private string $apiKey;

    private string $convertFrom;
    private string $convertTo;

    private array $rates;

    public function __construct(string $baseCurrency, string $apiUrl, string $apiKey)
    {
	$this->baseCurrency = $baseCurrency;
	$this->apiUrl = $apiUrl;
	$this->apiKey = $apiKey;
    }

    public function getRate(string $from, string $to): float
    {
	$isBaseCurrency = ($from === $this->baseCurrency);
	$this->convertFrom = $isBaseCurrency ? $from : $to;
	$this->convertTo = $isBaseCurrency ? $to : $from;

	if (isset($this->rates[$this->convertFrom][$this->convertTo])) {
	    $rate = $this->rates[$this->convertFrom][$this->convertTo];

	    return $isBaseCurrency ? $rate : 1 / $rate;
	}

	$exchangeRates = $this->fetchRates();

	if (isset($exchangeRates['rates'])) {
	    $rate = $exchangeRates['rates'][$this->convertTo];
	    $exchangeRate = $isBaseCurrency ? $rate : 1 / $rate;
	    $this->rates[$this->convertFrom][$this->convertTo] = $exchangeRate;

	    return $exchangeRate;
	} else {
	    $errorMessage = $exchangeRates['error']['message'] . ' ' . $exchangeRates['error']['code'] . '';

	    throw new MissedConversionRateException(sprintf('Cannot fetch rate for %s:%s currency pair. ' . $errorMessage, $this->convertFrom, $this->convertTo));
	}
    }

    private function fetchRates(): array
    {
	$buildQuery = http_build_query([
	    'access_key' => $this->apiKey,
	    'base' => $this->convertFrom,
	    'symbols' => $this->convertTo,
	]);

	$ch = curl_init($this->apiUrl . 'latest?' . $buildQuery);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	curl_close($ch);

	return json_decode($json, true);
    }
}
