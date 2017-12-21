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
use AppBundle\Entity\MinMax;
use AppBundle\Cryosoft\StudyService;


class PackingService
{
	private $doctrine;
	private $user;
	private $studyService;

	public function __construct(\Doctrine\ORM\EntityManager $doctrine, TokenStorageInterface $tokenStorage, Session $session, StudyService $studyService) 
	{
		$this->doctrine = $doctrine;
		$this->user = $tokenStorage->getToken()->getUser();
		$this->session = $session;
		$this->studyService = $studyService;
	}

	private function getDoctrine() 
	{
		return $this->doctrine;
	}

	public function setPackingToStudies($idStudy, $idPacking)
    {
    	$em = $this->getDoctrine();
        $study = $this->studyService->getInfoStudy($idStudy);
        $study->setIdPacking($idPacking);
        $em->flush();
    }
}