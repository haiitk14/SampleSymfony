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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\InitialTemperature;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\MeshGeneration;
use AppBundle\Entity\MeshPosition;
use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\Production;
use AppBundle\Entity\Packing;
use AppBundle\Entity\Product;
use AppBundle\Entity\Studies;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\PipeGen;
use AppBundle\Entity\Shape;
use Doctrine\ORM\EntityRepository;

class CheckControlService 
{

	private $doctrine;

	public function __construct(\Doctrine\ORM\EntityManager $doctrine) 
	{
		$this->doctrine = $doctrine;
	}

	private function getDoctrine() 
	{
		return $this->doctrine;
	}

	public function isStdCalcModeChecked($idUser, $idStudy) 
	{

		$studies = $this->getDoctrine()->getRepository(Studies::class)->findBy(['idStudy' => $idStudy, 'idUser' => $idUser]);

		if ($studies != null) {
			foreach ($studies as $study) {
				$calcMode = $study->getCalculationMode();
				if (($calcMode == 1) || ($calcMode == 2) || ($calcMode == 3)) {
					return true;
				}			
			}
		}

		return false ;
	}

	public function isStdCustomerChecked($currentStudy) 
	{

		if (($currentStudy == null) || ($currentStudy < 1)) {
			return false;
		}

		$prod = null;

		try {
			$prod = $this->loadCustomer($currentStudy);

		} catch (\Doctrine\ORM\EntityNotFoundException $ex) {
           return false;
        }

        if ($prod == null) {
        	return false;
        }

        if ($prod->getNbProdWeekPerYear() <= 0.0) {
        	return false;
        }

		return true;
	}

	public function isStdProductChecked($idStudy) 
	{
		
		try {
			$product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['idStudy' => $idStudy]);

			if ($product == null) {
	            return false;
	        }

	        $idProd = $product->getIdProd();
	        $productElmts = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $idProd]);

			if (count($productElmts) <= 0) {
				return false;
			}

	        return true;

        } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
           echo "Exception Found - " . $ex->getMessage() . "<br/>";
        }

		return false;
	}

	public function isStdMesh_InitTempChecked($idStudy, $idProd) 
	{

		try {
			$productElmt = $this->getDoctrine()->getRepository(ProductElmt::class)->findOneBy(['idProd' => $idProd]);
			$idProdElmt = null;

			if ($productElmt != null) {
				$idProdElmt = $productElmt->getIdProductElmt();
			}

			if ($idProdElmt != null) {
				$meshPositions = $this->getDoctrine()->getRepository(MeshPosition::class)->findBy(['idProductElmt' =>  $idProdElmt]);

				if (count($meshPositions) <= 0) {
					return false;
				}

				$meshGenerations = $this->getDoctrine()->getRepository(MeshGeneration::class)->findBy(['idProd' => $idProd]);

				if (count($meshGenerations) <= 0) {
					return false;
				}

				$production = $this->getDoctrine()->getRepository(Production::class)->findOneBy(['idStudy' => $idStudy]);

				if ($production == null) {
					return false;
				}

				$idProduction = $production->getIdProduction();
				$initialTemperatures = $this->getDoctrine()->getRepository(InitialTemperature::class)->findBy(
					['idProduction' => $idProduction]);

				if (count($initialTemperatures) <= 0) {
					return false;
				}

				return true;
			}

			return false;

		} catch (\Doctrine\ORM\EntityNotFoundException $ex) {
           echo "Exception Found - " . $ex->getMessage() . "<br/>";
        }

		return false;
	}

	public function isStdEquipmentChecked($idStudy, $loadEquipment) 
	{

		if (($loadEquipment == null) || ($loadEquipment == false)) {
			return false;
		}

		$studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(['idStudy' => $idStudy]);

		if ($studyEquipment == null) {
			return false;
		}

		return true;
	}

	public function isStdPackingChecked($idStudy) 
	{
		try {
			$this->loadPacking($idStudy);

		} catch (\Doctrine\ORM\EntityNotFoundException $ex) {
           echo "Exception Found - " . $ex->getMessage() . "<br/>";
           return false;
        }
		return true;
	}

	public function isStdLineChecked($idStudy) 
	{
		$pipe = null;
		try {
			$pipe = $this->loadPipeline($idStudy);

		} catch (\Doctrine\ORM\EntityNotFoundException $ex) {
           echo "Exception Found - " . $ex->getMessage() . "<br/>";
           return false;
        }

        if ($pipe == null) {
            return false;
        }

		return true;
	}

	public function loadCustomer($idStudy) 
	{
		$prod = null;
		try {
			$prod = $this->loadDBCustomer($idStudy);

		} catch (\Doctrine\ORM\EntityNotFoundException $ex) {
           echo "Exception Found - " . $ex->getMessage() . "<br/>";
        }

        return $prod;
	}

	public function loadDBCustomer($idStudy) 
	{
		$prod = new Production();

		$production = $this->getDoctrine()->getRepository(Production::class)->findOneBy(['idStudy' => $idStudy]);

		return $production;
	}

	public function loadPacking($idStudy) 
	{
		$product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['idStudy' => $idStudy]);

		if ($product == null) {
			return false;
        }

		$idProd = $product->getIdProd();
        $productElmt = $this->getDoctrine()->getRepository(ProductElmt::class)->findOneBy(['idProd' => $idProd]);
		$idShape = null;

		if ($productElmt != null) {
			$id = $productElmt->getIdShape();
			$idShape = $id->getIdShape();
		}

		if ($productElmt == null) {
			return false;
		}

		$shape = $this->getDoctrine()->getRepository(Shape::class)->findOneBy(['idShape' => $idShape]);

		if ($shape == null) {
            return false;
        }

        $packing = $this->getDoctrine()->getRepository(Packing::class)->findOneBy(['idStudy' => $idStudy]);

		if ($packing == null) {
			return false;
		}

		return true;
	}

	public function loadPipeline($idStudy)
	{	
		$studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(['idStudy' => $idStudy]);
		$pipeGen = null;
		
		if ($studyEquipment != null) {
			$countPipe = 0;
			$idStudyEquipments = $studyEquipment->getIdStudyEquipments();
			$pipeGen = $this->getDoctrine()->getRepository(PipeGen::class)->findOneBy(['idStudyEquipments' => $idStudyEquipments]);
		}
		return $pipeGen;
	}

	public function isCheckControl($idUser, $idStudy, $loadEquipment, $idProd) 
	{
		$isCheckControl = false;

		if ($this->isStdCalcModeChecked($idUser, $idStudy) && $this->isStdCustomerChecked($idStudy) 
			&& $this->isStdProductChecked($idStudy) && $this->isStdMesh_InitTempChecked($idStudy, $idProd) && 
			$this->isStdEquipmentChecked($idStudy, $loadEquipment) && $this->isStdPackingChecked($idStudy) 
			&& $this->isStdLineChecked($idStudy)) {
			$isCheckControl = true;
		} 

		return $isCheckControl;
	}
}