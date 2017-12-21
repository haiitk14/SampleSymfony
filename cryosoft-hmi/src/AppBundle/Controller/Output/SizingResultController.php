<?php

namespace AppBundle\Controller\Output;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Studies;
use AppBundle\Entity\Production;
use AppBundle\Entity\Product;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\StudEqpPrm;
use AppBundle\Entity\LayoutGeneration;
use AppBundle\Entity\MinMax;
use AppBundle\Entity\Translation;
use AppBundle\Entity\Post;
use AppBundle\Entity\DimaResults;
use AppBundle\Entity\Unit;
use AppBundle\Entity\ErrorTxt;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\LayoutResults;
use AppBundle\Entity\CalculationParameters;
use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\MeshPosition;
use AppBundle\Entity\TempRecordPts;
use AppBundle\Entity\EconomicResults;
use AppBundle\Entity\MonetaryCurrency;
use AppBundle\Cryosoft\CheckControlService;
use AppBundle\Cryosoft\UnitsConverterService as UnitConvert;
use AppBundle\Cryosoft\EquipmentsService;
use AppBundle\Cryosoft\MinMaxService;
use AppBundle\Cryosoft\DimaResultsService;
use AppBundle\Cryosoft\EconomicResultsService;
use AppBundle\Cryosoft\StudyService;
use AppBundle\Cryosoft\CalculateService;
use AppBundle\Cryosoft\BrainCalculateService;
use AppBundle\Cryosoft\KernelCalculateService;

class SizingResultController extends Controller 
{

    public function __construct(CheckControlService $check, UnitConvert $units, Session $session, TokenStorageInterface $tokenStorage, EquipmentsService $equipments, MinMaxService $minMax, DimaResultsService $dima, EconomicResultsService $ecomicResultService, StudyService $studyService, CalculateService $calculateService, BrainCalculateService $brainCalculateService, KernelCalculateService $kernel)
    {
        $this->user = $tokenStorage->getToken()->getUser();
        $this->_check = $check;
        $this->_unit = $units;
        $this->_equip = $equipments;
        $this->_minmax = $minMax;
        $this->_dima = $dima;
        $this->_eco = $ecomicResultService;
        $this->_study = $studyService;
        $this->_calculate = $calculateService;
        $this->_brain = $brainCalculateService;
        $this->_kernel = $kernel;

        /*$user = $this->user;
        if ($user == NULL) {
            return $this->redirectToRoute("login");
        }

        $idProd = $session->get("idProd");
        $loadEquipment = $session->get("loadEquipment");
        $idProd = 0;
        $loadEquipment = 0;
        $checkControl = $check->isCheckControl($idUser, $idStudy, $loadEquipment, $idProd);
        if($checkControl == false){
            return $this->redirectToRoute("checkcontrol");
        }*/
    }

    /**
    * @Route("/out-sizing-result", name="out-sizing-result")
    */
    public function outSizingResultAction(Request $request)
    {
        $session = $this->get("session");
        $doc = $this->getDoctrine();
        // check user login
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->redirectToRoute("login");
        }
        // check idStudy already exists
        $idStudy = $session->get("idStudy");
        if ($idStudy == null || $idStudy==0 || $idStudy == "") {
            return $this->redirectToRoute("load-study");
        }

        $idStudy = 26;
        $idUser = $user->getIdUser();
        $idProd = $session->get("idProd");
        $loadEquipment = $session->get("loadEquipment");
        
        $checkControl = $this->_check->isCheckControl($idUser, $idStudy, $loadEquipment, $idProd);
        //check control
        /*if($checkControl == false){
            return $this->redirectToRoute("checkcontrol");
        }*/

        // get object Study
        // $idStudy = 3;
        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $calculationMode = $objStudy->getCalculationMode();

        $data = array();
        $dimaResults = null;
        $massUnit = 37;
        $lfcoef = $this->_unit->unitConvert(Post::TYPE_UNIT_MASS_PER_UNIT, 1.0);

        $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(["idStudy"=>$objStudy->getIdStudy()],["idStudyEquipments"=>"ASC"]);

        $arrStudyEquipment = array();

