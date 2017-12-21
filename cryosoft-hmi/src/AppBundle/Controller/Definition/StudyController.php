<?php

namespace AppBundle\Controller\Definition;

use AppBundle\Entity\Prices;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\Production;
use AppBundle\Entity\Shape;
use AppBundle\Entity\Studies;
use AppBundle\Entity\TempRecordPts;
use AppBundle\Entity\Report;
use AppBundle\Entity\PrecalcLdgRatePrm;
use AppBundle\Entity\Post;
use AppBundle\Cryosoft\StudyService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
class StudyController extends Controller

{
    private $studyService;

    public function __construct(StudyService $studyService)
    {
        $this->studyService = $studyService;
    }

    /**
     * @Route("/definition", name="new-study")
     */
    public function createAction(Request $request, StudyService $study)
    {
        $user = $this->getUser();
        if($user == null){
            return $this->redirectToRoute('login');
        }
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $newStudy = new Studies();

        if($request->getMethod()=='POST')
        {
            $user = $this->getUser();
            // get parameter from view form
            $studyName = $request->get('_studyName');
            $commentTxt = $request->get('_comment');
            $calculationMode = $request->get('r1');
            $optionEco = $request->get('_econo');
            $optionCryopipeline = $request->get('_cyogen');
            $chainingControls = $request->get('_chainingControls');
            $chainingAddCompEnable = $request->get('_chainingAddCompEnable');
            $now = new\DateTime('now');
            $result = $now->format('Y-m-d H:i:s');
            // create var validator
            $validator = $this->get('validator');
            if($studyName == null || $studyName == ""){
                $session->getFlashBag()->set('error', "Study name do not blank");
                return $this->redirectToRoute('new-study');
            }

            // set value from view
            $newStudy->setIdUser($user);
            $newStudy->setIdPrice(0);
            $newStudy->setStudyName($studyName);
            $newStudy->setCommentTxt($commentTxt."  ". 'create at' .'  '.$result);;
            $newStudy->setCalculationMode($calculationMode);
            $newStudy->setOptionEco($optionEco);
            $newStudy->setOptionCryopipeline($optionCryopipeline);
            $newStudy->setChainingControls($chainingControls);
            $newStudy->setChainingAddCompEnable($chainingAddCompEnable);
            $newStudy->setIdPacking(0);
            $newStudy->setIdHaverageResults(0);
            $newStudy->setIdStudyResults(0);
            $newStudy->setOpenByOwner(0);
            $newStudy->setIdProd(0);
            // check valid
            $errors = $validator->validate($newStudy);

            if (count($errors) > 0) {
                $session->getFlashBag()->set('error', $errors[0]->getMessage());
                return $this->redirectToRoute('new-study');
            }else{
                // $session->getFlashBag()->set('mess-success', "New study created successful");
                $em->persist($newStudy);
                $em->flush();
                $study->setDefValuesStudy($newStudy->getIdStudy());

                $tempRecordPts = new TempRecordPts();
                $tempRecordPts->setIdStudy($newStudy);
                $study->createTempRecordPtsStudy($tempRecordPts);
                $newStudy->setIdTempRecordPts($tempRecordPts->getIdTempRecordPts());

                $production = new Production();
                $production->setIdStudy($newStudy);
                $study->createProductionStudy($production);
                $newStudy->setIdProduction($production->getIdProduction());

                $report = new Report();
                $report->setIdStudy($newStudy);
                $study->createReportStudy($report);
                $newStudy->setIdReport($report->getIdReport());

                $preCalc = new PrecalcLdgRatePrm();
                $preCalc->setIdStudy($newStudy);
                $study->createReCalLDGRatePRM($preCalc);
                $newStudy->setIdPrecalcLdgRatePrm($preCalc->getIdPrecalcLdgRatePrm());

                $em->flush();
                return $this->redirectToRoute('load-study');
            }
        }

        if($idStudy == null){
            $this->studyService->cleanStudyBeansUsedInSession($request);
            $this->studyService->setupOpenByOwner(0);

            return $this->render('study/new-study.html.twig');
        }else{
            $study = $this->studyService->getInfoStudy($idStudy);

            if(count($study) > 0){
                 $this->studyService->refreshStudies($request, $idStudy);

                return $this->redirectToRoute('load-study');
            }else{
                $this->studyService->removeStudyAttributes($request);

                return $this->redirectToRoute('new-study');
            }

        }
    }
    /**
     * @Route("/open", name="load-study")
     */
    public function loadAction(Request $request)
    {
        $session = $request->getSession();
        $user = $this->getUser();
        if($user == null){

            return $this->redirectToRoute('login');
        }
        $idCodeLang = $user->getCodeLangue();
        $session->set('idCodeLang', $idCodeLang);
        $idUserCurr = $user->getIdUser();

        $check = $session->get('checkSearchStudy');
        $idUser = 0;
        $idCompFamily = 0;
        $idCompSubFamily = 0;
        $idComp = 0;

        if($check){
            $idUser = $session->get('idUser');
            $idCompFamily = $session->get('idCompFamily');
            $idCompSubFamily = $session->get('idCompSubFamily');
            $idComp = $session->get('idComp');
            $session->set('checkSearchStudy', false);
            $session->set('checkSearchStudy', true);
            $session->set('idUser', 0);
            $session->set('idCompFamily', 0);
            $session->set('idCompSubFamily', 0);
            $session->set('idComp', 0);
        }

        $listUser = $this->studyService->getComboboxUser();
        $listFamily = $this->studyService->getComboboxFamily(Post::TRANSTYPE_FAMILY, $idCodeLang);
        $listSubFamily = $this->studyService->getComboboxSubFamily(Post::TRANSTYPE_SUBFAMILY, $idCodeLang, $idCompFamily);
        $listComponent = $this->studyService->searchAllStandardComponents(Post::TRANSTYPE_COMPONENT, $idCodeLang, $idUserCurr, 0, $idCompFamily, $idCompSubFamily, 0);
        $resStudy = $this->studyService->getFilteredStudiesList($idUser, $idCompFamily, $idCompSubFamily, $idComp, $idUserCurr, 0);
        $listStudy = array();

        if(count($resStudy) < 1){
            $session->getFlashBag()->set('error', "No study matches the selected criteria");
        }

        foreach ($resStudy as $key => $value) {

            if($value['idUser'] == $idUserCurr){
                $resObj = $this->studyService->makeSelectOptionLoggedOnUser($value['idStudy'], $value['idUser']);
                $resObj['idStudy'] = $value['idStudy'];
                array_push($listStudy, $resObj);
            }
        }

        foreach ($resStudy as $key => $value) {

            if($value['idUser'] != $idUserCurr){
                $resObj = $this->studyService->makeSelectOptionOtherUser($value['idStudy'], $value['idUser']);
                $resObj['idStudy'] = $value['idStudy'];
                array_push($listStudy, $resObj);
            }
        }

        $idStudyCurr = $session->get('idStudy');

        if($idStudyCurr != null || $idStudyCurr != 0){

            $studyCurr = $this->studyService->getInfoStudy($idStudyCurr);

            if(count($studyCurr) > 0){
                $chaningData = $this->studyService->loadChainingStudies($idStudyCurr, $request);
                $chainingParent = $chaningData['studyParent'];
                $chainingChild = $chaningData['studyChild'];

                $displayStringChaining = array();

                $arr1 = $chainingChild['studyName'];
                $arr2 = $chainingChild['equipName'];

                if($arr1 != "" || $arr2 != "" ){

                    for($i = 0; $i < count($arr1) ; $i++){
                        $item['equipName'] = $arr2[$i];
                        $item['studyName'] = $arr1[$i];
                        array_push($displayStringChaining, $item);
                    }
                }

                $objStudyCurr = $this->studyService->getInfoStudy($idStudyCurr);
                $objStudyCurr->isStudyHasFamilly = $this->studyService->isStudyHasFamilly($idStudyCurr);
                $objStudyCurr->chainingParent = $chainingParent;
                $objStudyCurr->chainingChild = $displayStringChaining;
                $objStudyCurr->studyNameCurr = $objStudyCurr->getStudyName();
                $objStudyCurr->isParent = $this->studyService->isStudyHasParent($idStudyCurr);
                $objStudyCurr->studyOfUser = $this->studyService->isStudyOfUser($user->getIdUser(), $idStudyCurr);
            }else{

                return $this->redirectToRoute('new-study');
            }

        }else{
            $objStudyCurr = null;
        }

        $listSearchStudy = [
            'idUser' => $idUser,
            'idCompFamily' => $idCompFamily,
            'idCompSubFamily' => $idCompSubFamily,
            'idComp' => $idComp
        ];

        return $this->render('study/open-study.html.twig', array(
            'listUser' => $listUser,
            'listFamily' => $listFamily,
            'listSubFamily' => $listSubFamily,
            'listComponent' => $listComponent,
            'listStudy' => $listStudy,
            'studyCurr' => $objStudyCurr,
            'listSearchStudy' => $listSearchStudy
        ));
    }

