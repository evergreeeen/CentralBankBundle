About
------
AJStudio Cental Bank Bundle designed to bring together Russian Central Banl XML API and Symfony for get actual currencies in real time or by needed date.

What can I do with this bundle
------------------------------
* Currency informers
* History of currencies by several countries
* E-commerce real-time price dependence
* Other applications by your taste

Overview
--------

1. Install
2. Add to AppKernel in your app
3. Configure bundle
4. Get currencies from service
5. Configure and synchronize currencies with DB

Let's do it !

Installation Instructions
-------------------------

### Step 1: Install via composer

`composer require ajstudio/central-bank-bundle`

### Step 2: Add to your App Kernel

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new \AJStudio\CentralBankBundle\AJStudioCentralBankBundle(),
    );
}
```
### Step 3: Configure bundle

Add the following to your app/config.yml

```yaml
# AJStudio Central Bank Configuration
ajstudio_central_bank:
    currencies:
        - USD
        - EUR
        - BYR
        - UAH
```

_Note: if you don't set currencies - bundle get all currencies from central bank_

### Step 4: Get currencies from service

Now, get service from any place of your app and get currencies

```php
public function currenciesAction() {
        //Now currencies
        $this->container->get('ajstudio.central_bank')->getNowCurrencies();

        //Or currencies by your needed date
        $this->container->get('ajstudio.central_bank')->getCurrencies(new \DateTime());
}
```

_Note: methods return array collection of objects instanceof `JStudio\CentralBankBundle\Model\Currency`_

### Step 5: Configure and synchronize currencies with DB

Bundle can get actual currencies and synchronize with your database by schedule with command

***Configure in yaml***

```yaml
# AJStudio Central Bank Configuration
ajstudio_central_bank:
    allow_db_history: true
    currency_entity: AJStudio\SiteBundle\Entity\Currency
    currency_has_value_entity: AJStudio\SiteBundle\Entity\CurrencyHasValue
    currencies:
        - USD
        - EUR
        - BYR
        - UAH
```
***Create entities***
```php
<?php
namespace AJStudio\SiteBundle\Entity;

use AJStudio\CentralBankBundle\Model\Currency as BaseCurrency;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="currency")
 * @ORM\Entity()
 */
class Currency extends BaseCurrency
{
}
```

```php
<?php
namespace AJStudio\SiteBundle\Entity;

use AJStudio\CentralBankBundle\Model\CurrencyHasValue as BaseCurrencyHasValue;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="currency_has_value")
 * @ORM\Entity()
 */
class CurrencyHasValue extends BaseCurrencyHasValue
{
}
```

***And start command in your OS shedule***
```command
php bin/console ajstudio:central-bank:update-currencies
```

_Note: Russian Central Bank update day currencies no later than 15:00 (GMT+3)_