<?php
/**
 * Created by PhpStorm.
 * User: thangnd
 * Date: 9/27/17
 * Time: 1:24 PM
 */

namespace AppBundle\Controller\packing;


use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\Product;
use AppBundle\Entity\Studies;
use AppBundle\Entity\Packing;
use AppBundle\Entity\PackingElmt;
use AppBundle\Entity\PackingLayer;
use AppBundle\Entity\Shape;
use AppBundle\Entity\Translation;
use AppBundle\Entity\MinMax;
use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Cryosoft\PackingService;




class PackingController extends Controller
{
    private $packingService;

    public function __construct(PackingService $packingService)
    {
        $this->packingService = $packingService;
    }

    /**
     * @Route("/packing", name="packing")
     */
    public function packingAction(Request $request)
    {
        $session = $request->getSession();
        $id=$session->get('idStudy');
        $name= $this->getDoctrine()->getRepository(Studies::class)->findBy(['idStudy'=>$id]);
        $listPro = $this->getDoctrine()->getRepository(Product::class)->findBy(array('idStudy'=>$id));
        if(count($listPro) != 0){
          $listProEML = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$listPro[0]->getIdProd()]);
          if(count($listProEML) != 0){
            $listIdShape = $this->getDoctrine()->getRepository(Shape::class)->findBy(['idShape' => $listProEML[0]->getIdShape()]);
            $session->set('idShape', $listIdShape[0]->getIdShape());
          //xuong db lay Shape
            if($listIdShape[0]->getIdShape() == 1){
              return $this->slabAction($request);
            } else if ($listIdShape[0]->getIdShape() == 2) {
              return $this->rbvlAction($request);
            }else if ($listIdShape[0]->getIdShape() == 3) {
              return $this->rbhlAction($request);
            }else if ($listIdShape[0]->getIdShape() == 4) {
              return $this->scAction($request);
            }else if ($listIdShape[0]->getIdShape() == 5) {
              return $this->lcAction($request);
            }else if ($listIdShape[0]->getIdShape() == 6) {
              return $this->smomAction($request);
            }else if ($listIdShape[0]->getIdShape() == 7) {
              return $this->sccAction($request);
            }else if ($listIdShape[0]->getIdShape() == 8) {
              return $this->lccAction($request);
            }else if ($listIdShape[0]->getIdShape() == 9) {
              return $this->rbbAction($request);
          }
        }else {

          return $this->redirectToRoute('Product-Characteristic');
        }
        }else {

          return $this->redirectToRoute('Product-Characteristic');
        }
}

