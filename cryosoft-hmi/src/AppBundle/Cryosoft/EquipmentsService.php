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
use AppBundle\Entity\LayoutGeneration;
use AppBundle\Entity\LayoutResults;
use AppBundle\Entity\Studies;
use AppBundle\Entity\MinMax;
use AppBundle\Entity\Post;
use AppBundle\Entity\StudEqpPrm;
use AppBundle\Cryosoft\UnitsConverterService;

class EquipmentsService
{
	
	private $doctrine;
	private $studyequipment;
	private $layoutgeneration;
	private $layoutresults;
	private $user;

	public function __construct(\Doctrine\ORM\EntityManager $doctrine, TokenStorageInterface $tokenStorage, UnitsConverterService $unit, Session $session) 
	{
		$this->doctrine = $doctrine;
		$this->user = $tokenStorage->getToken()->getUser();
        $this->_unit = $unit;
        $this->session = $session;
	}

	private function getDoctrine() 
	{
		return $this->doctrine;
	}

	public function getStudyEquipment()
	{
		return 10;
	}

	public function getCapability($capMask, $idEquip)	
    {
        $equipment = $this->getDoctrine()->getRepository(Equipment::class)->findOneBy(['idEquip' => $idEquip]);
        $capabilities = $equipment->getCapabilities();

        return ($capabilities & $capMask) != 0;
    }

    public function getLoadingRate($idLayoutResults)
    {
    	$layoutResult = $this->getDoctrine()->getRepository(LayoutResults::class)->findOneBy(['idLayoutResults' => $idLayoutResults]);
    	$loadingRate = $layoutResult->getLoadingRate();

    	return $loadingRate;
    }

    public function getTr($idStudyEquipments)
    {
    	$studEqpPrms = $this->loadStudEqpPrm($idStudyEquipments, 300);
    	$tR = array();

    	if (!empty($studEqpPrms)) {
    		foreach ($studEqpPrms as $prms) {
    			array_push($tR, $prms->getValue());
    		 } 
    	}

    	return $tR;
    }

    public function getTs($idStudyEquipments)
    {
    	$studEqpPrms = $this->loadStudEqpPrm($idStudyEquipments, 200);
    	$tS = array();

    	if (!empty($studEqpPrms)) {
    		foreach ($studEqpPrms as $prms) {
    			array_push($tS, $prms->getValue());
    		 } 
    	}
    	
    	return $tS;
    }

    public function loadStudEqpPrm($idStudyEquipments, $dataType)
    {
        $studEqpPrms = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder('s')
                    ->where('s.valueType >= :valueType1 AND s.valueType < :valueType2')
                    ->andWhere('s.idStudyEquipments = :idStudyEquipments')
                    ->setParameter('valueType1', $dataType)
                    ->setParameter('valueType2', $dataType + 100)
                    ->setParameter('idStudyEquipments', $idStudyEquipments)
                    ->getQuery()->getResult();

    	return $studEqpPrms;
    }
	
