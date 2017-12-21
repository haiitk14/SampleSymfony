<?php
/****************************************************************************
**
** Copyright (C) 2017 Oriental Tran.
** Contact: dongtp@dfm-engineering.com
** Company: DFM-Engineering Vietnam
**
** This file is part of the cryosoft project.
**
**All rights reserved.
****************************************************************************/
namespace  AppBundle\Cryosoft;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Unit;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\Studies;
use AppBundle\Entity\Post;
use AppBundle\Entity\MonetaryCurrency;
use AppBundle\Entity\Ln2user;

class UnitsConverterService
{
	private $doctrine;
	private $user;

	public function __construct(\Doctrine\ORM\EntityManager $doctrine, TokenStorageInterface $tokenStorage, Session $session) 
	{
		$this->doctrine = $doctrine;
		// $this->user = $tokenStorage->getToken()->getUser();
		$this->user = (!empty($tokenStorage->getToken())) ? $tokenStorage->getToken()->getUser() : null;
		$this->session = $session;
	}

	private function getDoctrine() 
	{
		return $this->doctrine;
	}

	public function convertToDouble($sValue) 
	{
        $value = 0.0;
        if ($sValue != null && $sValue != "" && is_numeric($sValue)) {
            $value = $sValue;
        }

        return $value;
	}
	
