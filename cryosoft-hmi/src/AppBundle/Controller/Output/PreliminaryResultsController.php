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
use AppBundle\Cryosoft\Unitsconverterservice as UnitConvert;
use AppBundle\Cryosoft\EquipmentsService;
use AppBundle\Cryosoft\MinMaxService;
use AppBundle\Cryosoft\DimaResultsService;
use AppBundle\Cryosoft\EconomicResultsService;
use AppBundle\Cryosoft\StudyService;
use AppBundle\Cryosoft\CalculateService;
use AppBundle\Cryosoft\BrainCalculateService;

class PreliminaryResultsController extends Controller 
{

    public function __construct(CheckControlService $check, UnitConvert $unitsConverter, Session $session, TokenStorageInterface $tokenStorage, EquipmentsService $equipments, MinMaxService $minMax, DimaResultsService $dima, EconomicResultsService $ecomicResultService, StudyService $studyService, CalculateService $calculateService, BrainCalculateService $brainCalculateService)
    {
        $this->user = $tokenStorage->getToken()->getUser();
        $this->_check = $check;
        $this->_unit = $unitsConverter;
        $this->_equip = $equipments;
        $this->_minmax = $minMax;
        $this->_dima = $dima;
        $this->_eco = $ecomicResultService;
        $this->_study = $studyService;
        $this->_calculate = $calculateService;
        $this->_brain = $brainCalculateService;

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
    * @Route("/head-balance", name="head-balance")
    */
    public function heatBalanceAction(Request $request)
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

        $objStudy= $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);

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

        $calculationMode = $this->_study->getCalculationMode($idStudy, $idUser);

        // get object Production by idStudy
        $production = $doc->getRepository(Production::class)->findOneBy(["idStudy" => $idStudy]);
        
        //get Real product mass per unit
        $listPro = $this->getDoctrine()->getRepository(Product::class)->findOneBy(array("idStudy" => $idStudy));
        $prodElmtRealweight = $listPro->getProdRealweight();

        $listObjSEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(["idStudy"=>$idStudy],["idStudyEquipments"=>"ASC"]);

        $user = $this->getUser();
        $lfcoef = $this->_unit->unitConvert(Post::TYPE_UNIT_MASS_PER_UNIT, 1.0);
        
        $ecoEnable = false;
        if ($user->getUserprio() < POST::PROFIL_GUEST && $objStudy->getOptionEco() == POST::STUDY_ECO_MODE) {
            $ecoEnable = true;
        }

        $arrStudyEquipment = array();
        
        if (count($listObjSEquipment)>0) {
            
            $i = 0;
            foreach ($listObjSEquipment as $key) {

                $capabilitie = $key->getIdEquip()->getCapabilities();
                
                if ($calculationMode == 1) {
                    $sCalculate = "";
                    $sBackground = $sSpecificSize = $sEquipRunning = $sCalculWarning = $sTR = $sTS = $sVC = $sH
                        = $sTFP = $sDHP = $sConso = $sTOC = $sPrecision = null;
                    
                    $trAffich = "";
                    $tsAffich = "";
                    $enthalpyVariationAffich = "";
                    $avgProdTempAffich = "";
                    $consmatAffich = "";
                    $tauxAffich = "";
                    $dhpAffich = "";
                    $consomaxAffich = "";
                    $tauxmaxAffich = "";
                    $precisionAffich = "0";
                    $sbgColor = "#CAE1F7";
                    
                    $shref_Brain = "#";
                    $soption_Brain = "";
                    $shref_EqpSize = "#";
                    $soption_EqpSize = "";

                    $shref_TR = "#";
                    $saction_TR = "";
                    $soption_TR = "";
                    
                    $debugLog = "";
                    $isBrainRunning = 0;
                    
                    $selectTR = ($request->query->get("selectTR") != "") ? $request->query->get("selectTR") : 1;
                    
                    
                    $sEquipName = $this->_equip->getResultsEquipName($key->getidStudyEquipments());
                    $sSpecificSize = $this->_equip->getSpecificEquipSize($key->getidStudyEquipments());
                    // 
                    // $sSpecificSize = "6.86 x 0.96";
                    
                    $studEqpPrm = $this->getDoctrine()->getRepository(StudEqpPrm::class)->findOneBy(["idStudyEquipments" => $key->getidStudyEquipments(), "valueType" => 300]);
                    $lfTR = $studEqpPrm->getValue();
                    
                    if ($selectTR == 2) {
                        $lfTR += -10.0;
                    } else if ($selectTR == 0) {
                        $lfTR += 10.0;
                    }
                                    
                    $itemTR = $key->getIdEquip()->getItemTr();
                    $mm = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(["limitItem" => $itemTR]);
                   
                    if (!($this->_equip->getCapabilityNnc($capabilitie , 16)) || !($this->_equip->getCapabilityNnc($capabilitie , 1)) && (($selectTR == 0) || ($selectTR == 2))) {
                        $dimaR = null;
                        $trAffich = $tsAffich = $enthalpyVariationAffich = $avgProdTempAffich = $consmatAffich = $tauxAffich = $dhpAffich = $consomaxAffich = $tauxmaxAffich = $precisionAffich = "---";
                    } else if ($lfTR < $mm->getLimitMin() || $lfTR > $mm->getLimitMax()) {
                        $dimaR = null;
                        $trAffich = $tsAffich = $enthalpyVariationAffich = $avgProdTempAffich = $consmatAffich = $tauxAffich = $dhpAffich = $consomaxAffich = $tauxmaxAffich = $precisionAffich = "****";
                    } else if ($key->getEquipStatus() != 0 && $key->getEquipStatus() != 1 && $key->getEquipStatus() != 100000) {
                        $dimaR = null;
                        $trAffich = $tsAffich = $enthalpyVariationAffich = $avgProdTempAffich = $consmatAffich = $tauxAffich = $dhpAffich = $consomaxAffich = $tauxmaxAffich = $precisionAffich = "****";
                    } else if ($key->getEquipStatus() == 100000) {
                        $dimaR = null;
                        $trAffich = $tsAffich = $enthalpyVariationAffich = $avgProdTempAffich = $consmatAffich = $tauxAffich = $dhpAffich = $consomaxAffich = $tauxmaxAffich = $precisionAffich = "";
                    } else {
                        $dimaResults = $this->getDoctrine()->getRepository(DimaResults::class)->findBy(["idStudyEquipments" => $key->getidStudyEquipments()], ["setpoint" => "DESC"]);
                        if (!empty($dimaResults)) {
                            $dimaR = $dimaResults[$selectTR];
                            
                            if (!empty($dimaR)) {
                                $trAffich = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $dimaR->getSetpoint());
                                $tsAffich = $this->_unit->unitConvert(Post::TYPE_UNIT_TIME, $dimaR->getDimaTS());

                                if (($key->getBrainType() != 0) && ($selectTR == 1)) {
                                    $sbgColor = "#FFFFEE";
                                    $avgProdTempAffich = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $key->getAverageProductTemp());
                                    $enthalpyVariationAffich = $this->_unit->unitConvert(Post::TYPE_UNIT_ENTHALPY, $key->getAverageProductEnthalpy());

                                    if ($key->getPrecis() < 50.0) {
                                        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(["coeffA" => 1.0, "coeffB" => 0.0, "symbol" => ""]);
                                        $precisionAffich = $this->_unit->unitConvert($unit->getTypeUnit(), $key->getAverageProductEnthalpy());
                                    } else {
                                        $precisionAffich = "!!!!";
                                    }
                                } else {
                                    $enthalpyVariationAffich = $this->_unit->unitConvert(Post::TYPE_UNIT_ENTHALPY, $dimaResults->getDimaVep());
                                    $avgProdTempAffich = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $dimaResults->getDimaTfp());
                                    $precisionAffich = "&nbsp;";
                                }

                                if ($this->_equip->getCapabilityNnc($capabilitie , 256)) {
                                    if ($lfcoef != 0.0) {
                                        if ($this->_dima->isConsoToDisplay($dimaR->getDimaStatus()) == 0) {
                                            $consmatAffich = "****";
                                        } else {
                                            $consumption = $dimaR->getConsum() / $lfcoef;
                                            $idCoolingFamily = $key->getIdEquip()->getIdCoolingFamily()->getIdCoolingFamily();

                                            $consmatAffich = $this->_unit->consumption($consumption, $idCoolingFamily, 1);
                                        }

                                        $consumption = $dimaR->getConsummax() / $lfcoef;
                                        $idCoolingFamily = $key->getIdEquip()->getIdCoolingFamily()->getIdCoolingFamily();

                                        $consomaxAffich = $this->_unit->consumption($consumption, $idCoolingFamily, 1);
                                    } else {
                                        $consmatAffich = $consomaxAffich = "****";
                                    }
                                } else {
                                    $consmatAffich = $consomaxAffich = "---";
                                }
                                

                                if ($this->_equip->getCapabilityNnc($capabilitie , 32)) {
                                    $batch = $key->getIdEquip()->getIdEquipseries()->getIdFamily()->isBatchProcess();
                                    if ($this->_dima->isConsoToDisplay($dimaR->getDimaStatus()) == 0) {
                                        $tauxAffich = "****";
                                    } else if ($batch){
                                        $massConvert =$this->_unit->unitConvert(Post::TYPE_UNIT_MASS, $dimaR->getUserate());
                                        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(["typeUnit" => Post::TYPE_UNIT_MASS]);
                                        $massSymbol =$unit->getSymbol();
                                        $tauxAffich = $massConvert . " " . $massSymbol . "/batch";
                                    } else {
                                        $percentSymbol = $dimaR->getUserate();
                                        $uPercent = $this->_unit->uPercent();
                                        $tauxAffich = $this->_unit->convertCalculator($percentSymbol, $uPercent["coeffA"], $uPercent["coeffB"]) . "%";
                                    }

                                    if ($batch) {
                                        $userateMax = $dimaR->getUserateMax();
                                        $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(["typeUnit" => Post::TYPE_UNIT_MASS]);
                                        $massSymbol =$unit->getSymbol();
                                        $tauxmaxAffich = $userateMax . " " . $massSymbol . "/batch";
                                    } else {
                                        $uPercent = $this->_unit->uPercent();
                                        $tauxAffich = $this->_unit->convertCalculator($dimaR->getUseratemax(), $uPercent["coeffA"], $uPercent["coeffB"]) . "%";
                                    }

                                    $dhpAffich = $this->_unit->unitConvert(Post::TYPE_UNIT_PRODUCT_FLOW, $dimaR->getHourlyoutputmax());
                                } else {
                                    $tauxAffich = $tauxmaxAffich = $dhpAffich = "---";
                                }
                                
                                //html
                                if (($isBrainRunning == 1) || (!($this->_equip->getCapabilityNnc($capabilitie , 128))) || ($selectTR != 1)) {
                                    $shref_Brain = "#";
                                    $soption_Brain = " disabled";
                                } else {
                                    $shref_Brain = "/refine-popup";
                                }
                                
                                if ($sSpecificSize != "") {
                                    if ($isBrainRunning == 1 && $selectTR != 1) {
                                        $shref_EqpSize = "#";
                                        $soption_EqpSize = " disabled";
                                    } else {
                                        $shref_EqpSize = "/equip-sizing";
                                    }
                                }
                                
                                if ($isBrainRunning == 1) {
                                    $saction_TR = "";
                                    $shref_TR = "#";
                                    $soption_TR = " disabled";
                                } else {
                                    if ($selectTR == 1) {
                                        $saction_TR = "popup-tr";
                                    } else {
                                        $saction_TR = "view-equip-tr";
                                    }
                                    $shref_TR = "<a href='javascript:;'' class='output-popup-tr' data-url='". $saction_TR ."'>". $soption_TR ."</a>";
                                }
                            } else {
                                $shref_Brain = "#";
                                $soption_Brain = "disabled";

                                $shref_EqpSize = "#";
                                $soption_EqpSize = "disabled";

                                $shref_TR = "#";
                                $saction_TR = "";
                                $soption_TR = "disabled";
                            }
                            
                            
                        } else {
                            $dimaR = null;
                            $trAffich = $tsAffich = $enthalpyVariationAffich = $avgProdTempAffich = $consmatAffich = $tauxAffich = $dhpAffich = $consomaxAffich = $tauxmaxAffich = $precisionAffich = "****";
                        
                            $shref_Brain = "#";
                            $soption_Brain = "disabled";

                            $shref_EqpSize = "#";
                            $soption_EqpSize = "disabled";

                            $shref_TR  = $trAffich;
                            // data demo
                            /*if ($selectTR == 1) {
                                $shref_TR = "<a href='javascript:;'' class='output-popup-tr' data-url='popup-tr' data-idstudyequipments='". $key->getidStudyEquipments() ."'>-55</a>";
                            } else {
                                $shref_TR = "<a href='javascript:;'' class='output-popup-tr' data-url='view-equip-tr' data-idstudyequipments='". $key->getidStudyEquipments() ."'>-55</a>";
                            }*/
                            $saction_TR = "";
                            $soption_TR = "disabled";
                        }                       
                    }


