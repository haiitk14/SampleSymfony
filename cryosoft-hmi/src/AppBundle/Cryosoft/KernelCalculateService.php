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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class KernelCalculateService
{

	public function __construct(ContainerInterface $container, \Doctrine\ORM\EntityManager $doctrine, 
		TokenStorageInterface $tokenStorage, Session $session) 
	{
		$this->doctrine = $doctrine;
		$this->user = $tokenStorage->getToken()->getUser();
		$this->container = $container;
	}

	private function getDoctrine() 
	{
		return $this->doctrine;
	}

	public function getKernelObject($name)
	{
		$ic = \Ice\initialize();
		$base = $ic->stringToProxy("{$name}:tcp -h ". $this->container->getParameter('kernel_host') ." -p ". $this->container->getParameter('kernal_port') ."");
		$className = "\\Cryosoft\\$name\\I{$name}PrxHelper";
		$obj = call_user_func( array( $className, 'checkedCast' ),$base );
		if(!$obj) {
	        throw new RuntimeException("Invalid proxy");
	    }
		return $obj;
	}

	public function testKernel($productId, $componentId = 0, $mode = 3){
		$ic = null;
		try
		{
		    $wc = $this->getKernelObject("WeightCalculator");
		    
		    $conf = new \Cryosoft\stSKConf(
		    	$this->container->getParameter('kernal_odbc'),
		    	$this->container->getParameter('kernel_user'), 
		    	$this->container->getParameter('kernel_pass'), 
		    	$this->container->getParameter('kernel_log'), 
		    	$productId, $componentId, 1, 1
		    );
		 
		    return $wc->WCWeightCalculation($conf, $mode);
		}
		catch(Exception $ex)
		{
		    return $ex;
		}
		 
		if($ic)
		{
		    $ic->destroy(); // Clean up
		}
	}

	public function meshBuilder($idStudy) 
	{
		$ic = null;
			// $idStudy = 2;
		 	// dump($idStudy); die();
		try
		{
		    $mb = $this->getKernelObject("MeshBuilder");
		    $conf = new \Cryosoft\stSKConf(
		    	$this->container->getParameter('kernal_odbc'),
		    	$this->container->getParameter('kernel_user'), 
		    	$this->container->getParameter('kernel_pass'), 
		    	$this->container->getParameter('kernel_log'), 
		    	$idStudy, 0, 1, 1
		    );
		    dump($mb->MBMeshBuild($conf)); die();
		    return $mb->MBMeshBuild($conf);
		}
		catch(Exception $ex)
		{
		    return $ex;
		}
		 
		if($ic)
		{
		    $ic->destroy(); // Clean up
		}
	}

}