	public function unitConvert($unitType, $value, $decimal = 2)
	{
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => $unitType]); 
        if (!empty($unit)) 
            return $this->convertCalculator($value, $unit->getCoeffA(), $unit->getCoeffB(),  $decimal);
        else 
            return $value;
	}
	
	public function convertCalculator($value, $coeffA, $coeffB, $decimal = 2)
	{
        return round(($value * $coeffA + $coeffB), $decimal);
	}
	
	public function symbolUnit($unitType)
	{
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => $unitType]);
        return $unit->getSymbol();
	}
	
	public function uNone()
	{
        return array(
            "coeffA" => "1.0",
            "coeffB" => "0.0",
            "symbol" => ""
        );
    }
    
	public function uPercent()
	{
        return array(
            "coeffA" => "100.0",
            "coeffB" => "0.0",
            "symbol" => "%"
        );
	}
	
	public function consumption($value, $energy, $type)
	{
        $sValue = "";
        $sUnitLabel = "";

        if ($energy == 2) {
            switch ($type) {
                case 1:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT_LN2;
                    break;
                case 2:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUM_MAINTIEN_LN2;
                    break;
                case 3:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUM_MEF_LN2;
                    break;
                default:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT_LN2;
                    break;
            }

        } else if ($energy == 3) {
            switch ($type) {
                case 1:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT_CO2;
                    break;
                case 2:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUM_MAINTIEN_CO2;
                    break;
                case 3:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUM_MEF_CO2;
                    break;
                default:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT_CO2;
                    break;

            }

        } else {
            switch ($type) {
                case 1:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT;
                    break;
                case 2:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUM_MAINTIEN;
                    break;
                case 3:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUM_MEF;
                    break;
                default:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT;
            }

        }

        return $this->unitConvert($sUnitLabel, $value);
	}
	
	public function uMoney()
	{
        $user = $this->user;

        $monetaryUnit = $this->getDoctrine()->getRepository(MonetaryCurrency::class)->findOneBy(
            ['idMonetaryCurrency' => $user->getIdMonetaryCurrency()]);
     
        $unit = $this->getDoctrine()->getRepository(Unit::class)->createQueryBuilder('u')
        ->where('u.typeUnit = :typeUnit')
        ->andWhere('u.symbol like :symbol')
        ->setParameter('typeUnit', 27)
        ->setParameter('symbol', '%'. $monetaryUnit->getMoneyText() .'%')
        ->getQuery()->setMaxResults(1)->getOneOrNullResult();
        $result = array();
        if ($unit == null) {
            $result = $this->uNone();
        } else {
            $result = [
                "coeffA" => $unit->getCoeffA(),
                "coeffB" => $unit->getCoeffB(),
                "symbol" => $unit->getSymbol()
            ];
        }

        return $result;
    }

	public function monetarySymbol() 
	{
        $sValue = "";
        $uMoney = $this->uMoney();
        if(!empty($uMoney)) $sValue = $uMoney["symbol"];
        return $sValue;
    }

	public function perUnitOfMassSymbol() 
	{
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => POST::TYPE_UNIT_MASS_PER_UNIT]); 
        if(!empty($unit)) $sValue = $unit->getSymbol();
        return $sValue;
    }

    public function convectionSpeedSymbol() 
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => POST::TYPE_UNIT_CONV_SPEED_UNIT]); 
        if(!empty($unit)) $sValue = $unit->getSymbol();
        return $sValue;
    }

    public function equipDimensionSymbol() 
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => POST::TYPE_UNIT_EQUIP_DIMENSION]); 
        if(!empty($unit)) $sValue = $unit->getSymbol();
        return $sValue;
    }

    public function productFlowSymbol() 
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => POST::TYPE_UNIT_PRODUCT_FLOW]); 
        if(!empty($unit)) $sValue = $unit->getSymbol();
        return $sValue;
    }

    public function massSymbol() 
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => POST::TYPE_UNIT_MASS]); 
        if(!empty($unit)) $sValue = $unit->getSymbol();
        return $sValue;
    }

    public function enthalpySymbol() 
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => POST::TYPE_UNIT_ENTHALPY]); 
        if(!empty($unit)) $sValue = $unit->getSymbol();
        return $sValue;
    }
    
	public function consumptionSymbol($energy, $type) 
	{
        $sValue = "";
        $sUnitLabel = "";
        if ($energy == 2) {
            switch ($type) {
                case 1:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT_LN2;
                    break;
                case 2:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUM_MAINTIEN_LN2;
                    break;
                case 3:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUM_MEF_LN2;
                    break;
                default:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT_LN2;
                    break;
            }

        } else if ($energy == 3) {
            switch ($type) {
                case 1:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT_CO2;
                    break;
                case 2:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUM_MAINTIEN_CO2;
                    break;
                case 3:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUM_MEF_CO2;
                    break;
                default:
                    $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT_CO2;
                    break;

            }

        } else {
            switch ($type) {
                case 1:
                $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT;
                    break;
                case 2:
                $sUnitLabel = POST::TYPE_UNIT_CONSUM_MAINTIEN;
                    break;
                case 3:
                $sUnitLabel = POST::TYPE_UNIT_CONSUM_MEF;
                    break;
                default:
                $sUnitLabel = POST::TYPE_UNIT_CONSUMPTION_UNIT;
            }

        }
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => $sUnitLabel]); 
        if(!empty($unit)) $sValue = $unit->getSymbol();
        return $sValue;
    }

	public function monetary($value, $nbDecimal = 3) 
	{
        $sValue = "";
        $uMoney = $this->uMoney();
        $sValue = $this->convertCalculator($value, $uMoney["coeffA"], $uMoney["coeffB"],  $nbDecimal);
        return $sValue;
    }

	public function initEnergyDef()
	{
        $energyDef = 0;
        $session = $this->session;
        $idStudy = $session->get("idStudy");
        
        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        if (!empty($objStudy)) {
            $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy' => $objStudy->getIdStudy()]);

            if (!empty($studyEquipments)) {
                foreach($studyEquipments as $row){
                    $ener =  $row->getIdEquip()->getIdCoolingFamily()->getIdCoolingFamily();
                    if (($energyDef == 0) && (($ener == 3) || ($ener == 2))) {
                        $energyDef = $ener;
                    }
                }
            }
        }

        return $energyDef;
    }

    public function controlTemperature($value)
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => Post::TYPE_UNIT_TEMPERATURE]);
        if(count($unit) > 0) $sValue = $this->convertCalculator($value, $unit->getCoeffA(), $unit->getCoeffB(), 0);
        return $sValue;
    }

    public function timeUnit($value)
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => Post::TYPE_UNIT_TIME]);
        if(count($unit) > 0) $sValue = $this->convertCalculator($value, $unit->getCoeffA(), $unit->getCoeffB(), 1);
        return $sValue;
    }

    public function convectionSpeed($value) 
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => Post::TYPE_UNIT_CONV_SPEED]);
        if(count($unit) > 0) $sValue = $this->convertCalculator($value, $unit->getCoeffA(), $unit->getCoeffB(), 1);
        return $sValue;
    }

    public function productFlow($value) 
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => Post::TYPE_UNIT_PRODUCT_FLOW]);
        if(count($unit) > 0) $sValue = $this->convertCalculator($value, $unit->getCoeffA(), $unit->getCoeffB(), 1);
        return $sValue;
    }

    public function mass($value) 
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => Post::TYPE_UNIT_MASS]);
        if(count($unit) > 0) $sValue = $this->convertCalculator($value, $unit->getCoeffA(), $unit->getCoeffB(), 3);
        return $sValue;
    }

    public function shelvesWidth($value)
    {
        $sValue = "";
        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => Post::TYPE_UNIT_W_CARPET_SHELVES]);
        if(count($unit) > 0) $sValue = $this->convertCalculator($value, $unit->getCoeffA(), $unit->getCoeffB());
        return $sValue;
    }

    public function toc($value) 
    {
        $sValue = "";
        $uPercent = $this->uPercent();
        $sValue = $this->convertCalculator($value, $uPercent["coeffA"], $uPercent["coeffB"], 1);
        return $sValue;
    }
}