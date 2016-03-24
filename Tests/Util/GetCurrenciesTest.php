<?php
/**
 * Created by PhpStorm.
 * User: ZhukovAD
 * Date: 24.03.2016
 * Time: 20:02
 */

namespace AJStudio\CentralBankBundle\Tests\Util;

use \AJStudio\CentralBankBundle\Service\CentralBankService;


class GetCurrenciesTest extends \PHPUnit_Framework_TestCase
{
    protected $config = [
        'url' => 'http://www.cbr.ru/scripts/XML_daily.asp',
        'currencies' => [],
        'allow_db_history' => false,
        'currency_entity' => '',
        'currency_has_value_entity' => ''
    ];

    public function testGetAllCurrencies() {
        $centralBankService = new CentralBankService();
        $centralBankService->setConfig($this->config);
        $currencies = $centralBankService->getCurrenciesByDate(new \DateTime());

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $currencies);

        foreach ($currencies as $currency) {
            $this->assertInstanceOf('AJStudio\CentralBankBundle\Model\Currency', $currency);
        }
    }

    public function testGetSeveralCurrencies() {
        $centralBankService = new CentralBankService();

        $this->config['currencies'] = [
            'USD',
            'EUR'
        ];

        $centralBankService->setConfig($this->config);
        $currencies = $centralBankService->getCurrenciesByDate(new \DateTime());

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $currencies);

        foreach ($currencies as $currency) {
            $this->assertInstanceOf('AJStudio\CentralBankBundle\Model\Currency', $currency);
        }
    }
}