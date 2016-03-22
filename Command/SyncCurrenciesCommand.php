<?php
/**
 * Created by PhpStorm.
 * User: ZhukovAD
 * Date: 21.03.2016
 * Time: 17:02
 */

namespace AJStudio\CentralBankBundle\Command;

use AJStudio\CentralBankBundle\Model\Currency;
use AJStudio\CentralBankBundle\Model\CurrencyHasValue;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCurrenciesCommand extends GeneralCommand
{
    public function configure() {
        $this->setName('ajstudio:central-bank:update-currencies');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('');

        $centralBankService = $this->getContainer()->get('ajstudio.central_bank');

        if (!$centralBankService->isAllowDbHistory()) {
            $this->writeComment($output, 'Sync with database is not allowed in bundle config');
        }

        if (!$centralBankService->getCurrencyEntity() || !$centralBankService->getCurrencyHasValueEntity()) {
            $this->writeComment($output, 'Please set bundle entities in app config');
        }

        $currencyEntityClass = $centralBankService->getCurrencyEntity();
        $currencyHasValueEntityClass = $centralBankService->getCurrencyHasValueEntity();

        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $nowCurrencies = $centralBankService->getNowCurrencies();
        /** @var Currency $nowCurrency */
        foreach ($nowCurrencies as $nowCurrency) {
            $currency = $entityManager->find($currencyEntityClass, $nowCurrency->getId());

            if (!$currency) {
                /** @var Currency $currency */
                $currency = $this->getCurrencyByCurrencyModel($currencyEntityClass, $nowCurrency);
                $entityManager->persist($currency);
            }
            else {
                $currency->setDate($nowCurrency->getDate());
            }

            $currencyHasValue = $entityManager->getRepository($currencyHasValueEntityClass)->findOneBy(['currency' => $currency, 'fixDate' => $nowCurrency->getDate()]);

            if ($currencyHasValue) {
                $this->writeComment($output,  sprintf('%s already synchronized', $nowCurrency->getCharCode()));
                continue;
            }

            try {
                /** @var CurrencyHasValue $currencyHasValue */
                $currencyHasValue = new $currencyHasValueEntityClass();
                $currencyHasValue->setCurrency($currency);
                $currencyHasValue->setFixDate($currency->getDate());
                $currencyHasValue->setValue($currency->getValue());

                $entityManager->persist($currencyHasValue);

            } catch (\Exception $e) {
                $this->writeError($output, sprintf("%s: %s", 'Add currency has value', $e->getMessage()));
            }
        }

        $entityManager->flush();
        $output->writeln('');
    }

    /**
     * @param $entityClassName
     * @param Currency $currencyModel
     * @return mixed
     */
    protected function getCurrencyByCurrencyModel($entityClassName, Currency $currencyModel) {
        $currency = new $entityClassName();
        $currency->setId($currencyModel->getId());
        $currency->setValue($currencyModel->getValue());
        $currency->setDate($currencyModel->getDate());
        $currency->setCharCode($currencyModel->getCharCode());
        $currency->setName($currencyModel->getName());
        $currency->setNominal($currencyModel->getNominal());
        $currency->setNumCode($currencyModel->getNumCode());

        return $currency;
    }
}