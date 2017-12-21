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
use AppBundle\Entity\Studies;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\Report;
use AppBundle\Entity\PrecalcLdgRatePrm;
use AppBundle\Entity\Packing;
use AppBundle\Entity\Prices;
use AppBundle\Entity\Product;
use AppBundle\Entity\Production;
use AppBundle\Entity\TempRecordPts;
use AppBundle\Entity\Translation;
use AppBundle\Entity\Component;
use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\Notice;
use AppBundle\Entity\TempRecordPtsDef;
use AppBundle\Entity\LayoutGeneration;
use AppBundle\Entity\LayoutResults;
use AppBundle\Entity\MinMax;
use AppBundle\Entity\Post;
use AppBundle\Entity\PackingLayer;
use AppBundle\Entity\ProdcharColors;
use AppBundle\Entity\MeshPosition;
use AppBundle\Entity\MeshGeneration;
use AppBundle\Entity\InitialTemperature;
use AppBundle\Entity\LineDefinition;
use AppBundle\Entity\PipeGen;
use AppBundle\Entity\CalculationParameters;
use AppBundle\Entity\StudEqpPrm;
use AppBundle\Entity\StudyResults;
use AppBundle\Entity\Equipment;
use AppBundle\Entity\Ramps;
use AppBundle\Entity\Shelves;
use AppBundle\Entity\Consumptions;
use AppBundle\Entity\EquipZone;
use AppBundle\Entity\Equipcharact;
use AppBundle\Entity\Compenth;
use AppBundle\Entity\LineElmt;





class  StudyService
{
	private $doctrine;

	public function __construct(\Doctrine\ORM\EntityManager $doctrine, TokenStorageInterface $tokenStorage) 
	{
		$this->doctrine = $doctrine;
		$this->user = $tokenStorage->getToken()->getUser();
	}

	private function getDoctrine() 
	{
		return $this->doctrine;
	}

    public function getInfoStudy($idStudy)
    {
        $rs = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);