private function common3Action($request, $trans_type){
  $NR_LAYERS = 9;
  $listSide = $this->getDoctrine()
      ->getRepository(PackingLayer::class)
      ->findAll();
      //        List of PACKINGLAYER
  $session = $request->getSession();
  $id=$session->get('idStudy');
  if($id < 1 ){
    return $this->redirectToRoute('load-study');
  }

  $name= $this->getDoctrine()->getRepository(Studies::class)->findBy(['idStudy'=>$id]);
  $objPacking = $this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy'=>$id]);
  $listLayer = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType'=>$trans_type]);
  $objPackingLayer = $this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking'=>$objPacking]);
  // $PackingLayer = $this->getDoctrine()->getRepository(PackingLayer::class)->find($objPackingLayer);
  $Thickness = $this->getDoctrine()->getRepository(MinMax::class)->find(Post::ID_PACKING_THICKNESS);

  $objPackingLayerTop = $this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking' => $objPacking, 'packingSideNumber'=>1]);
  $objPackingLayerRear = $this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking' => $objPacking,'packingSideNumber'=>2]);
  $objPackingLayerSide = $this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking' => $objPacking,'packingSideNumber'=>3]);
if (count($objPacking) == 0) {
  return array(
        'listSideNumber' => $listSide,
    //            Array List of PACKINGLAYER
        'listNR_LAYERS' => $NR_LAYERS,
        'packingThicknessDefault' => $Thickness->getDefaultValue(),
        'idStudy' =>$id,
        'studyName' =>$name[0]->getStudyName(),
        'listLayer'=>$listLayer,
        'idShape' => $session->get('idShape'),
        'objPackingLayerTop' => count($objPackingLayerTop)!= 0 ? $objPackingLayerTop : null,
        'sumObjPackingLayerTop' => count($objPackingLayerTop),
        'objPackingLayerRear' => count($objPackingLayerRear)!= 0 ? $objPackingLayerRear : null,
        'sumObjPackingLayerRear' => count($objPackingLayerRear),
        'objPackingLayerSide' => count($objPackingLayerSide)!= 0 ? $objPackingLayerSide : null,
        'sumObjPackingLayerSide' => count($objPackingLayerSide),
        'namePacking' => count($objPacking) != 0 ? $objPacking[0]->getNomembmat() : null,
        'packingThicknessDefault' => $Thickness->getDefaultValue(),
        'objPackingLayer' => $objPackingLayer
    );
}else {
  return array(
        'listSideNumber' => $listSide,
    //            Array List of PACKINGLAYER
        'listNR_LAYERS' => $NR_LAYERS,
        'packingThicknessDefault' => $Thickness->getDefaultValue(),
        'idStudy' =>$id,
        'studyName' =>$name[0]->getStudyName(),
        'listLayer'=>$listLayer,
        'idShape' => $session->get('idShape'),
        'objPackingLayerTop' => count($objPackingLayerTop)!= 0 ? $objPackingLayerTop : null,
        'sumObjPackingLayerTop' => count($objPackingLayerTop),
        'objPackingLayerRear' => count($objPackingLayerRear)!= 0 ? $objPackingLayerRear : null,
        'sumObjPackingLayerRear' => count($objPackingLayerRear),
        'objPackingLayerSide' => count($objPackingLayerSide)!= 0 ? $objPackingLayerSide : null,
        'sumObjPackingLayerSide' => count($objPackingLayerSide),
        'namePacking' => count($objPacking) != 0 ? $objPacking[0]->getNomembmat() : null,
        'packingThicknessDefault' => $Thickness->getDefaultValue(),
        'objPackingLayer' => $objPackingLayer
    );
}

}

    /**
     * @Route("/packing/lying-concentric-cylinder", name="packinglcc")
     */
    public function lccAction(Request $request)
    {

      return $this->render('packing/lying-concentric-cylinder.html.twig', $this->common3Action($request,3));

    }
//Route lcc(lying-concentric-cylinder)

    /**
     * @Route("/lying-cylinder", name="packinglc")
     */
    public function lcAction(Request $request)
    {

      return $this->render('packing/lying-cylinder.html.twig', $this->common3Action($request,3));
    }
// Route lc(lying-cylinder)

/**
 * @Route("/rectangular-block-breaded", name="packingrbb")
 */
public function rbbAction(Request $request)
    {

      return $this->render('packing/rectangular-block-breaded.html.twig', $this->common3Action($request,3));
    }
// Route rbb(rectangular-block-breaded)


/**
 * @Route("/rectangular-block-horizontal-layers", name="packingrrbhl")
 */
public function rbhlAction(Request $request)
{

    return $this->render('packing/rectangular-block-horizontal-layers.html.twig', $this->common3Action($request,3));
}
// Route rbhl(rectangular-block-horizontal-layers)


/**
 * @Route("/rectangular-block-vertical-layers", name="packingrrbvl")
 */
public function rbvlAction(Request $request)
{

  return $this->render('packing/rectangular-block-vertical-layers.html.twig', $this->common3Action($request,3));
}
// Route rbvl(rectangular-block-vertical-layers)


/**
 * @Route("/slab", name="packingslab")
 */

