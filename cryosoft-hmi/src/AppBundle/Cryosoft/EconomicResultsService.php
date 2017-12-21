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

class EconomicResultsService
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

	public function isConsoToDisplay($dimaStatus, $equipStatus)
    {
        $dimaStatus = $dimaStatus & 0xFFFF;
        $ldStatus = true;
      
        if ($equipStatus == 1) {
            if ($equipStatus != 0) {
                if ($equipStatus == 1) {
                    $ldStatus = true;
                } else if (($equipStatus & 0x100) != 0)
                {
                    $ldStatus = false;
                } else
                {
                    $ldStatus = true;
                }     

            } else {
                $ldStatus = false;
            }

        } else {
            $ldStatus = false;
        }
      
      return $ldStatus;
    }
}