	public function getEquipmentLayout(Request $request, $idEquip)
	{
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $em = $this->getDoctrine(); 

        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objEquip = $this->getDoctrine()->getRepository(Equipment::class)->find($idEquip);
        $objStudyEquip = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(
        	['idStudy' => $objStudy->getIdStudy(), 'idEquip' => $objEquip->getIdEquip()],['idStudyEquipments' => 'DESC']);

        if (count($objStudyEquip) > 0) {
            $objLayoutGener = $this->getDoctrine()->getRepository(LayoutGeneration::class)->findBy(
            	['idStudyEquipments' => $objStudyEquip[0]->getIdStudyEquipments()]);

            if (count($objLayoutGener) > 0) {
                $rsObjLayoutGeneration = $objLayoutGener[0];
            } else {
                $objLayoutGener_New = new LayoutGeneration();
                $objLayoutGener_New->setIdStudyEquipments($objStudyEquip[0]);
                $objLayoutGener_New->setProdPosition(1);

                $equipWithSpecificSize = false;

                if ($objStudyEquip[0]->getStdeqpWidth() != -1 && $objStudyEquip[0]->getStdeqpLength() != -1) {
                    $equipWithSpecificSize = true;
                }

                if ($equipWithSpecificSize) {
                    $objLayoutGener_New->setShelvesType(2);
                    $objLayoutGener_New->setShelvesLength($objStudyEquip[0]->getStdeqpLength());
                    $objLayoutGener_New->setShelvesWidth($objStudyEquip[0]->getStdeqpWidth());
                } else {
                    $batch = $objStudyEquip[0]->getIdEquip()->getIdEquipseries()->getIdFamily()->isBatchProcess();
                    if ($batch) {
                        $objLayoutGener_New->setShelvesType(0);
                        $objLayoutGener_New->setShelvesLength(0.8);
                        $objLayoutGener_New->setShelvesWidth(0.6);
                    } else {
                        $objLayoutGener_New->setShelvesType(2);
                        $objLayoutGener_New->setShelvesLength($objStudyEquip[0]->getIdEquip()->getEqpLength());
                        $objLayoutGener_New->setShelvesWidth($objStudyEquip[0]->getIdEquip()->getEqpLength());
                    }

                    $objLayoutGener_New->setLengthInterval(-1);
                    $objLayoutGener_New->setWidthInterval(-1);
                }

                $em->persist($objLayoutGener_New);                
                $em->flush();

                $objLayoutGener = $this->getDoctrine()->getRepository(LayoutGeneration::class)->findBy(
                	['idStudyEquipments' => $objStudyEquip[0]->getIdStudyEquipments()], ['idLayoutGeneration' => 'DESC']);
                
                $rsObjLayoutGeneration = $objLayoutGener[0];
            }

            $objStudyEquip[0]->setIdLayoutGeneration($rsObjLayoutGeneration->getIdLayoutGeneration());
            $em->flush();
        }

        return $rsObjLayoutGeneration;       
    }

    public function getLayoutResults(Request $request, $idEquip)
    {
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $em = $this->getDoctrine();

        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objEquip = $this->getDoctrine()->getRepository(Equipment::class)->find($idEquip);
        $objStudyEquip = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(
        	['idStudy' => $objStudy->getIdStudy(), 'idEquip' => $objEquip->getIdEquip()], ['idStudyEquipments' => 'DESC']);

        $objLayoutRs = $this->getDoctrine()->getRepository(LayoutResults::class)->findBy(
        	['idStudyEquipments' => $objStudyEquip[0]->getIdStudyEquipments()]);

        if (count($objLayoutRs) > 0) {
            $idLayoutRs = $objLayoutRs[0];
        } else {
            $minMaxLoadingRate = $this->getDoctrine()->getRepository(MinMax::class)->findBy(
            	['limitItem' => Post::LIMIT_ITEM_LOADING_RATE]);

            $objLayoutRs_New = new LayoutResults();
            $objLayoutRs_New->setIdStudyEquipments($objStudyEquip[0]);
            $objLayoutRs_New->setLoadingRate($minMaxLoadingRate[0]->getDefaultValue());
            $objLayoutRs_New->setLoadingRateMax($minMaxLoadingRate[0]->getLimitMax());
            $em->persist($objLayoutRs_New);
            $em->flush();

            $objLayoutRs = $this->getDoctrine()->getRepository(LayoutResults::class)->findBy(
            	['idStudyEquipments' => $objStudyEquip[0]->getIdStudyEquipments()], ['idLayoutResults' => 'DESC']);
            $idLayoutRs = $objLayoutRs[0];
        }

        $rsObjLayoutResults = $idLayoutRs;
        $objStudyEquip[0]->setIdLayoutResults($idLayoutRs->getIdLayoutResults());
        $em->flush();

        return $rsObjLayoutResults;
    }