        return $rs;
    }

    public function getInfoPacking($idPacking)
    {
        $rs = $this->getDoctrine()->getRepository(Packing::class)->find($idPacking);

        return $rs;
    }

    public function getInfoProduct($idProduct)
    {
        $rs = $this->getDoctrine()->getRepository(Product::class)->find($idProduct);

        return $rs;
    }

    public function getInfoProductElmt($idProductElmt)
    {
        $rs = $this->getDoctrine()->getRepository(ProductElmt::class)->find($idProductElmt);

        return $rs;
    }

    public function getInfoProduction($idProduction)
    {
        $rs = $this->getDoctrine()->getRepository(Production::class)->find($idProduction);

        return $rs;
    }

    public function getInfoPipeGen($idPipeGen)
    {
        $rs = $this->getDoctrine()->getRepository(PipeGen::class)->find($idPipeGen);

        return $rs;
    }

    public function getInfoStudyEquipments($idStudyEquipments)
    {
        $rs = $this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);

        return $rs;
    }

    public function getInfoEquipment($idEquip)
    {
        $rs = $this->getDoctrine()->getRepository(Equipment::class)->find($idEquip);

        return $rs;
    }

    public function getInfoComponent($idComp)
    {
        $rs = $this->getDoctrine()->getRepository(Component::class)->find($idComp);

        return $rs;
    }


	public function getCalculationMode($idStudy, $idUser)
	{
		$calcMode = 0;
		$study = $this->getDoctrine()->getRepository(Studies::class)->findOneBy(["idStudy" => $idStudy, "idUser" => $idUser]);

		if ($study != null) {
			$calcMode = $study->getCalculationMode();
		}
		return $calcMode;
	}

	public function cleanStudyBeansUsedInSession(Request $request)
	{
		$this->removeStudyAttributes($request);
		$session = $request->getSession();
        $studyBean = new Studies();
        $session->set('studyBean', $studyBean);
		$session->set('REFRESH_STUDY_NAME', '');
        $session->set('BANINOUTAUTHORIZED', 'NO');

	}

	public function removeStudyAttributes(Request $request) 
	{
        $session = $request->getSession(); 
        $session->remove("idStudy");
        $session->remove("studyBean");
        $session->remove("eqpform");
        $session->remove("eqpprm");
        $session->remove("top");
        $session->remove("calculate");
        $session->remove("brainCalculate");
        $session->remove("heatBalance");
        $session->remove("balanceTh");
        $session->remove("balanceConso");
        $session->remove("balanceEco");
        $session->remove("SizingResultBean");
        $session->remove("tempProfile");
        $session->remove("meshAppletBean");
        $session->remove("outProductChart");
        $session->remove("enthalpie");
        $session->remove("isovaleur");
        $session->remove("contentReport");
        $session->remove("checkControlBean");
        $session->remove("CalcStatusBean");
    }

    public function refreshStudies(Request $request, $idStudy)
    {
    	$objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
    	$objReport = array();

    	if($objStudy->getIdReport() > 0){
    		$idReport = $objStudy->getIdReport();
    		$objReport = $this->getDoctrine()->getRepository(Report::class)->find($idReport);
    	}
    	$objPrecalcLdgRatePrm = array();

    	if($objStudy->getIdPrecalcLdgRatePrm() > 0){
    		$idPrecalcLdgRatePrm = $objStudy->getIdPrecalcLdgRatePrm();
    		$objPrecalcLdgRatePrm = $this->getDoctrine()->getRepository(PrecalcLdgRatePrm::class)->find($idPrecalcLdgRatePrm);
    	}
    	$objPacking = array();

    	if($objStudy->getIdPacking() > 0){
    		$idPacking = $objStudy->getIdPacking();
    		$objPacking = $this->getDoctrine()->getRepository(Packing::class)->find($idPacking);
    	}
    	$objPrice = array();

    	if($objStudy->getIdPrice() > 0){
    		$idPrice = $objStudy->getIdPrice();
    		$objPrice = $this->getDoctrine()->getRepository(Prices::class)->find($idPrice);
    	}
    	$objProd = array();

    	if($objStudy->getIdProd() > 0){
    		$idProd = $objStudy->getIdProd();
    		$objProd = $this->getDoctrine()->getRepository(Product::class)->find($idProd);
    	}
    	$objProduction = array();

    	if($objStudy->getIdProduction() > 0){
    		$idProduction = $objStudy->getIdProduction();
    		$objProduction = $this->getDoctrine()->getRepository(Production::class)->find($idProduction);
    	}
    	$objTempRecordPts = array();

    	if($objStudy->getIdTempRecordPts() > 0){
    		$idTempRecordPts = $objStudy->getIdTempRecordPts();
    		$objTempRecordPts = $this->getDoctrine()->getRepository(TempRecordPts::class);
    	}
    	$objUser = array();

    	if($objStudy->getIdUser()->getIdUser() > 0){
    		$idUser = $objStudy->getIdUser()->getIdUser();
    		$objUser = $this->getDoctrine()->getRepository(Ln2user::class)->find($idUser);
    	}
    }

    public function load($idStudy, $currentUser, Request $request)
    {
        $rs = false;
        $session = $request->getSession();
        $em = $this->getDoctrine();
        if($idStudy > 0){
            $study = $this->loadStudy($idStudy, $request);

            if($study != null){
                $idUserS = $study->getIdUser()->getIdUser();

                if($idUserS == $currentUser){
                    $study->setOpenByOwner(1);
                    $em->flush();
                }

                $this->loadStudyElements($idStudy, $currentUser, $request);
                $studys = $this->getInfoStudy($idStudy);

                if($studys->getOptionCryopipeline()){
                    $session->set('PIPELINE_LINK_ACCESS', 'YES');
                }else{
                    $session->set('PIPELINE_LINK_ACCESS', 'NO');
                }

                $session->set('REFRESH_STUDY_NAME', $studys->getStudyName());
                $session->set('SHOWEQPPRMCHANGE', 'YES');
                $rs = true;
            }
            $session->set('idStudy', $idStudy);
        }

        return $rs;
    }

    public function loadStudy($idStudy, Request $request)
    {
        $session = $request->getSession();
        $study = null;

        if (!$this->isStudyBrainRunning($idStudy)) {
            $study = $this->getInfoStudy($idStudy);
            if ($study->getOpenByOwner()) {
                $study = null;
            }
        } else {
            $session->getFlashBag()->set('error', Notice::STUDYLOAD_INPROGRESS_MSG);
        }

        return $study;
    }   

    public function getComboboxUser()
    {
        $objUser = $this->getDoctrine()->getRepository(Ln2user::class)->createQueryBuilder('u')
        ->where('u.userprio <> 0')->orderBy("u.usernam", 'ASC')->getQuery()->getResult();
        
        return $objUser;
    }

    public function getComboboxFamily($transType, $codeLang)
    {
        $objFamily = $this->getDoctrine()->getRepository(Translation::class)->createQueryBuilder('t')
        ->where('t.transType = :transType')->andWhere('t.codeLangue = :codeLang')
        ->setParameter('transType', $transType)->setParameter('codeLang', $codeLang)->getQuery()->getResult();

        return $objFamily;
    }

    public function getComboboxSubFamily($transType, $codeLang, $idFamily)
    {
        $addQuery = ""; 

        if($idFamily != 0){
            $addQuery = ' AND t.idTranslation >= '.($idFamily * 100). ' AND t.idTranslation < '. ($idFamily + 1) * 100;
        }
        $objSubFamily = $this->getDoctrine()->getRepository(Translation::class)->createQueryBuilder('t')
        ->where('t.transType = :transType')->andWhere('t.codeLangue = :codeLang'.$addQuery)
        ->setParameter('transType', $transType)->setParameter('codeLang', $codeLang)->getQuery()->getResult();

        return $objSubFamily;
    }

    public function searchAllStandardComponents($transType, $codeLang, $currentUser, $idStudy ,$idFamily, $idSubFamily, $percentWater)
    {
        $addStudy = "";
        $addFamily = "";
        $addSubFamily = "";
        $addPercentWater = "";

        if($idStudy != 0){
            $addStudy = " AND (c.compImpIdStudy = 0 OR c.compImpIdStudy = ".$idStudy.") AND (c.compRelease = 3 OR c.compRelease = 4 OR c.compRelease = 8 OR (c.compRelease = 2 AND c.idUser = ".$currentUser."))";
        }else{
            $addStudy = " AND c.compRelease <> 6";
        }

        if($idFamily != 0){
            $addFamily = " AND c.classType =".$idFamily;
        }

        if($idSubFamily != 0){
            $addSubFamily = " AND c.subFamily =".$idSubFamily;
        }

        if($percentWater > 0){
            $addPercentWater = " AND c.water >= ".($percentWater - 1) * 10 ." AND c.water <= ".$percentWater * 10;
        }

        $objCompnents = $this->getDoctrine()->getRepository(Component::class)->createQueryBuilder('c')
        ->select('t.idTranslation, t.label, l.idUser, l.usernam, c.compRelease, c.compVersion, c.openByOwner, l.codeLangue')
        ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idComp')
        ->leftJoin(Ln2user::class, 'l', 'WITH', 'l.idUser = c.idUser')
        ->where('t.transType = :transType')->andWhere('t.codeLangue = :codeLang'.$addStudy.$addFamily.$addSubFamily.$addPercentWater)
        ->setParameter('transType', $transType)->setParameter('codeLang', $codeLang)
        ->getQuery()->getResult();

        return $objCompnents;
    }

    public function getFilteredStudiesList($idUser, $idCompFamily, $idCompSubFamily, $idComponent, $currentUser, $idSelectedStudy)
    {
        $addCompFamily = "";
        $addCompSubFamily = "";
        $addComponent = "";
        $addWho = "";

        if($idCompFamily > 0){
            $addCompFamily = " AND c.classType = " . $idCompFamily;
        }

        if($idCompSubFamily > 0){
            $addCompSubFamily = " AND c.subFamily = " . $idCompSubFamily;
        }

        if($idComponent > 0){
            $addComponent = " AND pe.idComp = " . $idComponent;
        }

        if($idUser > 0 || $idCompFamily > 0 || $idCompSubFamily > 0 || $idComponent > 0){
            if($idUser > 0){
                $addWho = " s.idUser = " . $idUser ;
            }else{
                $addWho = " s.idUser = " . $currentUser ;
            }
        }else{
            $addWho = " s.idUser = " .$currentUser . " OR s.idUser <> ".$currentUser;
        }
        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->createQueryBuilder("s")
        ->select('s.idStudy, s.studyName, l.idUser')
        ->leftJoin(ProductElmt::class, 'pe', 'WITH', 'pe.idProd = s.idProd')
        ->leftJoin(Component::class, 'c', 'WITH', 'pe.idComp = c.idComp')
        ->leftJoin(Ln2user::class, 'l', 'WITH', 'l.idUser = s.idUser')
        ->where($addWho . $addCompFamily . $addCompSubFamily . $addComponent)
        ->distinct(true)->orderBy("s.studyName", 'ASC')
        ->getQuery()->getResult();

        return $objStudy;
    }

    public function makeSelectOptionLoggedOnUser($idStudy, $idUser)
    {   
        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $idStudy = $objStudy->getIdStudy();

        if($this->isStudyBrainRunning($idStudy)){
            $classOptionStudy = "studyInProgress";
        }else{
            if($this->isStudyIsToRecalculate($objStudy)){
                $classOptionStudy = "studyIsToRecalculate";
            }else{
                $classOptionStudy = "mineElement";
            }
        }

        if($idUser > 0){
            $objUser = $this->getDoctrine()->getRepository(Ln2user::class)->find($idUser);
            $nameStudy = $objStudy->getStudyName() . " - " . $objUser->getUsernam();
        }else{
            $nameStudy = $objStudy->getStudyName() . " - unkown"  ;
        }

        return $rs = [
                'classOptionStudy' => $classOptionStudy,
                'studyName'=> $nameStudy
            ];
    }

    public function makeSelectOptionOtherUser($idStudy, $idUser)
    {
        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $idStudy = $objStudy->getIdStudy();

        if($objStudy->getOpenByOwner()){
            $classOptionStudy = "studyLocked";

        }else if($this->isStudyBrainRunning($idStudy)){
            $classOptionStudy = "studyLocked";

        }else{

            if($this->isStudyIsToRecalculate($objStudy)){
                $classOptionStudy = "studyIsToRecalculate";
            }else{
                $classOptionStudy = "userElement";
            }
        }

        if($idUser > 0){
            $objUser = $this->getDoctrine()->getRepository(Ln2user::class)->find($idUser);
            $nameStudy = '( '. $objStudy->getStudyName() . " - " . $objUser->getUsernam() . ' )';
        }else{
            $nameStudy = '( '. $objStudy->getStudyName() . " - unkown )" ;
        }

        return $rs = [
                'classOptionStudy' => $classOptionStudy,
                'studyName'=> $nameStudy
            ];
    }

    public function isStudyBrainRunning($idStudy) 
    {
        $isBrainRunning = false;
        $obj = $this->getDoctrine()->getRepository(StudyEquipments::class)->createQueryBuilder('se')
           ->where('se.idStudy = :idStudy AND se.equipStatus = :equipStatus')
           ->setParameter('idStudy', $idStudy)->setParameter('equipStatus', 100000)->getQuery()->getResult();

        if(count($obj) > 0){
            $isBrainRunning = true;
        }

        return $isBrainRunning;
    }

    public function isStudyIsToRecalculate($study) 
    {
        $bret = false;
        if ($study->getChainingControls() == 1 && $study->getParentId() > 0 && $study->getToRecalculate() == 1) {
            $bret = true;
        }

        return $bret;
    }

    public function loadStudyElements($idStudy, $currentUser, Request $request)
    {
        $chaining = null;
        $this->loadTempRecordPts($idStudy, $currentUser, $request);

        if ($this->isStudyHasFamilly($idStudy)) {
           $chaining = $this->loadChainingStudies($idStudy, $request);
        }

        // return $chaining;
    }

    public function loadTempRecordPts($idStudy, $currentUser, Request $request)
    {
        if($idStudy > 0){
            $study = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
            $idStudys = $study->getIdStudy();
            $temp = $this->getTempRecordPts($idStudys);
            
            if($temp == null){
               $createTemp = $this->createTempRecordPts($idStudy, $currentUser);               
            }else{
                $idTemp = $temp->getIdTempRecordPts();
                $this->saveTempRecordPts($idStudy, $idTemp);
            }
        }
    }

    public function getTempRecordPts($idStudy) 
    {
        $tempRecordPts = null;

        try {
            $study = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
            $idStudys = $study->getIdStudy();
            $rs = $this->getDoctrine()->getRepository(TempRecordPts::class)->findBy(['idStudy' => $idStudys]);
            
            if(count($rs) > 0){
                $tempRecordPts = $rs[0];
            }
        } catch (Exception $e) {
            $tempRecordPts = null;
        }

        return $tempRecordPts;
    }

     public function createTempRecordPts($idStudy, $currentUser) 
     {
        $bret = true;
        $em = $this->getDoctrine();
        try {
            $study = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
            $idStudy = $study->getIdStudy();
            $temps = new TempRecordPts();

            $tempDef = $this->getDoctrine()->getRepository(TempRecordPtsDef::class)->findBy(['idUser' => $currentUser]);
            $nbStep = $tempDef[0]->getNbStepsDef();
            $temps->setNbSteps($nbStep);
            $temps->setIdStudy($study);
            $em->persist($temps);
            $em->flush();

            $tempRs = $this->getDoctrine()->getRepository(TempRecordPts::class)->findBy(['idStudy' => $idStudy]);
            $idTemp = $tempRs[0]->getIdTempRecordPts();
            $this->saveTempRecordPts($idStudy, $idTemp);

        } catch (Exception $e) {

            $bret = false;
        }
            
        return $bret;
    }

    public function saveTempRecordPts($idStudy, $idTemp) 
    {
        $bret = true;
        $em = $this->getDoctrine();

        try {
            $study = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);

            if ($idTemp > 0) {
                $tempRs = $this->getDoctrine()->getRepository(TempRecordPts::class)->find($idTemp);
                $idTemps = $tempRs->getIdTempRecordPts();
                $study->setIdTempRecordPts($idTemps);
                $em->flush();
            }
        } catch (Exception $e) {

            $bret = false;
        }

        return $bret;
    }

    public function isStudyHasFamilly($idStudy) 
    {
        $bret = false;
        
        if($idStudy > 0){
            $study = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);

            if ($study->getChainingControls() && ($study->getHasChild() || $study->getParentId() > 0)) {
                $bret = true;
            }
        }
        
        return $bret;
    }

    public function loadChainingStudies($idStudy, Request $request)
    {
        $chaining = null;
        $equipname = "";
        
        if($idStudy > 0){
            $study = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);

            if ($study->getParentId() > 0) {
                $idStudyParent = $study->getParentId();
                $studyParent = $this->getDoctrine()->getRepository(Studies::class)->find($idStudyParent);
                $SEParent = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy' => $idStudyParent], ['idStudyEquipments' => "ASC"]);
                $number = 0;
                if(count($SEParent) > 0){

                    foreach ($SEParent as $key => $value) {
                        ++$number;
                        $idStudyEquipments = $value->getIdStudyEquipments();

                        if($value->getIdStudyEquipments() == $study->getParentStudEqpId()){
                            $displayString = $this->getEquipmentDisplayString($idStudyEquipments, $request);
                            $equipname = "( " . $number . " ): ". $displayString;
                            $lg = $this->getEquipmentLayout($idStudyEquipments);

                            break;
                        }

                    }    
                }

                $chaining['studyParent'] = [
                    'studyName' => $studyParent->getStudyName(),
                    'equipName' => $equipname
                ];
            }else{
                $chaining['studyParent'] = [
                    'studyName' => '',
                    'equipName' => ''
                ];
            }

            if($study->getHasChild()){
                $idStudyParent = $study->getIdStudy();
                $listStudyChild = $this->getDoctrine()->getRepository(Studies::class)->findBy(['parentId' => $idStudyParent], ['idStudy' => 'ASC']);

                if(count($listStudyChild) > 0){
                    $arrStudyName = array();
                    $arrEquip = array();

                    foreach ($listStudyChild as $key => $value) {
                        
                        $idStudy = $value->getIdStudy();
                        $studyName = $value->getStudyName();

                        $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudy);
                        $number = 0;
                        if(count($studyEquipments) > 0){

                            foreach ($studyEquipments as $key => $value) {
                                ++$number;
                                $idStudyEquipments = $value->getIdStudyEquipments();

                                if($value->getIdStudyEquipments() == $study->getParentStudEqpId()){
                                    $displayString = $this->getEquipmentDisplayString($idStudyEquipments, $request);
                                    $equipname = "( " . $number . " ): ". $displayString;
                                    $lg = $this->getEquipmentLayout($idStudyEquipments);

                                    break;
                                }
                            }    
                        }
                        array_push($arrStudyName, $studyName);
                        array_push($arrEquip, $equipname);
                    }
                    $chaining['studyChild'] = [
                        'studyName' => $arrStudyNamem,
                        'equipname' => $arrEquip
                    ]; 
                }
            }else{
                $chaining['studyChild'] = [
                    'studyName' => '',
                    'equipName' => ''
                ];
            }
        }
        return $chaining;
    }

    public function getEquipmentDisplayString($idStudyEquipments, Request $request) 
    {
        $DASH = " / ";
        $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $equip = $studyEquipments->getIdEquip();
        $st = "";

        if ($equip->isStd() && ($equip->getCapability() & 32768 ) == 0 && ($equip->getCapability() & 1048576) == 0 ) {
           
            $name1 = $equip->getSeriesName();
            $name2 = $equip->getEqpLength() + ( $studyEquipments->getNbModul() * $equip->getModulLength() );
            $name3 = $equip->getEqpWidth();
            $name4 = $equip->getEquipVersion();
            $name5 = $this->getLibValue(Post::TRANSTYPE_SUB_NAME_EQUIPMENT, $equip->getEquipRelease(), $request);

            $st = $name1 . " - " . $name2 . " x " . $name3 . " (v" . $name4 . ")/" . $name5;
        }else{

            if(($equip->getCapability() & 1048576 ) == 0 && $studyEquipments->getStdeqpLength() != -1 && $studyEquipments->getStdeqpWidth() != -1){
                $name1 = $equip->getEquipName();
                $name2 = $this->getSpecificEquipSize($idStudyEquipments);
                $name3 = $this->getLibValue(Post::TRANSTYPE_SUB_NAME_EQUIPMENT, $equip->getEquipRelease(), $request);

                $st = $name1 . " - " . $name2 . ' / ' . $name3;
            }else{
                $name1 = $equip->getEquipName();
                $name2 = $this->getLibValue(Post::TRANSTYPE_SUB_NAME_EQUIPMENT, $equip->getEquipRelease(), $request);
                
                $st = $name1 . " / " . $name2;
            }
        }

        return $st;
    }

    public function getLibValue($transType, $idTranslation, Request $request) 
    {
        $session = $request->getSession();
        $idCodeLang = $session->get('idCodeLang');
        $tran = $this->getDoctrine()->getRepository(Translation::class)->find(['transType' => $transType, 'idTranslation' => $idTranslation, 'codeLangue' => $idCodeLang]);
        
        return $tran == null ? "" : $tran->getLabel();
    }

    public function getSpecificEquipSize($idStudyEquipments) 
    {
        $sname = "";
        $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $equip = $studyEquipments->getIdEquip();
        
        if (($equip->getCapability() & 1048576) == 0 && $studyEquipments->getStdeqpLength() != -1 && $studyEquipments->getStdeqpWidth() != -1) {

            $sname = "(" + $studyEquipments->getStdeqpLength() + " x " + $studyEquipments->getStdeqpWidth() + ")";
        }

        return $sname;
    }

    public function getEquipmentLayout($idStudyEquipments)
    {
        $lg = null;
        $em = $this->getDoctrine();

        if($idStudyEquipments > 0){
            try {
                $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);

                if(count($studyEquipments) > 0){
                    $idStudyEquipments = $studyEquipments->getIdStudyEquipments();
                    $layoutGeneration = $this->getDoctrine()->getRepository(LayoutGeneration::class)->findBy(['idStudyEquipments' => $idStudyEquipments]);

                    if (count($layoutGeneration) == 0) {
                        $lg = new LayoutGeneration();
                        $lg->setIdStudyEquipments($studyEquipments);
                        $lg->setProdPosition(1);

                        $equipWithSpecificSize = $studyEquipments->getStdeqpWidth() != -1 && $studyEquipments->getStdeqpLength() != -1;

                        if ($equipWithSpecificSize) {
                            $lg->setShelvesType(2);
                            $lg->setShelvesLength($studyEquipments->getStdeqpLength());
                            $lg->setShelvesWidth($studyEquipments.getStdeqpWidth());
                        } else if ($studyEquipments->isBatch()) {
                            $lg->setShelvesType(0);
                            $lg->setShelvesLength(0.8);
                            $lg->setShelvesWidth(0.6);
                        } else {
                            $lg.setShelvesType(2);
                            $lg.setShelvesLength($studyEquipments->getEquip()->getEqpLength());
                            $lg.setShelvesWidth($studyEquipments->getEquip()->getEqpWidth());
                        }

                        $lg->setLengthInterval(-1);
                        $lg->setWidthInterval(-1);
                        $em->persist($lg);
                        $em->flush();

                        $idLayoutGeneration = $lg->getIdLayoutGeneration();
                        $studyEquipments->setIdLayoutGeneration($idLayoutGeneration);
                        $em->flush();
                    }else{
                        $lg = $layoutGeneration[0];
                    }
                }
            } catch (Exception $e) {
                $lg = null;
            }
        }

        return $lg;
    }

    public function isStudyOfUser($idUser, $idStudy)
    {
        $rs = false;
        $user = $this->getDoctrine()->getRepository(Ln2user::class)->find($idUser);
        $study = $this->getInfoStudy($idStudy);
        $studys = $this->getDoctrine()->getRepository(Studies::class)->findBy(['idUser' => $user->getIdUser(), 'idStudy' => $study->getIdStudy()]);

        if(count($studys) > 0){
            $rs = true;
        }

        return $rs;
    }

    public function RunStudyCleaner($idStudy) 
    {
        return $this->RunStudyCleaner2($idStudy, -1);
    }

    public function RunStudyCleaner2($idStudy, $ld_StudEqpId) 
    {
        // this.log.debug("Run Study CLeaner: mode= " + ld_Mode + " / Id study equipments= " + ld_StudEqpId);
        // $ret = false;
        // $ret = this.Cleaner.RunStudyCleaner(this.mySTD.getIdStudy(), ld_StudEqpId, ld_Mode);
        // if ($ret == 0 && $this->isStudyHasChilds()) {
            $ret = $this->setChildsStudiesToRecalculate($idStudy, -1);
        // }

        return $ret;
    }

    public function setChildsStudiesToRecalculate($idStudy, $ld_StudEqpId) {
        $ret = 0;
        $em = $this->getDoctrine();
        if ($this->isStudyHasChilds($idStudy)) {
            $childStudy = null;

            $std = $this->loadChainingChilds($idStudy);

            foreach ($std as $key => $value) {

                if($ld_StudEqpId != -1 && $ld_StudEqpId != $value->getParentStudEqpId()){
                    $value->setToRecalculate(1);
                    $em->flush();
                }
            }
        }

        return $ret;
    }

    public function isStudyHasChilds($idStudy) {
        $bret = false;
        $study = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);

        if ($study->getChainingControls() && $study->getHasChild()) {
            $bret = true;
        }

        return $bret;
    }

    public function isStudyHasParent($idStudy) {
        $bret = false;
        $study = $this->getInfoStudy($idStudy);

        if ($study->getChainingControls() && $study->getParentId() > 0) {
            $bret = true;
        }

        return $bret;
    }

    public function loadChainingChilds($idStudy)
    {
        $chaining = array();
        $study = $this->getInfoStudy($idStudy);
        if ($study->getHasChild()) {
            try {
                $std = $this->getDoctrine()->getRepository(Studies::class)->findBy(['parentId' => $study->getIdStudy()], ['idStudy' => 'ASC']);

                foreach ($std as $key => $value) {
                    array_push($chaining, $value);
                }
            } catch (Exception $e) {
                
            }
        }

        return $chaining;
    }

    public function setToRecalculate($idStudy, $status)
    {
        $rs = false;
        $em = $this->getDoctrine();

        if($idStudy > 0){
            try{
                $study = $this->getInfoStudy($idStudy);
                $study->setToRecalculate($status);

                $em->flush();
                $rs = true;
            }catch(Exception $e){
                $rs = false;
            }
        } 

        return $rs;
    }

    public function updateStudy($idStudy)
    {   
        

    }

    // public function resetPrice($idStudy)
    // {
    //     $study = $this->getInfoStudy($idStudy);

    //     $price = study.getPrice();
    //     if (price.getIdPrice() > 0) {
    //         price.setStudy(study);
    //         price.setEcoInCryo1(0.0D);
    //         price.setEcoInCryo2(0.0D);
    //         price.setEcoInCryo3(0.0D);
    //         price.setEcoInCryo4(0.0D);
    //         price.setEcoInPbp1(0.0D);
    //         price.setEcoInPbp2(0.0D);
    //         price.setEcoInPbp3(0.0D);
    //         price.setEnergy(0.0D);

    //         try {
    //             transaction.update(price);
    //         } catch (Exception var5) {
    //             LOG.error("CANNOT reset Prices " + var5.getMessage());
    //         }
    //     }

    //     return price;
    // }

	public function isMyStudy($idStudy) 
    {
        $bmine = false;

        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);

        if ($idStudy > 0) {
            $userID = -1;
            $studyOwnerID = -2;

            $user = $this->user;

            if (!empty($user)) {
                $userID = $user->getIdUser();
            }

            $studyOwnerID = $objStudy->getIdUser()->getIdUser();

            if ($userID == $studyOwnerID) {
                $bmine = true;
                $isMyStudy = true;
            } else {
                $isMyStudy = false;
            }
        }

        return $bmine;
    }

    public function disableFields($idStudy)
    {
        $sdisabled = "";
        $user = $this->user;
        $study = $this->getDoctrine()->getRepository(Studies::class)->findOneBy(["idStudy" => $idStudy]);

        $studyOwnerUserID = $study->getIdUser()->getIdUser();
        $userProfileID = $user->getUserprio();
        $userID = $user->getIdUser();

        if (($userProfileID > POST::PROFIL_EXPERT) || ($studyOwnerUserID != $userID)) {
			$sdisabled = "disabled";
		}
		return $sdisabled;
    }

    public function disableCalculate($idStudy)
    {
        $sdisabled = "";
        $user = $this->user;
        $study = $this->getDoctrine()->getRepository(Studies::class)->findOneBy(["idStudy" => $idStudy]);

        $studyOwnerUserID = $study->getIdUser()->getIdUser();
        $userProfileID = $user->getUserprio();
        $userID = $user->getIdUser();

        if ($studyOwnerUserID != $userID) {
			$sdisabled = "disabled";
		}
		return $sdisabled;
    }

    public function removeParentAttribute($idStudy) 
    {
        $bret = true;
        $em = $this->getDoctrine();

        try {
            $study = $this->getInfoStudy($idStudy);
            $listStudyChild = $this->getDoctrine()->getRepository(Studies::class)->findBy(['parentId' => $study->getIdStudy()]);
            
            if(count($listStudyChild) > 0){

                foreach ($listStudyChild as $key) {
                    $idProd = $key->getIdProd();

                    if($idProd != null){
                        $qb = $em->createQueryBuilder();
                        $qb->update(ProductElmt::class, 'pe')
                        ->set('pe.insertLineOrder',$qb->expr()->literal($key->getIdStudy()))
                        ->where('pe.idProd = :idProd')
                        ->setParameter('idProd', $idProd)
                        ->getQuery()->execute();
                    }
                    $key->setParentId(0);
                    $key->setParentStudEqpId(0);
                }
                $em->flush();
            }

            $list = $this->getDoctrine()->getRepository(Studies::class)->createQueryBuilder('s')
            ->where('s.idStudy <> :idStudy AND s.parentId = :idParent')
            ->setParameter('idStudy', $study->getIdStudy())
            ->setParameter('idParent', $study->getParentId())
            ->getQuery()->getResult();

            if(count($list) == 0){
                $qb = $em->createQueryBuilder();
                $qb->update(Studies::class, 's')
                ->set('s.hasChild',$qb->expr()->literal(0))
                ->where('s.idStudy = :idStudy')
                ->setParameter('idStudy', $study->getParentId())
                ->getQuery()->execute();
            }

        } catch (Exception $e) {
            $bret = false;
        }

        return $bret;
    }

    public function deleteAllResults() 
    {
        // * Delete all results: Gọi kernel chạy clear study với bộ mã (-1, 40). Nếu quá trình clear có lỗi thì show lên lỗi.
    }

    public function deleteCustomer($idStudy)
    {
        $em = $this->getDoctrine();
        $study = $this->getInfoStudy($idStudy);
        
        $em->createQueryBuilder()->delete(Production::class, 'p')
        ->where('p.idStudy = :idStudy')
        ->setParameter('idStudy', $study->getIdStudy())
        ->getQuery()->execute();
    }

    public function deletePacking($idPacking) 
    {
        $em = $this->getDoctrine();
        $packing = $this->getInfoPacking($idPacking);
        $em->createQueryBuilder()->delete(PackingLayer::class, 'p')
            ->where('p.idPacking = :idPacking')
            ->setParameter('idPacking', $packing->getIdPacking())
            ->getQuery()->execute();

        $em->createQueryBuilder()->delete(Packing::class, 'p')
            ->where('p.idPacking = :idPacking')
            ->setParameter('idPacking', $packing->getIdPacking())
            ->getQuery()->execute();
    }

    public function deleteProduct($idStudy, $idProduct)
    {
        $em = $this->getDoctrine();

        if($idStudy > 0){
            $study = $this->getInfoStudy($idStudy);

            if($idProduct > 0){
                $product = $this->getInfoProduct($idProduct);
                $this->deleteProdcharColors($product->getIdProd());
                $listProdElmt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $product->getIdProd()], ['getShapePos2' => 'ASC']);
                
                if(count($listProdElmt) > 0){

                    foreach ($listProdElmt as $key) {
                        $this->deleteMeshPosition($key->getIdProductElmt());
                        $this->deleteProductElmt($key->getIdProductElmt());

                    }
                }
                $this->deleteMeshGeneration($idProduct);
                $em->remove($product);
                $em->flush();
            }

            if($study->getIdProduction() > 0){
                $this->deleteInitialTemperature($study->getIdProduction());
            }
        }
    }

    public function deletePipeLine($idStudy)
    {
        $em = $this->getDoctrine();

        if($idStudy > 0){
            $study = $this->getInfoStudy($idStudy);
            $listSE = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy' => $study->getIdStudy()]);

            if(count($listSE) > 0){

                foreach ($listSE as $key) {
                    $this->deleteLineDefinition($key->getIdPipeGen());
                    $this->deletePipeGen($key->getIdPipeGen());
                    $key->setIdPipeGen(0);
                }
                $em->flush();
            }
        }
    }

    public function deleteStudyEquipment($idStudy)
    {
        $em = $this->getDoctrine();
        if($idStudy > 0){
            $listSE = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy' => $idStudy]);

            if(count($listSE) > 0){

                foreach ($listSE as $key) {
                    $this->deleteCalculationParameters($key->getIdStudyEquipments());
                    $this->deleteStudEqpPrm($key->getIdStudyEquipments());
                    $this->deleteLayoutGeneration($key->getIdStudyEquipments());
                    $this->deleteLayoutResults($key->getIdStudyEquipments());
                    $this->deleteLineDefinition($key->getIdPipeGen());
                    $this->deletePipeGen($key->getIdPipeGen());

                    $em->remove($key);
                }
                $em->flush();
            }
        }
    }

    public function deleteStudy($idStudy)
    {
        $em = $this->getDoctrine();

        if($idStudy > 0){
            $this->deleteStudyResults($idStudy);
            $this->deleteTempRecordPts($idStudy);
            $this->deletePrices($idStudy);
            $this->deleteReport($idStudy);
            $this->deletePrecalcLdgRatePrm($idStudy);
            $study = $this->getInfoStudy($idStudy);
            $em->remove($study);
            $em->flush();
        }
    }

    public function deleteImportedElement($idStudy)
    {
        $em = $this->getDoctrine();

        if($idStudy > 0){
            $study = $this->getInfoStudy($idStudy);
            $listEquip = $this->getDoctrine()->getRepository(Equipment::class)->findBy(['eqpImpIdStudy' => $study->getIdStudy()]);

            if(count($listEquip) > 0){

                foreach ($listEquip as $key) {
                    $idEquip = $key->getIdEquip();
                    $this->deleteRamps($idEquip);
                    $this->deleteShelves($idEquip);
                    $this->deleteConsumptions($idEquip);
                    $this->deleteEquipZone($idEquip);
                    $this->deleteEquipCharact($idEquip);
                    $em->remove($key);
                }
                $em->flush();
            }

            $listComponent = $this->getDoctrine()->getRepository(Component::class)->findBy(['compImpIdStudy' => $study->getIdStudy()]);

            if(count($listComponent) > 0){
                foreach ($listComponent as $key) {
                    $idComp = $key->getIdComp();
                    $this->deleteTranslation($idComp, 1);
                    $this->deleteCompenth($idComp);
                    $em->remove($key);
                }
                $em->flush();
            }
            $listPackingElmt = $this->getDoctrine()->getRepository(PackingEmlt::class)->findBy(['packImpIdStudy' => $study->getIdStudy()]);

            if(count($listPackingElmt) > 0){

                foreach ($listPackingElmt as $key) {
                    $idPacking = $key->getIdPackingElmt();
                    $this->deleteTranslation($idPacking, 3);
                    $em->remove($key);
                }
                $em->flush();
            }

            $listLineElmt = $this->getDoctrine()->getRepository(LineElmt::class)->findBy(['eltImpIdStudy' => $study->getIdStudy()]);

            if(count($listLineElmt) > 0){
                foreach ($listLineElmt as $key) {
                    $idPipelineElmt = $key->getIdPipelineElmt();
                    $this->deleteTranslation($idPipelineElmt, 27);
                    $em->remove($key);
                }
                $em->flush();
            }
        }
    }

    public function deleteTranslation($idTranslation, $transType)
    {
        $em = $this->getDoctrine();

        if($idTranslation > 0){
            $em->createQueryBuilder()->delete(Translation::class, 'p')
            ->where('p.idTranslation = :idTranslation AND p.transType = :transType')
            ->setParameter('idTranslation', $idTranslation)
            ->setParameter('transType', $transType)
            ->getQuery()->execute();
        }
    }

    public function deleteCompenth($idComp)
    {
        $em = $this->getDoctrine();

        if($idComp > 0){
            $comp = $this->getInfoComponent($idComp);
            $em->createQueryBuilder()->delete(Compenth::class, 'p')
            ->where('p.idComp = :idComp')
            ->setParameter('idComp', $comp->getIdComp())
            ->getQuery()->execute();
        }
    }

    public function deleteRamps($idEquip)
    {
        $em = $this->getDoctrine();

        if($idEquip > 0){
            $equip = $this->getInfoEquipment($idEquip);
            $em->createQueryBuilder()->delete(Ramps::class, 'p')
            ->where('p.idEquip = :idEquip')
            ->setParameter('idEquip', $equip->getIdEquip())
            ->getQuery()->execute();
        }
    }

    public function deleteShelves($idEquip)
    {
        $em = $this->getDoctrine();

        if($idEquip > 0){
            $equip = $this->getInfoEquipment($idEquip);
            $em->createQueryBuilder()->delete(Shelves::class, 'p')
            ->where('p.idEquip = :idEquip')
            ->setParameter('idEquip', $equip->getIdEquip())
            ->getQuery()->execute();
        }
    }

    public function deleteConsumptions($idEquip)
    {
        $em = $this->getDoctrine();

        if($idEquip > 0){
            $equip = $this->getInfoEquipment($idEquip);
            $em->createQueryBuilder()->delete(Consumptions::class, 'p')
            ->where('p.idEquip = :idEquip')
            ->setParameter('idEquip', $equip->getIdEquip())
            ->getQuery()->execute();
        }
    }

    public function deleteEquipZone($idEquip)
    {
        $em = $this->getDoctrine();

        if($idEquip > 0){
            $equip = $this->getInfoEquipment($idEquip);
            $em->createQueryBuilder()->delete(EquipZone::class, 'p')
            ->where('p.idEquip = :idEquip')
            ->setParameter('idEquip', $equip->getIdEquip())
            ->getQuery()->execute();
        }
    }

    public function deleteEquipCharact($idEquip)
    {
        $em = $this->getDoctrine();

        if($idEquip > 0){
            $equip = $this->getInfoEquipment($idEquip);
            $em->createQueryBuilder()->delete(Equipcharact::class, 'p')
            ->where('p.idEquip = :idEquip')
            ->setParameter('idEquip', $equip->getIdEquip())
            ->getQuery()->execute();
        }
    }

    public function deleteProductElmt($idProductElmt)
    {
        $em = $this->getDoctrine();

        if($idProductElmt > 0){
            $productElmt = $this->getInfoProductElmt($idProductElmt);
            $em->createQueryBuilder()->delete(ProductElmt::class, 'p')
            ->where('p.idProductElmt = :idProductElmt')
            ->setParameter('idProductElmt', $productElmt->getIdProductElmt())
            ->getQuery()->execute();
        }
    }

    public function deleteProdcharColors($idProduct)
    {
        $em = $this->getDoctrine();

        if($idProduct > 0){
            $product = $this->getInfoProduct($idProduct);
            $em->createQueryBuilder()->delete(ProdcharColors::class, 'p')
            ->where('p.idProd = :idProd')
            ->setParameter('idProd', $product->getIdProd())
            ->getQuery()->execute();
        }
    }

    public function deleteMeshPosition($idProductElmt)
    {
        $em = $this->getDoctrine();

        if($idProductElmt > 0){
            $productElmt = $this->getInfoProductElmt($idProductElmt);
            $em->createQueryBuilder()->delete(MeshPosition::class, 'm')
            ->where('m.idProductElmt = :idProductElmt')
            ->setParameter('idProductElmt', $productElmt->getIdProductElmt())
            ->getQuery()->execute();
        }
    }

    public function deleteMeshGeneration($idProduct)
    {
        $em = $this->getDoctrine();

        if($idProduct > 0){
            $product = $this->getInfoProduct($idProduct);
            $em->createQueryBuilder()->delete(MeshGeneration::class, 'm')
            ->where('m.idProd = :idProd')
            ->setParameter('idProd', $product->getIdProd())
            ->getQuery()->execute();
        }
    }
     
    public function deleteInitialTemperature($idProduction)
    {
        $em = $this->getDoctrine();

        if($getIdProduction > 0){
            $production = $this->getInfoProduction($idProduction);
            $em->createQueryBuilder()->delete(InitialTemperature::class, 'm')
            ->where('m.idProduction = :idProduction')
            ->setParameter('idProduction', $production->getIdProduction())
            ->getQuery()->execute();
        }
    }

    public function deleteLineDefinition($idPipeGen)
    {   
        $em = $this->getDoctrine();

        if($idPipeGen > 0){
            $pipeGen = $this->getInfoPipeGen($idPipeGen);
            $em->createQueryBuilder()->delete(LineDefinition::class, 'l')
            ->where('l.idPipeGen = :idPipeGen')
            ->setParameter('idPipeGen', $pipeGen->getIdPipeGen())
            ->getQuery()->execute();
        }  
    }          

    public function deletePipeGen($idPipeGen)
    {   
        $em = $this->getDoctrine();

        if($idPipeGen > 0){
            $pipeGen = $this->getInfoPipeGen($idPipeGen);
            $em->remove($pipeGen);
            $em->flush();
        }  
    }

    public function deleteCalculationParameters($idStudyEquipments)
    {
        if($idStudyEquipments > 0){
            $studyEquipments = $this->getInfoStudyEquipments($idStudyEquipments);
            $em->createQueryBuilder()->delete(CalculationParameters::class, 'c')
            ->where('c.idStudyEquipments = :idStudyEquipments')
            ->setParameter('idStudyEquipments', $studyEquipments->getIdStudyEquipments())
            ->getQuery()->execute();
        }
    }

    public function deleteStudEqpPrm($idStudyEquipments)
    {
        if($idStudyEquipments > 0){
            $studyEquipments = $this->getInfoStudyEquipments($idStudyEquipments);
            $em->createQueryBuilder()->delete(StudEqpPrm::class, 's')
            ->where('s.idStudyEquipments = :idStudyEquipments')
            ->setParameter('idStudyEquipments', $studyEquipments->getIdStudyEquipments())
            ->getQuery()->execute();
        }
    }  

    public function deleteLayoutGeneration($idStudyEquipments)
    {
        if($idStudyEquipments > 0){
            $studyEquipments = $this->getInfoStudyEquipments($idStudyEquipments);
            $em->createQueryBuilder()->delete(LayoutGeneration::class, 's')
            ->where('s.idStudyEquipments = :idStudyEquipments')
            ->setParameter('idStudyEquipments', $studyEquipments->getIdStudyEquipments())
            ->getQuery()->execute();
        }
    }    

    public function deleteLayoutResults($idStudyEquipments)
    {
        if($idStudyEquipments > 0){
            $studyEquipments = $this->getInfoStudyEquipments($idStudyEquipments);
            $em->createQueryBuilder()->delete(LayoutResults::class, 's')
            ->where('s.idStudyEquipments = :idStudyEquipments')
            ->setParameter('idStudyEquipments', $studyEquipments->getIdStudyEquipments())
            ->getQuery()->execute();
        }
    }  

    public function deleteStudyResults($idStudy)
    {
        $em = $this->getDoctrine();

        if($idStudy > 0){
            $study = $this->getInfoStudy($idStudy);
            $em->createQueryBuilder()->delete(StudyResults::class, 's')
            ->where('s.idStudy = :idStudy')
            ->setParameter('idStudy', $study->getIdStudy())
            ->getQuery()->execute();
        }
    }       

    public function deleteTempRecordPts($idStudy)
    {
        $em = $this->getDoctrine();

        if($idStudy > 0){
            $study = $this->getInfoStudy($idStudy);
            $em->createQueryBuilder()->delete(TempRecordPts::class, 's')
            ->where('s.idStudy = :idStudy')
            ->setParameter('idStudy', $study->getIdStudy())
            ->getQuery()->execute();
        }
    }  

    public function deletePrices($idStudy)
    {
        $em = $this->getDoctrine();

        if($idStudy > 0){
            $study = $this->getInfoStudy($idStudy);
            $em->createQueryBuilder()->delete(Prices::class, 's')
            ->where('s.idStudy = :idStudy')
            ->setParameter('idStudy', $study->getIdStudy())
            ->getQuery()->execute();
        }
    }

    public function deleteReport($idStudy)
    {
        $em = $this->getDoctrine();

        if($idStudy > 0){
            $study = $this->getInfoStudy($idStudy);
            $em->createQueryBuilder()->delete(Report::class, 's')
            ->where('s.idStudy = :idStudy')
            ->setParameter('idStudy', $study->getIdStudy())
            ->getQuery()->execute();
        }
    }  

    public function deletePrecalcLdgRatePrm($idStudy)
    {
        $em = $this->getDoctrine();

        if($idStudy > 0){
            $study = $this->getInfoStudy($idStudy);
            $em->createQueryBuilder()->delete(PrecalcLdgRatePrm::class, 's')
            ->where('s.idStudy = :idStudy')
            ->setParameter('idStudy', $study->getIdStudy())
            ->getQuery()->execute();
        }
    }

    public function unActivateSaveBan(Request $request)
    {
        $session = $request->getSession();
        $session->set('SAVEAUTHORIZED', 'NO');
        $session->set('BANINOUTAUTHORIZED', 'NO');
    }

    public function setupOpenByOwner($status)
    {
        $em = $this->getDoctrine();
        $qb = $em->createQueryBuilder();
        $qb->update(Studies::class, 's')
        ->set('s.openByOwner',$qb->expr()->literal($status))
        ->getQuery()->execute();
    }

    public function createTempRecordPtsStudy($tempRecPts) 
    {
        $em = $this->getDoctrine();
        $tempRecPts->setAxis1PtTopSurf(0);
        $tempRecPts->setAxis2PtTopSurf(0);
        $tempRecPts->setAxis3PtTopSurf(0);
        $tempRecPts->setAxis2Ax1(0);
        $tempRecPts->setAxis3Ax1(0);
        $tempRecPts->setAxis1Ax2(0);
        $tempRecPts->setAxis1PtIntPt(0);
        $tempRecPts->setAxis2PtIntPt(0);
        $tempRecPts->setAxis3PtIntPt(0);
        $tempRecPts->setAxis1PtBotSurf(0);
        $tempRecPts->setAxis2PtBotSurf(0);
        $tempRecPts->setAxis3PtBotSurf(0);
        $tempRecPts->setAxis3Ax2(0);
        $tempRecPts->setAxis1Ax3(0);
        $tempRecPts->setAxis2Ax3(0);
        $tempRecPts->setAxis1Pl23(0);
        $tempRecPts->setAxis2Pl13(0);
        $tempRecPts->setAxis3Pl12(0);
        $tempRecPts->setNbSteps(0);
        $tempRecPts->setContour2dTempMin(0);
        $tempRecPts->setContour2dTempMax(0);
        $em->persist($tempRecPts);
        $em->flush();
    }

    public function createProductionStudy($production)
    {
        $em = $this->getDoctrine();
        $dailyProd = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem'=>Post::LIMIT_ITEM_PRODUCTION_DURATION]);
        $dailyStartup = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem'=>Post::LIMIT_ITEM_DAILY_STARTUP]);
        $weeklyProd = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem'=>Post::LIMIT_ITEM_WEEKLY_PRODUCTION]);
        $prodFlowRate = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem'=>Post::LIMIT_ITEM_FLOW_RATE]);
        $nbProdWeekPerYear = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem'=>Post::LIMIT_ITEM_ANNUAL_PRODUCTION]);
        $amBientTemp = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem'=>Post::LIMIT_ITEM_AMBIENT_TEMPERATURE]);
        $amBientHum = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem'=>Post::LIMIT_ITEM_AMBIENT_HUMIDITY]);
        $avgTDesired = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem'=>Post::LIMMTI_ITEM_AVG_TEMPERATURE_DES]);
        $production->setDailyProd($dailyProd->getDefaultValue());
        $production->setDailyStartup($dailyStartup->getDefaultValue());
        $production->setWeeklyProd($weeklyProd->getDefaultValue());
        $production->setProdFlowRate($prodFlowRate->getDefaultValue());
        $production->setNbProdWeekPerYear($nbProdWeekPerYear->getDefaultValue());
        $production->setAmbientTemp($amBientTemp->getDefaultValue());
        $production->setAmbientHum($amBientHum->getDefaultValue());
        $production->setAvgTDesired($avgTDesired->getDefaultValue());
        $production->setAvgTInitial(0);
        $production->setApproxDwellingTime(5);
        $em->persist($production);
        $em->flush();
    }

    public function setDefValuesStudy($idStudy) {
        $em = $this->getDoctrine();
        $objStudy = $em->find(Studies::class, $idStudy);
        if($objStudy->getOptionCryopipeline() == null) {
            $objStudy->setOptionCryopipeline(0);
        }
        if($objStudy->getOptionEco() == null){
            $objStudy->setOptionEco(0);
        }
        if($objStudy->getOpenByOwner() == null) {
            $objStudy->setOpenByOwner(0);
        }
        if($objStudy->getChainingControls() == null) {
            $objStudy->setChainingControls(0);
        }
        if($objStudy->getChainingNodeDecimEnable() == null) {
            $objStudy->setChainingNodeDecimEnable(0);
        }
        if($objStudy->getChainingAddCompEnable() == null) {
            $objStudy->setChainingAddCompEnable(0);
        }
        if($objStudy->getHasChild() == null) {
            $objStudy->setHasChild(0);
        }
        if($objStudy->getToRecalculate() == null) {
            $objStudy->setToRecalculate(0);
        }
        if($objStudy->isOptionExhaustpipeline() == null) {
            $objStudy->setOptionExhaustpipeline(0);
        }
        if($objStudy->getParentId() == null) {
            $objStudy->setParentId(0);
        }
        if($objStudy->getParentStudEqpId() == null) {
            $objStudy->setParentStudEqpId(0);
        }
        if($objStudy->getCalculationStatus() == null) {
            $objStudy->setCalculationStatus(0);
        }
    }

    public function createReportStudy($report)
    {
        $em = $this->getDoctrine();
        $report->setRepCustomer(1);
        $report->setProdList(1);
        $report->setProdTemp(0);
        $report->setProd3d(1);
        $report->setPacking(0);
        $report->setEquipList(1);
        $report->setEquipParam(0);
        $report->setPipeline(0);
        $report->setAssesTermal(0);
        $report->setAssesConsump(0);
        $report->setAssesEco(1);
        $report->setAssesTr(0);
        $report->setAssesTrMin(0);
        $report->setAssesTrMax(0);
        $report->setSizingTr(1);
        $report->setSizingTrMin(0);
        $report->setSizingTrMax(0);
        $report->setSizingValues(0);
        $report->setSizingGraphe(0);
        $report->setSizingTempG(0);
        $report->setSizingTempV(0);
        $report->setSizingTempSample(0);
        $report->setAxe1X(0.1111);
        $report->setAxe1Y(0.111);
        $report->setAxe2X(0.1111);
        $report->setAxe2Z(0.1111);
        $report->setAxe3Y(0.1111);
        $report->setAxe3Z(0.1111);
        $report->setIsochroneG(0);
        $report->setIsochroneV(0);
        $report->setIsochroneSample(10);
        $report->setPoint1X(0.11111);
        $report->setPoint1Y(0.11111);
        $report->setPoint1Z(0.11111);
        $report->setPoint2X(0.11111);
        $report->setPoint2Y(0.11111);
        $report->setPoint2Z(0.11111);
        $report->setPoint3X(0.11111);
        $report->setPoint3Y(0.11111);
        $report->setPoint3Z(0.11111);
        $report->setIsovalueG(0);
        $report->setIsovalueV(0);
        $report->setIsovalueSample(10);
        $report->setPlanX(0.11111);
        $report->setPlanY(0.11111);
        $report->setPlanZ(0.11111);
        $report->setContour2dG(0);
        $report->setContour2dSample(0);
        $report->setContour2dTempStep(0);
        $report->setEnthalpyV(0);
        $report->setEnthalpyG(0);
        $report->setEnthalpySample(10);
        $report->setDestSurname("");
        $report->setDestName("");
        $report->setDestFunction("");
        $report->setDestCoord("");
        $report->setPhotoPath("");
        $report->setCustomerLogo("");
        $report->setConsSpecific(0);
        $report->setConsOverall(0);
        $report->setConsTotal(0);
        $report->setConsHour(0);
        $report->setConsDay(0);
        $report->setConsWeek(0);
        $report->setConsMonth(0);
        $report->setConsYear(0);
        $report->setConsEquip(0);
        $report->setConsPipe(0);
        $report->setConsTank(0);
        $report->setContour2dOutlineTime(0);
        $report->setReportComment("");
        $report->setWriterSurname("");
        $report->setWriterName("");
        $report->setWriterFunction("");
        $report->setWriterCoord("");
        $report->setRepConsPie(0);
        $report->setContour2dTempMin(0);
        $report->setContour2dTempMax(0);
        $em->persist($report);
        $em->flush();
    }

    public function createReCalLDGRatePRM($recalLdgRatePrm) 
    {
        $lInterval = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem'=>Post::LIMMIT_ITEM_LINTERVAL]);
        $wInterval = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem'=>Post::LIMMIT_ITEM_WINTERVAL]);
        
        $em = $this->getDoctrine();
        $recalLdgRatePrm->setLInterval($lInterval->getDefaultValue());
        $recalLdgRatePrm->setWInterval($wInterval->getDefaultValue());
        $recalLdgRatePrm->setPrecalcLdgTr(-100);
        $recalLdgRatePrm->setApproxLdgRate(0.5);
        $em->persist($recalLdgRatePrm);
        $em->flush();
    }
}