    /**
     * @Route("/save", name="saveAs")
     */
    public function saveAsAction(Request $request)
    {
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $em = $this->getDoctrine()->getManager();
        $saveAs = new Studies();

        if($request->getMethod()=='POST') {
                $user = $this->getUser();
                $username = $user->getUsernam();
                $price = $this->getDoctrine()
                    ->getRepository(Prices::class)
                    ->find(1);
                $prduction = $this->getDoctrine()
                    ->getRepository(Production::class)
                    ->find(1);
                $save_st_name = $request->get('_saveStName');
                $calmode = $request->get('mode');
                $economic = $request->get('_economicSave');
                $cryogen = $request->get('_cryogenSave');
                $chainingControls = $request->get('_chainingControlsSave');
                $chainingAddCompEnable = $request->get('_chainingAddCompEnableSave');
                $commentClone = $request->get('_comment');
                $now = new\DateTime('now');
                $result = $now->format('D M d H:i:s');
                $resultYear = $now->format('Y');
                $validator = $this->get('validator');
                if($save_st_name == null || $save_st_name == ""){
                    $session->getFlashBag()->set('error', "Study name do not blank");
                }else {
                  $saveAs->setIdUser($user);
                  $saveAs->setIdPrice($price->getIdPrice());
                  $saveAs->setIdProduction($prduction->getIdProduction());
                  $saveAs->setCalculationMode($calmode);
                  $saveAs->setStudyName($save_st_name);
                  $saveAs->setOptionEco($economic);
                  $saveAs->setOptionCryopipeline($cryogen);
                  $saveAs->setChainingControls($chainingControls);
                  $saveAs->setChainingAddCompEnable($chainingAddCompEnable);
                  // check valid
                  $errors = $validator->validate($saveAs);

                  if (count($errors) > 0) {
                      return $this->redirectToRoute('load-study', array(
                          'errors' => $errors));
                  } else {
                      $saveAs->setCommentTxt($commentClone ."\n".'Create on' . ' ' . $result .' ICT '. $resultYear . ' by '. $username);
                      $this->studyService->setDefValuesStudy($idStudy);
                      $em->persist($saveAs);
                      $em->flush();
                  }
                }
                  return $this->redirectToRoute('load-study');
        }
    }

