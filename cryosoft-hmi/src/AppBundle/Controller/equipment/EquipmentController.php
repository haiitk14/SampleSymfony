<?php


namespace AppBundle\Controller\equipment;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\CoolingFamily;
use AppBundle\Entity\Post;
use AppBundle\Entity\Notice;
use AppBundle\Entity\Translation;
use AppBundle\Entity\Equipseries;
use AppBundle\Entity\Equipment;
use AppBundle\Entity\Equipfamily;
use AppBundle\Entity\Studies;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\MinMax;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\EquipGeneration;
use AppBundle\Entity\CalculationParametersDef;
use AppBundle\Entity\CalculationParameters;
use AppBundle\Entity\PipeGen;
use AppBundle\Entity\LineDefinition;
use AppBundle\Entity\LineElmt;
use AppBundle\Entity\StudEqpPrm;
use AppBundle\Entity\LayoutGeneration;
use AppBundle\Entity\LayoutResults;
use AppBundle\Entity\Prices;
use AppBundle\Entity\PrecalcLdgRatePrm;
use AppBundle\Entity\TempExt;
use AppBundle\Entity\Ln2user;
use AppBundle\Cryosoft\EquipmentsService;



class EquipmentController extends Controller
{

    private $equiService;

    public function __construct(EquipmentsService $equiService)
    {
        $this->equiService = $equiService;
    }

    /**
     * @Route("/equipments", name="equipments")
     */
    public function equipmentsAction(Request $request)
    {
        $user = $this->getUser();

        if($user == null){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);

        $objProd = $this->getDoctrine()->getRepository(Product::class)->findBy(["idStudy"=>$objStudy->getIdStudy()]);
        
        if(count($objProd) < 1){

            return $this->redirectToRoute('Product-Characteristic');
        }
        $objProdEtml=$this->getDoctrine()->getRepository(ProductElmt::class)->findBy(["idProd"=>$objProd[0]->getIdProd()]);

        if(count($objProdEtml) < 1){

            return $this->redirectToRoute('Product-Characteristic');
        }
        $session->set('idShape', $objProdEtml[0]->getIdShape()->getIdShape());
        $ret = array();
        $this->getListInEquipment($request, $ret);

        return $this->render('equipment/equipment.html.twig',[
            'ret' => $ret,
            'studyName' => $objStudy
        ]);
    }

    function getListInEquipment(Request $request, &$rs)
    {
        $user = $this->getUser();

        if($user == null){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objProd = $this->getDoctrine()->getRepository(Product::class)->findBy(["idStudy"=>$objStudy->getIdStudy()]);
        
        if(count($objProd) < 1){

            return $this->redirectToRoute('Product-Characteristic');
        }
        $objProdEtml = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(["idProd"=>$objProd[0]->getIdProd()]);
        
        if(count($objProdEtml) < 1){

            return $this->redirectToRoute('Product-Characteristic');
        }
        $idRefri = $user->getUserEnergy();
        $idManu = $user->getUserConstructor();
        $idSeries = $user->getUserFamily();
        $idOrigin = $user->getUserOrigine();
        $idProcess = $user->getUserProcess();
        $idModel = $user->getUserModel();
        $stringRefri = "";$stringRefriEmpty = "";
        $stringManu = "";
        $stringSeries = "";
        $stringOrigin = "";
        $tringProcess = "";
        $stringModel = "";

        if($idRefri != -1 || $idRefri != "-1"){
            $stringRefri = " AND e.idCoolingFamily=".$idRefri;
            $stringRefriEmpty = "e.idCoolingFamily=".$idRefri;
        }

        if($idManu != ''){
            $stringManu = " AND es.constructor='".$idManu."'";
        }

        if($idSeries != -1 || $idSeries != "-1"){
            $stringSeries = " AND ef.idFamily=".$idSeries;
        }

        if($idOrigin != -1 || $idOrigin != "-1"){
            $stringOrigin = " AND e.std=".$idOrigin;
        }

        if($idProcess != -1 || $idProcess != "-1"){
            $tringProcess = " AND ef.batchProcess=".$idProcess;
        }

        if($idModel != -1 || $idModel != "-1"){
            $stringModel = " AND es.idEquipseries=".$idModel;
        }
        // get refrigeration
        $refrigeration = $this->getDoctrine()->getRepository(CoolingFamily::class)->createQueryBuilder('c')
                            ->select('c.idCoolingFamily , t.label')
                            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idCoolingFamily')
                            ->where('t.transType = :transType')
                            ->andWhere('t.codeLangue = :codeLangue')
                            ->setParameter('transType', Post::TRANSTYPE_COOLING_FAMILY)
                            ->setParameter('codeLangue',$user->getCodeLangue())
                            ->distinct(true)
                            ->orderBy("t.label", 'ASC')
                            ->getQuery()->getResult();
        $arrRefrigeration = [];

        if(count($refrigeration) > 0){

            foreach ($refrigeration as $key) {
                $ret = [
                        'idCoolingFamily' => $key['idCoolingFamily'],
                        'label'  => $key['label']
                ];
                array_push($arrRefrigeration,$ret);
            }
        }
        // get Manufacturer

        if($idRefri != -1 || $idRefri != "-1"){
            $manufacturer = $this->getDoctrine()->getRepository(Equipseries::class)->createQueryBuilder('es')
                            ->select('es.constructor')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->where($stringRefriEmpty)
                            ->distinct(true)
                            ->orderBy("es.constructor", 'ASC')
                            ->getQuery()->getResult();
        }else{
            $manufacturer = $this->getDoctrine()->getRepository(Equipseries::class)->createQueryBuilder('es')
                            ->select('es.constructor')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->distinct(true)
                            ->orderBy("es.constructor", 'ASC')
                            ->getQuery()->getResult();
        }
        $arrManufacturer=[];

        if(count($manufacturer) > 0){

            foreach ($manufacturer as $key) {
                $ret =[
                        'constructor' => $key['constructor']
                ];
                array_push($arrManufacturer,$ret);
            }
        }
        // get Equipment Series
        $equipSeries = $this->getDoctrine()->getRepository(Equipfamily::class)->createQueryBuilder('ef')
                            ->select('ef.idFamily , t.label')
                            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = ef.idFamily')
                            ->leftJoin(Equipseries::class, 'es', 'WITH', 'es.idFamily = ef.idFamily')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->where('t.transType = :transType')
                            ->andWhere('t.codeLangue = :codeLangue'.$stringRefri.$stringManu)
                            ->setParameter('transType', Post::TRANSTYPE_EQUIPMENT_FAMILY)
                            ->setParameter('codeLangue',$user->getCodeLangue())
                            ->distinct(true)
                            ->orderBy("t.label", 'ASC')
                            ->getQuery()->getResult();
        $arrEquipSeries=[];

        if(count($equipSeries) > 0){

            foreach ($equipSeries as $key) {
                $ret =[
                        'idFamily' => $key['idFamily'],
                        'label'  => $key['label']
                ];
                array_push($arrEquipSeries,$ret);
            }
        }
        // get Equipment Origin
        $equipOrigin = $this->getDoctrine()->getRepository(Equipment::class)->createQueryBuilder('e')
                            ->select('t.idTranslation , t.label')
                            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = e.std')
                            ->leftJoin(Equipseries::class, 'es', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->leftJoin(Equipfamily::class, 'ef', 'WITH', 'ef.idFamily = es.idEquipseries')
                            ->where('t.codeLangue = :codeLangue')
                            ->andWhere('t.transType = :transType'.$stringRefri.$stringManu.$stringSeries)
                            ->setParameter('codeLangue',$user->getCodeLangue() )
                            ->setParameter('transType', Post::TRANSTYPE_EQUIPMENT_ORIGIN)
                            ->distinct(true)
                            ->orderBy("t.label", 'ASC')
                            ->getQuery()->getResult();
        $arrEquipOrigin=[];

        if(count($equipOrigin) > 0){

            foreach ($equipOrigin as $key) {
                $ret =[
                        'std' => $key['idTranslation'],
                        'label'  => $key['label']
                ];
                array_push($arrEquipOrigin,$ret);
            }
        }
        // get Process Type
        $processType = $this->getDoctrine()->getRepository(Equipfamily::class)->createQueryBuilder('ef')
                            ->select('t.idTranslation , t.label')
                            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = ef.batchProcess')
                            ->leftJoin(Equipseries::class, 'es', 'WITH', 'es.idFamily = ef.idFamily')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->where('t.transType = :transType')
                            ->andWhere('t.codeLangue = :codeLangue'.$stringRefri.$stringManu.$stringSeries.$stringOrigin)
                            ->setParameter('transType', Post::TRANSTYPE_PROCESS_TYPE)
                            ->setParameter('codeLangue',$user->getCodeLangue())
                            ->distinct(true)
                            ->orderBy("t.label", 'ASC')
                            ->getQuery()->getResult();
        $arrProcessType=[];

        if(count($processType) > 0){

            foreach ($processType as $key) {
                $ret =[
                        'batchProcess' => $key['idTranslation'],
                        'label'  => $key['label']
                ];
                array_push($arrProcessType,$ret);
            }
        }
        // get Model
        $model = $this->getDoctrine()->getRepository(Equipseries::class)->createQueryBuilder('es')
                            ->select('es.idEquipseries, t.label')
                            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = es.idEquipseries')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->leftJoin(Equipfamily::class, 'ef', 'WITH', 'ef.idFamily = es.idFamily')
                            ->where('t.transType = :transType')
                            ->andWhere('t.codeLangue = :codeLangue'.$stringRefri.$stringManu.$stringSeries.$stringOrigin.$tringProcess)
                            ->setParameter('transType', Post::TRANSTYPE_MODEL)
                            ->setParameter('codeLangue',$user->getCodeLangue())
                            ->distinct(true)
                            ->orderBy("t.label", 'ASC')
                            ->getQuery()->getResult();
        $arrModel=[];

        if(count($model) > 0){

            foreach ($model as $key) {
                $ret =[
                        'idEquipseries' => $key['idEquipseries'],
                        'label' => $key['label']
                ];
                array_push($arrModel,$ret);
            } 
        }
        // get Size
        $size = $this->getDoctrine()->getRepository(Equipment::class)->createQueryBuilder('e')
                            ->select('e.eqpLength, e.eqpWidth')
                            ->leftJoin(Equipseries::class, 'es', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->leftJoin(Equipfamily::class, 'ef', 'WITH', 'ef.idFamily = es.idEquipseries')
                            ->where('(e.eqpImpIdStudy = :idStudy OR e.eqpImpIdStudy=0 )'.$stringRefri.$stringManu.$stringSeries.$stringOrigin.$tringProcess.$stringModel)
                            ->andWhere('(e.idUser=:idUser AND e.equipRelease = 2) OR (e.equipRelease=4 OR e.equipRelease=3)')
                            ->setParameter('idStudy', $idStudy)
                            ->setParameter('idUser', $user->getIdUser())
                            ->distinct(true)
                            ->orderBy("e.eqpLength", 'ASC')
                            ->getQuery()->getResult();
        $arrSize=[];

        if(count($size) > 0){

            foreach ($size as $key) {
                $ret =[
                        'eqpLength' => $key['eqpLength'] == null ? '' : $key['eqpLength'],
                        'eqpWidth' => $key['eqpWidth'] == null ? '' : $key['eqpWidth']
                ];
                array_push($arrSize,$ret);
            } 
        }
        // get search equipment
        $equipment=$this->getDoctrine()->getRepository(Equipment::class)->createQueryBuilder('e')
        ->select("e.idEquip, IDENTITY(e.idUser) AS idUser, e.openByOwner, e.equipRelease , e.equipName, e.equipVersion, e.equipRelease")
        ->leftJoin(Equipseries::class, 'es', 'WITH', 'e.idEquipseries = es.idEquipseries')
        ->leftJoin(Equipfamily::class, 'ef', 'WITH', 'ef.idFamily = es.idEquipseries')               
        ->where('(e.eqpImpIdStudy = :idStudy OR e.eqpImpIdStudy=0 )'.$stringRefri.$stringManu.$stringSeries.$stringOrigin.$tringProcess.$stringModel)
        ->andWhere('(e.idUser=:idUser AND e.equipRelease = 2) OR (e.equipRelease=4 OR e.equipRelease=3)')
        ->setParameter('idStudy', $idStudy)
        ->setParameter('idUser', $user->getIdUser())
        ->orderBy("e.equipName", 'ASC')
        ->getQuery()->getResult();
        $session->set('loadEquipment',true);
        $arrEquipment=[];
        $nameAppend = "";

        if($user->getUsername() != "KERNEL"){
            $nameAppend = " - ".$user->getUsername();
        }

        if(count($equipment) > 0){

            foreach ($equipment as $key) {
                $objTransRelease=$this->getDoctrine()->getRepository(Translation::class)->findBy(['transType'=>Post::TRANSTYPE_SUB_NAME_EQUIPMENT, 'idTranslation'=>$key['equipRelease'],'codeLangue'=>$user->getCodeLangue()]);
                $checkModalESize=null;

                if($key['idUser'] == $user->getIdUser()){
                    $name=$key['equipName'].' - '.$key['equipVersion'] .' / '. $objTransRelease[0]->getLabel().$nameAppend;
                }else{
                    $name=$key['equipName'].' - '.$key['equipVersion'] .' / '. $objTransRelease[0]->getLabel();
                }            
                $objETemp=$this->getDoctrine()->getRepository(Equipment::class)->find($key['idEquip']);
                
                if(($objETemp->getCapabilities() & 16384) != 0){
                    $checkModalESize=1;
                }
                $classOption = "";

                if($key['openByOwner'] && $key['idUser'] != $user->getIdUser()){
                    $classOption = "eqpLocked";

                }else if($user->getUsername() != "KERNEL"){

                    if($key["idUser"] == $user->getIdUser()){
                        $classOption = "mineElement";
                    }else{
                        $classOption = "userElement";
                    }
                }

                if($key['equipRelease'] == Post::OBSOLETE){
                    $classOption = "obsoleteElement";

                }else if($key['equipRelease'] == Post::DISABLED){
                    $classOption = "disabledElement";
                }

                $ret =[
                        'idEquip' => $key['idEquip'],
                        'equipName' => $name,
                        'checkModalESize'=>$checkModalESize,
                        'classOption' => $classOption
                ];
                array_push($arrEquipment, $ret);
            }
        }
        // contion display 
        // 1. Area for energy price and loading rate
        $conDisplay = [
            "optionEco" => $objStudy->getOptionEco(),
            "loadingRate" => $objProdEtml[0]->getIdShape()->getIdShape(),
            "unselect"=>$objStudy->getCalculationMode()
        ];
        // ::::::: loadEquipmentsInStudy
        //getEquipmentDisplayString(..) in Libelle.java ::::::::::: set Name Study Equipment
        $listObjSEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy()],['idStudyEquipments'=>'ASC']);

        if(count($listObjSEquipment) > 0){
            $nameAppend = "";

            foreach ($listObjSEquipment as $key) {
                $name = $key->getIdEquip()->getEquipName();

                if($key->getIdEquip()->isStd() && ($key->getIdEquip()->getCapabilities() & 32768) == 0 && ($key->getIdEquip()->getCapabilities() & 1048567) == 0 ){

                    $n1 = $key->getIdEquip()->getEqpLength()  +  ($key->getNbModul()  *  $key->getIdEquip()->getModulLength());
                    $n2 = $key->getIdEquip()->getEqpWidth();
                    $n3 = $key->getIdEquip()->getEquipVersion();
                    $objTrans=$this->getDoctrine()->getRepository(Translation::class)->findBy(['transType'=>Post::TRANSTYPE_SUB_NAME_EQUIPMENT, 'idTranslation'=>$key->getIdEquip()->getEquipRelease(),'codeLangue'=>$user->getCodeLangue()]);
                    $n4 = $objTrans[0]->getLabel();
                    $name = $name. ' - '. $n1.' x '.$n2.' (v'.$n3.')/'.$n4;
                    $key->setStudyEquipName($name);

                }else{

                    if(($key->getIdEquip()->getCapabilities() & 1048576) == 0 && $key->getIdEquip()->getEqpLength() != -1 &&  $key->getIdEquip()->getEqpWidth() != -1){

                        $n1=$key->getIdEquip()->getEqpLength();
                        $n2=$key->getIdEquip()->getEqpWidth();
                        $objTrans=$this->getDoctrine()->getRepository(Translation::class)->findBy(['transType'=>Post::TRANSTYPE_SUB_NAME_EQUIPMENT, 'idTranslation'=>$key->getIdEquip()->getEquipRelease(),'codeLangue'=>$user->getCodeLangue()]);
                        $n3=$objTrans[0]->getLabel();
                        $name=$name.' - ('.$n1.' x '.$n2.')/'.$n3;
                        $key->setStudyEquipName($name);

                    }else{

                        $objTrans=$this->getDoctrine()->getRepository(Translation::class)->findBy(['transType'=>Post::TRANSTYPE_SUB_NAME_EQUIPMENT, 'idTranslation'=>$key->getIdEquip()->getEquipRelease(),'codeLangue'=>$user->getCodeLangue()]);
                        $name=$name.' / '.$objTrans[0]->getLabel();
                        $key->setStudyEquipName($name);
                    }
                }
                // check display modal when click name Study Equipment in table 
                if(($key->getIdEquip()->getCapabilities() & 16384) != 0 ){
                     $key->setCheckModalName(1);
                }
                // set Orientation column StudyEquipment table
                if($key->getIdLayoutGeneration() != null){
                    $objLayoutGeneration=$this->getDoctrine()->getRepository(LayoutGeneration::class)->find($key->getIdLayoutGeneration());

                    if(count($objLayoutGeneration) > 0){

                        if($objLayoutGeneration->isProdPosition()){
                            $key->setOrientation("Parallel");

                        }else{

                            $key->setOrientation("Perpendicular");
                        }
                    }
                }
                // check display modal when click Orientation Study Equipment in table 
                if(($key->getIdEquip()->getCapabilities() & 32) == 0 ){
                     $key->setCheckModalOrientation(1);
                }
                // set TR TS VC to Study Equipment
                // trong lớp UnitConverter lần lượt là time(…), controlTemperature(…), convectionSpeed(…)
                // TR
                $queryTr = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder('s')
                    ->where('s.valueType >= :valueType1 AND s.valueType <= :valueType2')
                    ->andWhere('s.idStudyEquipments = :idStudyEquipments')
                    ->setParameter('valueType1', '300')
                    ->setParameter('valueType2', '399')
                    ->setParameter('idStudyEquipments', $key->getIdStudyEquipments())
                    ->getQuery()->getResult();
                $tr="";

                if(count($queryTr)>0){

                    for($i=0;$i<count($queryTr);$i++) {

                        if($i == count($queryTr) -1){
                            $tr.=$queryTr[$i]->getValue();

                        }else{
                            $tr.=$queryTr[$i]->getValue().'<br>';
                        }   
                    }
                }
                $key->setTR($tr==""?0:$tr);
                // TS
                $queryTs = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder('s')
                    ->where('s.valueType >= :valueType1 AND s.valueType <= :valueType2')
                    ->andWhere('s.idStudyEquipments = :idStudyEquipments')
                    ->setParameter('valueType1', '200')
                    ->setParameter('valueType2', '299')
                    ->setParameter('idStudyEquipments', $key->getIdStudyEquipments())
                    ->getQuery()->getResult();
                $ts="";

                if(count($queryTs)>0){

                    for($i=0;$i<count($queryTs);$i++) {

                        if($i == count($queryTs) -1){
                            $ts.=$queryTs[$i]->getValue();
                        }else{
                            $ts.=$queryTs[$i]->getValue().'<br>';
                        }   
                    }
                }
                $key->setTS($ts==""?0:$ts);
                // VC
                $queryVc = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder('s')
                    ->where('s.valueType >= :valueType1 AND s.valueType <= :valueType2')
                    ->andWhere('s.idStudyEquipments = :idStudyEquipments')
                    ->setParameter('valueType1', '100')
                    ->setParameter('valueType2', '199')
                    ->setParameter('idStudyEquipments', $key->getIdStudyEquipments())
                    ->getQuery()->getResult();
                $vc="";
                $VCType = $key->getIdEquip()->getItemVC();
                $minMaxVCType=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>$VCType]);

                if(count($queryVc)>0){

                    for($i=0;$i<count($queryVc);$i++) {

                        if($i == count($queryVc) -1){
                            $vc.=$queryVc[$i]->getValue();
                        }else{
                            $vc.=$queryVc[$i]->getValue().'<br>';
                        }   
                    }
                }
                $key->setVC($vc==""?$minMaxVCType[0]->getDefaultValue():$vc);
                // Cột Conveyor coverage or quantity of product per batch:
                $coverage="";
                $massSymbol="g";

                if($key->getIdLayoutResults() == null || ($key->getIdEquip()->getCapabilities() & 32) == 0){
                    $coverage="";
                }else{
                    $objLR=$this->getDoctrine()->getRepository(LayoutResults::class)->find($key->getIdLayoutResults());

                    if(count($objLR) > 0){

                        if($key->getIdEquip()->getIdEquipseries()->getIdFamily()->isBatchProcess()){
                            $coverage=$objLR->getQuantityPerBatchMax() .' '.$massSymbol.'/batch'; 
                        }else{                        
                            $coverage=$objLR->getLoadingRate() .' %'; 
                        }
                    }
                }

                if($key->getIdLayoutGeneration() !=null ){

                    if(count($objLayoutGeneration) > 0){

                        if($objLayoutGeneration->getWidthInterval() !=-1 || $objLayoutGeneration->getLengthInterval() !=-1){
                            $coverage .= '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
                        }
                    }
                }
                $key->setConveyor($coverage);
                // check display modal when click (Conveyor coverage or quantity of product per batch) in table 
                
                if(($key->getIdEquip()->getCapabilities() & 32) != 0){
                    $key->setCheckModalConveyor(true);
                }else{
                    $key->setCheckModalConveyor(false);
                }
            }
        }
        // load Energy Price
        $objPrices = $this->getDoctrine()->getRepository(Prices::class)->findBy(['idStudy' => $objStudy->getIdStudy()]);

