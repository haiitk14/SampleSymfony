<?php

namespace AppBundle\Controller\pipeLine;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Studies;
use AppBundle\Entity\LineElmt;
use AppBundle\Entity\Translation;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\Equipment;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\MinMax;
use AppBundle\Entity\Post;
use AppBundle\Entity\CoolingFamily;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\LineDefinition;
use AppBundle\Entity\PipeGen;

class lineController extends Controller
{
  /**
   * @Route("/line", name="lines")
   */
  public function listAction(Request $request)
  {
      $session = $request->getSession();
      $em=$this->getDoctrine();
      $user=$this->getUser();
      if ($user== NULL) {
        return $this->redirectToRoute('login');
      }
      $idStudy=$session->get('idStudy');
      if ($idStudy == null || $idStudy==0 || $idStudy == "") {
        return $this->redirectToRoute('load-study');
      }
      $idEquip=$session->get('idEquip');
      $nameStudy= $this->getDoctrine()->getRepository(Studies::class)->findBy(['idStudy'=>$idStudy]);
      $idStudyEquipment= $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$idStudy]);

      // get ID CoolingFamily
      if ($idStudyEquipment == null) {
        return $this->redirectToRoute('load-study');
      }
      $idEquip=$idStudyEquipment[0]->getIdEquip();
      $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->findBy(['idEquip'=>$idEquip]);
      $idCooling=$objEquip[0]->getIdCoolingFamily()->getIdCoolingFamily();
      // dump($idCooling);die();

      $objPipegen=$this->getDoctrine()->getRepository(PipeGen::class)->findBy(['idPipeGen'=>$idStudyEquipment[0]->getIdPipeGen()]);
      // dump($objPipegen);die();
      $qa12=$this->getDoctrine()->getRepository(LineDefinition::class)->findBy(['idPipeGen'=>$objPipegen[0]->getIdPipeGen()]);
      $idLineElmt_definition=$this->getDoctrine()->getRepository(LineDefinition::class)->findBy(['idPipelineElmt'=>$qa12[0]->getIdPipelineElmt()]);
      //show data insType with data first of LineDefinition
      $idLineElmt_insType = $this->getDoctrine()->getRepository(LineElmt::class)
      ->createQueryBuilder('t')
      ->select( 't.insulationType')
      ->where( 't.idPipelineElmt = :idPipelineElmt' )
      ->setParameter( 'idPipelineElmt', $idLineElmt_definition[0]->getIdPipelineElmt() )
      ->getQuery()
      ->getResult();
      // dump($idLineElmt_definition);die();
      //Displays the data diameter with data first of LineDefinition
      $idLineElmt_diameter = $this->getDoctrine()->getRepository(LineElmt::class)
      ->createQueryBuilder('t')
      ->select( 't.eltSize')
      ->where( 't.idPipelineElmt = :idPipelineElmt' )
      ->setParameter( 'idPipelineElmt', $idLineElmt_definition[0]->getIdPipelineElmt() )
      ->andWhere( 't.eltType <> :type' )
      ->setParameter( 'type', 2 )
      ->getQuery()
      ->getResult();

      //show name of seven select option
      $idLineElmt_diameter_value = count($idLineElmt_diameter) ? $idLineElmt_diameter[0]['eltSize'] : 0;
      $versionValue = $this->callSixSelect($idLineElmt_insType[0]['insulationType'], 5, $idLineElmt_diameter_value, $idCooling);
      $versionTee = $this->callSixSelect($idLineElmt_insType[0]['insulationType'], 3, $idLineElmt_diameter_value, $idCooling);
      $versionLine = $this->callSixSelect($idLineElmt_insType[0]['insulationType'], 1, $idLineElmt_diameter_value, $idCooling);
      $versionElbows = $this->callSixSelect($idLineElmt_insType[0]['insulationType'], 4, $idLineElmt_diameter_value, $idCooling);
      $versionNonInsLine = $this->callSixSelect(0, 1, $idLineElmt_diameter_value, $idCooling);
      $versionNonInsValue = $this->callSixSelect(0, 5, $idLineElmt_diameter_value, $idCooling);
      $versionTank = $this->callStorageTank($idLineElmt_insType[0]['insulationType'],$idCooling);

      //validate selected of Tank select option
      $whereTankSelected = [
          "idPipeGen" => $objPipegen,
          "typeElmt" => 7
      ];
      $versionTankSelected = $this->getDoctrine()->getRepository(LineDefinition::class)->findOneBy($whereTankSelected);
    
      $versionTankSelectedId = $versionTankSelected->getIdPipelineElmt()->getIdPipelineElmt();

      //validate selected of six select option
      $sixLine= $this->sixSelect($objPipegen, 1);
      $sixNonInsLine= $this->sixSelect($objPipegen, 2);
      $sixElbow= $this->sixSelect($objPipegen, 3);
      $sixTee= $this->sixSelect($objPipegen, 4);
      $sixInsValue= $this->sixSelect($objPipegen, 5);
      $sixNonInsValue= $this->sixSelect($objPipegen, 6);

      //Display Inside Diameter when Insulation Type has data
      $diameter =  $this->getDoctrine()->getRepository(LineElmt::class)->createQueryBuilder('le')
        ->select('DISTINCT le.eltSize, le.insulationType')
        ->where('le.insulationType = :type')
        ->setParameter('type', 1)
        ->andWhere('le.eltType <> :elt')
        ->setParameter('elt', 2)
        ->andWhere('le.idCoolingFamily = :cool')
        ->setParameter('cool', $idCooling)// dien bien nguoi dung chon
        ->getQuery()
        ->getResult();

    return $this->render('pipeLine/line.html.twig', [
      'studyName' =>$nameStudy[0]->getStudyName(),
      'diameter' =>  $diameter,
      'idLineElmt_insTypes' => $idLineElmt_insType[0]['insulationType'],
      'idLineElmt_diameter' => count($idLineElmt_diameter) ? $idLineElmt_diameter[0]['eltSize'] : 0,
      'idCoolingFamily' => $idCooling,
      'pipeGen' => $objPipegen[0],
      'versionValue' => $versionValue,
      'versionTee' => $versionTee,
      'versionLine' => $versionLine,
      'versionElbows' => $versionElbows,
      'versionNonInsLine' => $versionNonInsLine,
      'versionNonInsValue' => $versionNonInsValue,
      'versionTank' => $versionTank,
      'sixInsline' => count($sixLine) > 0 ? $sixLine : null,
      'sixNonInsline' => count($sixNonInsLine) > 0 ? $sixNonInsLine : null ,
      'sixElbows' =>  count($sixElbow) > 0 ? $sixElbow : null ,
      'sixTees' =>  count($sixTee) > 0 ? $sixTee : null ,
      'sixInsValue' =>  count($sixInsValue) > 0 ? $sixInsValue : null ,
      'sixNonInsValue' =>  count($sixNonInsValue) > 0 ? $sixNonInsValue : null ,
      'versionTankSelectedId' => $versionTankSelectedId,
    ]);
  }

    /**
     * @Route("/createLine", name="createLines")
     */
    public function createAction(Request $request){
      $session = $request->getSession();
      $em=$this->getDoctrine()->getManager();
      $idStudy=$session->get('idStudy');
      $idStudyEquipment= $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$idStudy]);
      $idEquip=$idStudyEquipment[0]->getIdEquip();
      $objLineDefinition=$this->getDoctrine()->getRepository(LineDefinition::class)->findAll();
      $objPipegen1=$this->getDoctrine()->getRepository(PipeGen::class)->find($idStudyEquipment[0]->getIdPipeGen());
      $objPipegen=$this->getDoctrine()->getRepository(PipeGen::class)->findBy(['idPipeGen'=>$idStudyEquipment[0]->getIdPipeGen()]);
      $objPipegen2=$this->getDoctrine()->getRepository(LineDefinition::class)->findBy(['idPipeGen'=>$objPipegen]);
      $typeElmt_Definition = $this->getDoctrine()->getRepository(LineDefinition::class)
      ->createQueryBuilder('l')->select( 'l' )
      ->select('(l.idPipelineElmt)', '(l.typeElmt)')
      ->where( 'l.idPipeGen = :idPipeGen' )
      ->setParameter( 'idPipeGen', $objPipegen[0]->getIdPipeGen() )
      ->orderBy('l.typeElmt',"ASC")
      ->getQuery()
      ->getResult();

      if($objPipegen2 != null){
        //create Line Definition
        $all_items = array(1 => 'insulatedLine', 2 => 'nonInsulatedLine', 5 => 'insulatedValves', 6 => 'nonInsulatedValves', 3 => 'elbows', 4 => 'tee', 7 => 'storageTankName');
        foreach($all_items as $k=>$v){
          //build lineElm
          $insulatedLine_ID = $request->get($v);
          if ($insulatedLine_ID < 1) continue;
          $lineElmt = $this->getDoctrine()->getRepository(LineElmt::class)->find($insulatedLine_ID);
          //build gen
          $gen = $this->getDoctrine()->getRepository(PipeGen::class)->find($objPipegen1);
          $updateDefinition = $this->getDoctrine()->getRepository(LineDefinition::class)->findBy(['idPipeGen'=>$objPipegen1]);
          for ($i=0; $i < count($updateDefinition) ; $i++) {
            $em->remove($updateDefinition[$i]);
          }
          $em->flush();
          $all_items = array(1 => 'insulatedLine', 2 => 'nonInsulatedLine', 5 => 'insulatedValves', 6 => 'nonInsulatedValves', 3 => 'elbows', 4 => 'tee', 7 => 'storageTankName');
          foreach($all_items as $k=>$v){
            //build lineElm
            $insulatedLine_ID = $request->get($v);
            if ($insulatedLine_ID < 1) continue;
            $lineElmt = $this->getDoctrine()->getRepository(LineElmt::class)->find($insulatedLine_ID);
            //build gen
            $gen = $this->getDoctrine()->getRepository(PipeGen::class)->find($objPipegen1);
            $createLineDefinition = new LineDefinition();
            $createLineDefinition->setIdPipeGen($gen);
            $createLineDefinition->setIdPipelineElmt($lineElmt);
            $createLineDefinition->setTypeElmt($k);
            $em->persist($createLineDefinition);
            $em->flush();
          }
        }
      }else {
         //create Line Definition
         $all_items = array(1 => 'insulatedLine', 2 => 'nonInsulatedLine', 5 => 'insulatedValves', 6 => 'nonInsulatedValves', 3 => 'elbows', 4 => 'tee', 7 => 'storageTankName');
         foreach($all_items as $k=>$v){
           //build lineElm
           $insulatedLine_ID = $request->get($v);
           if ($insulatedLine_ID < 1) continue;
           $lineElmt = $this->getDoctrine()->getRepository(LineElmt::class)->find($insulatedLine_ID);
           //build gen
           $gen = $this->getDoctrine()->getRepository(PipeGen::class)->find($objPipegen1);
           $createLineDefinition = new LineDefinition();
           $createLineDefinition->setIdPipeGen($gen);
           $createLineDefinition->setIdPipelineElmt($lineElmt);
           $createLineDefinition->setTypeElmt($k);
           $em->persist($createLineDefinition);
           $em->flush();
         }
      }
      if (count($objPipegen) != 0 ) {
      $piegen = $em->find(PipeGen::class, $objPipegen[0]->getIdPipeGen());
      $piegen->setInsullineLenght($request->get('_LINE_LENGTH'));
      $piegen->setInsulValves($request->get('_LINE_VALUE_LENGTH'));
      $piegen->setTees($request->get('_LINE_TEE'));
      $piegen->setElbows($request->get('_LINE_ELBOW'));
      $piegen->setNoinsullineLenght($request->get('_LINE_NON_LENGTH'));
      $piegen->setNoinsulValves($request->get('_LINE_NON_VALUE_LENGTH'));
      $piegen->setHeight($request->get('_LINE_TANK_OUTLET'));
      $piegen->setPressure($request->get('_LINE_TANK_PRESSURE'));
      $piegen->setMathigher(0);
      $piegen->setGasTemp(-40);
      $piegen->setFluid(0); // chua chac chan
      $em->flush();
      }else{
        // die('create');
        $createLine = new PipeGen();
        $createLine->setInsullineLenght($request->get('_LINE_LENGTH'));
        $createLine->setInsulValves($request->get('_LINE_VALUE_LENGTH'));
        $createLine->setTees($request->get('_LINE_TEE'));
        $createLine->setElbows($request->get('_LINE_ELBOW'));
        $createLine->setNoinsullineLenght($request->get('_LINE_NON_LENGTH'));
        $createLine->setNoinsulValves($request->get('_LINE_NON_VALUE_LENGTH'));
        $createLine->setHeight($request->get('_LINE_TANK_OUTLET'));
        $createLine->setPressure($request->get('_LINE_TANK_PRESSURE'));
        $createLine->setMathigher(0);
        $createLine->setGasTemp(-40);
        $createLine->setFluid(0);
        $createLine->setIdStudyEquipments($idStudyEquipment[0]);
       $em->persist($createLine);
       $em->flush();
      }
      $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->findBy(['idEquip'=>$idEquip]);
      return $this->redirectToRoute('lines');
    }

  /**
   * @Route("/getAllMinMaxLine", name="getAllMinMaxLine")
   */
  public function getAllMinMaxLineAction(){
      $user=$this->getUser();
          if($user== NULL){
              return $this->redirectToRoute('login');
          }
      $doc=$this->getDoctrine();
      $LINE_INSULATEDLINE=$doc->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_STUDY_LINE_INSULATEDLINE_LENGHT]);
      $LINE_NON_INSULATEDLINE=$doc->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_STUDY_LINE_NON_INSULATEDLINE_LENGHT]);
      $LINE_ELBOWS=$doc->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_STUDY_LINE_ELBOWS_NUMBER]);
      $LINE_TEES=$doc->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_STUDY_LINE_TEES_NUMBER]);
      $LINE_INSULATED_VALVE=$doc->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_STUDY_LINE_INSULATEDVALVE_NUMBER]);
      $LINE_NON_INSULATED_VALVE=$doc->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_STUDY_LINE_NON_INSULATEDVALVE_NUMBER]);
      $LINE_TANK_PRESSURE=$doc->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_STUDY_LINE_PRESSURE]);
      $LINE_TANK_OUTLET=$doc->getRepository(MinMax::class)->findBy(['limitItem'=>Post::MIN_MAX_STUDY_LINE_HEIGHT]);
      $ret =[
             'LI_MIN' => $LINE_INSULATEDLINE[0]->getLimitMin(),
             'LI_MAX' => $LINE_INSULATEDLINE[0]->getLimitMax(),
             'LNI_MIN' => $LINE_NON_INSULATEDLINE[0]->getLimitMin(),
             'LNI_MAX' => $LINE_NON_INSULATEDLINE[0]->getLimitMax(),
             'LE_MIN' => $LINE_ELBOWS[0]->getLimitMin(),
             'LE_MAX' => $LINE_ELBOWS[0]->getLimitMax(),
             'LT_MIN' => $LINE_TEES[0]->getLimitMin(),
             'LT_MAX' => $LINE_TEES[0]->getLimitMax(),
             'LIV_MIN' => $LINE_INSULATED_VALVE[0]->getLimitMin(),
             'LIV_MAX' => $LINE_INSULATED_VALVE[0]->getLimitMax(),
             'LNIV_MIN' => $LINE_NON_INSULATED_VALVE[0]->getLimitMin(),
             'LNIV_MAX' => $LINE_NON_INSULATED_VALVE[0]->getLimitMax(),
             'LTP_MIN' => $LINE_TANK_PRESSURE[0]->getLimitMin(),
             'LTP_MAX' => $LINE_TANK_PRESSURE[0]->getLimitMax(),
             'LTO_MIN' => $LINE_TANK_OUTLET[0]->getLimitMin(),
             'LTO_MAX' => $LINE_TANK_OUTLET[0]->getLimitMax(),
          ];

      return new JsonResponse($ret);
      }
      /**
       * @Route("/insType", name="insTypeLine")
       */
      public function diamiterAction(Request $request){
        $user=$this->getUser();
        if($user== NULL){
            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        $idEquip=$session->get('idEquip');
        $idStudyEquipment= $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$idStudy]);
        // get ID CoolingFamily
        $idEquip=$idStudyEquipment[0]->getIdEquip();
        $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->findBy(['idEquip'=>$idEquip]);
        $idCooling=$objEquip[0]->getIdCoolingFamily()->getIdCoolingFamily();
        $idinsType = $request->get('idfam');

        $diameter =  $this->getDoctrine()->getRepository(LineElmt::class)->createQueryBuilder('le')
          ->select('DISTINCT le.eltSize')
          ->where('le.insulationType = :type')
          ->setParameter('type', $idinsType)
          ->andWhere('le.eltType <> :elt')
          ->setParameter('elt', 2) // dien bien nguoi dung chon
          ->andWhere('le.idCoolingFamily = :cool')
          ->setParameter('cool', $idCooling)// dien bien nguoi dung chon
          ->getQuery()
          ->getResult();

          $arrType=[];
          for ($i=0; $i<count($diameter);$i++){
              $ret =[
                  'eltSize' => $diameter[$i]['eltSize']
              ];
              array_push($arrType,$ret);
          }

          $rs=[
            'listEltSize' => $arrType
          ];

          return new JsonResponse($rs);
        // }
      }

      /**
       * @Route("/insLine", name="insLine_eltType1")
       */
      public function insLineAction(Request $request){
        $user=$this->getUser();
        if($user== NULL){
            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        $idEquip=$session->get('idEquip');
        $idStudyEquipment= $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$idStudy]);
        // get ID CoolingFamily
        $idEquip=$idStudyEquipment[0]->getIdEquip();
        $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->findBy(['idEquip'=>$idEquip]);
        $idCooling=$objEquip[0]->getIdCoolingFamily()->getIdCoolingFamily();
        $diameter = $request->get('idInsLine');
        $idinsType = $request->get('idfam');
        $whereInsLine = [
            "insulationType" => $idinsType,
            "eltType" => 1,
            "eltSize" => $diameter,
            "idCoolingFamily" => $idCooling
        ];
        $insLine = $this->getDoctrine()->getRepository(LineElmt::class)->findBy($whereInsLine);
          $arrInsLine=[];
          foreach($insLine as $row){
            $whereNameLine = [
                "transType" => Post::TRANSTYPE_COMMENT_LINE_ELMT,
                "idTranslation" => $row->getIdPipelineElmt(),
                "codeLangue" => $user->getCodeLangue()
            ];
            $nameLine = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereNameLine);
            $whereActiveLine = [
                "transType" => Post::TRANSTYPE_ACTIVE,
                "idTranslation" => $row->getLineRelease(),
                "codeLangue" => $user->getCodeLangue()
            ];
            $active = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereActiveLine);
              $ret =[
                'name' =>  $nameLine->getLabel(),
                'lineVersion' => $row->getLineVersion(),
                'idPipelineElmt' => $row->getIdPipelineElmt(),
                'active' => $active->getLabel(),
              ];
              array_push($arrInsLine,$ret);
          }

          $rs=[
            'listInsLine' => $arrInsLine
          ];

          return new JsonResponse($rs);
        }

        /**
         * @Route("/insValues", name="insSize_eltType5")
         */
        public function insValuesAction(Request $request){
          $user=$this->getUser();
          if($user== NULL){
              return $this->redirectToRoute('login');
          }
          $session = $request->getSession();
          $idStudy=$session->get('idStudy');
          $idEquip=$session->get('idEquip');
          $idStudyEquipment= $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$idStudy]);
          // get ID CoolingFamily
          $idEquip=$idStudyEquipment[0]->getIdEquip();
          $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->findBy(['idEquip'=>$idEquip]);
          $idCooling=$objEquip[0]->getIdCoolingFamily()->getIdCoolingFamily();

            $diameter = $request->get('idInsValues');
            $idinsType = $request->get('idfam');
            $whereInsValues = [
                "insulationType" => $idinsType,
                "eltType" => 5,
                "eltSize" => $diameter,
                "idCoolingFamily" => $idCooling
            ];
            $insValues = $this->getDoctrine()->getRepository(LineElmt::class)->findBy($whereInsValues);



            $arrInsValues=[];
            foreach($insValues as $row){
              $whereNameLine = [
                  "transType" => Post::TRANSTYPE_COMMENT_LINE_ELMT,
                  "idTranslation" => $row->getIdPipelineElmt(),
                  "codeLangue" => $user->getCodeLangue()
              ];
              $nameLine = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereNameLine);
              $whereActiveLine = [
                  "transType" => Post::TRANSTYPE_ACTIVE,
                  "idTranslation" => $row->getLineRelease(),
                  "codeLangue" => $user->getCodeLangue()
              ];
              $active = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereActiveLine);
                $ret =[
                  'name' =>  $nameLine->getLabel(),
                  'lineVersion' => $row->getLineVersion(),
                  'idPipelineElmt' => $row->getIdPipelineElmt(),
                  'active' => $active->getLabel(),
                ];
                array_push($arrInsValues,$ret);
            }
            $rs=[
              'listInsValues' => $arrInsValues
            ];
            return new JsonResponse($rs);
          }


          /**
           * @Route("/tees", name="tees_eltType3")
           */
          public function teesAction(Request $request){
            $user=$this->getUser();
            if($user== NULL){
                return $this->redirectToRoute('login');
            }
            $session = $request->getSession();
            $idStudy=$session->get('idStudy');
            $idEquip=$session->get('idEquip');
            $idStudyEquipment= $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$idStudy]);
            // get ID CoolingFamily
            $idEquip=$idStudyEquipment[0]->getIdEquip();
            $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->findBy(['idEquip'=>$idEquip]);
            $idCooling=$objEquip[0]->getIdCoolingFamily()->getIdCoolingFamily();

              $diameter = $request->get('idTees');
              $idinsType = $request->get('idfam');

              $whereTees = [
                  "insulationType" => $idinsType,
                  "eltType" => 3,
                  "eltSize" => $diameter,
                  "idCoolingFamily" => $idCooling
              ];
              $tees = $this->getDoctrine()->getRepository(LineElmt::class)->findBy($whereTees);

              $arrTees=[];
              foreach($tees as $row){
                $whereNameLine = [
                    "transType" => Post::TRANSTYPE_COMMENT_LINE_ELMT,
                    "idTranslation" => $row->getIdPipelineElmt(),
                    "codeLangue" => $user->getCodeLangue()
                ];
                $nameLine = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereNameLine);
                $whereActiveLine = [
                    "transType" => Post::TRANSTYPE_ACTIVE,
                    "idTranslation" => $row->getLineRelease(),
                    "codeLangue" => $user->getCodeLangue()
                ];
                $active = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereActiveLine);
                  $ret =[
                    'name' =>  $nameLine->getLabel(),
                    'lineVersion' => $row->getLineVersion(),
                    'idPipelineElmt' => $row->getIdPipelineElmt(),
                    'active' => $active->getLabel(),
                  ];
                  array_push($arrTees,$ret);
              }
              $rs=[
                'ListTees' => $arrTees
              ];
              return new JsonResponse($rs);
            }
            /**
             * @Route("/elbows", name="elbows_eltType4")
             */
            public function elbowsAction(Request $request){
              $user=$this->getUser();
              if($user== NULL){
                  return $this->redirectToRoute('login');
              }
              $session = $request->getSession();
              $idStudy=$session->get('idStudy');
              $idEquip=$session->get('idEquip');
              $idStudyEquipment= $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$idStudy]);
              // get ID CoolingFamily
              $idEquip=$idStudyEquipment[0]->getIdEquip();
              $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->findBy(['idEquip'=>$idEquip]);
              $idCooling=$objEquip[0]->getIdCoolingFamily()->getIdCoolingFamily();

                $diameter = $request->get('idElbows');
                $idinsType = $request->get('idfam');
                $whereElbows = [
                    "insulationType" => $idinsType,
                    "eltType" => 4,
                    "eltSize" => $diameter,
                    "idCoolingFamily" => $idCooling
                ];
                $elbows = $this->getDoctrine()->getRepository(LineElmt::class)->findBy($whereElbows);
                $arrElbows=[];
                foreach($elbows as $row){
                  $whereNameLine = [
                      "transType" => Post::TRANSTYPE_COMMENT_LINE_ELMT,
                      "idTranslation" => $row->getIdPipelineElmt(),
                      "codeLangue" => $user->getCodeLangue()
                  ];
                  $nameLine = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereNameLine);
                  $whereActiveLine = [
                      "transType" => Post::TRANSTYPE_ACTIVE,
                      "idTranslation" => $row->getLineRelease(),
                      "codeLangue" => $user->getCodeLangue()
                  ];
                  $active = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereActiveLine);
                    $ret =[
                      'name' =>  $nameLine->getLabel(),
                      'lineVersion' => $row->getLineVersion(),
                      'idPipelineElmt' => $row->getIdPipelineElmt(),
                      'active' => $active->getLabel(),
                    ];
                    array_push($arrElbows,$ret);
                }
                $rs=[
                  'listElbows' => $arrElbows
                ];
                return new JsonResponse($rs);
              }

              /**
               * @Route("/nonInsLine", name="nonInsLine_eltType1")
               */
              public function nonInsLineAction(Request $request){
                $user=$this->getUser();
                if($user== NULL){
                    return $this->redirectToRoute('login');
                }
                $session = $request->getSession();
                $idStudy=$session->get('idStudy');
                $idEquip=$session->get('idEquip');
                $idStudyEquipment= $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$idStudy]);
                // get ID CoolingFamily
                $idEquip=$idStudyEquipment[0]->getIdEquip();
                $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->findBy(['idEquip'=>$idEquip]);
                $idCooling=$objEquip[0]->getIdCoolingFamily()->getIdCoolingFamily();

                  $diameter = $request->get('idNonInsLine');
                  $whereNonInsLine = [
                      "insulationType" => 0,
                      "eltType" => 1,
                      "eltSize" => $diameter,
                      "idCoolingFamily" => $idCooling
                  ];
                  $nonInsLine = $this->getDoctrine()->getRepository(LineElmt::class)->findBy($whereNonInsLine);

                  $arrNonInsLine=[];
                  foreach($nonInsLine as $row){
                    $whereNameLine = [
                        "transType" => Post::TRANSTYPE_COMMENT_LINE_ELMT,
                        "idTranslation" => $row->getIdPipelineElmt(),
                        "codeLangue" => $user->getCodeLangue()
                    ];
                    $nameLine = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereNameLine);

                    $whereActiveLine = [
                        "transType" => Post::TRANSTYPE_ACTIVE,
                        "idTranslation" => $row->getLineRelease(),
                        "codeLangue" => $user->getCodeLangue()
                    ];
                    $active = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereActiveLine);

                      $ret =[
                        'name' =>  $nameLine->getLabel(),
                        'lineVersion' => $row->getLineVersion(),
                        'idPipelineElmt' => $row->getIdPipelineElmt(),
                        'active' => $active->getLabel(),
                      ];
                      array_push($arrNonInsLine,$ret);
                  }
                  $rs=[
                    'listNonInsLine' => $arrNonInsLine
                  ];
                  return new JsonResponse($rs);
                }

                /**
                 * @Route("/nonInsValues", name="nonInsValues_eltType6")
                 */
                public function nonInsValuesAction(Request $request){
                  $user=$this->getUser();
                  if($user== NULL){
                      return $this->redirectToRoute('login');
                  }
                  $session = $request->getSession();
                  $idStudy=$session->get('idStudy');
                  $idEquip=$session->get('idEquip');
                  $idStudyEquipment= $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$idStudy]);
                  // get ID CoolingFamily
                  $idEquip=$idStudyEquipment[0]->getIdEquip();
                  $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->findBy(['idEquip'=>$idEquip]);
                  $idCooling=$objEquip[0]->getIdCoolingFamily()->getIdCoolingFamily();
                  $diameter = $request->get('idNonInsValues');
                  $whereNonInsValues = [
                      "insulationType" => 0,
                      "eltType" => 5,
                      "eltSize" => $diameter,
                      "idCoolingFamily" => $idCooling
                  ];
                  $nonInsValues = $this->getDoctrine()->getRepository(LineElmt::class)->findBy($whereNonInsValues);
                  $arrNonInsValues=[];
                  foreach($nonInsValues as $row){
                    $whereNameLine = [
                        "transType" => Post::TRANSTYPE_COMMENT_LINE_ELMT,
                        "idTranslation" => $row->getIdPipelineElmt(),
                        "codeLangue" => $user->getCodeLangue()
                    ];
                    $nameLine = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereNameLine);
                    $whereActiveLine = [
                        "transType" => Post::TRANSTYPE_ACTIVE,
                        "idTranslation" => $row->getLineRelease(),
                        "codeLangue" => $user->getCodeLangue()
                    ];
                    $active = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereActiveLine);
                      $ret =[
                        'name' =>  $nameLine->getLabel(),
                        'lineVersion' => $row->getLineVersion(),
                        'idPipelineElmt' => $row->getIdPipelineElmt(),
                        'active' => $active->getLabel(),
                      ];
                      array_push($arrNonInsValues,$ret);
                    }
                  $rs=[
                    'listNonInsValues' => $arrNonInsValues
                  ];
                  return new JsonResponse($rs);
                }

                  /**
                   * @Route("/storageTank", name="storageTank_eltType6")
                   */
      public function storageTankAction(Request $request){
        $user=$this->getUser();
        if($user== NULL){
            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        $idEquip=$session->get('idEquip');
        $idStudyEquipment= $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy'=>$idStudy]);
        // get ID CoolingFamily
        $idEquip=$idStudyEquipment[0]->getIdEquip();
        $objEquip=$this->getDoctrine()->getRepository(Equipment::class)->findBy(['idEquip'=>$idEquip]);
        $idCooling=$objEquip[0]->getIdCoolingFamily()->getIdCoolingFamily();
          $idinsType = $request->get('idfam');
          $whereStorageTank = [
              "insulationType" => $idinsType,
              "eltType" => 2,
              "idCoolingFamily" => $idCooling
          ];
          $storageTank = $this->getDoctrine()->getRepository(LineElmt::class)->findBy($whereStorageTank);
          $arrStorageTank=[];
        foreach($storageTank as $row){
            $whereNameStorageTank = [
                "transType" => Post::TRANSTYPE_COMMENT_LINE_ELMT,
                "idTranslation" => $row->getIdPipelineElmt(),
                "codeLangue" => $user->getCodeLangue()
            ];
            $nameLine = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereNameStorageTank);
            $whereActiveLine = [
                "transType" => Post::TRANSTYPE_ACTIVE,
                "idTranslation" => $row->getLineRelease(),
                "codeLangue" => $user->getCodeLangue()
            ];
            $active = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereActiveLine);
            $ret =[
              'name' =>  $nameLine->getLabel(),
              'lineVersion' => $row->getLineVersion(),
              'idPipelineElmt' => $row->getIdPipelineElmt(),
              'active' => $active->getLabel(),
            ];
            array_push($arrStorageTank,$ret);
            }
          $rs=[
            'listStorageTank' => $arrStorageTank
          ];
          return new JsonResponse($rs);
        }

      public function callSixSelect($param01, $param02, $param03, $param04)
      {
          $user=$this->getUser();
          $whereInsValues = [
              "insulationType" => $param01,
              "eltType" => $param02,
              "eltSize" => $param03,
              "idCoolingFamily" => $param04
          ];
          $insValues = $this->getDoctrine()->getRepository(LineElmt::class)->findBy($whereInsValues);
          $versionValue = "";
          $newArray = array();
          if(!empty($insValues)){
            $whereNameLine = [
                "transType" => Post::TRANSTYPE_COMMENT_LINE_ELMT,
                "idTranslation" => $insValues[0]->getIdPipelineElmt(),
                "codeLangue" => $user->getCodeLangue()
            ];
            $nameLine = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereNameLine);

            $whereActiveLine = [
                "transType" => Post::TRANSTYPE_ACTIVE,
                "idTranslation" => $insValues[0]->getLineRelease(),
                "codeLangue" => $user->getCodeLangue()
            ];
            $activeLine = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereActiveLine);
            $versionValue = $nameLine->getLabel() . ' - ' . $insValues[0]->getLineVersion() . ' (' . $activeLine->getLabel().')';
            $idLineElmt = $insValues[0]->getIdPipelineElmt();
            $newArray = [
                "versionValue" => $versionValue,
                "idLineElmt" => $idLineElmt,
            ];
          }
          return $newArray;
      }
      public function callStorageTank($param01, $param02)
      {
          $user=$this->getUser();
          $whereStorageTank = [
              "insulationType" => $param01,
              "eltType" => 2,
              "idCoolingFamily" => $param02
          ];
          $storageTank = $this->getDoctrine()->getRepository(LineElmt::class)->findBy($whereStorageTank);
          $storageTankArr = array();
          if(!empty($storageTank)){
              foreach($storageTank as $row){
                  $whereNameStorageTank = [
                      "transType" => Post::TRANSTYPE_COMMENT_LINE_ELMT,
                      "idTranslation" => $row->getIdPipelineElmt(),
                      "codeLangue" => $user->getCodeLangue()
                  ];
                  $nameStorageTank = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereNameStorageTank);

                  $whereActiveStorageTank = [
                      "transType" => Post::TRANSTYPE_ACTIVE,
                      "idTranslation" => $row->getLineRelease(),
                      "codeLangue" => $user->getCodeLangue()
                  ];
                  $activeStorageTank = $this->getDoctrine()->getRepository(Translation::class)->findOneBy($whereActiveStorageTank);
                  $item["versionTank"] = $nameStorageTank->getLabel() . " - " . $row->getLineVersion() . " (" . $activeStorageTank->getLabel() .") ";
                  $item["idLineElmt"] = $row->getIdPipelineElmt();
                  $storageTankArr[] = $item;
              }
          }
          return $storageTankArr;
      }

      public function sixSelect($param1, $param2){
        $sixValue = "";
        $whereSixTypeElmt = [
            "idPipeGen" => $param1,
            "typeElmt" => $param2
        ];
        $sixTypeElmt = $this->getDoctrine()->getRepository(LineDefinition::class)->findOneBy($whereSixTypeElmt);
        if(!empty($sixTypeElmt))
        $sixValue = $sixTypeElmt->getTypeElmt();

        return $sixValue;
      }
}
