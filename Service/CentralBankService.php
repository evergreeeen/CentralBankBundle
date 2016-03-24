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

    /** @var bool */
    protected $allowDbHistory;

    /** @var string */
    protected $currencyEntity;

    /** @var string */
    protected $currencyHasValueEntity;

    /**
     * @param array $config
     */
    public function setConfig(array $config) {
        $this->url = $config['url'];
        $this->currencies = $config['currencies'];
        $this->allowDbHistory = $config['allow_db_history'];
        $this->currencyEntity = $config['currency_entity'];
        $this->currencyHasValueEntity = $config['currency_has_value_entity'];
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
                $currency = $this->getCurrencyByDomElement($currencyElement);
                $currency->setDate($dateTime);
                $outputCurrencies->add($this->getCurrencyByDomElement($currencyElement));
            }
        }
        else {
            /** @var $currencyElement \DOMElement */
            foreach ($currencyElements as $currencyElement) {
                if (array_search($currencyElement->getElementsByTagName('CharCode')->item(0)->nodeValue, $this->currencies) === false) {
                    continue;
                }

                $currency = $this->getCurrencyByDomElement($currencyElement);
                $currency->setDate($dateTime);
                $outputCurrencies->add($currency);
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

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getCurrencies()
    {
        return $this->currencies;
    }

    /**
     * @param array $currencies
     */
    public function setCurrencies($currencies)
    {
        $this->currencies = $currencies;
    }

    /**
     * @return boolean
     */
    public function isAllowDbHistory()
    {
        return $this->allowDbHistory;
    }

    /**
     * @param boolean $allowDbHistory
     */
    public function setAllowDbHistory($allowDbHistory)
    {
        $this->allowDbHistory = $allowDbHistory;
    }

    /**
     * @return string
     */
    public function getCurrencyEntity()
    {
        return $this->currencyEntity;
    }

    /**
     * @param string $currencyEntity
     */
    public function setCurrencyEntity($currencyEntity)
    {
        $this->currencyEntity = $currencyEntity;
    }

    /**
     * @return string
     */
    public function getCurrencyHasValueEntity()
    {
        return $this->currencyHasValueEntity;
    }

    /**
     * @param string $currencyHasValueEntity
     */
    public function setCurrencyHasValueEntity($currencyHasValueEntity)
    {
        $this->currencyHasValueEntity = $currencyHasValueEntity;
    }
}