        if(count($objPrices) > 0){
            $energyPrice = $objPrices[0]->getEnergy();
        }else{
            $energyPrice = 0;
        }
        // Loading Rate frame ( length, width)
        $objPrecalcLdgRatePrm=$this->getDoctrine()->getRepository(PrecalcLdgRatePrm::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);

        if(count($objPrecalcLdgRatePrm) > 0){
            $loadingRateFrame = [
                'length' => $objPrecalcLdgRatePrm[0] ->getLInterval(),
                'width' => $objPrecalcLdgRatePrm[0] ->getWInterval()
            ];
        }else{
            $loadingRateFrame = [
                'length' => 0,
                'width' => 0
            ];
        }
        $rs=[
            'valueDefault' => $user,
            'listRefrigeration'=>$arrRefrigeration,
            'listManufacturer' => $arrManufacturer,
            'listEquipSeries'=> $arrEquipSeries,
            'listEquipOrigin' => $arrEquipOrigin,
            'listProcessType' => $arrProcessType,
            'listModel' => $arrModel,
            'listSize' => $arrSize,
            'listEquipment' => $arrEquipment,
            'listDisplay' => $conDisplay,
            'listStudyEquip'=> $listObjSEquipment,
            'energyPrice' => $energyPrice,
            'loadingRateFrame' => $loadingRateFrame
        ];
    }

    /**
     * @Route("/searchEquipment", name="searchEquipment")
     */
    public function searchEquipmentAction(Request $request)
    {
        $user = $this->getUser();

        if($user == null){
            
            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        
        if($idStudy == null || $idStudy == 0 || $idStudy == ""){
            
            return $this->redirectToRoute('load-study');
        }
        $idRefri=$request->get('idRefri');
        $idManu=$request->get('idManu');
        $idSeries=$request->get('idSeries');
        $idOrigin=$request->get('idOrigin');
        $idProcess=$request->get('idProcess');
        $idModel=$request->get('idModel');
        $idSize=$request->get('idSize');
        $stringRefri="";$stringRefriEmpty="";
        $stringManu="";
        $stringSeries="";
        $stringOrigin="";
        $tringProcess="";
        $stringModel="";
        $stringSize="";

        if($idRefri != -1 || $idRefri != "-1"){
            $stringRefri=" AND e.idCoolingFamily=".$idRefri;
            $stringRefriEmpty= "e.idCoolingFamily=".$idRefri;
        }

        if($idManu != -1 || $idManu != "-1"){
            $stringManu=" AND es.constructor='".$idManu."'";
        }

        if($idSeries != -1 || $idSeries != "-1"){
            $stringSeries=" AND ef.idFamily=".$idSeries;
        }

        if($idOrigin != -1 || $idOrigin != "-1"){
            $stringOrigin=" AND e.std=".$idOrigin;
        }

        if($idProcess != -1 || $idProcess != "-1"){
            $tringProcess=" AND ef.batchProcess=".$idProcess;
        }

        if($idModel != -1 || $idModel != "-1"){
            $stringModel=" AND es.idEquipseries=".$idModel;
        }

        if($idSize != -1 || $idSize != "-1"){
            $arrSize=explode("x",$idSize);
            $stringSize=" AND e.eqpLength=".$arrSize[0]." AND e.eqpWidth=".$arrSize[1];
        }

        // get Manufacturer
        if($idRefri != -1 || $idRefri != "-1"){
            $manufacturer = $this->getDoctrine()->getRepository(Equipseries::class)->createQueryBuilder('es')
                            ->select('es.constructor')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->where($stringRefriEmpty)
                            ->distinct(true)
                            ->orderBy("es.constructor", 'ASC')
                            ->getQuery()->getResult();
        }else{
            $manufacturer = $this->getDoctrine()->getRepository(Equipseries::class)->createQueryBuilder('es')
                            ->select('es.constructor')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->distinct(true)
                            ->orderBy("es.constructor", 'ASC')
                            ->getQuery()->getResult();
        }
        $arrManufacturer=[];

        if(count($manufacturer) > 0){

            foreach ($manufacturer as $key) {
                 $ret =[
                        'constructor' => $key['constructor']
                    ];
                    array_push($arrManufacturer,$ret);
            }
        }
        // get Equipment Series
        $equipSeries = $this->getDoctrine()->getRepository(Equipfamily::class)->createQueryBuilder('ef')
                            ->select('ef.idFamily , t.label')
                            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = ef.idFamily')
                            ->leftJoin(Equipseries::class, 'es', 'WITH', 'es.idFamily = ef.idFamily')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->where('t.transType = :transType')
                            ->andWhere('t.codeLangue = :codeLangue'.$stringRefri.$stringManu)
                            ->setParameter('transType', Post::TRANSTYPE_EQUIPMENT_FAMILY)
                            ->setParameter('codeLangue',$user->getCodeLangue())
                            ->distinct(true)
                            ->orderBy("t.label", 'ASC')
                            ->getQuery()->getResult();
        $arrEquipSeries=[];

        if(count($equipSeries) > 0){

            foreach ($equipSeries as $key) {
                $ret =[
                        'idFamily' => $key['idFamily'],
                        'label'  => $key['label']
                ];
                array_push($arrEquipSeries,$ret);
            }
        }
        // get Equipment Origin
        $equipOrigin = $this->getDoctrine()->getRepository(Equipment::class)->createQueryBuilder('e')
                            ->select('t.idTranslation , t.label')
                            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = e.std')
                            ->leftJoin(Equipseries::class, 'es', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->leftJoin(Equipfamily::class, 'ef', 'WITH', 'ef.idFamily = es.idEquipseries')
                            ->where('t.codeLangue = :codeLangue')
                            ->andWhere('t.transType = :transType'.$stringRefri.$stringManu.$stringSeries)
                            ->setParameter('codeLangue',$user->getCodeLangue() )
                            ->setParameter('transType', Post::TRANSTYPE_EQUIPMENT_ORIGIN)
                            ->distinct(true)
                            ->orderBy("t.label", 'ASC')
                            ->getQuery()->getResult();
        $arrEquipOrigin=[];

        if(count($equipOrigin) > 0){

            foreach ($equipOrigin as $key) {
                $ret =[
                        'std' => $key['idTranslation'],
                        'label'  => $key['label']
                ];
                array_push($arrEquipOrigin,$ret);
            }
        }
        // get Process Type
        $processType = $this->getDoctrine()->getRepository(Equipfamily::class)->createQueryBuilder('ef')
                            ->select('t.idTranslation , t.label')
                            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = ef.batchProcess')
                            ->leftJoin(Equipseries::class, 'es', 'WITH', 'es.idFamily = ef.idFamily')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->where('t.transType = :transType')
                            ->andWhere('t.codeLangue = :codeLangue'.$stringRefri.$stringManu.$stringSeries.$stringOrigin)
                            ->setParameter('transType', Post::TRANSTYPE_PROCESS_TYPE)
                            ->setParameter('codeLangue',$user->getCodeLangue())
                            ->distinct(true)
                            ->orderBy("t.label", 'ASC')
                            ->getQuery()->getResult();
        $arrProcessType=[];

        if(count($processType) > 0){

            foreach ($processType as $key) {
                $ret =[
                        'batchProcess' => $key['idTranslation'],
                        'label'  => $key['label']
                ];
                array_push($arrProcessType,$ret);
            }
        }
        // get Model
        $model = $this->getDoctrine()->getRepository(Equipseries::class)->createQueryBuilder('es')
                            ->select('es.idEquipseries, t.label')
                            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = es.idEquipseries')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->leftJoin(Equipfamily::class, 'ef', 'WITH', 'ef.idFamily = es.idFamily')
                            ->where('t.transType = :transType')
                            ->andWhere('t.codeLangue = :codeLangue'.$stringRefri.$stringManu.$stringSeries.$stringOrigin.$tringProcess)
                            ->setParameter('transType', Post::TRANSTYPE_MODEL)
                            ->setParameter('codeLangue',$user->getCodeLangue())
                            ->distinct(true)
                            ->orderBy("t.label", 'ASC')
                            ->getQuery()->getResult();
        $arrModel=[];

        if(count($model) > 0){

            foreach ($model as $key) {
                $ret =[
                        'idEquipseries' => $key['idEquipseries'],
                        'label' => $key['label']
                ];
                array_push($arrModel,$ret);
            }
        }
        // get Size
        $size = $this->getDoctrine()->getRepository(Equipment::class)->createQueryBuilder('e')
                            ->select('e.eqpLength, e.eqpWidth')
                            ->leftJoin(Equipseries::class, 'es', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->leftJoin(Equipfamily::class, 'ef', 'WITH', 'ef.idFamily = es.idEquipseries')
                            ->where('(e.eqpImpIdStudy = :idStudy OR e.eqpImpIdStudy=0 )'.$stringRefri.$stringManu.$stringSeries.$stringOrigin.$tringProcess.$stringModel)
                            ->andWhere('(e.idUser=:idUser AND e.equipRelease = 2) OR (e.equipRelease=4 OR e.equipRelease=3)')
                            ->setParameter('idStudy', $idStudy)
                            ->setParameter('idUser', $user->getIdUser())
                            ->distinct(true)
                            ->orderBy("e.eqpLength", 'ASC')
                            ->getQuery()->getResult();
        $arrSize=[];

        if(count($size) > 0){

            foreach ($size as $key) {
                $ret =[
                        'eqpLength' => $key['eqpLength'] == null ? '' : $key['eqpLength'],
                        'eqpWidth' => $key['eqpWidth'] == null ? '' : $key['eqpWidth']
                ];
                array_push($arrSize,$ret);
            } 
        }
        // get equipment
        $equipment=$this->getDoctrine()->getRepository(Equipment::class)->createQueryBuilder('e')
                            ->select('e.idEquip, e.equipName, e.equipVersion')
                            ->leftJoin(Equipseries::class, 'es', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->leftJoin(Equipfamily::class, 'ef', 'WITH', 'ef.idFamily = es.idEquipseries')
                            ->where('(e.eqpImpIdStudy = :idStudy OR e.eqpImpIdStudy=0 )'.$stringRefri.$stringManu.$stringSeries.$stringOrigin.$tringProcess.$stringModel.$stringSize)
                            ->andWhere('(e.idUser=:idUser AND e.equipRelease = 2) OR (e.equipRelease=4 OR e.equipRelease=3)')
                            ->setParameter('idStudy', $idStudy)
                            ->setParameter('idUser', $user->getIdUser())
                            ->orderBy("e.equipName", 'ASC')
                            ->getQuery()->getResult();
        $arrEquipment=[];

        if(count($equipment) > 0){

            foreach ($equipment as $key) {
                $ret =[
                        'idEquip' => $key['idEquip'],
                        'equipName' => $key['equipName'],
                        'equipVersion' => $key['equipVersion'],
                ];
                array_push($arrEquipment, $ret);
            }
        }
        $obj=[
            'listManufacturer' => $arrManufacturer,
            'listEquipSeries'=> $arrEquipSeries,
            'listEquipOrigin' => $arrEquipOrigin,
            'listProcessType' => $arrProcessType,
            'listModel' => $arrModel,
            'listSize' => $arrSize,
            'listEquipment' => $arrEquipment
        ];

        return new JsonResponse($obj);
        
    }

    /**
     * @Route("/getDataPipeline", name="getDataPipeline")
     */
    public function getDataPipelineAction(Request $request)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $ret =[
            'optionCryopipeline' => $objStudy->getOptionCryopipeline(),
            ];

        return new JsonResponse($ret);
    }

    /**
     * @Route("/addEquipment", name="addEquipment")
     */
    public function addEquipmentAction(Request $request , EquipmentsService $equipments)
    {
        $user=$this->getUser();
        $rs=false;

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $ret = array();
        $this->getListInEquipment($request, $ret);
        // get value from view
        $idEquip=$request->get('name-equipment');
        $idCooling=$request->get('name-refrigeration');
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);
        $calMode=$objStudy->getCalculationMode();

        // check calculationMode from STUDIES table database
        if(($calMode==1 || $calMode==3) && count($objStudyEquip)>0){

            if($idCooling == Post::ID_TRANSLATION_LN || $idCooling== Post::ID_TRANSLATION_CO2){
                $session->getFlashBag()->set('Noitce', Notice::ERROR_ENERGY_NOT_IDENTICAL);

                return $this->redirectToRoute('equipments');
            }
        }

        if ($calMode == 2 && count($objStudyEquip) > 0) {
            $session->getFlashBag()->set('Noitce', Notice::ERROR_ONE_EQUIPMENT_ONLY);

            return $this->redirectToRoute('equipments');
        } else {
            $minMaxVolume=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_VOLUME]);
            $objProduct=$this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);
            $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->find($idEquip);

            // check minmax Volume not ok, print error
            if(($objEquip->getCapabilities() & 4096) != 0  && ($objProduct[0]->getProdVolume() > $minMaxVolume[0]->getLimitMax())){
                $session->getFlashBag()->set('Noitce', Notice::ERROR_PROD_VOLUME_FOR_EQUIP);

                return $this->redirectToRoute('equipments');
            }else{

                if(($objEquip->getCapabilities() & 16384) != 0){
                    $session->getFlashBag()->set('Noitce', "EQUIPMENT_WITH_SPECIFIC_POPUP");

                    return $this->redirectToRoute('equipments');
                }else{
                    $rs=true;
                }
            }
        }

        // test condition, if $rs = true is not error, add study equipment 
        if($rs){  
            $em=$this->getDoctrine()->getManager();
            $objProdElmt=$this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$objProduct[0]->getIdProd()]);

            if(count($objProdElmt) <= 0 ){

                return $this->redirectToRoute('Product-Characteristic');
            }else{
                $idShape=$objProdElmt[0]->getIdShape();
                $nbComp=count($objProdElmt);
                $enableConsPie=0;
                $runCalculate=1;
                $brainSaveToDB=0;

                if($calMode==1){
                     $brainSaveToDB=1;
                }
                $specificWidth=-1;
                $specificLength=-1;
                $StudyEquip= new StudyEquipments();
                $StudyEquip->setIdStudy($objStudy);
                $StudyEquip->setIdEquip($objEquip);
                $StudyEquip->setEnableConsPie($enableConsPie);
                $StudyEquip->setRunCalculate($runCalculate);
                $StudyEquip->setBrainSavetodb($brainSaveToDB);
                $StudyEquip->setStdeqpWidth($specificWidth);
                $StudyEquip->setStdeqpLength($specificLength);
                $em->persist($StudyEquip);
                $em->flush();
                //  get object Study Equipment by idStudy + idEquip now. Get parameter default CalculateParametersDef table by idUser, add CalculateParameters 
                $objStudyEquipment=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy(), 'idEquip'=>$objEquip->getIdEquip()],['idStudyEquipments'=>'DESC']);
                $idStudyEquip=$objStudyEquipment[0]->getIdStudyEquipments();
                $objCalculationParamDef=$this->getDoctrine()->getRepository(CalculationParametersDef::class)->findBy(['idUser'=>$user->getIdUser()]);

                if(count($objCalculationParamDef)>0){
                    $isStudyAlphaTopFixedDef=$objCalculationParamDef[0]->isStudyAlphaTopFixedDef();
                    $isStudyAlphaBottomFixedDef=$objCalculationParamDef[0]->isStudyAlphaBottomFixedDef();
                    $isStudyAlphaLeftFixedDef=$objCalculationParamDef[0]->isStudyAlphaLeftFixedDef();
                    $isStudyAlphaRightFixedDef=$objCalculationParamDef[0]->isStudyAlphaRightFixedDef();
                    $isStudyAlphaFrontFixedDef=$objCalculationParamDef[0]->isStudyAlphaFrontFixedDef();
                    $isStudyAlphaRearFixedDef=$objCalculationParamDef[0]->isStudyAlphaRearFixedDef();
                    $getStudyAlphaTopDef=$objCalculationParamDef[0]->getStudyAlphaTopDef();
                    $getStudyAlphaBottomDef=$objCalculationParamDef[0]->getStudyAlphaBottomDef();
                    $getStudyAlphaRightDef=$objCalculationParamDef[0]->getStudyAlphaRightDef();
                    $getStudyAlphaLeftDef=$objCalculationParamDef[0]->getStudyAlphaLeftDef();
                    $getStudyAlphaFrontDef=$objCalculationParamDef[0]->getStudyAlphaFrontDef();
                    $getStudyAlphaRearDef=$objCalculationParamDef[0]->getStudyAlphaRearDef();
                    $isHoriz_scan_def=$objCalculationParamDef[0]->isHorizScanDef();
                    $isVert_scan_def=$objCalculationParamDef[0]->isVertScanDef();
                    $getMaxItNbDef=$objCalculationParamDef[0]->getMaxItNbDef();
                    $getRelaxCoeffDef=$objCalculationParamDef[0]->getRelaxCoeffDef();
                    $getStopTopSurfDef=$objCalculationParamDef[0]->getStopTopSurfDef();
                    $getStopIntDef=$objCalculationParamDef[0]->getStopIntDef();
                    $getStopBottomSurfDef=$objCalculationParamDef[0]->getStopBottomSurfDef();
                    $getStopAvgDef=$objCalculationParamDef[0]->getStopAvgDef();
                    $getTimeStepsNbDef=$objCalculationParamDef[0]->getTimeStepsNbDef();
                    $getStorageStepDef=$objCalculationParamDef[0]->getStorageStepDef();
                    $getPrecisionLogStepDef=$objCalculationParamDef[0]->getPrecisionLogStepDef();

                    $objCalParam=new CalculationParameters();
                    $objCalParam->setStudyAlphaTopFixed($isStudyAlphaTopFixedDef);
                    $objCalParam->setStudyAlphaBottomFixed($isStudyAlphaBottomFixedDef);
                    $objCalParam->setStudyAlphaLeftFixed($isStudyAlphaLeftFixedDef);
                    $objCalParam->setStudyAlphaRightFixed($isStudyAlphaRightFixedDef);
                    $objCalParam->setStudyAlphaFrontFixed($isStudyAlphaFrontFixedDef);
                    $objCalParam->setStudyAlphaRearFixed($isStudyAlphaRearFixedDef);
                    $objCalParam->setStudyAlphaTop($getStudyAlphaTopDef);
                    $objCalParam->setStudyAlphaBottom($getStudyAlphaBottomDef);
                    $objCalParam->setStudyAlphaRight($getStudyAlphaRightDef);
                    $objCalParam->setStudyAlphaLeft($getStudyAlphaLeftDef);
                    $objCalParam->setStudyAlphaFront($getStudyAlphaFrontDef);
                    $objCalParam->setStudyAlphaRear($getStudyAlphaRearDef);
                    $objCalParam->setHorizScan($isHoriz_scan_def);
                    $objCalParam->setVertScan($isVert_scan_def);
                    $objCalParam->setMaxItNb($getMaxItNbDef);
                    $objCalParam->setRelaxCoeff($getRelaxCoeffDef);
                    $objCalParam->setStopTopSurf($getStopTopSurfDef);
                    $objCalParam->setStopInt($getStopIntDef);
                    $objCalParam->setStopBottomSurf($getStopBottomSurfDef);
                    $objCalParam->setStopAvg($getStopAvgDef);
                    $objCalParam->setTimeStepsNb($getTimeStepsNbDef);
                    $objCalParam->setStorageStep($getStorageStepDef);
                    $objCalParam->setPrecisionLogStep($getPrecisionLogStepDef);
                    $objCalParam->setIdStudyEquipments($objStudyEquipment[0]);

                    $em->persist($objCalParam);
                    $em->flush();

                    $idObjCalParam=$this->getDoctrine()->getRepository(CalculationParameters::class)->findBy(['idStudyEquipments'=>$idStudyEquip],['idCalcParams'=>'DESC']);
                    $objStudyEquipment[0]->setIdCalcParams($idObjCalParam[0]->getIdCalcParams());
                }
                // Find Pipeline pipeG ::::: Step 1. get idStudyEquipments from STUDYEQUIPMENT by (idStudy).  Step 2. get data PIPE_GEN table by (idStudyEquipments). Step 3. add PIPE_GEN data to PIPE_GEN table with idStudyEquipment new. Step 4. update idPipeGen fields STUDYEQUIPMENT table. Step 5. create LineDefinition
                $objStudyEquip_Gen=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy()],['idStudyEquipments'=>'ASC']);

                if(count($objStudyEquip_Gen)>0){
                    $objPipeGen = $this->getDoctrine()->getRepository(PipeGen::class)->createQueryBuilder('p')
                        ->where('p.idStudyEquipments = :idStudyEquipments AND p.idStudyEquipments != :idEquip')
                        ->setParameter('idStudyEquipments', $objStudyEquip_Gen[0]->getIdStudyEquipments())
                        ->setParameter('idEquip', $objEquip->getIdEquip())
                        ->getQuery()->getResult();

                    if(count($objPipeGen)>0){
                        $objPipeGen_New = new PipeGen();
                        $objPipeGen_New->setIdStudyEquipments($objStudyEquipment[0]);
                        $objPipeGen_New->setElbows($objPipeGen[0]->getElbows());
                        $objPipeGen_New->setFluid($objPipeGen[0]->getFluid());
                        $objPipeGen_New->setGasTemp($objPipeGen[0]->getGasTemp());
                        $objPipeGen_New->setHeight($objPipeGen[0]->getHeight());
                        $objPipeGen_New->setInsullineLenght($objPipeGen[0]->getInsullineLenght());
                        $objPipeGen_New->setInsulValves($objPipeGen[0]->getInsulValves());
                        $objPipeGen_New->setMathigher($objPipeGen[0]->isMathigher());
                        $objPipeGen_New->setNoinsullineLenght($objPipeGen[0]->getNoinsullineLenght());
                        $objPipeGen_New->setNoinsulValves($objPipeGen[0]->getNoinsulValves());
                        $objPipeGen_New->setPressure($objPipeGen[0]->getPressure());
                        $objPipeGen_New->setTees($objPipeGen[0]->getTees());
                        $em->persist($objPipeGen_New);
                        $em->flush();
                        $objPipeGen2=$this->getDoctrine()->getRepository(PipeGen::class)->findBy(['idStudyEquipments'=>$idStudyEquip],['idPipeGen'=>'DESC'])[0];
                        $updateStudyEquipment=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquip);
                        $updateStudyEquipment->setIdPipeGen($objPipeGen2->getIdPipeGen());
                        $em->flush();
                        // createLineDefinition
                        $objLineDef=$this->getDoctrine()->getRepository(LineDefinition::class)->findBy(['idPipeGen'=>$objEquip->getIdEquip()]);

                        if(count($objLineDef)>0){
                            $objLineDef_New=new LineDefinition();
                            $objLineElmt=$this->getDoctrine()->getRepository(LineElmt::class)->find($objLineDef[0]->getIdPipelineElmt());
                            $objLineDef_New->setIdPipeGen($objPipeGen2);
                            $objLineDef_New->setTypeElmt($objLineDef[0]->getTypeElmt());
                            $objLineDef_New->setIdPipelineElmt($objLineElmt);
                            $em->persist($objLineDef_New);
                        }
                        
                    }
                }
            }
            $TRType = $objEquip->getItemTr();
            $TSType = $objEquip->getItemTS();
            $VCType = $objEquip->getItemVC();
            $std = $objEquip->isStd();
            $tr=[];
            $ts=[];
            $vc=[];
            $dh=[];
            $minMaxTRType=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>$TRType]);
            $objEquipGeneration=$this->getDoctrine()->getRepository(EquipGeneration::class)->findBy(['idEquip' => $objEquip->getIdEquip()]);
            $minMaxTSType=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>$TSType]);
            $MultiTSRatio = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_TRRATIO]);

            if(!$std && count($objEquipGeneration)>0){

                if($objEquip->getCapabilities() & 65536 != 0){

                    // get tr
                    for($i=0;$i<$objEquip->getNbTr(); $i++){

                        if ($TRType > 0 && $minMaxTRType != null) {
                            array_push($tr,$minMaxTRType[0]->getDefaultValue());
                        } else {
                            array_push($tr,0);
                        }
                    }

                    // get ts
                    for($i=0; $i<$objEquip->getNbTs();$i++){
                        array_push($ts, $objEquipGeneration[0]->getDwellingTime());
                    }
                }else{

                    // get tr
                    for($i=0;$i<$objEquip->getNbTr(); $i++){
                        array_push($tr, $objEquipGeneration[0]->getDwellingTime());
                    }

                    // get ts
                    for($i=0;$i<$objEquip->getNbTs(); $i++){

                        if ($TSType > 0 && $minMaxTSType != null) {
                            array_push($ts,$minMaxTSType[0]->getDefaultValue());
                        } else {
                            array_push($ts,0);
                        }
                    }

                    if (count($ts) > 1) {

                        if ($MultiTSRatio != null) {
                            $ts[0] = $ts[0] * $MultiTSRatio[0]->getDefaultValue();
                        }
                    }
                }
            }else{
                // get tr
                for($i=0;$i<$objEquip->getNbTr(); $i++){

                    if ($TRType > 0 && $minMaxTRType != null) {
                        array_push($tr,$minMaxTRType[0]->getDefaultValue());
                    } else {
                        array_push($tr,0);
                    }
                }
                // get ts
                for($i=0;$i<$objEquip->getNbTs(); $i++){

                    if ($TSType > 0 && $minMaxTSType != null) {
                        array_push($ts,$minMaxTSType[0]->getDefaultValue());
                    } else {
                        array_push($ts,0);
                    }
                }

                if (count($ts) > 1) {

                    if ($MultiTSRatio != null) {
                        $ts[0] = $ts[0] * $MultiTSRatio[0]->getDefaultValue();
                    }
                }
            }
            // GET VC
            $minMaxVCType=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>$VCType]);

            for($i=0;$i<$objEquip->getNbVc(); $i++){

                if ($TRType > 0 && $minMaxVCType != null) {
                    array_push($vc,$minMaxVCType[0]->getDefaultValue());
                } else {
                    array_push($vc,0);
                }
            }
            // GET DH
            $minMaxDHType=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>0]);

            for($i=0;$i<$objEquip->getNbTr(); $i++){

                if ($TRType > 0 && $minMaxDHType != null) {
                    array_push($dh,$minMaxDHType[0]->getDefaultValue());
                } else {
                    array_push($dh,0);
                }
            }
            // GET TEXT
            $text=null;

            if(count($tr)>0){
                $text=$tr[0];    
            }
            $rs = false;
            // add tr, ts, vc, dh, text to database
            $this->addDBEquipmentData($request,$idEquip, $tr,$ts,$vc,$dh,$text, $rs);
            // getEquipmentLayout create record LayoutGeneration
            $rsObjLayoutGeneration= new LayoutGeneration();
            $rsObjLayoutGeneration = $equipments->getEquipmentLayout($request,$idEquip);
            // $this->getEquipmentLayout($request,$idEquip,$rsObjLayoutGeneration);
            // update Product position
            // if choose chaining dispaly add study
            $prodPosition=1;
            $chaining=$objStudy->getChainingControls();

            if($chaining == null || $chaining ==0 || $chaining == ""){
                $rsObjLayoutGeneration->setProdPosition($prodPosition);
            }
            $em->flush();
            // getLayoutResults 
            $rsObjLayoutResults=new LayoutResults();
            $rsObjLayoutResults = $equipments->getLayoutResults($request,$idEquip);
            ////
            ////  runLayoutCalculator -> gọi LayoutCalculation start LCCalculation (kernel)
            ////            
            ////
            ////  kernel success call function  getEquipmentLayout()

            if (($objEquip->getCapabilities() & 2) != 0 && ($objEquip->getCapabilities() & 131072) != 0) {
            /////
            /////  runTSCalculator -> gọi LayoutCalculation start TSCalculation (kernel)
            /////
            /////                
            }
            $flagPhamCast=false;

            if (($objEquip->getCapabilities() & 1) != 0 && ($objEquip->getCapabilities() & 524288) != 0 && ($objEquip->getCapabilities() & 8) != 0) {
                /////
                ///// ** Gọi PhamCastCalculation start (kernel)
                //// Gán flag PhamCast ở trên thành true
                //// Sau khi PhamCast chạy xong, lấy giá trị TR ra và set vào Study Equipment hiện tại
                ////
                 $flagPhamCast=true;
            }

            if (!$flagPhamCast && ($objEquip->getCapabilities() & 2) != 0 && ($objEquip->getCapabilities() & 262144) != 0 && ($objEquip->getCapabilities() & 8) != 0) {
                /////
                /////  ** Gọi PhamCastCalculation start (kernel)
                //// Sau khi PhamCast chạy xong, lấy giá trị TS ra và set vào Study Equipment hiện tại
                /////
            }
            ////
            //// Gọi KernelToolsCalculation start KTCalculation
            //// Sau khi KernelToolsCalculation chạy xong, lấy giá trị TExt ra và set vào Study
            ////
            ////
            if(!$std){

                if(($objEquip->getCapabilities() & 65536) != 0){
                    $session->getFlashBag()->set('WARNING', Notice::WARNING_GENERATED_EQUIPMENT_TS ." AND ".Noitce::WARNING_GENERATED_EQUIPMENT_TR);

                    return $this->redirectToRoute('equipments');
                }
            }

            if(($objEquip->getCapabilities() & 8) == 0 || (($objEquip->getCapabilities() & 1) != 0 && ($objEquip->getCapabilities() & 2) != 0 && ($objEquip->getCapabilities() & 524288) == 0 && ($objEquip->getCapabilities() & 131072) == 0 && ($objEquip->getCapabilities() & 262144) == 0)){
                $session->getFlashBag()->set('WARNING', Notice::WARNING_NO_TR_EQUIPMENT);
                    
                    return $this->redirectToRoute('equipments');
            }
            // Nếu không có lỗi EQUIPMENT_WITH_SPECIFIC_POPUP:

            $rsObjLayoutGeneration = $equipments->getEquipmentLayout($request,$idEquip);
            $rsObjLayoutResults = $equipments->getLayoutResults($request,$idEquip);
            // Nếu có lỗi tính toán, show thông báo lỗi

            return $this->redirectToRoute('equipments');
        }
    }

    private function addDBEquipmentData(Request $request,$idEquip, $tr, $ts, $vc, $dh, $text, &$rs)
    {
        $user = $this->getUser();

        if($user == null){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->find($idEquip);
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy(), 'idEquip'=>$objEquip->getIdEquip()],['idStudyEquipments'=>'DESC']);
        // DELETE StudEqpPrm
        $em=$this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $query = $qb->delete(StudEqpPrm::class, 's')
                    ->where('s.idStudyEquipments = :id')
                    ->setParameter('id',$objStudyEquip[0]->getIdStudyEquipments())
                    ->getQuery()->execute();

        if(count($tr)>0){
            // SET tr data to StudEqpPrm table
            $objStudEqpPrm=new StudEqpPrm();

            for($i=0; $i<count($tr); $i++){
                $objStudEqpPrm->setIdStudyEquipments($objStudyEquip[0]);
                $objStudEqpPrm->setValueType((300+$i));
                $objStudEqpPrm->setValue($tr[$i]);
                $em->persist($objStudEqpPrm);
                $objStudEqpPrm=new StudEqpPrm();
            }   
        }

        if(count($ts)>0){
            // SET ts data to StudEqpPrm table
            $objStudEqpPrm=new StudEqpPrm();

            for($i=0; $i<count($ts); $i++){
                $objStudEqpPrm->setIdStudyEquipments($objStudyEquip[0]);
                $objStudEqpPrm->setValueType((200+$i));
                $objStudEqpPrm->setValue($ts[$i]);
                $em->persist($objStudEqpPrm);
                $objStudEqpPrm=new StudEqpPrm();
            }   
        }

        if(count($vc)>0){
            // SET vc data to StudEqpPrm table
            $objStudEqpPrm=new StudEqpPrm();

            for($i=0; $i<count($vc); $i++){
                $objStudEqpPrm->setIdStudyEquipments($objStudyEquip[0]);
                $objStudEqpPrm->setValueType((100+$i));
                $objStudEqpPrm->setValue($vc[$i]);
                $em->persist($objStudEqpPrm);
                $objStudEqpPrm=new StudEqpPrm();
            }   
        }

        if(count($dh)>0){
            // SET dh data to StudEqpPrm table
            $objStudEqpPrm=new StudEqpPrm();
            for($i=0; $i<count($dh); $i++){
                $objStudEqpPrm->setIdStudyEquipments($objStudyEquip[0]);
                $objStudEqpPrm->setValueType((400+$i));
                $objStudEqpPrm->setValue($dh[$i]);
                $em->persist($objStudEqpPrm);
                $objStudEqpPrm=new StudEqpPrm();
            }   
        }

        if($text!=null){
            // SET text data to StudEqpPrm table
            $objStudEqpPrm=new StudEqpPrm();
            $objStudEqpPrm->setIdStudyEquipments($objStudyEquip[0]);
            $objStudEqpPrm->setValueType(500);
            $objStudEqpPrm->setValue($text);
            $em->persist($objStudEqpPrm);
        }
        // update calculationParams in StudyEquipment table
        // $calcParams = $objStudyEquip[0]->getIdCalcParams();
        // if($calcParams != null){
        // }
        $em->flush();
       
    }

    /**
     * @Route("/getAllMinMaxEquip", name="getAllMinMaxEquip")
     */
    public function getAllMinMaxEquipAction(Request $request)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $minMaxPrice=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_ENERGY_PRICE]); 
        $minMaxL=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_L]); 
        $minMaxW=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_W]);
        $minMaxSizeL=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_EQUIP_SIZE_LENGTH]); 
        $minMaxSizeW=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_EQUIP_SIZE_WIDTH]);  
        $ret =[
            'minPrice'=>$minMaxPrice[0]->getLimitMin(),
            'maxPrice'=>$minMaxPrice[0]->getLimitMax(),
            'minL'=>$minMaxL[0]->getLimitMin(),
            'maxL'=>$minMaxL[0]->getLimitMax(),
            'minW'=>$minMaxW[0]->getLimitMin(),
            'maxW'=>$minMaxW[0]->getLimitMax(),

            'minSizeL'=>$minMaxSizeL[0]->getLimitMin(),
            'maxSizeL'=>$minMaxSizeL[0]->getLimitMax(),
            'minSizeW'=>$minMaxSizeW[0]->getLimitMin(),
            'maxSizeW'=>$minMaxSizeW[0]->getLimitMax(),
            ];

        return new JsonResponse($ret);
    }

    /**
     * @Route("/onChangePriceEquip/{arr}", name="onChangePriceEquip")
     */
    public function onChangePriceEquipAction(Request $request, $arr)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $price=$request->get('price-of-cryogen');
        $em=$this->getDoctrine()->getManager();

        if($objStudy->getOptionEco() && !$objStudy->getHasChild()){

            if($price == null){
                $price = -1;
            }else{  
                // tính $price sử dụng hàm converter
            }
            
            if($objStudy->getIdPrice() == null || $objStudy->getIdPrice() == 0){
                $objPrices = new Prices();
                $objPrices->setIdStudy($objStudy);
                $objPrices->setEcoInCryo1(0);
                $objPrices->setEcoInPbp1(0);
                $objPrices->setEcoInCryo2(0);
                $objPrices->setEcoInPbp2(0);
                $objPrices->setEcoInCryo3(0);
                $objPrices->setEcoInPbp3(0);
                $objPrices->setEcoInCryo4(0);

                $em->persist($objPrices);
                $em->flush();
            }
            $updateObjPrices=$this->getDoctrine()->getRepository(Prices::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);

            if(count($updateObjPrices) > 0){
                $objStudy->setIdPrice($updateObjPrices[0]->getIdPrice());
                $updateObjPrices[0]->setEnergy($price);
                $em->flush();
            }
        }

        if($objStudy->getCalculationMode() == 3 && !$objStudy->getHasChild()){
            $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);

            if($arr != "0"){
                $listIdCheck=explode(",",$arr);

                if(count($objStudyEquip) > 0){

                    for($i=0; $i<count($objStudyEquip);$i++){

                        if($objStudyEquip[$i]->getBrainType() ==0){

                            for($i=0; $i<count($listIdCheck);$i++){

                                if($objStudyEquip[$i]->getIdStudyEquipments()== $listIdCheck[$i]){
                                    $objStudyEquip[$i]->setRunCalculate(1);
                                }else{
                                    $objStudyEquip[$i]->setRunCalculate(0);
                                }
                            }
                            $em->flush(); 
                        }
                    }
                }
            }
        }

        return $this->redirectToRoute('equipments');
    }

    /**
     * @Route("/onChangeEquipLenght", name="onChangeEquipLenght")
     */
    public function onChangeEquipLenghtAction(Request $request)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $em=$this->getDoctrine()->getManager();
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $lenght=$request->get('input-equip-lenght');
        $width=$request->get('input-equip-width');
        // saveLdgRatePrm()
        $objMinMax_ApproxLdgRate=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_LOADING_RATE]);

        if(count($objMinMax_ApproxLdgRate) > 0){
            // Case: update object PrecalcLdgRatePrm
            $objPrecalcLdgRatePrm=$this->getDoctrine()->getRepository(PrecalcLdgRatePrm::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);

            if(count($objPrecalcLdgRatePrm) > 0){
                $objPrecalcLdgRatePrm[0]->setApproxLdgRate($objMinMax_ApproxLdgRate[0]->getDefaultValue());
                $objPrecalcLdgRatePrm[0]->setLInterval($lenght);
                $objPrecalcLdgRatePrm[0]->setWInterval($width);
                $objPrecalcLdgRatePrm[0]->setPrecalcLdgTr(0);
                $objPrecalcLdgRatePrm[0]->setIdStudy($objStudy);
                $em->flush();
            }
        }
        // Overwrite Intervals for All Equipments
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);

        if(count($objStudyEquip) > 0){

            foreach ($objStudyEquip as $key ) {
                $idLayoutGeneration=$key->getIdLayoutGeneration();

                if($idLayoutGeneration != null){
                    $objLayoutGener=$this->getDoctrine()->getRepository(LayoutGeneration::class)->find($idLayoutGeneration);
                    $objLayoutGener->setLengthInterval(-1);
                    $objLayoutGener->setWidthInterval(-1);
                    $em->flush();
                }
            }
        }

        return $this->redirectToRoute('equipments');
    }

    /**
     * @Route("/recalculateEquipment", name="recalculateEquipment")
     */
    public function recalculateEquipmentAction(Request $request)
    {
         $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $em=$this->getDoctrine()->getManager();
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);

        if(count($objStudyEquip)>0){

            foreach ($objStudyEquip as $key) {
                $key->setBrainSavetodb(0);
                $key->setBrainType(0);
                $key->setEquipStatus(0);
                $key->setAverageProductEnthalpy(0);
                $key->setAverageProductTemp(0);
                $key->setEnthalpyVariation(0);
                $key->setPrecis(0);
                $key->setRunCalculate(0);
            }
            $em->flush();
        }

        return $this->redirectToRoute('equipments');  
    }

     /**
     * @Route("/unselectall", name="unselectall")
     */
    public function unselectallAction(Request $request)
    {
         $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $em=$this->getDoctrine()->getManager();
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);

        if(count($objStudyEquip)>0){

            foreach ($objStudyEquip as $key) {

                if($key->getBrainType()==0){
                    $key->setRunCalculate(0);
                }
            }
            $em->flush();
        }

        return $this->redirectToRoute('equipments');  
    }

    /**
     * @Route("/delStudyEquip/{id}", name="delStudyEquip")
     */
    public function delStudyEquipAction(Request $request,$id)
    {
         $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $em=$this->getDoctrine()->getManager();
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($id);
        // Delete StudyEquipment in CalculationParameters table DB
        $em->createQueryBuilder()
            -> delete(CalculationParameters::class, 'c')
            ->where('c.idStudyEquipments = :id')
            ->setParameter('id',$objStudyEquip->getIdStudyEquipments())
           ->getQuery()->execute();
        // delete StudyEquipment in Stud_Eqp_Prm table DB
        $em->createQueryBuilder()
        ->delete(StudEqpPrm::class,'s')
        ->where('s.idStudyEquipments = :id')
        ->setParameter('id',$objStudyEquip->getIdStudyEquipments())
        ->getQuery()->execute();
        // delete StudyEquipment in LAYOUT_GENERATION table DB
        $em->createQueryBuilder()
        ->delete(LayoutGeneration::class,'l')
        ->where('l.idStudyEquipments = :id')
        ->setParameter('id',$objStudyEquip->getIdStudyEquipments())
        ->getQuery()->execute();
        // delete StudyEquipment in LAYOUT_RESULTS table DB
        $em->createQueryBuilder()
        ->delete(LayoutResults::class, 'l')
        ->where('l.idStudyEquipments = :id')
        ->setParameter('id',$objStudyEquip->getIdStudyEquipments())
        ->getQuery()->execute();

        // delete LineDefinition + PipeGen table DB
        if($objStudy->getOptionCryopipeline()){
            $objPipeGen=$this->getDoctrine()->getRepository(PipeGen::class)->findBy(['idStudyEquipments'=>$objStudyEquip->getIdStudyEquipments()]);

            if(count($objPipeGen)>0){
                $em->createQueryBuilder()
                ->delete(LineDefinition::class,'l')
                ->where('l.idPipeGen = :idPipeGen')
                ->setParameter('idPipeGen',$objPipeGen[0]->getIdPipeGen())
                ->getQuery()->execute();
            }
        }
        $em->createQueryBuilder()
            ->delete(PipeGen::class,'p')
            ->where('p.idStudyEquipments= :id')
            ->setParameter('id',$objStudyEquip->getIdStudyEquipments())
            ->getQuery()->execute();
        // delete Study Equipment it
        $em->remove($objStudyEquip);
        $em->flush();

        return $this->redirectToRoute('equipments');  
    }

    /**
     * @Route("/modalOperatingSettings", name="modalOperatingSettings")
     */

    public function modalOperatingSettingsAction(Request $request)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idStudyEquip=$request->get('id');
        $session->set('idStudyEquipments', $idStudyEquip);
        $arr = array();
        $this->getTsTrVcAlphaEquipment($request,$idStudyEquip, $arr);

        return new JsonResponse($arr);
    }

    private function getTsTrVcAlphaEquipment(Request $request,$idStudyEquip, &$arr)
    {
        $objStudyEquipment=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquip);
        // get array value TR and check display (input, button) 
        $queryTr = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder('s')
                    ->where('s.valueType >= :valueType1 AND s.valueType <= :valueType2')
                    ->andWhere('s.idStudyEquipments = :idStudyEquipments')
                    ->setParameter('valueType1', '300')
                    ->setParameter('valueType2', '399')
                    ->setParameter('idStudyEquipments', $objStudyEquipment->getIdStudyEquipments())
                    ->getQuery()->getResult();
        $rsTr=[];

        if(count($queryTr) > 0){

            foreach ($queryTr as $key) {
                array_push($rsTr,$key->getValue());             
            }
        }
        $displayInputTr=true;

        if(($objStudyEquipment->getIdEquip()->getCapabilities() & 1) == 0){
            $displayInputTr=false;
        }
        $displayResidence=true;

        if(!$objStudyEquipment->getIdEquip()->isStd() || ($objStudyEquipment->getIdEquip()->getCapabilities() & 2) == 0 || ($objStudyEquipment->getIdEquip()->getCapabilities() & 8) == 0){
                        $displayResidence=false;
        }
        // TS get array value TS and check display (button) 
        $queryTs = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder('s')
                    ->where('s.valueType >= :valueType1 AND s.valueType <= :valueType2')
                    ->andWhere('s.idStudyEquipments = :idStudyEquipments')
                    ->setParameter('valueType1', '200')
                    ->setParameter('valueType2', '299')
                    ->setParameter('idStudyEquipments', $objStudyEquipment->getIdStudyEquipments())
                    ->getQuery()->getResult();
        $rsTs=[];

        if(count($queryTs) > 0){

            foreach ($queryTs as $key) {
                array_push($rsTs,$key->getValue());             
            }
        }
        $displayControlTemper=true;

        if(!$objStudyEquipment->getIdEquip()->isStd() || ($objStudyEquipment->getIdEquip()->getCapabilities() & 8) == 0){
            $displayControlTemper=false;
        }  
        // VC get array value VC and check display (input) 
        $queryVc = $this->getDoctrine()->getRepository(StudEqpPrm::class)->createQueryBuilder('s')
                    ->where('s.valueType >= :valueType1 AND s.valueType <= :valueType2')
                    ->andWhere('s.idStudyEquipments = :idStudyEquipments')
                    ->setParameter('valueType1', '100')
                    ->setParameter('valueType2', '199')
                    ->setParameter('idStudyEquipments', $objStudyEquipment->getIdStudyEquipments())
                    ->getQuery()->getResult();
        $rsVc=[];

        if(count($queryVc) > 0){

            foreach ($queryVc as $key) {
                array_push($rsVc,$key->getValue());             
            }
        }
        $displayInputVc=true;

        if(($objStudyEquipment->getIdEquip()->getCapabilities() & 4) == 0){
            $displayInputVc=false;
        }
        // 6 position Alpha 
        $queryAlpha = $this->getDoctrine()->getRepository(CalculationParameters::class)->findBy(['idStudyEquipments'=>$objStudyEquipment->getIdStudyEquipments()]);
        $rsAlpha=[
                    'topfixed'=>$queryAlpha[0]->isStudyAlphaTopFixed(),
                    'top'=>$queryAlpha[0]->getStudyAlphaTop(),
                    'buttomfixed'=>$queryAlpha[0]->isStudyAlphaBottomFixed(),
                    'buttom'=>$queryAlpha[0]->getStudyAlphaBottom(),
                    'leftfixed'=>$queryAlpha[0]->isStudyAlphaLeftFixed(),
                    'left'=>$queryAlpha[0]->getStudyAlphaLeft(),
                    'rightfixed'=>$queryAlpha[0]->isStudyAlphaRightFixed(),
                    'right'=>$queryAlpha[0]->getStudyAlphaRight(),
                    'frontfixed'=>$queryAlpha[0]->isStudyAlphaFrontFixed(),
                    'front'=>$queryAlpha[0]->getStudyAlphaFront(),
                    'rearfixed'=>$queryAlpha[0]->isStudyAlphaRearFixed(),
                    'rear'=>$queryAlpha[0]->getStudyAlphaRear()
        ];                
        // check display frame Gas temperature
        $displayGasTemper=false;
        $rsTExt=null;

        if(($objStudyEquipment->getIdEquip()->getCapabilities() & 512) != 0){
            $displayGasTemper=true;
            $queryTExt=$this->getDoctrine()->getRepository(StudEqpPrm::class)->findBy(['idStudyEquipments'=>$objStudyEquipment->getIdStudyEquipments(), 'valueType'=>500]);

            if(count($queryTExt)>0){
                $rsTExt=$queryTExt[0]->getValue();
            }
        }
        // get data frame Gas temperature table
        $objTempExt=$this->getDoctrine()->getRepository(TempExt::class)->findBy(['idEquipseries'=>$objStudyEquipment->getIdEquip()->getIdEquipseries()]);
        $minMaxTr=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>$objStudyEquipment->getIdEquip()->getItemTr()]);
        $rsGas=[];

        if(count($objTempExt) >0 && count($minMaxTr)>0){

            foreach ($objTempExt as $key) {

                if($key->getTr() >= $minMaxTr[0]->getLimitMin() && $key->getTr() <= $minMaxTr[0]->getLimitMax()){
                    $temp=[
                        'Tr' => $key->getTr(),
                        'TExt' => $key->getExt()
                    ];
                    array_push($rsGas, $temp);
                }
            }
        }      
        // get validate MINMAX TR, TS, VC, Alpha
        $rsMinMaxTr=[];

        if(count($minMaxTr)>0){

            foreach ($minMaxTr as $key) {
                $rsMinMaxTr=[
                    'limitMin'=>$key->getLimitMin(),
                    'limitMax' =>$key->getLimitMax()
                ];
            }
        }
        $rsMinMaxTs=[];
        $minMaxTs=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>$objStudyEquipment->getIdEquip()->getItemTs()]);

        if(count($minMaxTs)>0){

            foreach ($minMaxTs as $key) {
                $rsMinMaxTs=[
                    'limitMin'=>$key->getLimitMin(),
                    'limitMax' =>$key->getLimitMax()
                ];
            }
        }
        $rsMinMaxVC=[];
        $minMaxVC=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_VC]);
                
        if(count($minMaxVC)>0){

            foreach ($minMaxVC as $key) {
                $rsMinMaxVC=[
                    'limitMin'=>$key->getLimitMin(),
                    'limitMax' =>$key->getLimitMax()
                ];
            }
        }
        $rsMinMaxAlpha=[];
        $minMaxAlpha=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_ALPHA]);

        if(count($minMaxAlpha)>0){

            foreach ($minMaxAlpha as $key) {
                $rsMinMaxAlpha=[
                    'limitMin'=>$key->getLimitMin(),
                    'limitMax' =>$key->getLimitMax()
                ];
            }
        }
        $arrMinMaxEquip=[
            'minMaxTr'=>$rsMinMaxTr,
            'minMaxTs'=>$rsMinMaxTs,
            'minMaxVC'=>$rsMinMaxVC,
            'minMaxAlpha'=>$rsMinMaxAlpha
        ];
        // check add icon change_tr.gif  on Control temperature
        $iconEquip=false;

        if(($objStudyEquipment->getIdEquip()->getCapabilities() & 1) == 0 || ($objStudyEquipment->getIdEquip()->getCapabilities() & 65536) == 0  ){
            $iconEquip=true;
        }   
        $arr=[
                    'Ts'=>$rsTs,
                    'Tr'=>$rsTr,
                    'Vc'=>$rsVc,
                    'Alpha'=>$rsAlpha,
                    'btnCoTemper'=>$displayControlTemper,
                    'inputTr'=>$displayInputTr,
                    'btnResidence'=>$displayResidence,
                    'inputVc'=>$displayInputVc,
                    'iconEquip'=>$iconEquip,
                    'frameGasTemper'=>$displayGasTemper,
                    'Gas'=>$rsGas,
                    'minMaxEquip'=>$arrMinMaxEquip,
                    'TExt'=>$rsTExt=null?0:$rsTExt
        ];                
    }

     /**
     * @Route("/calculateTr/{arrTR}", name="calculateTr")
     */
    public function calculateTrAction(Request $request, $arrTR)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idStudyEquipments=$session->get('idStudyEquipments');

        if($idStudyEquipments==null || $idStudyEquipments==0 || $idStudyEquipments==""){

            return $this->redirectToRoute('equipments');  
        }
        $em=$this->getDoctrine()->getManager();
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $listTR=explode(",",$arrTR);
        
        if(($objStudyEquip->getIdEquip()->getCapabilities() & 8) != 0){
            // KERNEL
            //   run PhamCast calculation và cập nhật lại equip data xuống database cũng như load lại trên giao diện, đọc thêm hàm runPhamCast(…) trong file EquipmentBean.java
            //
        }

        if(($objStudyEquip->getIdEquip()->getCapabilities() & 512) != 0){
            // KERNEL
            //   run ExhaustGasTemp calculation và cập nhật lại equip data xuống database cũng như load lại trên giao diện, đọc thêm hàm runExhaustGasTemp(…) trong file EquipmentBean.java
            //
        }

        return $this->redirectToRoute('equipments');  
    }

    /**
     * @Route("/calculateTs/{arrTS}", name="calculateTs")
     */
    public function calculateTsAction(Request $request, $arrTS)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idStudyEquipments=$session->get('idStudyEquipments');

        if($idStudyEquipments==null || $idStudyEquipments==0 || $idStudyEquipments==""){

            return $this->redirectToRoute('equipments');  
        }
        $em=$this->getDoctrine()->getManager();
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $listTS=explode(",",$arrTS);
        
        if(($objStudyEquip->getIdEquip()->getCapabilities() & 8) != 0){
            // KERNEL
            //   run PhamCast calculation và cập nhật lại equip data xuống database cũng như load lại trên giao diện, đọc thêm hàm runPhamCast(…) trong file EquipmentBean.java
            //
        }

        if(($objStudyEquip->getIdEquip()->getCapabilities() & 512) != 0){
            // KERNEL
            //   run ExhaustGasTemp calculation và cập nhật lại equip data xuống database cũng như load lại trên giao diện, đọc thêm hàm runExhaustGasTemp(…) trong file EquipmentBean.java
            //
        }

        return $this->redirectToRoute('equipments');  
    }

    /**
     * @Route("/setEquipmentData", name="setEquipmentData")
     */
    public function setEquipmentDataAction(Request $request)
    {
         $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idStudyEquipments=$session->get('idStudyEquipments');

        if($idStudyEquipments==null || $idStudyEquipments==0 || $idStudyEquipments==""){

            return $this->redirectToRoute('equipments');  
        }
        $em=$this->getDoctrine()->getManager();
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $arr = array();
        $this->getTsTrVcAlphaEquipment($request,$idStudyEquipments,$arr);
        $listTS=$request->get('listTS');
        $listTR=$request->get('listTR');
        $listVC=$request->get('listVC');
        $listAlpha=$request->get('listAlpha');
        $TExt=$request->get('TExt');
        $check=true;

        if(($objStudyEquip->getIdEquip()->getCapabilities() & 1) != 0){
        }else{
            $listTR=$arr['Tr'];
        }

        if(($objStudyEquip->getIdEquip()->getCapabilities() & 4) != 0){
        }else{
            $listVC=$arr['Vc'];
        }

        if(($objStudyEquip->getIdEquip()->getCapabilities() & 512) != 0){
        }else{
            $TExt=0;
        }

        for($i=0;$i<count($listTR);$i++){

            if($listTR[$i] == null){

                return new JsonResponse(['notice'=>1]);
            }
        }

        for($i=0;$i<count($listTS);$i++){

            if($listTS[$i] == null){

                return new JsonResponse(['notice'=>2]);
            }
        }

        for($i=0;$i<count($listVC);$i++){

            if($listVC[$i] == null){

                return new JsonResponse(['notice'=>3]);
            }
        }

        if(($objStudyEquip->getIdEquip()->getCapabilities() & 1) != 0){
            // Delete StudEqpPrm and SET tr data to StudEqpPrm table
            $em->createQueryBuilder()
            -> delete(StudEqpPrm::class, 's')
            ->where('s.idStudyEquipments = :id')
            ->andWhere('s.valueType >= 300 AND s.valueType < 400')
            ->setParameter('id',$objStudyEquip->getIdStudyEquipments())
            ->getQuery()->execute();
            $objStudEqpPrm=new StudEqpPrm();

            for($i=0; $i<count($listTR); $i++){
                $objStudEqpPrm->setIdStudyEquipments($objStudyEquip);
                $objStudEqpPrm->setValueType((300+$i));
                $objStudEqpPrm->setValue($listTR[$i]);
                $em->persist($objStudEqpPrm);
                $objStudEqpPrm=new StudEqpPrm();
            }
        }
        // Delete StudEqpPrm SET ts data to StudEqpPrm table
        $em->createQueryBuilder()
            -> delete(StudEqpPrm::class, 's')
            ->where('s.idStudyEquipments = :id')
            ->andWhere('s.valueType >= 200 AND s.valueType < 300')
            ->setParameter('id',$objStudyEquip->getIdStudyEquipments())
            ->getQuery()->execute();
        $objStudEqpPrm=new StudEqpPrm();

        for($i=0; $i<count($listTS); $i++){
            $objStudEqpPrm->setIdStudyEquipments($objStudyEquip);
            $objStudEqpPrm->setValueType((200+$i));
            $objStudEqpPrm->setValue($listTS[$i]);
            $em->persist($objStudEqpPrm);
            $objStudEqpPrm=new StudEqpPrm();
        }  
        // Delete StudEqpPrm SET vc data to StudEqpPrm table
        $em->createQueryBuilder()
            -> delete(StudEqpPrm::class, 's')
            ->where('s.idStudyEquipments = :id')
            ->andWhere('s.valueType >= 100 AND s.valueType < 200')
            ->setParameter('id',$objStudyEquip->getIdStudyEquipments())
            ->getQuery()->execute();
        $objStudEqpPrm=new StudEqpPrm();

        for($i=0; $i<count($listVC); $i++){
            $objStudEqpPrm->setIdStudyEquipments($objStudyEquip);
                $objStudEqpPrm->setValueType((100+$i));
                $objStudEqpPrm->setValue($listVC[$i]);
                $em->persist($objStudEqpPrm);
                $objStudEqpPrm=new StudEqpPrm();
        }
        // Add alpha to  CALCULATION_PARAMETERS table top, buttom, left, right, front ,rear.
        $objCalParam=$this->getDoctrine()->getRepository(CalculationParameters::class)->findBy(['idStudyEquipments'=>$objStudyEquip->getIdStudyEquipments()]);

        if(count($objCalParam)>0){

            if($listAlpha[0] !=''){
                $objCalParam[0]->setStudyAlphaTopFixed(1);
                $objCalParam[0]->setStudyAlphaTop($listAlpha[0]);
            }else{
                $objCalParam[0]->setStudyAlphaTopFixed(0);
                $objCalParam[0]->setStudyAlphaTop(0);
            }

            if($listAlpha[1] !=''){
                $objCalParam[0]->setStudyAlphaBottomFixed(1);
                $objCalParam[0]->setStudyAlphaBottom($listAlpha[1]);
            }else{
                $objCalParam[0]->setStudyAlphaBottomFixed(0);
                $objCalParam[0]->setStudyAlphaBottom(0);
            }

            if($listAlpha[2] !=''){
                $objCalParam[0]->setStudyAlphaLeftFixed(1);
                $objCalParam[0]->setStudyAlphaLeft($listAlpha[2]);
            }else{
                $objCalParam[0]->setStudyAlphaLeftFixed(0);
                $objCalParam[0]->setStudyAlphaLeft(0);
            }

            if($listAlpha[3] !=''){
                $objCalParam[0]->setStudyAlphaRightFixed(1);
                $objCalParam[0]->setStudyAlphaRight($listAlpha[3]);
            }else{
                $objCalParam[0]->setStudyAlphaRightFixed(0);
                $objCalParam[0]->setStudyAlphaRight(0);
            }

            if($listAlpha[4] !=''){
                $objCalParam[0]->setStudyAlphaFrontFixed(1);
                $objCalParam[0]->setStudyAlphaFront($listAlpha[4]);
            }else{
                $objCalParam[0]->setStudyAlphaFrontFixed(0);
                $objCalParam[0]->setStudyAlphaFront(0);
            }

            if($listAlpha[5] !=''){
                $objCalParam[0]->setStudyAlphaRearFixed(1);
                $objCalParam[0]->setStudyAlphaRear($listAlpha[5]);
            }else{
                $objCalParam[0]->setStudyAlphaRearFixed(0);
                $objCalParam[0]->setStudyAlphaRear(0);
            }
        }
        $em->flush();

        return new JsonResponse(['notice'=>'success']);
    }

    /**
     * @Route("/getLengthWidthSE", name="getLengthWidthSE")
     */
    public function getLengthWidthSEAction(Request $request)
    {
         $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idStudyEquipments=$request->get('id');
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);

        return new JsonResponse([
            'length'=>$objStudyEquip->getStdeqpLength(),
            'width'=>$objStudyEquip->getStdeqpWidth()
            ]);
    }

    /**
     * @Route("/updateEquipmentSize", name="updateEquipmentSize")
     */
    public function updateEquipmentSizeAction(Request $request, EquipmentsService $equipments)
    {
         $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idStudyEquipments=$request->get('_idSE');
        $session->set('idStudyEquipments',$idStudyEquipments);
        //  (nhớ convert sang đơn vị người dùng, sử dụng hàm equipDimension trong lớp UnitConverter, nếu textbox trên giao diện rỗng thì trả ra -1)
        $length=$request->get('_input-equip-size-lenght');
        $width=$request->get('_input-equip-size-width');
        
        if($idStudyEquipments==null || $idStudyEquipments==0 || $idStudyEquipments==""){

            return $this->redirectToRoute('equipments');  
        }
        $em=$this->getDoctrine()->getManager();
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        // convert sang đơn vị người dùng
        $stdeqpLength=$objStudyEquip->getStdeqpLength();
        $stdeqpWidth=$objStudyEquip->getStdeqpWidth();

        if($length != $stdeqpLength || $width != $stdeqpWidth){
            $objStudyEquip->setStdeqpLength($length);
            $objStudyEquip->setStdeqpWidth($width);
            $em->flush();
        }
        // * Gọi RunStudyCleaner (gọi kernel clear study, nếu study có chaning thì gọi mỗi study con chạy tính lại, rồi cập nhật lại study con xuống database).
        ////
        ////
        $rs=false;
        $this->recalculateEquipment($request, $equipments, $idStudyEquipments);
        
       return $this->redirectToRoute('equipments');
    }

    private function recalculateEquipment(Request $request, EquipmentsService $equipments, $idStudyEquipments)
    {
        $check1=$check2=$check3=true;
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $idEquip=$objStudyEquip->getIdEquip()->getIdEquip();
        $rsObjLayoutGeneration=new LayoutGeneration();

        try{    
            $rsObjLayoutGeneration = $equipments->getEquipmentLayout($request, $idEquip);
            // chạy runLayoutCalculator: gọi kernel LayoutCalculation chạy StartLCCalculation
            ////
            ////
        } catch(Exception $e){
             $check1=false;
        }

        if(!$check1){

            try{

                if(($objStudyEquip->getIdEquip()->getCapabilities() & 2) != 0 && ($objStudyEquip->getIdEquip()->getCapabilities() & 131072) != 0){
                    $rsObjLayoutGeneration = $equipments->getEquipmentLayout($request, $idEquip);
                    // chạy runTSCalculator: gọi kernel LayoutCalculation chạy StartTSCalculation
                    ////
                    ////
                }
            }catch(Exception $e){
                $check2=false;
            }
        }

        if(!$check1 && !$check2){

            try{

                if(($objStudyEquip->getIdEquip()->getCapabilities() & 1) != 0 && ($objStudyEquip->getIdEquip()->getCapabilities() & 524288) != 0 && ($objStudyEquip->getIdEquip()->getCapabilities() & 8) != 0){
                    // gọi kernel PhamCastCalculation chạy StartPCC_TS2TRCalculation
                    ////
                    ////
                }
            }catch(Exception $e){
                $check3=false;
            }
        }

        if(!$check1 && !$check2 && !$check3){

            if(($objStudyEquip->getIdEquip()->getCapabilities() & 2) != 0 && ($objStudyEquip->getIdEquip()->getCapabilities() & 262144) != 0 && ($objStudyEquip->getIdEquip()->getCapabilities() & 8) != 0){
                // gọi kernel PhamCastCalculation chạy StartPCC_TR2TSCalculation
                ////
                ////
            }
        }

        if(!$check1 && !$check2 && !$check3){  
            // gọi kernel KernelToolsCalculation chạy StartKTCalculation
            ////
            ////            
        }
    }

    /**
     * @Route("/updateEquipmentSizeIdEquip", name="updateEquipmentSizeIdEquip")
     */
    public function updateEquipmentSizeIdEquipAction(Request $request, EquipmentsService $equipments)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idEquip=$request->get('_idSE');
        //  (nhớ convert sang đơn vị người dùng, sử dụng hàm equipDimension trong lớp UnitConverter, nếu textbox trên giao diện rỗng thì trả ra -1)
        $length=$request->get('_input-equip-size-lenght');
        $width=$request->get('_input-equip-size-width');
        $this->addEquipmentInStudy($request, $equipments, $idStudy, $idEquip, $width, $length);

        return $this->redirectToRoute('equipments');
    }

    private function addEquipmentInStudy(Request $request, EquipmentsService $equipments, $study_id, $eq_id, $specificWidth, $specificLength)
    {
        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $objStudyEquip=new StudyEquipments();
        $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->find($eq_id);
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($study_id);
        $objStudyEquip->setIdStudy($objStudy);
        $objStudyEquip->setIdEquip($objEquip);
        $objStudyEquip->setEnableConsPie(0);
        $objStudyEquip->setRunCalculate(1);

        if($objStudy->getCalculationMode()==1){
            $objStudyEquip->setBrainSaveToDB(1);
        }else{
            $objStudyEquip->setBrainSaveToDB(0);
        }
        $objStudyEquip->setStdeqpWidth($specificWidth);
        $objStudyEquip->setStdEqpLength($specificLength);
        $em->persist($objStudyEquip);
        $em->flush();
        $objStudyEquipments=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy(), 'idEquip'=>$objEquip->getIdEquip()],['idStudyEquipments'=>'DESC']);
        $idStudyEquipments=$objStudyEquipments[0]->getIdStudyEquipments();
        $objCalculationParamDef=$this->getDoctrine()->getRepository(CalculationParametersDef::class)->findBy(['idUser'=>$user->getIdUser()]);

        if(count($objCalculationParamDef)>0){
            $objCalParam=new CalculationParameters();
            $objCalParam->setStudyAlphaTopFixed($objCalculationParamDef[0]->isStudyAlphaTopFixedDef());
            $objCalParam->setStudyAlphaBottomFixed($objCalculationParamDef[0]->isStudyAlphaBottomFixedDef());
            $objCalParam->setStudyAlphaLeftFixed($objCalculationParamDef[0]->isStudyAlphaLeftFixedDef());
            $objCalParam->setStudyAlphaRightFixed($objCalculationParamDef[0]->isStudyAlphaRightFixedDef());
            $objCalParam->setStudyAlphaFrontFixed($objCalculationParamDef[0]->isStudyAlphaFrontFixedDef());
            $objCalParam->setStudyAlphaRearFixed($objCalculationParamDef[0]->isStudyAlphaRearFixedDef());
            $objCalParam->setStudyAlphaTop($objCalculationParamDef[0]->getStudyAlphaTopDef());
            $objCalParam->setStudyAlphaBottom($objCalculationParamDef[0]->getStudyAlphaBottomDef());
            $objCalParam->setStudyAlphaRight($objCalculationParamDef[0]->getStudyAlphaRightDef());
            $objCalParam->setStudyAlphaLeft($objCalculationParamDef[0]->getStudyAlphaLeftDef());
            $objCalParam->setStudyAlphaFront($objCalculationParamDef[0]->getStudyAlphaFrontDef());
            $objCalParam->setStudyAlphaRear($objCalculationParamDef[0]->getStudyAlphaRearDef());
            $objCalParam->setHorizScan($objCalculationParamDef[0]->isHorizScanDef());
            $objCalParam->setVertScan($objCalculationParamDef[0]->isVertScanDef());
            $objCalParam->setMaxItNb($objCalculationParamDef[0]->getMaxItNbDef());
            $objCalParam->setRelaxCoeff($objCalculationParamDef[0]->getRelaxCoeffDef());
            $objCalParam->setStopTopSurf($objCalculationParamDef[0]->getStopTopSurfDef());
            $objCalParam->setStopInt($objCalculationParamDef[0]->getStopIntDef());
            $objCalParam->setStopBottomSurf($objCalculationParamDef[0]->getStopBottomSurfDef());
            $objCalParam->setStopAvg($objCalculationParamDef[0]->getStopAvgDef());
            $objCalParam->setTimeStepsNb($objCalculationParamDef[0]->getTimeStepsNbDef());
            $objCalParam->setStorageStep($objCalculationParamDef[0]->getStorageStepDef());
            $objCalParam->setPrecisionLogStep($objCalculationParamDef[0]->getPrecisionLogStepDef());
            $objCalParam->setIdStudyEquipments($objStudyEquipments[0]);
            $em->persist($objCalParam);
            $em->flush();
        }
        $idObjCalParam=$this->getDoctrine()->getRepository(CalculationParameters::class)->findBy(['idStudyEquipments'=>$idStudyEquipments],['idCalcParams'=>'DESC']);

        if(count($idObjCalParam) > 0){
            $objStudyEquipments[0]->setIdCalcParams($idObjCalParam[0]->getIdCalcParams());
            $em->flush();
        }
        // ** Find Pipeline pipeG
        $objStudyEquip_Gen=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy()],['idStudyEquipments'=>'ASC']);
        
        if(count($objStudyEquip_Gen)>0){
            $objPipeGen = $this->getDoctrine()->getRepository(PipeGen::class)->createQueryBuilder('p')
                        ->where('p.idStudyEquipments = :idStudyEquipments AND p.idStudyEquipments != :idEquip')
                        ->setParameter('idStudyEquipments', $objStudyEquip_Gen[0]->getIdStudyEquipments())
                        ->setParameter('idEquip', $objEquip->getIdEquip())
                        ->getQuery()->getResult();
            
            if(count($objPipeGen)>0){
                $objPipeGen_New = new PipeGen();
                $objPipeGen_New->setIdStudyEquipments($objStudyEquipments[0]);
                $objPipeGen_New->setElbows($objPipeGen[0]->getElbows());
                $objPipeGen_New->setFluid($objPipeGen[0]->getFluid());
                $objPipeGen_New->setGasTemp($objPipeGen[0]->getGasTemp());
                $objPipeGen_New->setHeight($objPipeGen[0]->getHeight());
                $objPipeGen_New->setInsullineLenght($objPipeGen[0]->getInsullineLenght());
                $objPipeGen_New->setInsulValves($objPipeGen[0]->getInsulValves());
                $objPipeGen_New->setMathigher($objPipeGen[0]->isMathigher());
                $objPipeGen_New->setNoinsullineLenght($objPipeGen[0]->getNoinsullineLenght());
                $objPipeGen_New->setNoinsulValves($objPipeGen[0]->getNoinsulValves());
                $objPipeGen_New->setPressure($objPipeGen[0]->getPressure());
                $objPipeGen_New->setTees($objPipeGen[0]->getTees());
                $em->persist($objPipeGen_New);
                $em->flush();
                $objPipeGen2=$this->getDoctrine()->getRepository(PipeGen::class)->findBy(['idStudyEquipments'=>$objStudyEquipments[0]->getIdStudyEquipments()],['idPipeGen'=>'DESC'])[0];
                $updateStudyEquipment=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($objStudyEquipments[0]->getIdStudyEquipments());
                // createLineDefinition
                $objLineDef=$this->getDoctrine()->getRepository(LineDefinition::class)->findBy(['idPipeGen'=>$objEquip->getIdEquip()]);

                if(count($objLineDef)>0){
                    $objLineDef_New=new LineDefinition();
                    $objLineElmt=$this->getDoctrine()->getRepository(LineElmt::class)->find($objLineDef[0]->getIdPipelineElmt());
                    $objLineDef_New->setIdPipeGen($objPipeGen2);
                    $objLineDef_New->setTypeElmt($objLineDef[0]->getTypeElmt());
                    $objLineDef_New->setIdPipelineElmt($objLineElmt);
                    $em->persist($objLineDef_New);
                }
                $em->flush();
            }
        }
        $TRType = $objEquip->getItemTr();
        $TSType = $objEquip->getItemTS();
        $VCType = $objEquip->getItemVC();
        $std = $objEquip->isStd();
        $tr=[];
        $ts=[];
        $vc=[];
        $dh=[];
        $minMaxTRType=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>$TRType]);
        $objEquipGeneration=$this->getDoctrine()->getRepository(EquipGeneration::class)->findBy(['idEquip' => $objEquip->getIdEquip()]);
        $minMaxTSType=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>$TSType]);
        $MultiTSRatio = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_TRRATIO]);

        if(!$std && count($objEquipGeneration)>0){

            if($objEquip->getCapabilities() & 65536 != 0){

                // get tr
                for($i=0;$i<$objEquip->getNbTr(); $i++){

                    if ($TRType > 0 && $minMaxTRType != null) {
                        array_push($tr,$minMaxTRType[0]->getDefaultValue());
                    } else {
                        array_push($tr,0);
                    }
                }

                // get ts
                for($i=0; $i<$objEquip->getNbTs();$i++){
                    array_push($ts, $objEquipGeneration[0]->getDwellingTime());
                }
            }else{

                // get tr
                for($i=0;$i<$objEquip->getNbTr(); $i++){
                    array_push($tr, $objEquipGeneration[0]->getDwellingTime());
                }

                // get ts
                for($i=0;$i<$objEquip->getNbTs(); $i++){

                    if ($TSType > 0 && $minMaxTSType != null) {
                        array_push($ts,$minMaxTSType[0]->getDefaultValue());
                    } else {
                        array_push($ts,0);
                    }
                }

                if (count($ts) > 1) {

                    if ($MultiTSRatio != null) {
                        $ts[0] = $ts[0] * $MultiTSRatio[0]->getDefaultValue();
                    }
                }
            }
        }else{

            // get tr
            for($i=0;$i<$objEquip->getNbTr(); $i++){

                if ($TRType > 0 && $minMaxTRType != null) {
                    array_push($tr,$minMaxTRType[0]->getDefaultValue());
                } else {
                    array_push($tr,0);
                }
            }

            // get ts
            for($i=0;$i<$objEquip->getNbTs(); $i++){

                if ($TSType > 0 && $minMaxTSType != null) {
                    array_push($ts,$minMaxTSType[0]->getDefaultValue());
                } else {
                    array_push($ts,0);
                }
            }

            if (count($ts) > 1) {

                if ($MultiTSRatio != null) {
                    $ts[0] = $ts[0] * $MultiTSRatio[0]->getDefaultValue();
                }
            }
        }
       // GET VC
            $minMaxVCType=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>$VCType]);

            for($i=0;$i<$objEquip->getNbVc(); $i++){

                if ($TRType > 0 && $minMaxVCType != null) {
                    array_push($vc,$minMaxVCType[0]->getDefaultValue());
                } else {
                    array_push($vc,0);
                }
            }
            // GET DH
            $minMaxDHType=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>0]);
            
            for($i=0;$i<$objEquip->getNbTr(); $i++){

                if ($TRType > 0 && $minMaxDHType != null) {
                    array_push($dh,$minMaxDHType[0]->getDefaultValue());
                } else {
                    array_push($dh,0);
                }
            }
            // GET TEXT
            $text=null;
            
            if(count($tr)>0){
                $text=$tr[0];    
            }
            $rs = false;
            // add tr, ts, vc, dh, text to database
            $this->addDBEquipmentData($request,$eq_id, $tr,$ts,$vc,$dh,$text, $rs);
            // getEquipmentLayout create record LayoutGeneration
            $rsObjLayoutGeneration=new LayoutGeneration();
            $rsObjLayoutGeneration = $equipments->getEquipmentLayout($request,$eq_id);
            // getLayoutResults 
            $rsObjLayoutResults=new LayoutResults();
            $rsObjLayoutResults = $equipments->getLayoutResults($request,$eq_id);
            ////
            ////  runLayoutCalculator -> gọi LayoutCalculation start LCCalculation (kernel)
            ////            
            ////
            ////  kernel success call function  getEquipmentLayout()

            if (($objEquip->getCapabilities() & 2) != 0 && ($objEquip->getCapabilities() & 131072) != 0) {
                /////
                /////  runTSCalculator -> gọi LayoutCalculation start TSCalculation (kernel)
                /////
                /////                
            }
            $flagPhamCast=false;

            if (($objEquip->getCapabilities() & 1) != 0 && ($objEquip->getCapabilities() & 524288) != 0 && ($objEquip->getCapabilities() & 8) != 0) {
                /////
                ///// ** Gọi PhamCastCalculation start (kernel)
                //// Gán flag PhamCast ở trên thành true
                //// Sau khi PhamCast chạy xong, lấy giá trị TR ra và set vào Study Equipment hiện tại
                ////
                 $flagPhamCast=true;
            }

            if (!$flagPhamCast && ($objEquip->getCapabilities() & 2) != 0 && ($objEquip->getCapabilities() & 262144) != 0 && ($objEquip->getCapabilities() & 8) != 0) {
                /////
                /////  ** Gọi PhamCastCalculation start (kernel)
                //// Sau khi PhamCast chạy xong, lấy giá trị TS ra và set vào Study Equipment hiện tại
                /////
            }
            ////
            //// Gọi KernelToolsCalculation start KTCalculation
            //// Sau khi KernelToolsCalculation chạy xong, lấy giá trị TExt ra và set vào Study
            ////
            ////

        if(!$std){

            if(($objEquip->getCapabilities() & 65536) != 0){
                $session->getFlashBag()->set('WARNING', Notice::WARNING_GENERATED_EQUIPMENT_TS ." AND ".Noitce::WARNING_GENERATED_EQUIPMENT_TR);

                return $this->redirectToRoute('equipments');
            }
        }

        if(($objEquip->getCapabilities() & 8) == 0 || (($objEquip->getCapabilities() & 1) != 0 && ($objEquip->getCapabilities() & 2) != 0 && ($objEquip->getCapabilities() & 524288) == 0 && ($objEquip->getCapabilities() & 131072) == 0 && ($objEquip->getCapabilities() & 262144) == 0)){

            $session->getFlashBag()->set('WARNING', Notice::WARNING_NO_TR_EQUIPMENT);

            return $this->redirectToRoute('equipments');
        }
    }
    /**
     * @Route("/eventConfirmProdPosition", name="eventConfirmProdPosition")
     */
    public function eventConfirmProdPositionAction(Request $request, EquipmentsService $equipments){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $em=$this->getDoctrine()->getManager();
        $idStudyEquipments = $request->get('_idSE');
        $objStudyEquipments=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $idEquip=$objStudyEquipments->getIdEquip()->getIdEquip();
        $rsObjLayoutGeneration=new LayoutGeneration();
        $rsObjLayoutGeneration = $equipments->getEquipmentLayout($request,$idEquip);
        $orientation=$request->get('_orientation');
        $rsObjLayoutGeneration->setProdPosition($orientation);
        $rsObjLayoutGeneration->setWidthInterval(-1);
        $rsObjLayoutGeneration->setLengthInterval(-1);
        $em->flush();
        
        return $this->redirectToRoute('equipments');
    }

    /**
     * @Route("/eventCancelProdPosition", name="eventCancelProdPosition")
     */
    public function eventCancelProdPositionAction(Request $request, EquipmentsService $equipments)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idStudyEquipments = $request->get('_idSE');
        $objStudyEquipments=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $idEquip=$objStudyEquipments->getIdEquip()->getIdEquip();
        
        if(($objStudyEquipments->getIdEquip()->getCapabilities() & 8192) != 0 ){
            $rsObjLayoutGeneration=new LayoutGeneration();
            $rsObjLayoutGeneration = $equipments->getEquipmentLayout($request,$idEquip);

            $rsObjLayoutResults = new LayoutResults();
            $rsObjLayoutResults = $equipments->getLayoutResultsFirst($request,$idEquip);
            //// * Nếu oldlr LoadingRate != newlr LoadingRate || oldlr LoadingRateMax != newlr LoadingRateMax || oldlr NbShelves != newlr NbShelves || oldlr NbShelvesMax != newlr NbShelvesMax || (oldlg ProdPosition != newlg ProdPosition
            //// Reset lại các giá trị cho EquipmentBean và reset xuống database
            //// ebean.updateLayoutGeneration(oldlg); //Update oldlg xuống bảng LayoutGeneration
            //// ebean.updateLayoutResults(oldlr); //Update oldlr xuống bảng LayoutResults
            //// top.setSequip(sequip);
            ////
        }     
       
        return $this->redirectToRoute('equipments');
    }

    private function getLayoutResultsFirst(Request $request, $idEquip, &$rsObjLayoutResults)
    {
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        $em=$this->getDoctrine()->getManager();
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
        $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->find($idEquip);
        $objStudyEquip=$this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$objStudy->getIdStudy(), 'idEquip'=>$objEquip->getIdEquip()],['idStudyEquipments'=>'DESC']);
        $objLayoutRs=$this->getDoctrine()->getRepository(LayoutResults::class)->findBy(['idStudyEquipments'=>$objStudyEquip[0]->getIdStudyEquipments()]);

        if(count($objLayoutRs)>0){
            $idLayoutRs=$objLayoutRs[0];
        }else{
            $idLayoutRs=null;
        }
        $rsObjLayoutResults=$idLayoutRs;
    }

    /**
     * @Route("/frameInputConveyor", name="frameInputConveyor")
     */
    public function eventFrameInputConveyorAction(Request $request, EquipmentsService $equipments)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $session->set('layoutGenerated', false);
        $em=$this->getDoctrine()->getManager();
        $idStudyEquipments = $request->get('idSE');
        $objStudyEquipments=$this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);
        $idEquip=$objStudyEquipments->getIdEquip()->getIdEquip();
        $rsObjLayoutGeneration=new LayoutGeneration();
        $rsObjLayoutGeneration = $equipments->getEquipmentLayout($request,$idEquip);
        $rsObjLayoutResults=new LayoutResults();
        $this->getLayoutResultsFirst($request,$idEquip,$rsObjLayoutResults);
        $mmShelvesLength=[
            'minShelvesLength'=> 0,
            'maxShelvesLength'=>-1
        ];
        $mmShelvesWidth=[
            'minShelvesWidth'=> 0,
            'maxShelvesWidth'=>-1
        ];
        $mmShelvesNb=[
            'minShelvesNb'=> 0,
            'maxShelvesNb'=>-1
        ];
        // chech BATCH 
        $checkDisplayShelves = 0;

        if($objStudyEquipments->getIdEquip()->getIdEquipseries()->getIdFamily()->isBatchProcess()){
            $objShelvesLength = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_LENGTH_SHELVES]);
            $objShelvesWidth = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_WIDTH_SHELVES]);
            $objShelvesNb = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_NB_SHELVES]);
            $mmShelvesLength=[
                'minShelvesLength'=> $objShelvesLength[0]->getLimitMin(),
                'maxShelvesLength'=> $objShelvesLength[0]->getLimitMax()
            ];
            $mmShelvesWidth=[
                'minShelvesWidth'=> $objShelvesWidth->getLimitMin(),
                'maxShelvesWidth'=> $objShelvesWidth->getLimitMax()
            ];
            $mmShelvesNb=[
                'minShelvesNb'=> $objShelvesNb->getLimitMin(),
                'maxShelvesNb'=> $objShelvesNb->getLimitMax()
            ];
            $checkDisplayShelves = 1;
        }
        $lengthDisabled = null;
        $shelvesSelectDisabled = null;
        $idShape = $session->get('idShape');

        if($idShape == 1){
            $lengthDisabled = 'disabled';
        }

        if($objStudyEquipments->getStdeqpWidth() != -1 && $objStudyEquipments->getStdeqpLength() != -1){
            $shelvesSelectDisabled = 'disabled';
        }
        $checkCalculateConveyor=0;
        $checkDisplayImage = 0;
        $objOutPut = [
            'LeftRightInterval' => '',
            'NumberPerM'=> '',
            'NumberInWidth'=> '',
            'QuantityPerBatch'=> ''
        ];

        if(($objStudyEquipments->getIdEquip()->getCapabilities() & 8192) != 0 ){
            $checkCalculateConveyor = 1;
            $intervalsLength = $rsObjLayoutGeneration->getLengthInterval();
            $intervalsWidth = $rsObjLayoutGeneration->getWidthInterval();

            if($intervalsLength != -1 || $intervalsWidth != -1){
            //// chuyển giá trị sang đơn vị người dùng rồi hiển thị lên giao diện.
            ////
            }else{
                $intervalsLength = '???';
                $intervalsWidth = '???';
            }

            //// - Show SVG image
            if($rsObjLayoutResults != null && $intervalsLength != -1){
                $checkDisplayImage = 1;
            }

            if($intervalsLength != -2 && $intervalsWidth != -2){
                //// generateSVG (đọc hàm generateSVG(…) trong file TopBean.java) và lưu hình đó ra dưới dạng ảnh PNG rồi load lên
                ////
                ////
            }
            // - Show frame OUTPUT
            $massSymbol="g";

            if($rsObjLayoutResults != null){
                $LeftRightInterval = $rsObjLayoutResults->getLeftRightInterval();
                //// dùng hàm prodDimension để convert trước khi hiển thị)
                ////
                $NumberPerM = $rsObjLayoutResults->getNumberPerM();
                //// (dùng hàm none để convert trước khi hiển thị)
                ////
                $NumberInWidth = $rsObjLayoutResults->getNumberInWidth();
                //// (dùng hàm none để convert trước khi hiển thị)
                ////

                if($checkDisplayShelves == 1){
                    $QuantityPerBatch = $rsObjLayoutResults->getQuantityPerBatch(). ' '.$massSymbol.' /batch';
                }else{
                    $QuantityPerBatch = $rsObjLayoutResults->getLoadingRate();
                    //// (dùng hàm toc để convert trước khi hiển thị)
                    ////
                }
                $objOutPut = [
                    'LeftRightInterval' => $LeftRightInterval ? $LeftRightInterval : 0,
                    'NumberPerM'=> $NumberPerM ? $NumberPerM : 0,
                    'NumberInWidth'=> $NumberInWidth ? $NumberInWidth : 0,
                    'QuantityPerBatch'=> $QuantityPerBatch ? $QuantityPerBatch : 0
                ];
            }
        }
        $BeltCoverage = 0;

        if($rsObjLayoutResults != null){
            $BeltCoverage = $rsObjLayoutResults->getLoadingRate();
            //// dùng hàm toc để convert trước khi show lên giao diện
            ////
        }
        // get shelvesLength + shelvesWidth
        $shelvesValaue =[
            'shelvesLength'=> $rsObjLayoutGeneration->getShelvesLength(),
            'shelvesWidth'=> $rsObjLayoutGeneration->getShelvesWidth(),
        ];
        $session->set('showDims', false);
        $session->set('flag', false);
        $session->set('checkCalculateConveyor', $checkCalculateConveyor);
        $rs=[
            'mmShelvesLength'=> $mmShelvesLength,
            'mmShelvesWidth'=> $mmShelvesWidth,
            'mmShelvesNb'=> $mmShelvesNb,
            'checkCalculateConveyor'=> $checkCalculateConveyor,
            'lengthDisabled'=> $lengthDisabled,
            'shelvesSelectDisabled'=> $shelvesSelectDisabled,
            'intervalsLength'=> $intervalsLength,
            'intervalsWidth'=> $intervalsWidth,
            'shelvesValaue'=> $shelvesValaue,
            'checkDisplayShelves'=> $checkDisplayShelves,
            'dataOutput'=> $objOutPut,
            'beltCoverage'=>$BeltCoverage,
            'idShape'=> $idShape
        ];

        return new JsonResponse($rs);
    }

     /**
     * @Route("/calculateconveyor", name="calculateconveyor")
     */
    public function calculateConveyorAction(Request $request, EquipmentsService $equipments)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idStudyEquipments = $request->get('_idSE');
        $intervalLength = $request->get('_intervalslength');
        $intervalWidth = $request->get('_intervalswidth');
        $orientation = $request->get('_orientation');
        $lengthShelves = $request->get('_lengthshelves');
        $widthShelves = $request->get('_widthshelves');
        $numberofShelves = $request->get('_numberofshelves');
        $equipments->generateLayouts($request, $idStudyEquipments, $intervalLength, $intervalWidth, $orientation, $lengthShelves, $widthShelves, $numberofShelves);

        return new JsonResponse(['status' => 1]);
    }

     /**
     * @Route("/saveTop", name="saveTop")
     */
    public function saveTopAction(Request $request, EquipmentsService $equipments)
    {
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idStudyEquipments = $request->get('_idSE');
        $intervalLength = $request->get('_intervalslength');
        $intervalWidth = $request->get('_intervalswidth');
        $orientation = $request->get('_orientation');
        $lengthShelves = $request->get('_lengthshelves');
        $widthShelves = $request->get('_widthshelves');
        $numberofShelves = $request->get('_numberofshelves');
        $toc = $request->get('_input-belt-converage');
        $shelvesType = $request->get('_shelves');
        $objStudyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->find($idStudyEquipments);

        if(($objStudyEquipments->getIdEquip()->getCapabilities() & 8192) != 0){
            $equipments->saveTop1($request, $idStudyEquipments, $toc, $orientation);
        }else{
            $equipments->saveTop2($request, $idStudyEquipments, $orientation, $shelvesType, $lengthShelves, $widthShelves, $numberofShelves, $toc);
        }

        return $this->redirectToRoute('equipments');
    }

    /**
     * @Route("/showDims", name="showDims")
     */
    public function showDimsAction(Request $request)
    {
        $session = $request->getSession();
        $session->set('showDims', true);

        return new JsonResponse([]);
    }

    /**
     * @Route("/UserTocManuel", name="UserTocManuel")
     */
    public function userTocManuelAction(Request $request)
    {
        $session = $request->getSession();
        $session->set('flag', true);

        return new JsonResponse([]);
    }
}