public function slabAction(Request $request)
{
  $NR_LAYERS = 9;
  $listSide = $this->getDoctrine()
      ->getRepository(PackingLayer::class)
      ->findAll();
      //        List of PACKINGLAYER
  $session = $request->getSession();
  $id=$session->get('idStudy');
  if($id < 1 ){
    return $this->redirectToRoute('load-study');
  }

  $name= $this->getDoctrine()->getRepository(Studies::class)->findBy(['idStudy'=>$id]);
  $objPacking = $this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy'=>$id]);
  $listLayer = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType'=>3]);
  $objPackingLayer = $this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking'=>$objPacking]);
  // $PackingLayer = $this->getDoctrine()->getRepository(PackingLayer::class)->find($objPackingLayer);
  $Thickness = $this->getDoctrine()->getRepository(MinMax::class)->find(Post::ID_PACKING_THICKNESS);
  $objPackingLayerTop = $this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking' => $objPacking, 'packingSideNumber'=>1]);
  $objPackingLayerRear = $this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking' => $objPacking, 'packingSideNumber'=>2]);
if(count($objPacking) == 0){
  return $this->render('packing/slab.html.twig', array(
      'listSideNumber' => $listSide,
  //            Array List of PACKINGLAYER
      'listNR_LAYERS' => $NR_LAYERS,
      'objPackingLayerTop' => count($objPackingLayerTop)!= 0 ? $objPackingLayerTop : null,
      'sumObjPackingLayerTop' => count($objPackingLayerTop),
      'objPackingLayerRear' => count($objPackingLayerRear)!= 0 ? $objPackingLayerRear : null,
      'sumObjPackingLayerRear' => count($objPackingLayerRear),
      'namePacking' => count($objPacking) != 0 ? $objPacking[0]->getNomembmat() : null,
      'packingThicknessDefault' => $Thickness->getDefaultValue(),
      'idStudy' =>$id,
      'studyName' =>$name[0]->getStudyName(),
      'listLayer'=>$listLayer,
      'idShape' => $session->get('idShape'),
      'objPackingLayer' => $objPackingLayer,
  ));
}else{
  return $this->render('packing/slab.html.twig', array(
  'listSideNumber' => $listSide,
//            Array List of PACKINGLAYER
  'listNR_LAYERS' => $NR_LAYERS,
  'objPackingLayerTop' => count($objPackingLayerTop)!= 0 ? $objPackingLayerTop : null,
  'sumObjPackingLayerTop' => count($objPackingLayerTop),
  'objPackingLayerRear' => count($objPackingLayerRear)!= 0 ? $objPackingLayerRear : null,
  'sumObjPackingLayerRear' => count($objPackingLayerRear),
  'namePacking' => count($objPacking) != 0 ? $objPacking[0]->getNomembmat() : null,
  'packingThicknessDefault' => $Thickness->getDefaultValue(),
  'idStudy' =>$id,
  'studyName' =>$name[0]->getStudyName(),
  'listLayer'=>$listLayer,
  'idShape' => $session->get('idShape'),
  'objPackingLayer' => $objPackingLayer,
));
}
}
// Route slag


/**
 * @Route("/sphere-mono-or-multi", name="packingsmom")
 */