    /**
     * @Route("/selectStudy", name="selectStudy")
     */
    public function selectStudyAction(Request $request)
    {
        $session = $request->getSession();
        $user = $this->getUser();

        if($user == null){

            return $this->redirectToRoute('login');
        }
        $currentUser = $user->getIdUser();

        if($request->getMethod()=='POST')
        {
            $study_id = $request->get('_idStudy');
            $load = $this->studyService->load($study_id, $currentUser, $request);

            if($load){
                $study = $this->studyService->getInfoStudy($study_id);
                $studyOfUser = $this->studyService->isStudyOfUser($user->getIdUser(), $study_id);

                if($studyOfUser){

                    if($study->getChainingControls() && $study->getParentId() > 0 && $study->getToRecalculate() == 1){

                        $this->studyService->RunStudyCleaner($study_id);
                        $this->studyService->setToRecalculate($study_id, 0);
                    }else{
                        //  delete Temporary Results: Gọi kernel clear study, nếu clear có lỗi thì show lỗi lên (error message sẽ nằm trong bảng ErrorTxt với code language = language của user đang login, idErrCode = CalculationStatus của study đang chọn, idErrComp = 0)
                    }
                }
            }
            // else{
            //     $this->studyService->cleanStudyBeansUsedInSession($request);
            // }

            return $this->redirectToRoute('load-study');
        }
    }

