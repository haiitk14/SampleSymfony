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


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Equipment;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\Studies;
use AppBundle\Entity\MinMax;
use AppBundle\Entity\Post;
use AppBundle\Entity\StudEqpPrm;
use AppBundle\Entity\DimaResults;
use AppBundle\Cryosoft\UnitsConverterService;

class DimaResultsService
{

	public function __construct(\Doctrine\ORM\EntityManager $doctrine, TokenStorageInterface $tokenStorage, UnitsConverterService $unit) 
	{
		$this->doctrine = $doctrine;
		$this->user = $tokenStorage->getToken()->getUser();
        $this->_unit = $unit;
	}

	private function getDoctrine() 
	{
		return $this->doctrine;
	}

	public function getCalculationStatus($dimaStatus)
    {
        $ldStatus = $dimaStatus & 0xFFFF;
        return $ldStatus;
    }

    public function getCalculationWarning($param)
    {
        $r = $param & 0xFFFF0000;
        $r >>= 16;
        return $r;
    }

	public function isConsoToDisplay($dimaStatus)
    {
        return (($this->getCalculationStatus($dimaStatus) & 0x100) == 0) ? true : false;
    }

    public function consumptionCell($lfcoef, $calculationStatus, $valueStr, $fluidOverImg, $dhpOverImg) {
        $sConso = "";

        if ($calculationStatus != 0) {
            if (($calculationStatus == 1) && ($lfcoef == 0.0)) {
                $sConso = "****";
            } else if ($calculationStatus == 1) {
                $sConso = $valueStr;
            } else if (($calculationStatus & 0x100) != 0) {
                $sConso = "";
                $sConso = $sConso . $fluidOverImg;
                if (($calculationStatus & 0x10) != 0) {
                    $sConso = $sConso + $dhpOverImg;
                }

            } else if (($calculationStatus & 0x10) != 0) {
                $sConso = $valueStr + "<br>" + $dhpOverImg;
            }

        } else {
            $sConso = "****";
        }

        return $sConso;
    }

    public function getDimaResultsByTr($idStudyEquipments){
        
    }
	
}