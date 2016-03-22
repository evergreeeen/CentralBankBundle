<?php
/**
 * Created by PhpStorm.
 * User: aj
 * Date: 21.03.2016
 * Time: 1:53
 */

namespace AJStudio\CentralBankBundle\Model;

use Doctrine\ORM\Mapping as ORM;

class CurrencyHasValue
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Currency", inversedBy="values")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    protected $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="fix_date", type="date", nullable=false)
     */
    protected $fixDate;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float", nullable=false)
     */
    protected $value;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getFixDate()
    {
        return $this->fixDate;
    }

    /**
     * @param string $fixDate
     */
    public function setFixDate($fixDate)
    {
        $this->fixDate = $fixDate;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}