    public function generateLayouts(Request $request, $idStudyEquipments, $intervalLength, $intervalWidth, $orientation, $lengthShelves, $widthShelves, $numberofShelves)
    {
        $session = $request->getSession();
        $session->set('layoutGenerated', true);
        $em = $this->getDoctrine();

        $objStudyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $idEquip = $objStudyEquipments->getIdEquip()->getIdEquip();
        $rsObjLayoutGeneration = $this->getEquipmentLayout($request, $idEquip);

		// Convert prodDimension before set $intervalLength, $intervalWidth 
        $rsObjLayoutGeneration->setLengthInterval($intervalLength);
        $rsObjLayoutGeneration->setWidthInterval($intervalWidth);
        $rsObjLayoutGeneration->setProdPosition($orientation);

        if ($objStudyEquipments->getIdEquip()->getIdEquipseries()->getIdFamily()->isBatchProcess()) {
            $rsObjLayoutGeneration->setShelvesWidth($widthShelves);
            $rsObjLayoutGeneration->setShelvesLength($lengthShelves);
            $rsObjLayoutGeneration->setNbShelvesPerso($numberofShelves);
        }

        $em->flush();
		// Run Layout Calculator: update layout generation to database and call kernel LayoutCalculation run StartLCCalculation
    }

    public function saveTop1(Request $request, $idStudyEquipments, $toc, $orientation)
    {
        $em = $this->getDoctrine();
        $session = $request->getSession();
        $objStudyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);

        $idEquip = $objStudyEquipments->getIdEquip()->getIdEquip();
        $rsObjLayoutResults = $this->getLayoutResults($request, $idEquip);
        $rsObjLayoutGeneration = $this->getEquipmentLayout($request, $idEquip);
        $layoutGenerated = $session->get('layoutGenerated');
        $flag = $session->get('flag');

        if ($flag || $layoutGenerated) {
			// Get value belt coverage (use function toc convert)
            $rsObjLayoutResults->setLoadingRate($toc);
            $rsObjLayoutResults->setLeftRightInterval(0);
            $rsObjLayoutResults->setNumberInWidth(0);
            $rsObjLayoutResults->setNumberPerM(0);
        } 