                    $item["debugLog"] = $debugLog;
                    $item["sBackground"] = $sbgColor;
                    $item["sSpecificSize"] = $sSpecificSize;
                    $item["sEquipName"] = $sEquipName;
                    $item["sEquipRunning"] = $sEquipRunning;
                    $item["sCalculate"] = $sCalculate;
                    $item["sCalculWarning"] = $sCalculWarning;
                    $item["sTR"] = $trAffich;
                    $item["sTS"] = $enthalpyVariationAffich;
                    $item["sH"] = $avgProdTempAffich;
                    $item["sTFP"] = $consmatAffich;
                    $item["sDHP"] = $tauxAffich;
                    $item["sConso"] = $dhpAffich;
                    $item["sTOC"] = $consomaxAffich;
                    $item["sPrecision"] = $tauxmaxAffich;
                    $item["sVC"] = $sVC;
                    $item["selectTR"] = $selectTR;
                    $item["shref_Brain"] = $shref_Brain;
                    $item["soption_Brain"] = $soption_Brain;

                    $item["shref_EqpSize"] = $shref_EqpSize;
                    $item["soption_EqpSize"] = $soption_EqpSize;

                    $item["shref_TR"] = $shref_TR;
                    $item["saction_TR"] = $saction_TR;
                    $item["soption_TR"] = $soption_TR;