public function smomAction(Request $request)
{
  $NR_LAYERS = 9;
  $listSide = $this->getDoctrine()
      ->getRepository(PackingLayer::class)
      ->findAll();
      //        List of PACKINGLAYER
  $session = $request->getSession();
  $id=$session->get('idStudy');
  if($id < 1 ){
    return $this->redirectToRoute('load-study');
  }

  $name= $this->getDoctrine()->getRepository(Studies::class)->findBy(['idStudy'=>$id]);
  $objPacking = $this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy'=>$id]);
  $listLayer = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType'=>3]);
  $objPackingLayer = $this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking'=>$objPacking]);
  // $PackingLayer = $this->getDoctrine()->getRepository(PackingLayer::class)->find($objPackingLayer);
  $Thickness = $this->getDoctrine()->getRepository(MinMax::class)->find(Post::ID_PACKING_THICKNESS);
  $objPackingLayerTop = $this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking' => $objPacking,'packingSideNumber'=>1]);

if(count($objPacking) == 0){
  return $this->render('packing/sphere-mono-or-multi.html.twig', array(
      'listSideNumber' => $listSide,
  //            Array List of PACKINGLAYER
      'listNR_LAYERS' => $NR_LAYERS,
      'objPackingLayerTop' => count($objPackingLayerTop)!= 0 ? $objPackingLayerTop : null,
      'sumObjPackingLayerTop' => count($objPackingLayerTop),
      'namePacking' => count($objPacking) != 0 ? $objPacking[0]->getNomembmat() : null,
      'packingThicknessDefault' => $Thickness->getDefaultValue(),
      'idStudy' =>$id,
      'studyName' =>$name[0]->getStudyName(),
      'listLayer'=>$listLayer,
      'idShape' => $session->get('idShape'),
      'objPackingLayer' => $objPackingLayer
  ));
}else{
  return $this->render('packing/sphere-mono-or-multi.html.twig', array(
      'listSideNumber' => $listSide,
  //            Array List of PACKINGLAYER
      'listNR_LAYERS' => $NR_LAYERS,
      'objPackingLayerTop' => count($objPackingLayerTop)!= 0 ? $objPackingLayerTop : null,
      'sumObjPackingLayerTop' => count($objPackingLayerTop),
      'namePacking' => count($objPacking) != 0 ? $objPacking[0]->getNomembmat() : null,
      'packingThicknessDefault' => $Thickness->getDefaultValue(),
      'idStudy' =>$id,
      'studyName' =>$name[0]->getStudyName(),
      'listLayer'=>$listLayer,
      'idShape' => $session->get('idShape'),
      'objPackingLayer' => $objPackingLayer
));
}
}
// Route smom(sphere-mono-or-multi)


/**
 * @Route("/standing-concentric-cylinder", name="packingscc")
 */
public function sccAction(Request $request)
{


  return $this->render('packing/standing-concentric-cylinder.html.twig', $this->common3Action($request,3));
}
// Route scc(standing-concentric-cylinder)


/**
 * @Route("/standing-cylinder", name="packingsc")
 */