    /**
     * @Route("/searchStudy", name="searchStudy")
     */
    public function searchStudyAction(Request $request)
    {
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        if($idStudy != null){
            $this->studyService->cleanStudyBeansUsedInSession($request);
        }

        $idUser = $request->get('_who');
        $idCompFamily = $request->get('_compfamily');
        $idCompSubFamily = $request->get('_compsubfamily');
        $idComp = $request->get('_comp');

        $session->set('checkSearchStudy', true);
        $session->set('idUser', $idUser);
        $session->set('idCompFamily', $idCompFamily);
        $session->set('idCompSubFamily', $idCompSubFamily);
        $session->set('idComp', $idComp);

        return $this->redirectToRoute('load-study');
    }

    /**
     * @Route("/deletestudy", name="delete-study")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine();
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $study = $this->studyService->getInfoStudy($idStudy);

        if ($request->getMethod() == 'POST') {
            try {

                $this->studyService->removeParentAttribute($idStudy);
                $isStudyHasFamilly = $this->studyService->isStudyHasFamilly($idStudy);
                if($isStudyHasFamilly){
                    $this->studyService->removeParentAttribute($idStudy);
                }

                // KERNEL
                $this->studyService->deleteAllResults();

                if($study->getIdPacking() > 0){
                // delete PACKING
                    $this->studyService->deletePacking($study->getIdPacking());
                }

                if($study->getIdProd() > 0){
                // delete Product
                    $this->studyService->deleteProduct($idStudy, $study->getIdProd());
                }

                if($study->getIdProduction() > 0){
                // delete PRODUCTION
                    $this->studyService->deleteCustomer($idStudy);
                }

                // delete PIPELINE
                $this->studyService->deletePipeLine($idStudy);

                // Delete imported element
                $this->studyService->deleteImportedElement($idStudy, $request);

                // delete STUDY EQUIPMENT
                $this->studyService->deleteStudyEquipment($idStudy);

                // delete Study
                $this->studyService->deleteStudy($idStudy);

                $this->studyService->cleanStudyBeansUsedInSession($request);

                $this->studyService->unActivateSaveBan($request);

                return $this->redirectToRoute('load-study');

            }catch (\Doctrine\ORM\EntityNotFoundException $ex){
                echo "Exception Found - " . $ex->getMessage() . "<br/>";
            }
        }
    }

    /**
     * @Route("/update", name="update-study")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine();
        $session = $request->getSession();

        if ($request->getMethod() == 'POST') {

            $idStudy = $session->get('idStudy');
            $study = $this->studyService->getInfoStudy($idStudy);
            $comment = $request->get('_comment');
            $calmode = $request->get('mode');
            $economic = $request->get('_economicSave');
            $cryogen = $request->get('_cryogenSave');
            $chainingControls = $request->get('_chainingControlsSave');
            $chainingAddCompEnable = $request->get('_chainingAddCompEnableSave');
            $now = new\DateTime('now');
            $result = $now->format('Y-m-d H:i:s');
            $update->setCalculationMode($calmode);
            $update->setOptionEco($economic);
            $update->setOptionCryopipeline($cryogen);
            $update->setChainingControls($chainingControls);
            $update->setChainingAddCompEnable($chainingAddCompEnable);
            $update->setCommentTxt(" " . "\n" . $comment ." Update at ". $result);
            $this->studyService->setDefValuesStudy($idStudy);
            $em->flush();
            return $this->redirectToRoute('load-study');

        }
    }

    /**
     * @Route("/setSessionOpenStudy", name="setSessionOpenStudy")
     */
    public function setSessionOpenStudyAction(Request $request, StudyService $study){
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');

        if($idStudy > 0){
            $session->set('BANINOUTAUTHORIZED', 'YES');
        }else{
            $study->cleanStudyBeansUsedInSession();
        }

        return new JsonResponse([]);
    }

    /**
     * @Route("/setSessionNewStudy", name="setSessionNewStudy")
     */
    public function setSessionNewStudyAction(Request $request, StudyService $study){
        $session = $request->getSession();
        $study->cleanStudyBeansUsedInSession($request);
        $session->set('REDIRECT' , 'TRUE');

        return new JsonResponse([]);
    }
}