                    $item["idStudyEquipments"] = $key->getidStudyEquipments();
                    $arrStudyEquipment[] = $item;
                    
                }
                
                if ($calculationMode == 2 || $calculationMode == 3) {
                    $sCalculate = "";
                    $sBackground = $sSpecificSize = $sEquipRunning = $sCalculWarning = $sTR = $sTS = $sVC = $sH
                        = $sTFP = $sDHP = $sConso = $sTOC = $sPrecision = null;
                    $debugLog = "";

                    $sSpecificSize = $this->_equip->getSpecificEquipSize($key->getidStudyEquipments());
                    // $sSpecificSize = "6.86 x 0.96";
                    $sEquipName = $this->_equip->getResultsEquipName($key->getidStudyEquipments());

                    //check kernelCalculate
                    $kernelCalculateRunning = 0;
                    if ($kernelCalculateRunning == 1) {
                        $sEquipRunning = 'disabled';
                    }

                    $runBrainPopup = false;

                    if ($this->_equip->getCapabilityNnc($capabilitie , 128)) {
                        $runBrainPopup = true;
                    }

                    if (!($this->_equip->getCapabilityNnc($capabilitie , 128))) {
                        $debugLog = "Result not applicable: no capability";
                        $sBackground = "#CAE1F7";
                        $sTR = $sTS = $sVC = $sH = $sTFP = $sDHP = $sConso = $sTOC = $sPrecision = "null";
                        $sCalculate = "disabled";
                    } else if (($key->getEquipStatus() != 0) && ($key->getEquipStatus() != 1) && ($key->getEquipStatus() != 100000)){
                        $debugLog = "Result not applicable: brain error";
                        $sBackground = "#CAE1F7";
                        $sTR = $sTS = $sVC = $sH = $sTFP = $sDHP = $sConso = $sTOC = $sPrecision = "****";
                        $sCalculate = "disabled";
                    } else if ($key->getEquipStatus() == 10000) {
                        $debugLog = "Calculation in progress";
                        $sBackground = "#CAE1F7";
                        $sTR = $sTS = $sVC = $sH = $sTFP = $sDHP = $sConso = $sTOC = $sPrecision = "";
                        $sCalculate = "disabled";
                    } else {
                        $dimaResults = $doc->getRepository(DimaResults::class)->findOneBy(['idStudyEquipments' => $key->getidStudyEquipments(), 'dimaType' => 1]);
                        if ($dimaResults == null) {
                            $sBackground = "#CAE1F7";
                            $sTR = $sTS = $sVC = $sH = $sTFP = $sDHP = $sConso = $sTOC = $sPrecision = "";
                        } else {
                            switch ($key->getBrainType()) {
                                case 0:
                                    $sCalculate = "";
                                    $sBackground = "#CAE1F7";
                                    break;

                                case 1:
                                case 2:
                                case 3:    
                                    $sCalculate = "disabled";
                                    $sBackground = "#FFFFCC";
                                    break;

                                case 4:    
                                    $sCalculate = "disabled";
                                    $sBackground = "#FFFFEE";
                                    break;

                                default:
                                    $sCalculate = "";
                                    $sBackground = "#CAE1F7";
                                    break;
                            }

                            if ($this->_dima->getCalculationWarning($dimaResults->getDimaStatus()) != 0) {
                                $currentUserLang = $user->getCodeLangue();
                                $dimaResultsCalculationWarning = $this->_dima->getCalculationWarning($dimaResults->getDimaStatus());
                                $errorTxt = $doc->getRepository(ErrorTxt::class)->findOneBy([
                                    "codeLangue" => $currentUserLang, 
                                    "errCode" => $dimaResultsCalculationWarning,
                                    "errComp" => 0
                                ]);

                                $sCalculWarning = $errorTxt->getErrTxt();
                            }

                            $sTR = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $dimaResults->getSetpoint());
                            $sTS = $this->_unit->unitConvert(Post::TYPE_UNIT_TIME, $dimaResults->getDimaTs());
                            $sVC = $this->_unit->unitConvert(Post::TYPE_UNIT_CONV_SPEED, $dimaResults->getDimaVc());
                            $sH = $this->_unit->unitConvert(Post::TYPE_UNIT_ENTHALPY, $dimaResults->getDimaVep());
                            $sTFP = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $dimaResults->getDimaTfp());

                            if ($key->getIdEquip()->getCapabilities() & 256) {
                                $consumption = $dimaResults->getConsum() / $lfcoef;
                                $idCoolingFamily = $key->getIdEquip()->getIdCoolingFamily()->getIdCoolingFamily();

                                $valueStr = $this->_unit->consumption($consumption, $idCoolingFamily, 1);

                                $calculationStatus = $this->_dima->getCalculationStatus($dimaResults->getDimaStatus());
                                $fluidOverImg = "<img src='assets/dist/img/output/warning_fluid_overflow.gif' width='30' height='30' /> ";
                                $dhpOverImg = "<img src='assets/dist/img/output/warning_dhp_overflow.gif' width='30' height='30' /> ";

                                $sConso = $this->_dima->consumptionCell($lfcoef, $calculationStatus, $valueStr, $fluidOverImg, $dhpOverImg);

                            } else {
                                $sConso = "****";
                            }


                            if ($this->_equip->getCapabilityNnc($capabilitie , 32)) {
                                $sDHP = $this->_unit->unitConvert(Post::TYPE_UNIT_PRODUCT_FLOW, $dimaResults->getHourlyoutputmax());
                                
                                $batch = $key->getIdEquip()->getIdEquipseries()->getIdFamily()->isBatchProcess();
                                if ($batch) {
                                    $massConvert =$this->_unit->unitConvert(Post::TYPE_UNIT_MASS, $dimaResults->getUserate());
                                    $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(['typeUnit' => Post::TYPE_UNIT_MASS]);
                                    $massSymbol =$unit->getSymbol();
                                    $sTOC = $massConvert . $massSymbol . "/batch";
                                } else {
                                    $percentSymbol = $dimaResults->getUserate();
                                    $uPercent = $this->_unit->uPercent();
                                    // dump($uPercent);die;
                                    $sTOC = $this->_unit->convertCalculator($percentSymbol, $uPercent["coeffA"], $uPercent["coeffB"]) . "%";
                                }
                            } else {
                                $sDHP = $sTOC = "****";
                            }

                            if ($dimaResults->getDimaPrecis() < 50.0) {
                                $uNone = $this->_unit->uNone();
                                $sPrecision = $this->_unit->convertCalculator($dimaResults->getDimaPrecis(), $uNone["coeffA"], $uNone["coeffB"]);
                            } else {
                                $sPrecision = "!!!!";
                            }
                        }
                    }
                    
                    $item["runBrainPopup"] = $runBrainPopup;
                    $item["idEquip"] = $key->getIdEquip()->getIdEquip();
                    $item["debugLog"] = $debugLog;
                    $item["sCalculate"] = $sCalculate;
                    $item["sBackground"] = $sBackground;
                    $item["sSpecificSize"] = $sSpecificSize;
                    $item["sEquipName"] = $sEquipName;
                    $item["sEquipRunning"] = $sEquipRunning;
                    $item["sCalculWarning"] = $sCalculWarning;
                    $item["sTR"] = $sTR;
                    $item["sTS"] = $sTS;
                    $item["sVC"] = $sVC;
                    $item["sH"] = $sH;
                    $item["sTFP"] = $sTFP;
                    $item["sDHP"] = $sDHP;
                    $item["sConso"] = $sConso;
                    $item["sTOC"] = $sTOC;
                    $item["sPrecision"] = $sPrecision;
                    $item["idStudyEquipments"] = $key->getidStudyEquipments();
                    $arrStudyEquipment[] = $item;
                } 
                $i++;
            }
        }

        $productFlowSymbol = $this->_unit->productFlowSymbol();
        $massSymbol = $this->_unit->massSymbol();
        $temperatureSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_TEMPERATURE);
        $enthalpySymbol = $this->_unit->enthalpySymbol();
        $perUnitOfMassSymbol = $this->_unit->perUnitOfMassSymbol();
        $timeSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_TIME);
        $convectionSpeedSymbol = $this->_unit->convectionSpeedSymbol();
     
        $isBrainRunning = 0;
        $data = [
            "ecoEnable" => $ecoEnable,
            "objProduction" => $production,
            "prodElmtRealweight" => $prodElmtRealweight,
            "objStudyEquipment" => $listObjSEquipment,
            "arrStudyEquipment" => $arrStudyEquipment,
            "isBrainRunning" => $isBrainRunning,
            "calculationMode" => $calculationMode,
            "brandModeFull" => POST::BRAIN_MODE_OPTIMUM_FULL,
            "brandModeRefine" => POST::BRAIN_MODE_OPTIMUM_REFINE,
            "productFlowSymbol" => $productFlowSymbol,
            "massSymbol" => $massSymbol,
            "temperatureSymbol" => $temperatureSymbol,
            "enthalpySymbol" => $enthalpySymbol,
            "percentSymbol" => "%",
            "perUnitOfMassSymbol" => $perUnitOfMassSymbol,
            "timeSymbol" => $timeSymbol,
            "convectionSpeedSymbol" => $convectionSpeedSymbol
        ];
        
        if (isset($selectTR)) $data["selectTR"] = $selectTR;

        if ($calculationMode == 1)
            $urlRender = "output/analytical/estimation/analytical-thermic.html.twig";
        else 
            $urlRender = "output/analytical/optimum/head-balance.html.twig";
        
        return $this->render($urlRender, $data);
    }
    
    /**
    * @Route("/head-balance-max", name="head-balance-max")
    */
    public function heatBalanceMaxAction(Request $request)
    {
        $session = $request->getSession();
        $doc = $this->getDoctrine();
        // check user login
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->redirectToRoute("login");
        }
        // check idStudy already exists
        $idStudy = $session->get("idStudy");
        if ($idStudy == null || $idStudy == 0 || $idStudy == "") {
            return $this->redirectToRoute("load-study");
        }
        
        // get object Study
        $idStudy = 26;
        $objStudy= $doc->getRepository(Studies::class)->find($idStudy);

        $idUser = $user->getIdUser();
        $idProd = $session->get("idProd");
        $loadEquipment = $session->get("loadEquipment");

        $checkControl = $this->_check->isCheckControl($idUser, $idStudy, $loadEquipment, $idProd);
        //check control
        /*if($checkControl == false){
            return $this->redirectToRoute("checkcontrol");
        }*/
        
        if ($objStudy->getCalculationMode() == 1) return $this->redirect("/");
        
        // get object Production by idStudy
        $production = $doc->getRepository(Production::class)->findOneBy(["idStudy" => $objStudy->getIdStudy()]);
        
        //get Real product mass per unit
        $listPro = $this->getDoctrine()->getRepository(Product::class)->findOneBy(array("idStudy" => $objStudy->getIdStudy()));
        $prodElmtRealweight = $listPro->getProdRealweight();

        $listObjSEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(["idStudy"=>$objStudy->getIdStudy()],["idStudyEquipments"=>"ASC"]);

        $ecoEnable = false;
        if ($user->getUserprio() < POST::PROFIL_GUEST && $objStudy->getOptionEco() == POST::STUDY_ECO_MODE) {
            $ecoEnable = true;
        }

        $lfcoef = $this->_unit->unitConvert(Post::TYPE_UNIT_MASS_PER_UNIT, 1.0);

        $arrStudyEquipment = array();
        if (count($listObjSEquipment)>0) { 
            $i = 0;
            foreach ($listObjSEquipment as $key) {
                $sBackground = $sSpecificSize = $sEquipRunning = $sCalculWarning = $sTR = $sTS = $sVC = $sH
                    = $sTFP = $sDHP = $sConso = $sTOC = $sPrecision = null;
                $debugLog = "";
                $capabilitie = $key->getIdEquip()->getCapabilities();

                $sSpecificSize = $this->_equip->getSpecificEquipSize($key->getidStudyEquipments());
                $sEquipName = $this->_equip->getResultsEquipName($key->getidStudyEquipments());            
              
                $dimaResults = $doc->getRepository(DimaResults::class)->findOneBy(["idStudyEquipments" => $key->getidStudyEquipments(), "dimaType" => 16]);
            
                if (!($this->_equip->getCapabilityNnc($capabilitie , 128))) {
                     $debugLog = "Result not applicable: no capability or brain error";
                     $sBackground = "#CAE1F7";
                     $sTS = $sVC = $sH = $sTFP = $sDHP = $sConso = $sTOC = $sPrecision = "****";
                } else if ($dimaResults == null) {
                    $sBackground = "#CAE1F7";
                    $sTS = $sVC = $sH = $sTFP = $sDHP = $sConso = $sTOC = $sPrecision = "";
                } else {
                    $ldError = $this->_dima->getCalculationWarning($dimaResults->getDimaStatus());
                    $ldWarning = 0;
                    if (($ldError == 282) || ($ldError == 283) || ($ldError == 284) || ($ldError == 285) || ($ldError == 286)) {
                        $ldWarning = $ldError;
                        $ldError = 0;
                        
                        $debugLog = "Error code " . $ldError;
                        $debugLog . "- Warning code " . $ldWarning;
                        $debugLog . "- Dima status " . $this->_dima->getCalculationStatus($dimaResults->getDimaStatus());
                    }
                    
                    if ($ldError != 0) {
                        $sBackground = "#CAE1F7";
                        $sTS = $sVC = $sH = $sTFP = $sDHP = $sConso = $sTOC = $sPrecision = "****";
                    } else {
                        if ($ldWarning != 0) {
                            $currentUserLang = $user->getCodeLangue();
                            $errorTxt = $doc->getRepository(ErrorTxt::class)->findOneBy([
                                "codeLangue" => $currentUserLang, 
                                "errCode" => $ldWarning,
                                "errComp" => 0
                            ]);
                            $sCalculWarning = $errorTxt.getErrTxt(); 
                        }
                        
                        $sBackground = "#FFF";
                        $sTR = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $dimaResults->getSetpoint());
                        $sTS = $this->_unit->unitConvert(Post::TYPE_UNIT_TIME, $dimaResults->getDimaTs());
                        $sVC = $this->_unit->unitConvert(Post::TYPE_UNIT_CONV_SPEED, $dimaResults->getDimaVc());
                        $sH = $this->_unit->unitConvert(Post::TYPE_UNIT_ENTHALPY, $dimaResults->getDimaVep());
                        $sTFP = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $dimaResults->getDimaTfp());
                        
                        if ($this->_equip->getCapabilityNnc($capabilitie , 256)) {
                            $consumption = $dimaResults->getConsum() / $lfcoef;
                            $idCoolingFamily = $key->getIdEquip()->getIdCoolingFamily()->getIdCoolingFamily();

                            $valueStr = $this->_unit->consumption($consumption, $idCoolingFamily, 1);

                            $calculationStatus = $this->_dima->getCalculationStatus($dimaResults->getDimaStatus());
                            $fluidOverImg = "<img src='assets/dist/img/output/warning_fluid_overflow.gif' width='30' height='30' /> ";
                            $dhpOverImg = "<img src='assets/dist/img/output/warning_dhp_overflow.gif' width='30' height='30' /> ";

                            $sConso = $this->_dima->consumptionCell($lfcoef, $calculationStatus, $valueStr, $fluidOverImg, $dhpOverImg);
                        } else {
                            $sConso = "****";
                        }
                        
                        if ($this->_equip->getCapabilityNnc($capabilitie , 32)) {
                            $sDHP = $this->_unit->unitConvert(Post::TYPE_UNIT_PRODUCT_FLOW, $dimaResults->getHourlyoutputmax());

                            $batch = $key->getIdEquip()->getIdEquipseries()->getIdFamily()->isBatchProcess();
                            if ($batch) {
                                $massConvert =$this->_unit->unitConvert(Post::TYPE_UNIT_MASS, $dimaResults->getUserate());
                                $unit = $this->getDoctrine()->getRepository(Unit::class)->findOneBy(["typeUnit" => Post::TYPE_UNIT_MASS]);
                                $massSymbol =$unit->getSymbol();
                                $sTOC = $massConvert . " " . $massSymbol . "/batch";
                            } else {
                                $percentSymbol = $dimaResults->getUserate();
                                $uPercent = $this->_unit->uPercent();
                                $sTOC = $this->_unit->convertCalculator($percentSymbol, $uPercent["coeffA"], $uPercent["coeffB"]) . "%";
                            }
                        } else {
                            $sDHP = $sTOC = "****";
                        }

                        if ($dimaResults->getDimaPrecis() < 50.0) {
                            $uNone = $this->_unit->uNone();
                            $sPrecision = $this->_unit->convertCalculator($dimaResults->getDimaPrecis(), $uNone["coeffA"], $uNone["coeffB"]);
                        } else {
                            $sPrecision = "!!!!";
                        }
                    }
                }
       
                $item["debugLog"] = $debugLog;
                $item["sBackground"] = $sBackground;
                $item["sSpecificSize"] = $sSpecificSize;
                $item["sEquipName"] = $sEquipName;
                $item["sEquipRunning"] = $sEquipRunning;
                $item["sCalculWarning"] = $sCalculWarning;
                $item["sTR"] = $sTR;
                $item["sTS"] = $sTS;
                $item["sVC"] = $sVC;
                $item["sH"] = $sH;
                $item["sTFP"] = $sTFP;
                $item["sDHP"] = $sDHP;
                $item["sConso"] = $sConso;
                $item["sTOC"] = $sTOC;
                $item["sPrecision"] = $sPrecision;
                $item["idStudyEquipments"] = $key->getidStudyEquipments();
                $arrStudyEquipment[] = $item;
                $i++;
            }
        }
        
        $isBrainRunning = 0;

        $data = [
            "ecoEnable" => $ecoEnable,
            "objProduction" => $production,
            "prodElmtRealweight" => $prodElmtRealweight,
            "objStudyEquipment" => $listObjSEquipment,
            "arrStudyEquipment" => $arrStudyEquipment,
            "isBrainRunning" => $isBrainRunning,
            "objStudy" => $objStudy,
            "brandModeMax" => POST::BRAIN_MODE_OPTIMUM_DHPMAX,
        ];


        $urlRender = "output/analytical/optimum/head-balance-max.html.twig";
        
        return $this->render($urlRender, $data);
    }

    /**
    * @Route("/analytical-consumption", name="analytical-consumption")
    */
    public function analyticalConsumptionAction(Request $request)
    {
        $session = $request->getSession();
        $doc = $this->getDoctrine();
        // check user login
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->redirectToRoute("login");
        }
        // check idStudy already exists
        $idStudy = $session->get("idStudy");
        if ($idStudy == null || $idStudy == 0 || $idStudy == "") {
            return $this->redirectToRoute("load-study");
        }
        $idStudy = 3;
        // get object Study
        $objStudy= $doc->getRepository(Studies::class)->find($idStudy);

        $idUser = $user->getIdUser();
        $idProd = $session->get("idProd");
        $loadEquipment = $session->get("loadEquipment");

        $checkControl = $this->_check->isCheckControl($idUser, $idStudy, $loadEquipment, $idProd);
        //check control
        /*if($checkControl == false){
            return $this->redirectToRoute("checkcontrol");
        }*/

        $calculationMode = $objStudy->getCalculationMode();

        // $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(["idStudy"=>$objStudy->getIdStudy(), "brainType >=" => 0],["idStudyEquipments"=>"ASC"]);   
        
        $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->createQueryBuilder("s")
            ->where("s.idStudy = :idStudy")
            ->andWhere("s.brainType >= :brainType")
            ->setParameter("idStudy", $objStudy->getIdStudy())
            ->setParameter("brainType", 0)
            ->orderBy("s.idStudyEquipments", "ASC")
            ->getQuery()->getResult();
        
        $lfcoef = $this->_unit->unitConvert(Post::TYPE_UNIT_MASS_PER_UNIT, 1.0);

        $ecoEnable = false;
        if ($user->getUserprio() < POST::PROFIL_GUEST && $objStudy->getOptionEco() == POST::STUDY_ECO_MODE) {
            $ecoEnable = true;
        }

        $consumSymbol = $this->_unit->consumptionSymbol($this->_equip->initEnergyDef(), 1);
        $perUnitOfMassSymbol = $this->_unit->perUnitOfMassSymbol();
        $consumMaintienSymbol = $this->_unit->consumptionSymbol($this->_equip->initEnergyDef(), 2);
        $mefSymbol = $this->_unit->consumptionSymbol($this->_equip->initEnergyDef(), 3);

        $arrStudyEquipment = array();

        foreach ($studyEquipments as $row) {
            $equipment = $row->getIdEquip();

            $capabilities = $equipment->getCapabilities();

            $message = "";
            $sSpecificSize = $sEquipName = $energy = "";
            $tc = "";
            $ckgProduct = "";
            $cProduct = "";
            $sCday = "";
            $cWeek = "";
            $cHour = "";
            $CMonth = "";
            $cYear = "";
            $cEqptperm = "";
            $CEqptcold = "";
            $cLinecold = "";
            $cLineperm = "";
            $cTank = "";

            $item["dimaStatus"] = "";
            $item["equipStatus"] = $row->getEquipStatus();

            if ($this->_equip->getCapabilityNnc($capabilities, 256)) {
                $sEquipName = $this->_equip->getSpecificEquipName($row->getIdStudyEquipments());

                $energy = $equipment->getIdCoolingFamily()->getIdCoolingFamily();

                $ecoR = $this->getDoctrine()->getRepository(EconomicResults::class)->findOneBy(["idStudyEquipments"=>$row->getIdStudyEquipments()]);

                if (empty($economicResults)) {
                    $message = "Problems occurs while retrieving data from EconomicResults table ...";
                }

                if ($calculationMode == 1) {
                    $studEqpPrm = $this->getDoctrine()->getRepository(StudEqpPrm::class)->findOneBy(["idStudyEquipments"=>$row->getIdStudyEquipments(), "valueType" =>300]); 
                    $lfSetpoint = 0.0;
                    if (!empty($lfSetpoint)) {
                        $lfSetpoint = $studEqpPrm->getValue();
                    } else {
                        $message = "Problems occurs while retrieving data from StudEqpPrm table ...";
                    }

                    $dimaR = $doc->getRepository(DimaResults::class)->findOneBy(["idStudyEquipments" => $row->getidStudyEquipments(), "setpoint" => $lfSetpoint]);
                } else {
                    $dimaR = $doc->getRepository(DimaResults::class)->findOneBy(["idStudyEquipments" => $row->getidStudyEquipments(), "dimaType" => 1]);
                }
                
                if ($dimaR == null) {
                    $message = "Problems occurs while retrieving data from DimaResults table ...";
                }

                if ($ecoR != null) {
                    
                    if ($dimaR != null) {
                        $dimaStatus = $this->_dima->getCalculationStatus($dimaR->getDimaStatus());
                        $equipStatus = $row->getEquipStatus();
                    } else {
                        $dimaStatus = 1;
                        $equipStatus = 0;
                    }
                    

                    $item["dimaStatus"] = $dimaStatus;
                    $item["equipStatus"] = $equipStatus;

                    $consoToDisplay = $this->_eco->isConsoToDisplay($dimaStatus, $equipStatus);
                    if (!$consoToDisplay) {
                        $tc = "****";
                        $ckgProduct = "****";
                        $cProduct = "****";
                        $sCday = "****";
                        $cWeek = "****";
                        $cHour = "****";
                        $CMonth = "****";
                        $cYear = "****";
                        $cEqptperm = "****";
                        $CEqptcold = "****";
                        $cLinecold = "****";
                        $cLineperm = "****";
                        $cTank = "****";
                        $cPercentProduct = 0;
                        $cPercentEquipmentPerm = 0;
                        $cPercentEquipmentDown = 0;
                        $cPercentLine = 0;
                    } else {
                        $tc = $this->_unit->consumption($ecoR->getFluidConsumptionTotal() / $lfcoef, $energy, 1);
                        if ($lfcoef != 0.0) {
                            $ckgProduct = $this->_unit->consumption($ecoR->getFluidConsumptionPerKg() / $lfcoef, $energy, 1);
                            $cProduct = $this->_unit->consumption($ecoR->getFluidConsumptionProduct() / $lfcoef, $energy, 1);
                        } else {
                            $ckgProduct = $cProduct = "****";
                        }
    
                        $cday = $ecoR->getFluidConsumptionDay();
                        $sCday = $this->_unit->consumption($cday, $energy, 0);
                        $cEqptcold = $this->_unit->consumption($ecoR->getFluidConsumptionMatGetCold(), $energy, 3);
                        $cEqptperm = $this->_unit->consumption($ecoR->getFluidConsumptionMatPerm(), $energy, 2);
                        $cLinecold = $this->_unit->consumption($ecoR->getFluidConsumptionLineGetCold(), $energy, 3);
                        $cLineperm = $this->_unit->consumption($ecoR->getFluidConsumptionLinePerm(), $energy, 2);
                        $cTank = $this->_unit->consumption($ecoR->getFluidConsumptionTank(), $energy, 1);
                        $cWeek = $this->_unit->consumption($ecoR->getFluidConsumptionWeek(), $energy, 1);
                        $cHour = $this->_unit->consumption($ecoR->getFluidConsumptionHour(), $energy, 1);
                        $cMonth = $this->_unit->consumption($ecoR->getFluidConsumptionMonth(), $energy, 1);
                        $cYear = $this->_unit->consumption($ecoR->getFluidConsumptionYear(), $energy, 1);

                        $cPercentProduct = $ecoR->getPercentProduct();
                        $cPercentEquipmentPerm = $ecoR->getPercentEquipmentPerm();
                        $cPercentEquipmentDown = $ecoR->getPercentEquipmentDown();
                        $cPercentLine = $ecoR->getPercentLine();


                        $item["cPercentProduct"] = round($cPercentProduct * 100);
                        $item["cPercentEquipmentPerm"] = round($cPercentEquipmentPerm * 100);
                        $item["cPercentEquipmentDown"] = round($cPercentEquipmentDown * 100);
                        $item["cPercentLine"] = round($cPercentLine * 100);
                    }             
                }
            }

            $item["iDstudyE"] = $row->getIdStudyEquipments();
            $item["eqptName"] = $equipment->getEquipName();
            
            $item["tc"] = $tc;
            $item["ckgProduct"] = $ckgProduct;
            $item["cProduct"] = $cProduct;
            $item["sCday"] = $sCday;
            $item["cWeek"] = $cWeek;
            $item["cHour"] = $cHour;
            $item["cMonth"] = $CMonth;
            $item["cYear"] = $cYear;
            $item["cEqptperm"] = $cEqptperm;
            $item["cEqptcold"] = $CEqptcold;
            $item["cLinecold"] = $cLinecold;
            $item["cLineperm"] = $cLineperm;
            $item["cTank"] = $cTank;

            $item["cPercentProduct"] = round($cPercentProduct * 100);
            $item["cPercentEquipmentPerm"] = round($cPercentEquipmentPerm * 100);
            $item["cPercentEquipmentDown"] = round($cPercentEquipmentDown * 100);
            $item["cPercentLine"] = round($cPercentLine * 100);
            

            $item["chartPercent"] = '{"Product":"'. $item["cPercentProduct"] .'", "EquipmentPerm":"'. $item["cPercentEquipmentPerm"] .'", "EquipmentDown":"'. $item["cPercentEquipmentDown"] .'", "Line":"'. $item["cPercentLine"] .'"}';

            $item["chartlabel"] = '{"Product":"Product", "EquipmentPerm":"Equipment(permanent)", "EquipmentDown":"Equipment(cooldonw)", "Line":"Line"}';

            $arrStudyEquipment[] = $item;
        }

        // dump($arrStudyEquipment);die;

        $data = [
            "ecoEnable" => $ecoEnable,
            "consumSymbol" => $consumSymbol,
            "perUnitOfMassSymbol" => $perUnitOfMassSymbol,
            "consumMaintienSymbol" => $consumMaintienSymbol,
            "mefSymbol" => $mefSymbol,
            "arrStudyEquipment" => $arrStudyEquipment
        ];
        $urlRender = "output/analytical/common/analytical-consumption.html.twig";
        
        return $this->render($urlRender, $data);
    }

    /**
    * @Route("/analytical-economic", name="analytical-economic")
    */
    public function analyticalEconomicAction(Request $request)
    {
        $session = $request->getSession();
        $doc = $this->getDoctrine();
        // check user login
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->redirectToRoute("login");
        }
        // check idStudy already exists
        $idStudy=$session->get("idStudy");
        if($idStudy == null || $idStudy == 0 || $idStudy == ""){
            return $this->redirectToRoute("load-study");
        }
        $idStudy = 4;

        // get object Study
        $objStudy= $doc->getRepository(Studies::class)->find($idStudy);

        $idUser = $user->getIdUser();
        $idProd = $session->get("idProd");
        $loadEquipment = $session->get("loadEquipment");

        $checkControl = $this->_check->isCheckControl($idUser, $idStudy, $loadEquipment, $idProd);
        //check control
        /*if($checkControl == false){
            return $this->redirectToRoute("checkcontrol");
        }*/

        $calculationMode = $objStudy->getCalculationMode();

        $studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(["idStudy"=>$objStudy->getIdStudy()],["idStudyEquipments"=>"ASC"]);   
        // dump($studyEquipments);die;
        $lfcoef = $this->_unit->unitConvert(Post::TYPE_UNIT_MASS_PER_UNIT, 1.0);

        $arrStudyEquipment = array();

        foreach ($studyEquipments as $row) {
            $equipment = $row->getIdEquip();
            $capabilitie = $equipment->getCapabilities();
            $message = "";
            $sSpecificSize = $sEquipName = $energy = "";
            $tc = "";
            $ckgProduct = "";
            $cProduct = "";
            $sCday = "";
            $cWeek = "";
            $cHour = "";
            $CMonth = "";
            $cYear = "";
            $cEqptperm = "";
            $CEqptcold = "";
            $cLinecold = "";
            $cLineperm = "";
            $cTank = "";

            $item["dimaStatus"] = "";
            $item["equipStatus"] = $row->getEquipStatus();

            if ($this->_equip->getCapabilityNnc($capabilitie , 256)) {
                $sEquipName = $this->_equip->getSpecificEquipName($row->getIdStudyEquipments());
                $energy = $equipment->getIdCoolingFamily()->getIdCoolingFamily();
                $ecoR = $this->getDoctrine()->getRepository(EconomicResults::class)->findOneBy(["idStudyEquipments"=>$row->getIdStudyEquipments()]);

                if (empty($economicResults)) {
                    $message = "Problems occurs while retrieving data from EconomicResults table ...";
                }

                $studEqpPrm = $this->getDoctrine()->getRepository(StudEqpPrm::class)->findOneBy(["idStudyEquipments"=>$row->getIdStudyEquipments(), "valueType" =>300]); 
                $lfSetpoint = 0.0;

                if (!empty($lfSetpoint)) {
                    $lfSetpoint = $studEqpPrm->getValue();
                } else {
                    $message = "Problems occurs while retrieving data from StudEqpPrm table ...";
                }

                $dimaR = $doc->getRepository(DimaResults::class)->findOneBy(["idStudyEquipments" => $row->getidStudyEquipments(), "setpoint" => $lfSetpoint]);
                
                
                if ($dimaR == null) {
                    $message = "Problems occurs while retrieving data from DimaResults table ...";
                }

                if ($ecoR != null) {
                    if ($dimaR != null) {
                        $dimaStatus = $this->_dima->getCalculationStatus($dimaR->getDimaStatus());
                        $equipStatus = $row->getEquipStatus();
                    } else {
                        $dimaStatus = 1;
                        $equipStatus = 0;
                    }

                    $item["dimaStatus"] = $dimaStatus;
                    $item["equipStatus"] = $equipStatus;

                    $consoToDisplay = $this->_eco->isConsoToDisplay($dimaStatus, $equipStatus);
                    if (!$consoToDisplay) {
                        $tc = "****";
                        $ckgProduct = "****";
                        $cProduct = "****";
                        $sCday = "****";
                        $cWeek = "****";
                        $cHour = "****";
                        $CMonth = "****";
                        $cYear = "****";
                        $cEqptperm = "****";
                        $CEqptcold = "****";
                        $cLinecold = "****";
                        $cLineperm = "****";
                        $cTank = "****";
                    } else {
                        $tc = $this->_unit->monetary($ecoR->getCostTotal());
                        if ($lfcoef != 0.0) {
                            $ckgProduct = $this->_unit->monetary($ecoR->getCostKg() / $lfcoef);
                            $cProduct = $this->_unit->monetary($ecoR->getCostProduct() / $lfcoef); 
                        } else {
                            $ckgProduct = $cProduct = "****";
                        }
                        $cYear = $this->_unit->monetary($ecoR->getCostYear(), 0);
                        $cWeek = $this->_unit->monetary($ecoR->getCostWeek(), 0);
                        $sCday = $this->_unit->monetary($ecoR->getCostDay(), 0);
                        $cHour = $this->_unit->monetary($ecoR->getCostHour());
                        $cEqptperm = $this->_unit->monetary($ecoR->getCostMatPerm());
                        $cEqptcold = $this->_unit->monetary($ecoR->getCostMatGetCold());
                        $cLineperm = $this->_unit->monetary($ecoR->getCostLinePerm());
                        $cLinecold = $this->_unit->monetary($ecoR->getCostLineGetCold());
                        $cTank = $this->_unit->monetary($ecoR->getCostTank());
                    }
                    
                }
                
            }

            $item["iDstudyE"] = $row->getIdStudyEquipments();
            $item["eqptName"] = $equipment->getEquipName();
            
            $item["tc"] = $tc;
            $item["ckgProduct"] = $ckgProduct;
            $item["cProduct"] = $cProduct;
            $item["sCday"] = $sCday;
            $item["cWeek"] = $cWeek;
            $item["cHour"] = $cHour;
            $item["cMonth"] = $CMonth;
            $item["cYear"] = $cYear;
            $item["cEqptperm"] = $cEqptperm;
            $item["cEqptcold"] = $CEqptcold;
            $item["cLinecold"] = $cLinecold;
            $item["cLineperm"] = $cLineperm;
            $item["cTank"] = $cTank;

            $arrStudyEquipment[] = $item;


        }

        // dump($arrStudyEquipment);die;
        $monetarySymbol = $this->_unit->monetarySymbol();
        $perUnitOfMassSymbol = $this->_unit->perUnitOfMassSymbol();

        $data = [
            "monetarySymbol" => $monetarySymbol,
            "perUnitOfMassSymbol" => $perUnitOfMassSymbol,
            "arrStudyEquipment" => $arrStudyEquipment
        ];
        $urlRender = "output/analytical/common/analytical-economic.html.twig";
        
        return $this->render($urlRender, $data);
    }
    
    /**
    * @Route("/refine-popup", name="refine-popup")
    */
    public function refinePopupAction(Request $request)
    {
        $session = $request->getSession();
        $doc = $this->getDoctrine();
        // check user login
        $user = $this->getUser();
        if ($user == NULL){
            return $this->redirectToRoute("login");
        }
        // check idStudy already exists
        $idStudy = $session->get("idStudy");
        if ($idStudy == null || $idStudy == 0 || $idStudy == "") {
            return $this->redirectToRoute("load-study");
        }

        $data = array();

        $sdisableCalculate 	= $this->_study->disableCalculate( $idStudy );
        $sdisableFields = $this->_study->disableFields( $idStudy );
        $objStudy = $doc->getRepository(Studies::class)->find($idStudy);
        $idStudyEquipments = $request->get("idstudyequipments");

        $calcParameters = "";
        
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudy"=>$objStudy->getIdStudy(), "idStudyEquipments" => $idStudyEquipments]);
        $calculationMode = $objStudy->getCalculationMode();

        $epsilonTemp = $epsilonEnth = $nbOptimIter = "";
        $timestep = $precision = $storagestep = "";
        $maxIter = $relaxCoef = "";
        $tempPtSurf = $tempPtIn = $tempPtBot = $tempPtAvg =  "";

        if ($sdisableFields == "") {
            switch ($calculationMode) {
                case Post::BRAIN_MODE_ESTIMATION:
                case Post::BRAIN_MODE_ESTIMATION_OPTIM:
                $sdisableTS = $sdisableTR = $sdisableTOC = $sdisableNbOptim = $sdisableStorage = "disabled";
				$sclassTS = $sclassTR = $sclassTOC = $sclassNbOptim = $sclassStorage = "sous-titredisabled";
				$scheckOptim = $scheckStorage = "";
                    break;

                case Post::BRAIN_MODE_OPTIMUM_FULL:
                case Post::BRAIN_MODE_SELECTED_FULL:
                    $sdisableTS = $sdisableTR = $sdisableNbOptim = $sdisableStorage = "";
                    $sclassTS = $sclassTR = $sclassNbOptim = $sclassStorage = "sous-titre";
                    $sdisableTOC = "disabled";
                    $sclassTOC = "sous-titredisabled";
                    $scheckOptim = "";
                    $scheckStorage = "checked";
                        break;

                case Post::BRAIN_MODE_OPTIMUM_REFINE:
                case Post::BRAIN_MODE_SELECTED_REFINE:
                    $sdisableTS = $sdisableTR = $sdisableNbOptim = "";
                    $sclassTS = $sclassTR = $sclassNbOptim = "sous-titre";
                    $sdisableTOC = $sdisableStorage = "disabled";
                    $sclassTOC = $sclassStorage = "sous-titredisabled";
                    $scheckOptim = "checked";
                    $scheckStorage = "";
                    break;

                case Post::BRAIN_MODE_OPTIMUM_DHPMAX:
                case Post::BRAIN_MODE_SELECTED_DHPMAX:
                    $sdisableTR = $sdisableTOC = "";
                    $sclassTR = $sclassTOC = "sous-titre";
                    $sdisableTS = $sdisableNbOptim = $sdisableStorage = "disabled";
                    $sclassTS = $sclassNbOptim = $sclassStorage = "sous-titredisabled";
                    $scheckOptim = "checked";
                    $scheckStorage = "";
                    break;
                
                default:
                    $sdisableTS = $sdisableTR = $sdisableTOC = $sdisableNbOptim = $sdisableStorage = "disabled";
                    $sclassTS = $sclassTR = $sclassTOC = $sclassNbOptim = $sclassStorage = "sous-titredisabled";
                    $scheckOptim = $scheckStorage = "";
                    break;
            }

            if (!($this->_equip->getCapabilityNnc($studyEquipment->getIdEquip()->getCapabilities(), 64))) {
                $sdisableOptim = $sdisableNbOptim = "disabled";
                $scheckOptim = "";
            } else {
                $sdisableOptim = "";
            }
        } else {
            $sdisableTS = $sdisableTR = $sdisableTOC = $sdisableOptim = $sdisableNbOptim = $sdisableStorage = "disabled";
            $sclassTS = $sclassTR = $sclassTOC = $sclassNbOptim = $sclassStorage = "sous-titredisabled";
            $scheckOptim = $scheckStorage = "";
        }

        $isDHPMax = false;
        $lfToc = null;
        $lTR = array();
        $lTS = array();
        
        
        if ($calculationMode == Post::BRAIN_MODE_OPTIMUM_DHPMAX || $calculationMode = Post::BRAIN_MODE_SELECTED_DHPMAX) {
            $isDHPMax = true;
            if ($studyEquipment->getIdLayoutResults() != null) {
                $layoutResult = $this->getDoctrine()->getRepository(LayoutResults::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);
                if (!empty($layoutResult)) $lfToc = $layoutResult->getLoadingRateMax();
            } 
        } else {
            if ($studyEquipment->getIdLayoutResults() != null){
                $layoutResult = $this->getDoctrine()->getRepository(LayoutResults::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);
                $lfToc = $layoutResult->getLoadingRate();
            } 
            $lTR = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder("s")
                    ->where("s.valueType >= :valueType1 AND s.valueType <= :valueType2")
                    ->andWhere("s.idStudyEquipments = :idStudyEquipments")
                    ->setParameter("valueType1", "300")
                    ->setParameter("valueType2", "399")
                    ->setParameter("idStudyEquipments", $studyEquipment->getIdStudyEquipments())
                    ->getQuery()->getResult();
        }
        $lTS = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder("s")
                    ->where("s.valueType >= :valueType1 AND s.valueType <= :valueType2")
                    ->andWhere("s.idStudyEquipments = :idStudyEquipments")
                    ->setParameter("valueType1", "200")
                    ->setParameter("valueType2", "299")
                    ->setParameter("idStudyEquipments", $studyEquipment->getIdStudyEquipments())
                    ->getQuery()->getResult();
        
        
        $nbMaxEqpPrm = (count($lTR) > count($lTS)) ? count($lTR) : count($lTS);

        
        $lfDwellingTime = 0.0;
        for ($i = 0; $i < count($lTS); $i++) {
            $lfDwellingTime += $lTS[$i]->getValue();
        }

        $arrLts = array();
        if ($lTS != null) {
            foreach ($lTS as $row) {
                $item["inputValue"] = ($isDHPMax) ? POST::VALUE_N_A  : $this->_unit->unitConvert(Post::TYPE_UNIT_TIME, $row->getValue());
                $item["inputDisabled"] = ($sdisableTS == "disabled") ? "disabled" : "";
                $arrLts[] = $item;
            }
        }

        $arrLtr = array();
        if ($lTR != null) {
            foreach ($lTR as $row) {
                if (($studyEquipment->getidEquip()->isStd() == POST::EQUIP_NOT_STANDARD) && (!($studyEquipment->getIdEquip()->getCapabilities() & 1))  && (!($studyEquipment->getIdEquip()->getCapabilities() & 65536))) {
                    $item["submitChange"] = true;
                } else {
                    $item["submitChange"] = false;
                }

                $item["inputValue"] = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $row->getValue());
                $item["inputDisabled"] = ($sdisableTS == "disabled") ? "disabled" : "";
                if ((!($studyEquipment->getIdEquip()->getCapabilities() & 1)) || ($sdisableTR == "disabled")) {
                    $sdisableTR = "disabled";
                } else {
                    $sdisableTR = "";
                }

                $arrLtr[] = $item;
            }
        }

        $uPercent = $this->_unit->uPercent();
        $unitlfToc = $this->_unit->convertCalculator($lfToc, $uPercent["coeffA"], $uPercent["coeffB"]);
        
        $ldStorageStepRatio = 1;
        if ($studyEquipment->getIdCalcParams() != null) {
            $calcParameters = $this->getDoctrine()->getRepository(CalculationParameters::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);
            $timeStep = $calcParameters->getTimeStep();
            $lfTimeStep = $this->_unit->convertToDouble($timeStep);
            if ( $lfTimeStep > 0 ) {
                $lfStep = $calcParameters->getStorageStep() * $calcParameters->getTimeStep(); 
                $lfStep = $this->_unit->unitConvert(Post::TYPE_UNIT_TIME, $lfStep);
                $lfStorageStep = $this->_unit->convertToDouble($lfStep);
                $ldStorageStepRatio = $lfStorageStep / $lfStep;
            }

            $uNone = $this->_unit->uNone();
            $timestep = $this->_unit->unitConvert(Post::TYPE_UNIT_TIME, $calcParameters->getTimeStep());
            $precision = $this->_unit->convertCalculator($calcParameters->getPrecisionRequest(), $uNone["coeffA"], $uNone["coeffB"]);
            
            $lfStep = $calcParameters->getStorageStep() * $calcParameters->getTimeStep();
            $storagestep = $this->_unit->unitConvert(Post::TYPE_UNIT_TIME, $lfStep);

            // 
            $maxIter = $this->_unit->convertCalculator($calcParameters->getMaxItNb(), $uNone["coeffA"], $uNone["coeffB"]);
            $relaxCoef = $this->_unit->convertCalculator($calcParameters->getRelaxCoeff(), $uNone["coeffA"], $uNone["coeffB"]);
        
            // 
            $tempPtSurf = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $calcParameters->getStopTopSurf());
            $tempPtIn = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $calcParameters->getStopInt());
            $tempPtBot = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $calcParameters->getStopBottomSurf());
            $tempPtAvg = $this->_unit->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $calcParameters->getStopAvg());
        }
        
        $sTitle = "";
        if ($calculationMode == 3) {
            $sTitle = "POPUP_BRAIN_OPTIMUM";
        } else if($calculationMode == 2) {
            $sTitle = "POPUP_BRAIN_SELECTED";
        }
        
        $timeSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_TIME);
        $temperatureSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_TEMPERATURE);
        $meshesSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_MESH_CUT);
        $percentSymbol = "%";
        
        // 
        $epsilonTemp = $this->_brain->getOptimErrorT($calculationMode, $idStudyEquipments);
        $epsilonEnth = $this->_brain->getOptimErrorH($calculationMode, $idStudyEquipments);
        $nbOptimIter = $this->_brain->getNbOptim($calculationMode, $idStudyEquipments);

        // 
        $select1 = $this->_calculate->getOption($idStudy, "X", "TOP");
        $select2 = $this->_calculate->getOption($idStudy, "Y", "TOP");
        $select3 = $this->_calculate->getOption($idStudy, "Z", "TOP");
        $select4 = $this->_calculate->getOption($idStudy, "Z", "INT");
        $select5 = $this->_calculate->getOption($idStudy, "Z", "INT");
        $select6 = $this->_calculate->getOption($idStudy, "Z", "INT");
        $select7 = $this->_calculate->getOption($idStudy, "Z", "BOT");
        $select8 = $this->_calculate->getOption($idStudy, "Z", "BOT");
        $select9 = $this->_calculate->getOption($idStudy, "Z", "BOT");
        
        $data = [
            "timeSymbol" => $timeSymbol,
            "temperatureSymbol" => $temperatureSymbol,
            "percentSymbol" => $percentSymbol,
            "meshesSymbol" => $meshesSymbol,
            "objStudy" => $objStudy,
            "sdisableTS" => $sdisableTS,
            "sdisableTR" => $sdisableTR,
            "sdisableTOC" => $sdisableTOC,
            "sdisableOptim" => $sdisableOptim,
            "sdisableNbOptim" => $sdisableNbOptim,
            "sdisableStorage" => $sdisableStorage,
            "sclassTS" => $sclassTS,
            "sclassTR" => $sclassTR,
            "sclassTOC" => $sclassTOC,
            "sclassNbOptim" => $sclassNbOptim,
            "sclassStorage" => $sclassStorage,
            "scheckOptim" => $scheckOptim,
            "scheckStorage" => $scheckStorage,
            "sdisableFields" => $sdisableFields,
            "isDHPMax" => $isDHPMax,
            "calcParameters" => $calcParameters,
            "lTS" => $lTS,
            "lTR" => $lTR,
            "lfToc" => $lfToc,
            "arrLts" => $arrLts,
            "arrLtr" => $arrLtr,
            "unitlfToc" => $unitlfToc,
            "nbMaxEqpPrm" => $nbMaxEqpPrm,
            "epsilonTemp" => $epsilonTemp,
            "epsilonEnth" => $epsilonEnth,
            "nbOptimIter" => $nbOptimIter,
            "timestep" => $timestep,
            "precision" => $precision,
            "storagestep" => $storagestep,
            "maxIter" => $maxIter,
            "relaxCoef" => $relaxCoef,
            "tempPtSurf" => $tempPtSurf,
            "tempPtIn" => $tempPtIn,
            "tempPtBot" => $tempPtBot,
            "tempPtAvg" => $tempPtAvg,
            "select1" => $select1,
            "select2" => $select2,
            "select3" => $select3,
            "select4" => $select4,
            "select5" => $select5,
            "select6" => $select6,
            "select7" => $select7,
            "select8" => $select8,
            "select9" => $select9,
        ];
        
        $urlRender = "output/analytical/optimum/refine-popup.html.twig";
        return $this->render($urlRender, $data);
    }

    /**
    * @Route("/equip-sizing", name="equip-sizing")
    */
    public function equipSizingAction(Request $request)
    {
        $session = $request->getSession();
        $doc = $this->getDoctrine();
        // check user login
        $user = $this->getUser();
        if($user == NULL){
            return $this->redirectToRoute("login");
        }
        // check idStudy already exists
        $idStudy = $session->get("idStudy");
        if($idStudy == null || $idStudy == 0 || $idStudy == ""){
            return $this->redirectToRoute("load-study");
        }

        $objStudy = $doc->getRepository(Studies::class)->find($idStudy);
        
        $idStudyEquipments = $request->get("idstudyequipments");

        $calcParameters = "";
        $data = array();
        
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);

        $calculationMode = $objStudy->getCalculationMode();
        
        $sEquipName = $this->_equip->getSpecificEquipName($idStudyEquipments);
        $initWidth = $this->_unit->convertToDouble($this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $studyEquipment->getStdeqpWidth()));
        $initLength = $this->_unit->convertToDouble($this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $studyEquipment->getStdEqpLength()));

        $minWidth 	= 0;
        $maxWidth 	= -1;
        $minLength 	= 0;
        $maxLength 	= -1;

        $mmWidth = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(["limitItem" => POST::MIN_MAX_EQUIPMENT_WIDTH]);
        $minWidth = $this->_unit->convertToDouble($this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $mmWidth->getLimitMin()));
        $maxWidth = $this->_unit->convertToDouble($this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $mmWidth->getLimitMax()));

        $mmLength = $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(["limitItem" => POST::MIN_MAX_EQUIPMENT_LENGTH]);
        $minLength = $this->_unit->convertToDouble($this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $mmLength->getLimitMin()));
        $maxLength = $this->_unit->convertToDouble($this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $mmLength->getLimitMax()));

        $minSurf = $minWidth * $minLength;
        $maxSurf = $maxWidth * $maxLength;

        $sDisabled = "";
        if (!($this->_study->isMyStudy($idStudy)) && $user->getUserprio() < POST::PROFIL_EXPERT) {
            $sDisabled = "disabled";
        }

        $equipDimensionSymbol = $this->_unit->equipDimensionSymbol();

        $data = [
            "objStudy" => $objStudy,
            "idStudyEquipments" => $idStudyEquipments,
            "studyEquipment" => $studyEquipment,
            "sEquipName" => $sEquipName,
            "calculationMode" => $calculationMode,
            "equipDimensionSymbol" => $equipDimensionSymbol,
            "initWidth" => $initWidth,
            "initLength" => $initLength,
            "mmWidth" => $mmWidth,
            "minWidth" => $minWidth,
            "maxWidth" => $maxWidth,
            "mmLength" => $mmLength,
            "minLength" => $minLength,
            "maxLength" => $maxLength,
            "sDisabled" => $sDisabled,
            "minSurf" => $minSurf,
            "maxSurf" => $maxSurf
        ];

        $urlRender = "output/analytical/optimum/equip-sizing.html.twig";
        return $this->render($urlRender, $data);
    }

    /**
    * @Route("/save-new-size-and-recalculate", name="saveNewSizeAndRecalculate")
    */
    public function saveNewSizeAndRecalculateAction(Request $request)
    {
        $session = $request->getSession();
        $doc = $this->getDoctrine();
        // check user login
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->redirectToRoute("login");
        }
        // check idStudy already exists
        $idStudy = $session->get("idStudy");
        
        $idStudyEquipments = $request->get("idStudyEquipments");
        $calculatemode = $request->get("calculatemode");
        $sequipWidth = $request->get("width");
        $sequipLength = $request->get("length");

        if ($sequipWidth != null) {
            $sequipWidth = $this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $sequipWidth);
        }

        if ($sequipLength != null) {
            $sequipLength = $this->_unit->unitConvert(Post::TYPE_UNIT_EQUIP_DIMENSION, $sequipLength);
        }

        $data = array();
        
        $em = $this->getDoctrine()->getManager();   
       
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);
        if (!empty($studyEquipment)) {
            $studyEquipment->setStdeqpWidth($sequipWidth);
            $studyEquipment->setStdeqpLength($sequipLength);
            $em->flush();

            if ($calculatemode == 1) {
                $objStudy= $em->getRepository(Studies::class)->find($idStudy);
                if ($objStudy->getChainingControls() == 1 && $objStudy->getHasChild() == 1) {
                    $itChilds = $this->getDoctrine()->getRepository(Studies::class)->findBy(["parentId" => $objStudy->getIdStudy()],["idStudy"=>"ASC"]);
                    if (!empty($itChilds)) {
                        foreach ($itChilds as $row) {
                            if ($idStudyEquipments == -1 || $idStudyEquipments = $row->getParentStudEqpId()) {
                                $em = $this->getDoctrine()->getManager();
                                $childStudy= $em->getRepository(Studies::class)->find($row->getIdStudy());
                                $childStudy->setToRecalculate(1);
                                $em->flush();
                            }
                        }
                    }
                }
            } else {
                $lg = $em->getRepository(LayoutGeneration::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);
                if (!empty($lg)) {
                    $lg->setLengthInterval(-2.0)->setWidthInterval(-2.0);
                    $em->flush();
                }
                
                $lr = $em->getRepository(LayoutResults::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);
                if (!empty($lr)) {
                    $lr->setLeftRightInterval(0.0)->setNumberInWidth(0)->setNumberPerM(0.0);
                    $em->flush();
                } 
            }      
            
            return die(json_encode(true));
        } else {
            return die(json_encode(false));
        }   
                  
    }

    /**
    * @Route("/popup-tr", name="popup-tr")
    */
    public function popupTrAction(Request $request)
    {
        $session = $request->getSession();
        $doc = $this->getDoctrine();
        $user = $this->getUser();
        $idStudy = $session->get("idStudy");

        $objStudy = $doc->getRepository(Studies::class)->find($idStudy);
        
        $idStudyEquipments = $request->get("idstudyequipments");

        $calcParameters = "";
        $data = array();
        
        $sdisableFields = $this->_study->disableFields($idStudy);
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);

        $equipment = $studyEquipment->getIdEquip();


        $sEquipName = $this->_equip->getResultsEquipName($idStudyEquipments);
        
        $lTR = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder("s")
                    ->where("s.valueType >= :valueType1 AND s.valueType < :valueType2")
                    ->andWhere("s.idStudyEquipments = :idStudyEquipments")
                    ->setParameter("valueType1", "300")
                    ->setParameter("valueType2", "400")
                    ->setParameter("idStudyEquipments", $idStudyEquipments)
                    ->getQuery()->getResult();

        $lTS = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder("s")
                    ->where("s.valueType >= :valueType1 AND s.valueType < :valueType2")
                    ->andWhere("s.idStudyEquipments = :idStudyEquipments")
                    ->setParameter("valueType1", "200")
                    ->setParameter("valueType2", "300")
                    ->setParameter("idStudyEquipments", $idStudyEquipments)
                    ->getQuery()->getResult();

        $lVC = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder("s")
                    ->where("s.valueType >= :valueType1 AND s.valueType < :valueType2")
                    ->andWhere("s.idStudyEquipments = :idStudyEquipments")
                    ->setParameter("valueType1", "100")
                    ->setParameter("valueType2", "200")
                    ->setParameter("idStudyEquipments", $idStudyEquipments)
                    ->getQuery()->getResult();

        $lTE = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder("s")
                    ->where("s.valueType >= :valueType1 AND s.valueType < :valueType2")
                    ->andWhere("s.idStudyEquipments = :idStudyEquipments")
                    ->setParameter("valueType1", "500")
                    ->setParameter("valueType2", "500")
                    ->setParameter("idStudyEquipments", $idStudyEquipments)
                    ->getQuery()->getResult();
        if(count($lTE) > 0) $lTE = $lTE[0]; 

        // dump($lTS);die;

        $ldSetpointmax = ( count($lTS) > count($lTR) ) ? ( count($lTS) > count($lVC) ) ? count($lTS) : count($lVC) : (count($lTR) > count($lVC)) ? count($lTR) : count($lVC);

        $mmTr = $this->_minmax->getDefaultValue($equipment->getItemTR());
        $mmTs = $this->_minmax->getDefaultValue($equipment->getItemTS());

        $trMin = $this->_unit->convertToDouble($this->_unit->controlTemperature($mmTr->getLimitMin()));
        $trMax = $this->_unit->convertToDouble($this->_unit->controlTemperature($mmTr->getLimitMax()));
        $tsMin = $this->_unit->convertToDouble($this->_unit->controlTemperature($mmTs->getLimitMin()));
        $tsMax = $this->_unit->convertToDouble($this->_unit->controlTemperature($mmTs->getLimitMax()));

        $timeSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_TIME);
        $temperatureSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_TEMPERATURE);
        $convectionSpeedSymbol = $this->_unit->convectionSpeedSymbol();

        $data = [
            "objStudy" => $objStudy,
            "idStudyEquipments" => $idStudyEquipments,
            "studyEquipment" => $studyEquipment,
            "sEquipName" => $sEquipName,
            "sdisableFields" => $sdisableFields,
            "lTR" => $lTR,
            "lTS" => $lTS,
            "lVC" => $lVC,
            "lTE" => $lTE,
            "ldSetpointmax" => $ldSetpointmax,
            "mmTr" => $mmTr,
            "mmTs" => $mmTs,
            "trMin" => $trMin,
            "trMax" => $trMax,
            "tsMin" => $tsMin,
            "tsMax" => $tsMax,
            "timeSymbol" => $timeSymbol,
            "temperatureSymbol" => $temperatureSymbol,
            "convectionSpeedSymbol" => $convectionSpeedSymbol
        ];

        $urlRender = "output/analytical/estimation/popup-tr.html.twig";
        return $this->render($urlRender, $data);
    }

    /**
    * @Route("/view-equip-tr", name="view-equip-tr")
    */
    public function viewEquipTrAction(Request $request)
    {
        $session = $request->getSession();
        $doc = $this->getDoctrine();
        $user = $this->getUser();
        $idStudy = $session->get("idStudy");

        $objStudy = $doc->getRepository(Studies::class)->find($idStudy);
        
        $idStudyEquipments = $request->get("idstudyequipments");

        // 
        // $idStudyEquipments = 3;

        $calcParameters = "";
        $data = array();
        
        $sdisableFields = $this->_study->disableFields($idStudy);
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);

        $equipment = $studyEquipment->getIdEquip();

        $sEquipName = $this->_equip->getResultsEquipName($idStudyEquipments);

        $timeSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_TIME);
        $temperatureSymbol = $this->_unit->symbolUnit(Post::TYPE_UNIT_TEMPERATURE);
        $convectionSpeedSymbol = $this->_unit->convectionSpeedSymbol(Post::TYPE_UNIT_TEMPERATURE);

        $rDimaResults = $this->getDoctrine()->getRepository(DimaResults::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);

        $data = [
            "objStudy" => $objStudy,
            "idStudyEquipments" => $idStudyEquipments,
            "studyEquipment" => $studyEquipment,
            "sEquipName" => $sEquipName,
            "rDimaResults" => $rDimaResults,
            "timeSymbol" => $timeSymbol,
            "temperatureSymbol" => $temperatureSymbol,
            "convectionSpeedSymbol" => $convectionSpeedSymbol
        ];

        $urlRender = "output/analytical/estimation/view-equip-tr.html.twig";
        return $this->render($urlRender, $data);
    }

    /**
    * @Route("/consumption-pie", name="consumption-pie")
    */
    public function consumptionPieAction(Request $request){
        $session = $request->getSession();
        $doc = $this->getDoctrine();
        $user = $this->getUser();
        $idStudy = $session->get("idStudy");

        $objStudy = $doc->getRepository(Studies::class)->find($idStudy);
        
        $idStudyEquipments = $request->get("idstudyequipments");

        $data = array();
        
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);

        $equipment = $studyEquipment->getIdEquip();

        $sEquipName = $this->_equip->getResultsEquipName($idStudyEquipments);

        $data = [
            "objStudy" => $objStudy,
            "idStudyEquipments" => $idStudyEquipments,
            "studyEquipment" => $studyEquipment,
            "sEquipName" => $sEquipName
        ];

        $urlRender = "output/analytical/common/consumption-pie.html.twig";
        return $this->render($urlRender, $data);
    }

    /**
    * @Route("/close-pie", name="close-pie")
    */
    public function closePieAction(Request $request){
    
        $idStudyEquipments = $request->get("idstudyequipments");
        $reportPie = $request->get("report_pie");

        $em = $this->getDoctrine()->getManager();
        
        $studyEquipment = $em->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);

        $studyEquipment->setEnableConsPie($reportPie);
        $em->flush();
        return die(json_encode(true));
    }

    /**
    * @Route("/toc-popup", name="toc-popup")
    */
    public function tocPopupAction(Request $request){
        $session = $request->getSession();
        $doc = $this->getDoctrine();
        // check user login
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->redirectToRoute("login");
        }
        // check idStudy already exists
        $idStudy = $session->get("idStudy");
        if($idStudy == null || $idStudy == 0 || $idStudy == ""){
            return $this->redirectToRoute("load-study");
        }

        $em = $this->getDoctrine()->getManager();

        $data = array();
        $idStudyEquipments = $request->get("idstudyequipments");
        $studyEquipment = $em->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);

        if(!empty($studyEquipment)){

            $equipment = $studyEquipment->getIdEquip();

            $pl = array([
                "key" => 1,
                "value" => "Parallel"
            ]);

            $pl = array([
                "key" => 0,
                "value" => "Perpendicular"
            ]);

            $sDisabled = "";
            if (!($this->_study->isMyStudy($idStudy)) && $user->getUserprio() < POST::PROFIL_EXPERT) {
                $sDisabled = "disabled";
            }

            $objStudy = $doc->getRepository(Studies::class)->find($idStudy);

            $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['idStudy'=>$objStudy->getIdStudy()]);

            $prodElmt = $this->getDoctrine()->getRepository(ProductElmt::class)->findOneBy(['idProd'=>$product->getIdProd()]);

            $productShape = $prodElmt->getIdShape()->getIdShape();
            $lengthDisabled = "";
            if (($productShape == POST::SLAB ) || ($sDisabled != "")) {
                $lengthDisabled = "disabled";
            }

            $equipWithSpecificSize = (($studyEquipment->getStdeqpWidth() != POST::NO_SPECIFIC_SIZE) && ($studyEquipment->getStdEqpLength() != POST::NO_SPECIFIC_SIZE)) ? true : false;

            $shelvesSelectDisabled = "";
            if ($equipWithSpecificSize || $sDisabled != "") {
                $shelvesSelectDisabled = "disabled";
            }

            $minNbShelves     = 0;
            $maxNbShelves     = -1;
            $minShelvesL  = 0;
            $maxShelvesL  = -1;
            $minShelvesW  = 0;
            $maxShelvesW  = -1;

            $batch = $equipment->getIdEquipseries()->getIdFamily()->isBatchProcess();
            if ($batch) {
                $mmShelvesL = $this->_minmax->getDefaultValue(1096);
                if ($mmShelvesL != null) {
                    $minShelvesL = $this->_unit->convertToDouble($this->_unit->shelvesWidth($mmShelvesL->getLimitMin()));
                    $maxShelvesL = $this->_unit->convertToDouble($this->_unit->shelvesWidth($mmShelvesL->getLimitMax()));
                }

                $mmShelvesW = $this->_minmax->getDefaultValue(1097);
                if ($mmShelvesW != null) {
                    $minShelvesW = $this->_unit->convertToDouble($this->_unit->shelvesWidth($mmShelvesW->getLimitMin()));
                    $maxShelvesW = $this->_unit->convertToDouble($this->_unit->shelvesWidth($mmShelvesW->getLimitMax()));
                }

                $mmNbShelves = $this->_minmax->getDefaultValue(1090);
                if ($mmNbShelves != null) {
                    $minNbShelves = $this->_unit->convertToDouble($this->_unit->shelvesWidth($mmShelvesW->getLimitMin()));
                    $minNbShelves = $this->_unit->convertToDouble($this->_unit->shelvesWidth($mmShelvesW->getLimitMax()));
                }
            }

            $sEquipName = $this->_equip->getSpecificEquipName($idStudyEquipments);
            $layoutGeneration = "";

            if($studyEquipments->getIdLayoutGeneration() != null){
                $layoutGeneration = $this->getDoctrine()->getRepository(LayoutGeneration::class)->find($studyEquipments->getIdLayoutGeneration());

                $prodPosition = $layoutGeneration->isProdPosition();
                $interval = $layoutGeneration->getWidthInterval();
                if ($interval == -1.0) {

                    
                }
            }   

            $data = [
                "studyEquipment" => $studyEquipment,
                "equipment" => $equipment,
                "sEquipName" => $sEquipName,
                "objStudy" => $objStudy,
                "prodElmt" => $prodElmt,
                "productShape" => $productShape,
                "lengthDisabled" => $lengthDisabled,
                "sDisabled" => $sDisabled,
                "equipWithSpecificSize" => $equipWithSpecificSize,
                "shelvesSelectDisabled" => $shelvesSelectDisabled,
                "minNbShelves" => $minNbShelves,
                "maxNbShelves" => $maxNbShelves,
                "minShelvesL" => $minShelvesL,
                "maxShelvesL" => $maxShelvesL,
                "minNbShelves" => $minNbShelves,
                "minNbShelves" => $minNbShelves,
            ];

            $urlRender = "output/analytical/optimum/toc-popup.html.twig";
            return $this->render($urlRender, $data);
        }

        
    }

    
    private function formatString($sString)
    {
        $idx = 0;
        while (($idx = $sString.strpos('\'', $idx)) != -1) {
            $sString = substr($sString, 0, $idx) . "\\" . substr($sString, $idx);
            $idx += 2;
        }
        return $sString;
    }  

}
