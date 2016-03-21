<?php
/**
 * Created by PhpStorm.
 * User: aj
 * Date: 19.03.2016
 * Time: 4:06
 */

namespace AJStudio\CentralBankBundle\Service;

use AJStudio\CentralBankBundle\Model\Currency;
use Doctrine\Common\Collections\ArrayCollection;

class CentralBankService
{
    /** @const string */
    const DATE_PARAM = 'date_req';

    /** @var string */
    protected $url;

    /** @var array */
    protected $currencies;

    /**
     * @param array $config
     */
    public function setConfig(array $config) {
        $this->url = $config['url'];
        $this->currencies = $config['currencies'];
    }

    /**
     * @param \DateTime $dateTime
     * @return array
     */
    public function getCurrenciesByDate(\DateTime $dateTime) {
        $formattedDate = $this->formatDateTime($dateTime);
        $requestUrl = $this->url . '?' . $this::DATE_PARAM . '=' . $formattedDate;
        $response = $this->getResponse($requestUrl);

        //Convert response to utf-8
        $response = iconv('windows-1251', 'utf-8', $response);
        $response = str_replace('encoding="windows-1251"', 'encoding="utf-8"', $response);

        $dom = new \DOMDocument();
        $dom->loadXML($response);

        $currencyElements = $dom->getElementsByTagName('Valute');
        $outputCurrencies = new ArrayCollection();

        if (empty($this->currencies)) {
            /** @var $currencyElement \DOMElement */
            foreach ($currencyElements as $currencyElement) {
                $outputCurrencies->add($this->getCurrencyByDomElement($currencyElement));
            }
        }
        else {
            /** @var $currencyElement \DOMElement */
            foreach ($currencyElements as $currencyElement) {
                if (array_search($currencyElement->getElementsByTagName('CharCode')->item(0)->nodeValue, $this->currencies) === false) {
                    continue;
                }

                $outputCurrencies->add($this->getCurrencyByDomElement($currencyElement));
            }
        }

        return $outputCurrencies;
    }

    /**
     *
     */
    public function getNowCurrencies() {
        $datetime = new \DateTime();
        return $this->getCurrenciesByDate($datetime);
    }

    /**
     * @param $url
     * @return mixed
     */
    protected function getResponse($url) {
        $ch = curl_init();

        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1');
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt ($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);

        return curl_exec($ch);
    }

    /**
     * Format DateTime to {day}/{month}/{year} string
     *
     * @param \DateTime $dateTime
     * @return string
     */
    protected function formatDateTime(\DateTime $dateTime) {
        return $dateTime->format('d/m/Y');
    }

    /**
     * @param \DOMElement $DOMElement
     * @return Currency
     */
    protected function getCurrencyByDomElement(\DOMElement $DOMElement) {
        $currency = new Currency();

        $currency->setId($DOMElement->attributes->getNamedItem('ID')->nodeValue);
        $currency->setNumCode($DOMElement->getElementsByTagName('NumCode')->item(0)->nodeValue);
        $currency->setCharCode($DOMElement->getElementsByTagName('CharCode')->item(0)->nodeValue);
        $currency->setNominal($DOMElement->getElementsByTagName('Nominal')->item(0)->nodeValue);
        $currency->setName($DOMElement->getElementsByTagName('Name')->item(0)->nodeValue);
        $currency->setValue($DOMElement->getElementsByTagName('Value')->item(0)->nodeValue);

        return $currency;
    }
}