<?php
/**
 * Created by PhpStorm.
 * User: aj
 * Date: 21.03.2016
 * Time: 1:53
 */

namespace AJStudio\CentralBankBundle\Model;

use Doctrine\ORM\Mapping as ORM;

class Currency
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id()
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="num_code", type="string")
     */
    protected $NumCode;

    /**
     * @var string
     *
     * @ORM\Column(name="char_code", type="string")
     */
    protected $charCode;

    /**
     * @var int
     *
     * @ORM\Column(name="nominal", type="integer")
     */
    protected $nominal;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    protected $value;

    /**
     * @var \DateTime
     */
    protected $date;

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
    public function getNumCode()
    {
        return $this->NumCode;
    }

    /**
     * @param string $NumCode
     */
    public function setNumCode($NumCode)
    {
        $this->NumCode = $NumCode;
    }

    /**
     * @return string
     */
    public function getCharCode()
    {
        return $this->charCode;
    }

    /**
     * @param string $charCode
     */
    public function setCharCode($charCode)
    {
        $this->charCode = $charCode;
    }

    /**
     * @return int
     */
    public function getNominal()
    {
        return $this->nominal;
    }

    /**
     * @param int $nominal
     */
    public function setNominal($nominal)
    {
        $this->nominal = $nominal;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}