        $rsObjLayoutGeneration->setProdPosition($orientation);
        $rsObjLayoutGeneration->setLengthInterval(-2);
        $rsObjLayoutGeneration->setWidthInterval(-2);
        $em->flush();
    }

    public function saveTop2(Request $request, $idStudyEquipments, $orientation, $shelvesType, $lengthShelves, $widthShelves, $numberofShelves, $toc)
    {
        $em = $this->getDoctrine();
        $session = $request->getSession();
        $objStudyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $idEquip = $objStudyEquipments->getIdEquip()->getIdEquip();

        $rsObjLayoutResults = $this->getLayoutResults($request, $idEquip);
        $rsObjLayoutGeneration = $this->getEquipmentLayout($request, $idEquip);
        $rsObjLayoutGeneration->setProdPosition($orientation);
        $layoutGenerated = $session->get('layoutGenerated');

        if ($layoutGenerated) {
            $showDims = $session->get('showDims');

            if (!$showDims) {
                $shelvesType = 2;
            }

            if ($shelvesType == 0) {
                $rsObjLayoutGeneration->setShelvesLength(0.8);
                $rsObjLayoutGeneration->setShelvesWidth(0.6);
            }

            if ($shelvesType == 1) {
                $rsObjLayoutGeneration->setShelvesLength(0.65);
                $rsObjLayoutGeneration->setShelvesWidth(0.53);
            }

            if ($shelvesType == 2) {
				// Use function shelvesWidth convert before set
                $rsObjLayoutGeneration->setShelvesLength($lengthShelves);
                $rsObjLayoutGeneration->setShelvesWidth($widthShelves);
            }

            $rsObjLayoutGeneration->setShelvesType($shelvesType);
            $rsObjLayoutGeneration->setNbShelvesPerso($numberofShelves);
        }

        $flag = $session->get('flag');

        if ($flag) {
            $rsObjLayoutGeneration->setLengthInterval(-2);
            $rsObjLayoutGeneration->setWidthInterval(-2);
            $rsObjLayoutResults->setLoadingRate($toc);
            $rsObjLayoutResults->setLeftRightInterval(0);
            $rsObjLayoutResults->setNumberInWidth(0);
            $rsObjLayoutResults->setNumberPerM(0);
            $em->flush();
        } else {

            if ($rsObjLayoutGeneration->getLengthInterval() != -2) {
                $rsObjLayoutGeneration->setLengthInterval(-1);
            }

            if ($rsObjLayoutGeneration->getWidthInterval() != -2) {
                $rsObjLayoutGeneration->setWidthInterval(-1);
            }
        }

        $em->flush();
    }

    public function getResultsEquipName($idStudyEquipments) {
        $sname = "";
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments"=>$idStudyEquipments]);
        $equip = $studyEquipment->getIdEquip();
        $capabilitie = $equip->getCapabilities();

        if (($equip->isStd() == 1) && (!($this->getCapabilityNnc($capabilitie , 32768))) && (!($this->getCapabilityNnc($capabilitie , 1048576)))) {

            $n = $equip->getIdEquipseries()->getSeriesName();
            $equipParameter = $equip->getEqpLength()  +  ($studyEquipment->getNbModul()  *  $equip->getModulLength());
            $n1 = $this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $equipParameter);
            $n2 = $this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $equip->getEqpWidth());
            $n3 = $equip->getEquipVersion();
            $sname = $n . " - ". $n1." x ".$n2." (v".$n3.")";

        } else if (($this->getCapabilityNnc($capabilitie , 1048576)) && ($equip->getEqpLength() != -1.0) && ($equip->getEqpWidth() != -1.0)) {
            $sname = $equip->getEquipName() . " - " . $this->getSpecificEquipSize($idStudyEquipments);
        } else {
            $sname = $equip->getEquipName();
        }

        return $sname;
    }

    public function getSpecificEquipSize($idStudyEquipments) {
        $sname = "";
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments"=>$idStudyEquipments]);
        $equip = $studyEquipment->getIdEquip();
        $capabilitie = $equip->getCapabilities();

        if (($this->getCapabilityNnc($capabilitie , 1048576)) && ($studyEquipment->getStdEqpLength() != -1.0) && ($studyEquipment->getStdeqpWidth() != -1.0)) {

            $stdEqpLength = $this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $studyEquipment->getStdEqpLength());
            $stdeqpWidth = $this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $studyEquipment->getStdeqpWidth());

            $sname = "(" . $stdEqpLength . "x" . $stdeqpWidth + ")";
        }

        return $sname;
    }
 

    public function getSpecificEquipName($idStudyEquipments) 
    {
        $sEquipName = "";
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments"=>$idStudyEquipments]);
        $equip = $studyEquipment->getIdEquip();
        $capabilitie = $equip->getCapabilities();
        if (($equip->isStd() == 1) && (!($this->getCapabilityNnc($capabilitie , 32768))) && (!($this->getCapabilityNnc($capabilitie , 1048576)))) {
            $n = $equip->getIdEquipseries()->getSeriesName();
            $equipParameter = $equip->getEqpLength()  +  ($studyEquipment->getNbModul()  *  $equip->getModulLength());
            $n1 = $this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $equipParameter);
            $n2 = $this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $equip->getEqpWidth());
            $n3 = $equip->getEquipVersion();
            $sEquipName = $n . " - ". $n1." x ".$n2." (v".$n3.")";
        } else if (($this->getCapabilityNnc($capabilitie , 1048576)) && ($equip->getEqpLength() != -1.0) && ($equip->getEqpWidth() != -1.0)) {
            $stdEqpLength = $this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $equip->getEqpLength());
            $stdeqpWidth = $this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $equip->getStdeqpWidth());
            $sEquipName = $equip->getEquipName() . " - " . $stdEqpLength . "x" . $sEquipName;
        } else {
            $sEquipName = $equip->getEquipName();
        }
               
        return $sEquipName;

    }

    public function getCapabilityNnc($capabilities, $capMask)
    {
        if (($capabilities & $capMask) != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function initEnergyDef()
    {
        $energyDef = 0;
        $session = $this->session;
        $idStudy = $session->get("idStudy");
        
        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        if (!empty($objStudy)) {
            $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);

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
}