public function scAction(Request $request)
{

      return $this->render('packing/standing-cylinder.html.twig', $this->common3Action($request,3));
}
// Route sc(standing-cylinder)



  /**
   * @Route("/createPack1", name="create-packing1")
   */
  public function createPack1Action(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $packing = new Packing();
    $session = $request->getSession();
    $idStudy=$session->get("idStudy");
    $idShape=$session->get("idShape");
    $namePacking=$request->get('namePacking'); // put data from client
    $arrTop=$request->get('arrTop'); // put data from client
    $arrThickTop=$request->get('arrThickTop'); // put data from client

    $objStudy = $this->getDoctrine()->getRepository(Studies::class)->findBy(['idStudy'=> $idStudy]);
    $objShape = $this->getDoctrine()->getRepository(Shape::class)->findBy(['idShape'=> $idShape]);
    $objPacking=$this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy'=> $idStudy,
     'idShape'=> $idShape]);
      $objPacking1=$this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy'=> $objStudy[0]->getIdStudy()]);
     if(count($objPacking1) == 0){
       $packing->setIdStudy($objStudy[0]);
       $packing->setIdShape($objShape[0]);
       $packing->setNomembmat($namePacking);
       $em->persist($packing);
       $em->flush();

       $this->packingService->setPackingToStudies($idStudy, $packing->getIdPacking());

     }else{
       $namePacking=$request->get('namePacking'); // put data from client
       $em = $this->getDoctrine()->getManager();
       $packing = $objPacking1[0];
       $packing->setIdShape($objShape[0]);
       $packing->setNomembmat($namePacking);
       $em->persist($packing);
       $em->flush();
       $objPackingLayer=$this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking'=>$objPacking1[0]->getIdPacking()]);
       if(count($objPackingLayer)!=0){
         $qb = $em->createQueryBuilder();
         $query = $qb->delete(packingLayer::class, 'p')
                     ->where('p.idPacking = :id')
                     ->setParameter('id',$packing->getIdPacking() )
                     ->getQuery();
         $query->execute();
       }
     }

     for($i=0;$i<count($arrTop);$i++){
         $packingLayer = new PackingLayer();
         $objPackingElmt=$this->getDoctrine()->getRepository(PackingElmt::class)->find($arrTop[$i]);

         $packingLayer->setIdPacking($packing);
         $packingLayer->setIdPackingElmt($objPackingElmt);
         $packingLayer->setThickness($arrThickTop[$i]);
         $packingLayer->setPackingSideNumber(1);
         $packingLayer->setPackingLayerOrder($i+1);
         $em->persist($packingLayer);
     }
     $em->flush();

    return new JsonResponse([]);
  }

  /**
   * @Route("/createPack2", name="create-packing2")
   */
  public function createPack2Action(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $packing = new Packing();
    $session = $request->getSession();
    $idStudy=$session->get("idStudy");
    $idShape=$session->get("idShape");
    $namePacking=$request->get('namePacking'); // put data from client
    $arrTop=$request->get('arrTop'); // put data from client
    $arrRear=$request->get('arrRear'); // put data from client
    $arrThickTop=$request->get('arrThickTop'); // put data from client
    $arrThickRear=$request->get('arrThickRear'); // put data from client

    $objStudy = $this->getDoctrine()->getRepository(Studies::class)->findBy(['idStudy'=> $idStudy]);
    $objShape = $this->getDoctrine()->getRepository(Shape::class)->findBy(['idShape'=> $idShape]);
    $objPacking=$this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy'=> $idStudy,
     'idShape'=> $idShape]);
      $objPacking1=$this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy'=> $objStudy[0]->getIdStudy()]);
     if(count($objPacking1) == 0){
       $packing->setIdStudy($objStudy[0]);
       $packing->setIdShape($objShape[0]);
       $packing->setNomembmat($namePacking);
       $em->persist($packing);
       $em->flush();

       $this->packingService->setPackingToStudies($idStudy, $packing->getIdPacking());

     }else{
       $namePacking=$request->get('namePacking'); // put data from client
       $em = $this->getDoctrine()->getManager();
       $packing = $objPacking1[0];
       $packing->setIdShape($objShape[0]);
       $packing->setNomembmat($namePacking);
       $em->persist($packing);
       $em->flush();
       $objPackingLayer=$this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking'=>$objPacking1[0]->getIdPacking()]);
       if(count($objPackingLayer)!=0){
         $qb = $em->createQueryBuilder();
         $query = $qb->delete(packingLayer::class, 'p')
                     ->where('p.idPacking = :id')
                     ->setParameter('id',$packing->getIdPacking() )
                     ->getQuery();
         $query->execute();
       }
     }

     for($i=0;$i<count($arrTop);$i++){
         $packingLayer = new PackingLayer();
         $objPackingElmt=$this->getDoctrine()->getRepository(PackingElmt::class)->find($arrTop[$i]);

         $packingLayer->setIdPacking($packing);
         $packingLayer->setIdPackingElmt($objPackingElmt);
         $packingLayer->setThickness($arrThickTop[$i]);
         $packingLayer->setPackingSideNumber(1);
         $packingLayer->setPackingLayerOrder($i+1);
         $em->persist($packingLayer);
     }
     for($i=0;$i<count($arrRear);$i++){
         $packingLayer = new PackingLayer();
         $objPackingElmt=$this->getDoctrine()->getRepository(PackingElmt::class)->find($arrRear[$i]);

         $packingLayer->setIdPacking($packing);
         $packingLayer->setIdPackingElmt($objPackingElmt);
         $packingLayer->setThickness($arrThickRear[$i]);
         $packingLayer->setPackingSideNumber(2);
         $packingLayer->setPackingLayerOrder($i+1);
         $em->persist($packingLayer);
     }
     $em->flush();

    return new JsonResponse([]);
  }


  /**
   * @Route("/createPack3", name="create-packing3")
   */
  public function createPack3Action(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $packing = new Packing();
    $session = $request->getSession();
    $idStudy=$session->get("idStudy");
    $idShape=$session->get("idShape");
    $namePacking=$request->get('namePacking'); // put data from client
    $arrTop=$request->get('arrTop'); // put data from client
    $arrRear=$request->get('arrRear'); // put data from client
    $arrSide=$request->get('arrSide'); // put data from client
    $arrThickTop=$request->get('arrThickTop'); // put data from client
    $arrThickRear=$request->get('arrThickRear'); // put data from client
    $arrThickSide=$request->get('arrThickSide'); // put data from client

    $objStudy = $this->getDoctrine()->getRepository(Studies::class)->findBy(['idStudy'=> $idStudy]);
    $objShape = $this->getDoctrine()->getRepository(Shape::class)->findBy(['idShape'=> $idShape]);
    $objPacking=$this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy'=> $idStudy,
     'idShape'=> $idShape]);
      $objPacking1=$this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy'=> $objStudy[0]->getIdStudy()]);
     if(count($objPacking1) == 0){
       $packing->setIdStudy($objStudy[0]);
       $packing->setIdShape($objShape[0]);
       $packing->setNomembmat($namePacking);
       $em->persist($packing);
       $em->flush();

       $this->packingService->setPackingToStudies($idStudy, $packing->getIdPacking());
     }else{
       $namePacking=$request->get('namePacking'); // put data from client
       $em = $this->getDoctrine()->getManager();
       $packing = $objPacking1[0];
       $packing->setIdShape($objShape[0]);
       $packing->setNomembmat($namePacking);
       $em->persist($packing);
       $em->flush();
       $objPackingLayer=$this->getDoctrine()->getRepository(PackingLayer::class)->findBy(['idPacking'=>$objPacking1[0]->getIdPacking()]);
       if(count($objPackingLayer)!=0){
         $qb = $em->createQueryBuilder();
         $query = $qb->delete(packingLayer::class, 'p')
                     ->where('p.idPacking = :id')
                     ->setParameter('id',$packing->getIdPacking() )
                     ->getQuery();
         $query->execute();
       }
     }

     for($i=0;$i<count($arrTop);$i++){
         $packingLayer = new PackingLayer();
         $objPackingElmt=$this->getDoctrine()->getRepository(PackingElmt::class)->find($arrTop[$i]);

         $packingLayer->setIdPacking($packing);
         $packingLayer->setIdPackingElmt($objPackingElmt);
         $packingLayer->setThickness($arrThickTop[$i]);
         $packingLayer->setPackingSideNumber(1);
         $packingLayer->setPackingLayerOrder($i+1);
         $em->persist($packingLayer);
     }
     for($i=0;$i<count($arrRear);$i++){
         $packingLayer = new PackingLayer();
         $objPackingElmt=$this->getDoctrine()->getRepository(PackingElmt::class)->find($arrRear[$i]);

         $packingLayer->setIdPacking($packing);
         $packingLayer->setIdPackingElmt($objPackingElmt);
         $packingLayer->setThickness($arrThickRear[$i]);
         $packingLayer->setPackingSideNumber(2);
         $packingLayer->setPackingLayerOrder($i+1);
         $em->persist($packingLayer);
     }
     for($i=0;$i<count($arrSide);$i++){
         $packingLayer = new PackingLayer();
         $objPackingElmt=$this->getDoctrine()->getRepository(PackingElmt::class)->find($arrSide[$i]);

         $packingLayer->setIdPacking($packing);
         $packingLayer->setIdPackingElmt($objPackingElmt);
         $packingLayer->setThickness($arrThickSide[$i]);
         $packingLayer->setPackingSideNumber(3);
         $packingLayer->setPackingLayerOrder($i+1);
         $em->persist($packingLayer);
     }
     $em->flush();

    return new JsonResponse([]);
  }
  /**
   * @Route("/getAllMinMaxThickness", name="getAllMinMaxThickness")
   */
  public function getAllMinMaxAction(){
      $user=$this->getUser();
          if($user== NULL){
              return $this->redirectToRoute('login');
          }
      $limitThickness=$this->getDoctrine()->getRepository(MinMax::class)->find(Post::ID_PACKING_THICKNESS);

      $ret =[
          'limitThicknessMin'=>$limitThickness->getLimitMin(),
          'limitThicknessMax'=>$limitThickness->getLimitMax(),
          ];

      return new JsonResponse($ret);
  }
}