        $listOfSelectedEquipments =  array();
        $listOfAvailableEquipments = array(); 

        if(!empty($studyEquipments)){

            if($calculationMode == 1){
                //get list equipment value
                foreach ($studyEquipments as $row) {
                    $equipment = $row->getIdEquip();

                    $capabilities = $equipment->getCapabilities();

                    $sEquipName = $this->_equip->getSpecificEquipName($row->getidStudyEquipments());
                    $equipStatus = $row->getEquipStatus();

                    $sTR = array();
                    $sTS = array();
                    $sVC = array();
                    $sDHP = array();
                    $sConso = array();
                    $sTOC = array();

                    
                    

                    $item["equipment"] = $equipment;
                    $item["sEquipName"] = $sEquipName;
                    $item["sTR"] = $sTR;
                    $item["sTS"] = $sTS;
                    $item["sVC"] = $sVC;
                    $item["sDHP"] = $sDHP;
                    $item["sConso"] = $sConso;
                    $item["sTOC"] = $sTOC;

                    $arrStudyEquipment[] = $item;
                }

                // get list equipment grap
                
            }

            if($calculationMode == 2 || $calculationMode == 3){
                //get list equipment value
                foreach ($studyEquipments as $row) {
                    $equipment = $row->getIdEquip();

                    $capabilities = $equipment->getCapabilities();

                    $sEquipName = $this->_equip->getSpecificEquipName($row->getidStudyEquipments());
                    $equipStatus = $row->getEquipStatus();

                    $sTR = array();
                    $sTS = array();
                    $sVC = array();
                    $sDHP = array();
                    $sConso = array();
                    $sTOC = array();

                    
                    if (!($this->_equip->getCapabilityNnc($capabilities , 128))){
                        for ($i = 0; $i < 2; $i++) {
                            $tmp181_180 = $sVC[$i] = $sDHP[$i] = $sConso[$i] =  $sTOC[$i] = "****";
                            $sTS[$i] = $tmp181_180;
                            $sTR[$i] = $tmp181_180;
                        }
                    } else if ($equipStatus == 100000) {
                        for ($i = 0; $i < 2; $i++) {
                            $tmp246_245 = $sVC[$i] = $sDHP[$i] = $sConso[$i] =  $sTOC[$i] = "";
                            $sTS[$i] = $tmp246_245;
                            $sTR[$i] = $tmp246_245;
                        }
                    } else {
                        for ($i = 0; $i < 2; $i++) {
                            if (($i == 0) && ($equipStatus != 0) && ($equipStatus != 1) && ($equipStatus != 100000)) {
                                $tmp333_332 = $sVC[$i] = $sDHP[$i] = $sConso[$i] =  $sTOC[$i] = "****";
                                $sTS[$i] = $tmp333_332;
                                $sTR[$i] = $tmp333_332;
                            } else {
                                $dimaType = ($i == 0) ? 1 : 16;
                                $dimaResults = $doc->getRepository(DimaResults::class)->findOneBy(["idStudyEquipments" => $row->getidStudyEquipments(), "dimaType" => $dimaType]);

                                if ($dimaResults == null) {
                                    $tmp403_402 = $sVC[$i] = $sDHP[$i] = $sConso[$i] =  $sTOC[$i] = "";
                                    $sTS[$i] = $tmp403_402;
                                    $sTR[$i] = $tmp403_402;
                                } else {
                                    $ldError = 0;
                                    if ($i == 1) {
                                        $ldError = $this->_dima->getCalculationWarning($dimaResults->getDimaStatus());
                                        if (($ldError == 282) || ($ldError == 283) || ($ldError == 284) || ($ldError == 285) || ($ldError == 286)) {
                                            $ldError = 0;
                                        }
                                    }
                                    if (($i == 1) && ($ldError != 0)) {
                                        $tmp513_512 = $sVC[$i] = $sDHP[$i] = $sConso[$i] =  $sTOC[$i] = "****";
                                        $sTS[$i] = $tmp513_512;
                                        $sTR[$i] = $tmp513_512;
                                    } else {
                                        $sTR[$i] = $this->_unit->controlTemperature($dimaResults->getSetpoint());
                                        $sTS[$i] = $this->_unit->timeUnit($dimaResults->getDimaTS());
                                        $sVC[$i] = $this->_unit->convectionSpeed($dimaResults->getDimaVC());

                                        if ($this->_equip->getCapabilityNnc($capabilities, 128)) {
                                            $consumption = $dimaResults->getConsum() / $lfcoef;
                                            $idCoolingFamily = $equipment->getIdCoolingFamily()->getIdCoolingFamily();

                                            $valueStr = $this->_unit->consumption($consumption, $idCoolingFamily, 1);

                                            $calculationStatus = $this->_dima->getCalculationStatus($dimaResults->getDimaStatus());
                                            $fluidOverImg = "<img src='assets/dist/img/output/warning_fluid_overflow.gif' width='30' height='30' /> ";
                                            $dhpOverImg = "<img src='assets/dist/img/output/warning_dhp_overflow.gif' width='30' height='30' /> ";

                                            $sConso[$i] = $this->_dima->consumptionCell($lfcoef, $calculationStatus, $valueStr, $fluidOverImg, $dhpOverImg); 
                                        } else {
                                            $sConso[$i] = "****";
                                        }
                                        if ($this->_equip->getCapabilityNnc($capabilities, 32)) {
                                            $sDHP[$i] = $this->_unit->productFlow($dimaResults->getHourlyoutputmax());

                                            $batch = $equipment->getIdEquipseries()->getIdFamily()->isBatchProcess();
                                            if ($batch) {
                                                $sTOC[$i] = $this->_unit->mass($dimaResults->getUserate()) . " " . $this->_unit->massSymbol() . "/batch"; 
                                            } else {
                                                $sTOC[$i] = $this->_unit->toc($dimaResults->getUserate()) . " %";
                                            }
                                        } else {
                                            $tmp866_864 = "****";
                                            $sTOC[$i] = $tmp866_864;
                                            $sDHP[$i] = $tmp866_864;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $item["equipment"] = $equipment;
                    $item["sEquipName"] = $sEquipName;
                    $item["sTR"] = $sTR;
                    $item["sTS"] = $sTS;
                    $item["sVC"] = $sVC;
                    $item["sDHP"] = $sDHP;
                    $item["sConso"] = $sConso;
                    $item["sTOC"] = $sTOC;

                    $arrStudyEquipment[] = $item;
                }

                // get list equipment grap
                $j = 0;
                foreach ($studyEquipments as $row) {
                    $equipment = $row->getIdEquip();
                    $idStudyEquipments = $row->getIdStudyEquipments();

                    $capabilities = $equipment->getCapabilities();

                    $sEquipName = $this->_equip->getSpecificEquipName($row->getidStudyEquipments());
                    $equipStatus = $row->getEquipStatus();
                    $energy = $equipment->getIdCoolingFamily()->getIdCoolingFamily();

                    if ((!($this->_equip->getCapabilityNnc($capabilities , 128)) || ($row->getBrainType() == 0) || ($row->getEquipStatus() != 1))) {
                        $debug = "brain type: " . $row->getBrainType();
                        $debug .= " - equip status: " . $row->getEquipStatus();
                    } else {
                        $dimaResults = array();
                        $dimaResults[0] = $doc->getRepository(DimaResults::class)->findOneBy(["idStudyEquipments" => $row->getidStudyEquipments(), "dimaType" => 1]);
                        $dimaResults[1] = $doc->getRepository(DimaResults::class)->findOneBy(["idStudyEquipments" => $row->getidStudyEquipments(), "dimaType" => 16]);
                        if($dimaResults[0] != null || $dimaResults[1] != null){
                            for ($i = 0; $i < 2; $i++) {
                                $dimaType = ($i == 0) ? 1 : 16;
                                $dimaResult = $dimaResults[$i];

                                if (($dimaResult != null)) {
                                    
                                    if ($this->_equip->getCapabilityNnc($capabilities , 256)){
                                        if ($this->_dima->isConsoToDisplay($dimaResult->getDimaStatus())) {
                                            if ($lfcoef != 0.0) {
                                                $sConso[$i] = $this->_unit->consumption($dimaResult->getConsum() / $lfcoef, $energy, 1);
                                            } else {
                                                $sConso[$i] = "****";
                                            }
                                        } else {
                                            $sConso[$i] = "****";
                                        }
                                    } else {
                                        $sConso[$i] = "****";
                                    }

                                    if ($this->_equip->getCapabilityNnc($capabilities , 32)) {
                                        $sDHP[$i] = $this->_unit->productFlow($dimaResult->getHourlyoutputmax());

                                    } else {
                                        $sDHP[$i] = "****";
                                    }
                                                                   
                                } else {
                                    $tmp425_423 = "";
                                    $sConso[$i] = $tmp425_423;
                                    $sDHP[$i] = $tmp425_423;
                                }
                            }

                            $itemGrap["equipment"] = $equipment;
                            $itemGrap["idStudyEquipments"] = $idStudyEquipments;
                            $itemGrap["sEquipName"] = $sEquipName;
                            $itemGrap["sDHP"] = $sDHP;
                            $itemGrap["sConso"] = $sConso;

                            if ($j < 4) {
                                $listOfSelectedEquipments[] = $itemGrap;
                            } else {
                                $listOfAvailableEquipments[] = $itemGrap;
                            }

                        }
                        
                    }

                    $j++;
                }
            }
            

        }

        $temperatureSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_TEMPERATURE);
        $productFlowSymbol = $this->_unit->productFlowSymbol();
        $perUnitOfMassSymbol = $this->_unit->perUnitOfMassSymbol();
        $consumptionSymbol = $this->_unit->consumptionSymbol($this->_unit->initEnergyDef(), 1);
        $timeSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_TIME);
        $convectionSpeedSymbol = $this->_unit->convectionSpeedSymbol();

        $data = [
            "objStudy" => $objStudy,
            "arrStudyEquipment" => $arrStudyEquipment,
            "listOfSelectedEquipments" => $listOfSelectedEquipments,
            "listOfAvailableEquipments" => $listOfAvailableEquipments,
            "temperatureSymbol" => $temperatureSymbol,
            "productFlowSymbol" => $productFlowSymbol,
            "perUnitOfMassSymbol" => $perUnitOfMassSymbol,
            "consumptionSymbol" => $consumptionSymbol,
            "timeSymbol" => $timeSymbol,
            "convectionSpeedSymbol" => $convectionSpeedSymbol
        ];
        

        $urlRender = "output/sizing/optimum/sizing.html.twig";
        
        return $this->render($urlRender, $data);
    }

    /**
    * @Route("/grap-out-sizing-result", name="grap-out-sizing-result")
    */
    public function grapOutSizingResultAction(Request $request){

        $listStudyEquip = $request->get("liststudyequip");

        $idStudy = 26;

        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);

        $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->createQueryBuilder("s")
            ->where("s.idStudy = :idStudy")
            ->andWhere("s.idStudyEquipments IN(:idStudyEquipments)")
            ->setParameter("idStudy", $idStudy)
            ->setParameter("idStudyEquipments", $listStudyEquip)
            ->orderBy("s.idStudyEquipments", "ASC")
            ->getQuery()->getResult();

        $dimaResults = null;
        $lfcoef = $this->_unit->unitConvert(Post::TYPE_UNIT_MASS_PER_UNIT, 1.0);

        $s1 = "Product flowrate";
        $s2 = "Maximum product flowrate";
        $s3 = "Cryogen consumption (product + equipment heat losses)";
        $s4 = "Maximum cryogen consumption (product + equipment heat losses)";
        $s5 = "Custom flow rate";

        $axisLeftLabel = "Flow rate " . $this->_unit->productFlowSymbol();
        

        $production = $this->getDoctrine()->getRepository(Production::class)->findOneBy(["idStudy" => $idStudy]);

        $lfRequiredProductFlow = $this->_unit->productFlow($production->getProdFlowRate());
        $ldDefaultEnergy = $this->_unit->consumptionSymbol($this->_equip->initEnergyDef(), 1);
        $perUnitOfMassSymbol = $this->_unit->perUnitOfMassSymbol();

        $axisRightLabel = "Conso ". $ldDefaultEnergy . "/" . $perUnitOfMassSymbol;

        $data = array();
        $dataChart = array();
        $listOfSelectedEquipments = array();
        foreach ($studyEquipments as $row) {
            $equipment = $row->getIdEquip();
            $idStudyEquipments = $row->getIdStudyEquipments();

            $capabilities = $equipment->getCapabilities();

            $sEquipName = $this->_equip->getSpecificEquipName($row->getidStudyEquipments());
            $equipStatus = $row->getEquipStatus();
            $energy = $equipment->getIdCoolingFamily()->getIdCoolingFamily();

            if ((!($this->_equip->getCapabilityNnc($capabilities , 128)) || ($row->getBrainType() == 0) || ($row->getEquipStatus() != 1))) {
                $debug = "brain type: " . $row->getBrainType();
                $debug .= " - equip status: " . $row->getEquipStatus();
            } else {
                for ($i = 0; $i < 2; $i++) {
                    $dimaType = ($i == 0) ? 1 : 16;
                    $dimaResults = $this->getDoctrine()->getRepository(DimaResults::class)->findOneBy(["idStudyEquipments" => $row->getidStudyEquipments(), "dimaType" => $dimaType]);

                    if (($dimaResults != null)) {
                        
                        if ($this->_equip->getCapabilityNnc($capabilities , 256)){
                            if ($this->_dima->isConsoToDisplay($dimaResults->getDimaStatus())) {
                                if ($lfcoef != 0.0) {
                                    $sConso[$i] = $this->_unit->consumption($dimaResults->getConsum() / $lfcoef, $energy, 1);
                                } else {
                                    $sConso[$i] = "****";
                                }
                            } else {
                                $sConso[$i] = "****";
                            }
                        } else {
                            $sConso[$i] = "****";
                        }

                        if ($this->_equip->getCapabilityNnc($capabilities , 32)) {
                            $sDHP[$i] = $this->_unit->productFlow($dimaResults->getHourlyoutputmax());

                        } else {
                            $sDHP[$i] = "****";
                        }
                            
                        
                    } else {
                        $tmp425_423 = "";
                        $sConso[$i] = $tmp425_423;
                        $sDHP[$i] = $tmp425_423;
                    }
                }

            }

            $itemGrap["equipment"] = $equipment;
            $itemGrap["idStudyEquipments"] = $idStudyEquipments;
            $itemGrap["sEquipName"] = $sEquipName;
            $itemGrap["sDHP"] = $sDHP;
            $itemGrap["sConso"] = $sConso;

            $listOfSelectedEquipments[] = $itemGrap;
        }

        foreach ($listOfSelectedEquipments as $key => $row) {
            $sDHP = $row["sDHP"];
            $sConso = $row["sConso"]; 

            foreach ($sDHP as $keyDph => $sdph) {
                if (($sdph == null) || ($sdph == "****") || ($sdph == "")) {
                    $sDHPData[$key][$keyDph] = 0.0;
                } else {
                    $sDHPData[$key][$keyDph] = $this->_unit->convertToDouble($sdph);
                }
            }

            foreach ($sConso as $keyConso => $sconso) {
                if (($sconso == null) || ($sconso == "****") || ($sconso == "")) {
                    $sConsoData[$key][$keyConso] = 0.0;
                } else {
                    $sConsoData[$key][$keyConso] = $this->_unit->convertToDouble($sconso);
                }
            }

            $item["sDHP"] = $sDHPData[$key];
            $item["sConso"] = $sConsoData[$key];
            $item["sEquipName"] = $row["sEquipName"];
            $dataChart[] =  $item;
            
        }

        $data = [
            "s1" => $s1,
            "s2" => $s2,
            "s3" => $s3,
            "s4" => $s4,
            "s5" => $s5,
            "custom_flow_rate" => $lfRequiredProductFlow,
            "axisLeftLabel" => $axisLeftLabel,
            "axisRightLabel" => $axisRightLabel,
            "dataChart" => $dataChart
        ];
        
       return new JsonResponse($data);
    }

    /**
    * @Route("/test-kernel", name="test-kernel")
    */
    public function testKernelAction(Request $request){
        echo $this->_kernel->testKernel(3);die;
    }

}