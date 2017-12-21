<?php


namespace AppBundle\Controller\productCharacteristic;

use AppBundle\Entity\Component;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\Shape;
use AppBundle\Entity\Studies;
use AppBundle\Entity\Translation;
use AppBundle\Entity\MinMax;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\Post;
use AppBundle\Entity\Packing;
use AppBundle\Cryosoft\MeshService;
use AppBundle\Entity\PackingLayer;
use AppBundle\Entity\MeshPosition;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductChartController extends Controller
{
    /**
     * @Route("/productChar", name="Product-Characteristic")
     */
    public function showAction(Request $request, MeshService $mesh)
    {
        // check user login
        $user = $this->getUser();

        if ($user == NULL) {
            return $this->redirectToRoute('login');
        }
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $id = $session->get('idStudy');
        $objCheckProdOcne=[
            'dim1check'=>null,
            'dim3check'=>null,
            'dim2check'=>null,
            'nameproductcheck'=>null,
            'idShapeCheck'=>null
        ];
        $checkProductOnce = $session->get('checkProductOnce');

        if($checkProductOnce != null){
            $dim1check=$session->get('dim1check');
            $dim3check=$session->get('dim1check');
            $nameproductcheck=$session->get('nameproductcheck');
            $idShapeCheck=$session->get('idShapeCheck');
            $shapeCheck=$this->getDoctrine()->getRepository(Shape::class)->find($idShapeCheck);
            $shapeAlreadyCheck = $this->getDoctrine()->getRepository(Shape::class)->createQueryBuilder('s')
                ->select('t.label, s.idShape, s.shapepict')
                ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = s.idShape')
                ->where('t.transType = :transType')
                ->andWhere('t.codeLangue = :codeLangue')
                ->andWhere('s.idShape = :idShape')
                ->setParameter('transType', Post::TRANSTYPE_SHAPE)
                ->setParameter('codeLangue', $user->getCodeLangue())
                ->setParameter('idShape', $shapeCheck->getIdShape())
                ->orderBy('s.idShape', 'ASC')
                ->getQuery()->getResult();

            $objCheckProdOcne=[
                'dim1check'=>$dim1check,
                'dim3check'=>$dim3check,
                'dim2check'=>0,
                'nameproductcheck'=>$nameproductcheck,
                'idShapeCheck'=>$idShapeCheck,
                'nameShapeCheck'=>$shapeAlreadyCheck[0]['label']
            ];
            $session->set('checkProductOnce', null);
        }
        
        if ($id == null) {

            return $this->redirectToRoute('load-study');
        }
        $prodElmtWeight = "";
        $prodElmtRealweight = "";

        $objStudy = $this->getDoctrine()->getRepository(Studies::class)->findBy(['idStudy' => $id]);
        // get all family
        $listFamily = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType' => Post::TRANSTYPE_FAMILY, 'codeLangue' => $user->getCodeLangue()]);
        // get all subfamily
        $listSubFamily = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType' => Post::TRANSTYPE_SUBFAMILY, 'codeLangue' => $user->getCodeLangue()]);
        // get all component
        $listComp = $this->getDoctrine()->getRepository(Component::class)->createQueryBuilder('c')
            ->select('c.idComp,c.water,c.classType,c.subFamily,c.compRelease, t.idTranslation, t.label')
            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idComp')
            ->leftJoin(Ln2user::class, 'u', 'WITH', 'u.idUser = c.idUser')
            ->where('t.transType = :transType')
            ->andWhere('c.compImpIdStudy = 0 OR c.compImpIdStudy = :idStudy')
            ->andWhere('c.compRelease = 3 OR c.compRelease = 4 OR c.compRelease = 8 OR c.compRelease = 6 OR (c.compRelease = 2 AND c.idUser = :idUser)')
            ->andWhere('t.codeLangue = :codeLangue')
            ->setParameter('transType', Post::TRANSTYPE_COMPONENT)
            ->setParameter('idStudy', $id)
            ->setParameter('idUser', $user->getIdUser())
            ->setParameter('codeLangue', $user->getCodeLangue())->getQuery()->getResult();
        $arrComp = [];

        for ($i = 0; $i < count($listComp); $i++) {
            $ret = [
                'idComp' => $listComp[$i]['idComp'],
                'label' => $listComp[$i]['label'],
                'idTranslation' => $listComp[$i]['idTranslation'],
                'water' => $listComp[$i]['water'],
                'classType' => $listComp[$i]['classType'],
                'subFamily' => $listComp[$i]['subFamily'],
                'compRelease' => $listComp[$i]['compRelease'],
            ];
            array_push($arrComp, $ret);
        }
        // get product by idStudy
        $listPro = $this->getDoctrine()->getRepository(Product::class)->findBy(array('idStudy' => $objStudy[0]->getIdStudy()));
        // get all shape
        $listShape = $this->getDoctrine()->getRepository(Shape::class)->createQueryBuilder('s')
            ->select('t.label, s.idShape, s.shapepict')
            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = s.idShape')
            ->where('t.transType = :transType')
            ->andWhere('t.codeLangue = :codeLangue')
            ->setParameter('transType', Post::TRANSTYPE_SHAPE)
            ->setParameter('codeLangue', $user->getCodeLangue())
            ->orderBy('s.idShape', 'ASC')
            ->getQuery()->getResult();

        if (count($listPro) != 0) {
            $session->set('idProd', $listPro[0]->getIdProd());
            // get ProductElmt by idProduct
            $listProEML = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $listPro[0]->getIdProd()]);

            if (count($listProEML) != 0) {
                // get componet in table ProductElmt by idShape, idProduct
                $listComponentList = $this->getDoctrine()->getRepository(ProductElmt::class)->createQueryBuilder('pe')->select('t.idTranslation,t.label,pe.idProductElmt,pe.prodElmtName, pe.originalThick, pe.prodElmtWeight, pe.prodElmtRealweight , s.idShape, c.idComp,pe.shapeParam1,pe.shapeParam3')
                            ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = pe.idComp')
                            ->leftJoin(Shape::class, 's', 'WITH', 'pe.idShape = s.idShape')
                            ->leftJoin(Component::class, 'c', 'WITH', 'pe.idComp = c.idComp')
                            ->where('t.transType = :transType')
                            ->andWhere('pe.idProd = :idProd')
                            ->andWhere('pe.idShape = :idShape')
                            ->andWhere('t.codeLangue = :codeLangue')
                            ->setParameter('transType', Post::TRANSTYPE_COMPONENT)
                            ->setParameter('idShape', $listProEML[0]->getIdShape()->getIdShape())
                            ->setParameter('idProd', $listPro[0]->getIdProd())
                            ->setParameter('codeLangue', $user->getCodeLangue())
                            ->orderBy('pe.idProductElmt', 'DESC')
                ->getQuery()->getResult();
                // save session idShape, dim1, dim3
                $session->set('idShape', $listProEML[0]->getIdShape()->getIdShape());
                $session->set('dim1', $listComponentList[0]["shapeParam1"]);
                $session->set('dim3', $listComponentList[0]["shapeParam3"]);
                // get weight and real weight Product
                $prodElmtWeight = $listPro[0]->getProdWeight();
                $prodElmtRealweight = $listPro[0]->getProdRealweight();
                // get SHAPE already
                $listShapeAlready = $this->getDoctrine()->getRepository(Shape::class)->createQueryBuilder('s')
                ->select('t.label, s.idShape, s.shapepict')
                ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = s.idShape')
                ->where('t.transType = :transType')
                ->andWhere('t.codeLangue = :codeLangue')
                ->andWhere('s.idShape = :idShape')
                ->setParameter('transType', Post::TRANSTYPE_SHAPE)
                ->setParameter('codeLangue', $user->getCodeLangue())
                ->setParameter('idShape', $listProEML[0]->getIdShape()->getIdShape())
                ->orderBy('s.idShape', 'ASC')
                ->getQuery()->getResult();

                return $this->render('productchar/product-characteristic.html.twig', [
                            'studyName' => $objStudy[0]->getStudyName(),
                            'idStudy' => $id,
                            'listShape' => $listShape,
                            'showId_Shape' => count($listShapeAlready) ? $listShapeAlready[0] : null,
                            'showId_ProdELM' => count($listProEML) ? $listProEML[0] : null,
                            'productName' => $listPro[0]->getProdName(),
                            'idProduct' => $listPro[0]->getIdProd(),
                            'listComp' => $arrComp,
                            'listFamily' => $listFamily,
                            'listSubFamily' => $listSubFamily,
                            'componentList' => $listComponentList,
                            'countCompList' => count($listComponentList) >= 3 ? 3 : 0,
                            'prodElmtWeight' => $prodElmtWeight,
                            'prodElmtRealweight' => $prodElmtRealweight,
                            'objCheckProdOcne'=>$objCheckProdOcne
                ]);
            } else {

                return $this->render('productchar/product-characteristic.html.twig', [
                            'studyName' => $objStudy[0]->getStudyName(),
                            'idStudy' => $id,
                            'listShape' => $listShape,
                            'showId_Shape' => null,
                            'showId_ProdELM' => null,
                            'productName' => $listPro[0]->getProdName(),
                            'idProduct' => $listPro[0]->getIdProd(),
                            'listComp' => $arrComp,
                            'listFamily' => $listFamily,
                            'listSubFamily' => $listSubFamily,
                            'componentList' => null,
                            'countCompList' => 0,
                            'prodElmtWeight' => $prodElmtWeight,
                            'prodElmtRealweight' => $prodElmtRealweight,
                            'objCheckProdOcne'=>$objCheckProdOcne
                ]);
            }
        } else {

            return $this->render('productchar/product-characteristic.html.twig', [
                        'studyName' => $objStudy[0]->getStudyName(),
                        'idStudy' => $id,
                        'listShape' => $listShape,
                        'showId_Shape' => null,
                        'showId_ProdELM' => null,
                        'productName' => null,
                        'idProduct' => null,
                        'listComp' => $arrComp,
                        'listFamily' => $listFamily,
                        'listSubFamily' => $listSubFamily,
                        'componentList' => null,
                        'countCompList' => 0,
                        'prodElmtWeight' => $prodElmtWeight,
                        'prodElmtRealweight' => $prodElmtRealweight,
                        'objCheckProdOcne'=>$objCheckProdOcne
            ]);
        }
    }

    /**
     * @Route("/createModify", name="Productssssss")
     */
    public function createModifyAction(Request $request, MeshService $mesh)
    {
        $user=$this->getUser();

        if($user== NULL){
            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $em=$this->getDoctrine()->getManager();
        $idStudy = $session->get('idStudy');
        $mesh->delAllMeshPosition($idStudy);

        if($idStudy == null){

            return $this->redirectToRoute('load-study');
        }

        if ($request->getMethod() == "POST") {
            $product = new Product();
            $namePro = $request->get('namePro');
            $idShape = $request->get('idShape');
            $dim1 = $request->get('Dim1');
            $dim3 = $request->get('Dim3');
            $check=0;
            // save session idShape, dim1, dim3
            $session->set('idShape', $idShape);
            $session->set('dim1', $dim1);
            $session->set('dim3', $dim3);
            // get Studies by idStudy
            $objStudy = $this->getDoctrine()->getRepository(Studies::class)->findBy(array('idStudy' => $idStudy));
            // get Product by idStudy
            $objProduct = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $objStudy[0]->getIdStudy()]);

            if (count($objProduct) == 0) {
                // create Product new
                $product->setIdStudy($objStudy[0]);
                $product->setProdname($namePro);
                $em->persist($product);
                $em->flush();
                $objProductId = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $objStudy[0]->getIdStudy()]);
                // save session idProduct
                $session->set('idProd', $objProductId[0]->getIdProd());
                $objStudy[0]->setIdProd($objProductId[0]->getIdProd());
                $em->flush();
                $ret = [
                    'idProduct'=>$objProductId[0]->getIdProd(),
                    'namePro' => $namePro
                ];

                return new JsonResponse($ret);
            } else {
                // get Shape by idShape
                $objShape=$this->getDoctrine()->getRepository(Shape::class)->findBy(['idShape'=>$idShape]);
                // get ProductElmt by idProduct and idShape
                $objProductEML1= $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$objProduct[0]->getIdProd(),
                    'idShape'=>$objShape[0]->getIdShape()]) ;
                $objProductEMLCheck= $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$objProduct[0]->getIdProd()]) ;
                // get idShape , idStudy check exsit in packing
                $objPacking = $this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy' => $objStudy[0]->getIdStudy(),
                    'idShape'=>$objShape[0]->getIdShape()]);
                // get idStudy to delete this row
                $objPackingCheck = $this->getDoctrine()->getRepository(Packing::class)->findBy(['idStudy' => $objStudy[0]->getIdStudy()]);
                 // if objPacikngCheck change shape will execute delete action

                if(count($objPacking) == 0 && count($objPackingCheck) > 0){
                    $objPackingLayer = $this->getDoctrine()->getRepository(PackingLayer::class)->findBy(
                    ['idPacking'=>$objPackingCheck[0]->getIdPacking()]);
                    // delete PackingLayer form idPacking
                    $delPackinglayer = $em->createQueryBuilder();
                    $queryPackingLayer = $delPackinglayer->delete(PackingLayer::class, 'packingLayer')
                                ->where('packingLayer.idPacking = :id')
                                ->setParameter('id',$objPackingCheck[0]->getIdPacking() )
                                ->getQuery();
                    $queryPackingLayer->execute();
                    // delete packing
                    $delPacking = $em->createQueryBuilder();
                    $queryPacking = $delPacking->delete(Packing::class, 'packing')
                                ->where('packing.idPacking = :id')
                                ->setParameter('id',$objPackingCheck[0]->getIdPacking() )
                                ->getQuery();
                    $queryPacking->execute();
                }

                if(count($objProductEML1) == 0 && count($objProductEMLCheck) > 0){//Check ProductElmt to update or delete old data
                    $check=1;
                    // remove all item ProductElmt by idProduct
                    $qb = $em->createQueryBuilder();
                    $query = $qb->delete(ProductElmt::class, 'p')
                                ->where('p.idProd = :id')
                                ->setParameter('id',$objProduct[0]->getIdProd() )
                                ->getQuery();
                    $query->execute();
                    // update weight + real weight Product = 0
                    $objProduct[0]->setProdWeight(0);
                    $objProduct[0]->setProdRealweight(0);
                    $em->flush();
                }else{ // ProductElmt already exists
                    if(count($objProductEML1) > 0){
                        // check dim1 or dim3 change
                        $objProductEMLDim1= $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$objProduct[0]->getIdProd(),
                        'idShape'=>$objShape[0]->getIdShape(), 'shapeParam1'=>$dim1]);
                        $objProductEMLDim3= $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$objProduct[0]->getIdProd(),
                        'idShape'=>$objShape[0]->getIdShape(), 'shapeParam3'=>$dim3]);

                        if(count($objProductEMLDim1)==0 || count($objProductEMLDim3)==0 ){ 
                            $check=2;
                            // update dim1 and dim3 Component in ProductElmt table by idProduct
                            $qb=$em->createQueryBuilder();
                            $q=$qb->update(ProductElmt::class,'p')
                            ->set('p.shapeParam1',$qb->expr()->literal($dim1))
                            ->set('p.shapeParam3',$qb->expr()->literal($dim3))
                            ->where('p.idProd= ?1')
                            ->setParameter(1, $objProduct[0]->getIdProd())->getQuery();
                            $q->execute();
                            // calculate dim 1, 3
                            $calDim1=0;
                            $calDim3=0;

                            if($idShape==9){
                                 $listProductChar=$this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$objProduct[0]->getIdProd()]);

                                 if(count($listProductChar) !=0){

                                    for($i=0;$i<count($listProductChar);$i++){

                                        if($i==0){
                                            $calDim1= $dim1;
                                            $calDim3= $dim3;
                                        }else{
                                            $calDim1+=2 * $listProductChar[$i]->getOriginalThick();
                                            $calDim3+=2 * $listProductChar[$i]->getOriginalThick();
                                        }
                                    }
                                    $qbDim1=$em->createQueryBuilder();
                                    $q=$qbDim1->update(ProductElmt::class,'p')
                                    ->set('p.shapeParam1',$qbDim1->expr()->literal($calDim1))
                                    ->set('p.shapeParam3',$qbDim1->expr()->literal($calDim3))
                                    ->where('p.idProd= ?1')
                                    ->setParameter(1, $objProduct[0]->getIdProd())->getQuery()->execute();
                                }
                            }
                        }
                    }
                }
                // update name product
                $objProduct[0]->setProdname($namePro);
                $em->flush();
                $ret = [
                    'idProduct'=>$objProduct[0]->getIdProd(),
                    'namePro' => $namePro,
                    'check'=>$check

                ];

                return new JsonResponse($ret);
            }
        }else{

            return $this->redirectToRoute('fromlogin');
        }
    }

    /**
     * @Route("/getComponentListFam", name="get-Component-ListFam")
     */
    public function ComponentAction(Request $request){
        $user=$this->getUser();

        if($user== NULL){
            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }

        if($request->getMethod()=="POST"){
            $idfam=$request->get('idfam');
            // get Component by idFamily, idStudy
            $listComp = $this->getDoctrine()->getRepository(Component::class)->createQueryBuilder('c')
                        ->select('c.idComp,c.water,c.classType,c.subFamily,c.compRelease, t.idTranslation, t.label')
                        ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idComp')
                        ->leftJoin(Ln2user::class, 'u', 'WITH', 'u.idUser = c.idUser')
                        ->where('t.transType = :transType')
                        ->andWhere('c.compImpIdStudy = 0 OR c.compImpIdStudy = :idStudy')
                        ->andWhere('c.compRelease = 3 OR c.compRelease = 4 OR c.compRelease = 8 OR c.compRelease = 6 OR (c.compRelease = 2 AND c.idUser = :idUser)')
                        ->andWhere('c.classType = :classType')
                        ->setParameter('transType', Post::TRANSTYPE_COMPONENT)
                        ->setParameter('classType', $idfam)
                        ->setParameter('idStudy',$idStudy)
                        ->setParameter('idUser',$user->getIdUser())->getQuery()->getResult();

            $arrComp=[];

            for ($i=0; $i<count($listComp);$i++){
                $ret =[
                    'idComp' => $listComp[$i]['idComp'],
                    'label'  => $listComp[$i]['label'],
                    'idTranslation'  => $listComp[$i]['idTranslation'],
                    'water' => $listComp[$i]['water'],
                    'classType' => $listComp[$i]['classType'],
                    'subFamily' => $listComp[$i]['subFamily'],
                    'compRelease' => $listComp[$i]['compRelease'],
                ];
                array_push($arrComp,$ret);
            }
            // get SubFamily by idFamily
            $emTrans=$this->getDoctrine()->getRepository(Translation::class);
            $com = $emTrans->createQueryBuilder('t');
            $com->where('t.idTranslation BETWEEN :id1 AND :id2')
                    ->andWhere('t.transType = :const')
                    ->andWhere('t.codeLangue = :codeLangue')
                    ->setParameter('id1', $idfam * 100)
                    ->setParameter('id2', ($idfam+1) * 100 - 1)
                    ->setParameter('const', Post::TRANSTYPE_SUBFAMILY)
                    ->setParameter('codeLangue', $user->getCodeLangue());
            $listSubFamily = $com->getQuery()->getResult();
            $arrSubFam=[];

            for ($i=0; $i<count($listSubFamily);$i++){
                $ret =[
                    'label' => $listSubFamily[$i]->getLabel(),
                    'idTranslation' => $listSubFamily[$i]->getIdTranslation(),
                    'transType' => $listSubFamily[$i]->getTransType(),
                    'codeLangue' => $listSubFamily[$i]->getCodeLangue()
                ];
                array_push($arrSubFam,$ret);
            }
            $obj=[
                'compList'=>$arrComp,
                'listSubFamily'=>$arrSubFam
            ];

            return new JsonResponse($obj);
        }
    }

    /**
     * @Route("/getComponentListSub", name="get-ComponentSub-List")
     */
    public function ComponentSubAction(Request $request){
        $user=$this->getUser();

        if($user== NULL){
            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        // check idStudy already or not

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){
            return $this->redirectToRoute('load-study');
        }

        if($request->getMethod()=="POST"){
            $idsub=$request->get('idsub');
            // get Component in Component table by idStudy, subfamily
            $listComp = $this->getDoctrine()->getRepository(Component::class)->createQueryBuilder('c')
                        ->select('c.idComp,c.water,c.classType,c.subFamily,c.compRelease, t.idTranslation, t.label')
                        ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idComp')
                        ->leftJoin(Ln2user::class, 'u', 'WITH', 'u.idUser = c.idUser')
                        ->where('t.transType = :transType')
                        ->andWhere('c.compImpIdStudy = 0 OR c.compImpIdStudy = :idStudy')
                        ->andWhere('c.compRelease = 3 OR c.compRelease = 4 OR c.compRelease = 8 OR c.compRelease = 6 OR (c.compRelease = 2 AND c.idUser = :idUser)')
                        ->andWhere('c.subFamily = :subFamily')
                        ->setParameter('transType', Post::TRANSTYPE_COMPONENT)
                        ->setParameter('subFamily', $idsub)
                        ->setParameter('idStudy',$idStudy)
                        ->setParameter('idUser',$user->getIdUser())->getQuery()->getResult();
            $arrComp=[];

            for ($i=0; $i<count($listComp);$i++){
                $ret =[
                    'idComp' => $listComp[$i]['idComp'],
                    'label'  => $listComp[$i]['label'],
                    'idTranslation'  => $listComp[$i]['idTranslation'],
                    'water' => $listComp[$i]['water'],
                    'classType' => $listComp[$i]['classType'],
                    'subFamily' => $listComp[$i]['subFamily'],
                    'compRelease' => $listComp[$i]['compRelease'],
                ];
                array_push($arrComp,$ret);
            }
            $obj=[
                'compList'=>$arrComp
            ];

            return new JsonResponse($obj);
        }

    }

    /**
     * @Route("/getComponentListWater", name="get-ComponentWater-List")
     */
    public function ComponentWaterAction(Request $request){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        // check idStudy already or not

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){
            return $this->redirectToRoute('load-study');
        }

        if($request->getMethod()=="POST"){
            $water1=$request->get('water1');
            $water2=$request->get('water2');
            // get Component in Component table by water, idStudy
            $listComp = $this->getDoctrine()->getRepository(Component::class)->createQueryBuilder('c')
                        ->select('c.idComp,c.water,c.classType,c.subFamily,c.compRelease, t.idTranslation, t.label')
                        ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idComp')
                        ->leftJoin(Ln2user::class, 'u', 'WITH', 'u.idUser = c.idUser')
                        ->where('t.transType = :transType')
                        ->andWhere('c.compImpIdStudy = 0 OR c.compImpIdStudy = :idStudy')
                        ->andWhere('c.compRelease = 3 OR c.compRelease = 4 OR c.compRelease = 8 OR c.compRelease = 6 OR (c.compRelease = 2 AND c.idUser = :idUser)')
                        ->andWhere('c.water >= :water1 AND c.water <= :water2')
                        ->setParameter('transType', Post::TRANSTYPE_COMPONENT)
                        ->setParameter('water1', $water1)
                        ->setParameter('water2', $water2)
                        ->setParameter('idStudy',$idStudy)
                        ->setParameter('idUser',$user->getIdUser())
                        ->getQuery()->getResult();
            $arrComp=[];

            for ($i=0; $i<count($listComp);$i++){
                $ret =[
                    'idComp' => $listComp[$i]['idComp'],
                    'label'  => $listComp[$i]['label'],
                    'idTranslation'  => $listComp[$i]['idTranslation'],
                    'water' => $listComp[$i]['water'],
                    'classType' => $listComp[$i]['classType'],
                    'subFamily' => $listComp[$i]['subFamily'],
                    'compRelease' => $listComp[$i]['compRelease'],
                ];
                array_push($arrComp,$ret);
            }
            // get all subfamily in Translation table
            $listSubFamily = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType'=>Post::TRANSTYPE_SUBFAMILY,'codeLangue'=>$user->getCodeLangue()]);
            $arrSubFam=[];

            for ($i=0; $i<count($listSubFamily);$i++){
                $ret =[
                        'label' => $listSubFamily[$i]->getLabel(),
                        'idTranslation' => $listSubFamily[$i]->getIdTranslation(),
                        'transType' => $listSubFamily[$i]->getTransType(),
                        'codeLangue' => $listSubFamily[$i]->getCodeLangue()
                ];
                array_push($arrSubFam,$ret);
            }
            $obj=[
                    'compList'=>$arrComp,
                    'listSubFamily'=>$arrSubFam,

            ];

            return new JsonResponse($obj);
        }
    }

    /**
     * @Route("/getComponentListFamSub", name="get-Component-ListFamSub")
     */
    public function ComponentFamSubAction(Request $request){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        // check idStudy already or not

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }

        if($request->getMethod()=="POST"){
            $idfam=$request->get('idfam');
            $idsub=$request->get('idsub');
            // get Component in Component table by idFamily, subFamily, idStudy
            $listComp = $this->getDoctrine()->getRepository(Component::class)->createQueryBuilder('c')
                        ->select('c.idComp,c.water,c.classType,c.subFamily,c.compRelease, t.idTranslation, t.label')
                        ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idComp')
                        ->leftJoin(Ln2user::class, 'u', 'WITH', 'u.idUser = c.idUser')
                        ->where('t.transType = :transType')
                        ->andWhere('c.compImpIdStudy = 0 OR c.compImpIdStudy = :idStudy')
                        ->andWhere('c.compRelease = 3 OR c.compRelease = 4 OR c.compRelease = 8 OR c.compRelease = 6 OR (c.compRelease = 2 AND c.idUser = :idUser)')
                        ->andWhere('c.subFamily = :subFamily')
                        ->andWhere('c.classType = :classType')
                        ->setParameter('transType', Post::TRANSTYPE_COMPONENT)
                        ->setParameter('subFamily', $idsub)
                        ->setParameter('classType', $idfam)
                        ->setParameter('idStudy',$idStudy)
                        ->setParameter('idUser',$user->getIdUser())
                        ->getQuery()->getResult();
            $arrComp=[];

            for ($i=0; $i<count($listComp);$i++){
                $ret =[
                    'idComp' => $listComp[$i]['idComp'],
                    'label'  => $listComp[$i]['label'],
                    'idTranslation'  => $listComp[$i]['idTranslation'],
                    'water' => $listComp[$i]['water'],
                    'classType' => $listComp[$i]['classType'],
                    'subFamily' => $listComp[$i]['subFamily'],
                    'compRelease' => $listComp[$i]['compRelease'],
                ];
                array_push($arrComp,$ret);
            }
            $obj=[
                'compList'=>$arrComp
            ];

            return new JsonResponse($obj);
        }

    }

    /**
     * @Route("/getComponentListFamWater", name="get-Component-ListFamWater")
     */
    public function ComponentFamWaterAction(Request $request){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        // check idStudy already or not

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }

        if($request->getMethod()=="POST"){
            $idfam=$request->get('idfam');
            $water1=$request->get('water1');
            $water2=$request->get('water2');
            // get Component in Component table by idFamily, water, idStudy
            $listComp = $this->getDoctrine()->getRepository(Component::class)->createQueryBuilder('c')
                        ->select('c.idComp,c.water,c.classType,c.subFamily,c.compRelease, t.idTranslation, t.label')
                        ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idComp')
                        ->leftJoin(Ln2user::class, 'u', 'WITH', 'u.idUser = c.idUser')
                        ->where('t.transType = :transType')
                        ->andWhere('c.compImpIdStudy = 0 OR c.compImpIdStudy = :idStudy')
                        ->andWhere('c.compRelease = 3 OR c.compRelease = 4 OR c.compRelease = 8 OR c.compRelease = 6 OR (c.compRelease = 2 AND c.idUser = :idUser)')
                        ->andWhere('c.classType = :classType')
                        ->andWhere('c.water >= :water1 AND c.water <= :water2')
                        ->setParameter('transType', Post::TRANSTYPE_COMPONENT)
                        ->setParameter('classType', $idfam)
                        ->setParameter('water1', $water1)
                        ->setParameter('water2', $water2)
                        ->setParameter('idStudy',$idStudy)
                        ->setParameter('idUser',$user->getIdUser())
                        ->getQuery()->getResult();
            $arrComp=[];

            for ($i=0; $i<count($listComp);$i++){
                $ret =[
                    'idComp' => $listComp[$i]['idComp'],
                    'label'  => $listComp[$i]['label'],
                    'idTranslation'  => $listComp[$i]['idTranslation'],
                    'water' => $listComp[$i]['water'],
                    'classType' => $listComp[$i]['classType'],
                    'subFamily' => $listComp[$i]['subFamily'],
                    'compRelease' => $listComp[$i]['compRelease'],
                ];
                array_push($arrComp,$ret);
            }
            // get all subfamily in Translation table
            $emTrans=$this->getDoctrine()->getRepository(Translation::class);
            $com = $emTrans->createQueryBuilder('t');
            $com->where('t.idTranslation BETWEEN :id1 AND :id2')
                    ->andWhere('t.transType = :const')
                    ->andWhere('t.codeLangue = :codeLangue')
                    ->setParameter('id1', $idfam * 100)
                    ->setParameter('id2', ($idfam+1) * 100 - 1)
                    ->setParameter('const', Post::TRANSTYPE_SUBFAMILY)
                    ->setParameter('codeLangue', $user->getCodeLangue());
            $listSubFamily = $com->getQuery()->getResult();
            $arrSubFam=[];

            for ($i=0; $i<count($listSubFamily);$i++){
                $ret =[
                        'label' => $listSubFamily[$i]->getLabel(),
                        'idTranslation' => $listSubFamily[$i]->getIdTranslation(),
                        'transType' => $listSubFamily[$i]->getTransType(),
                        'codeLangue' => $listSubFamily[$i]->getCodeLangue()
                ];
                array_push($arrSubFam,$ret);
            }
            $obj=[
                'compList'=>$arrComp,
                'listSubFamily'=>$arrSubFam
            ];

            return new JsonResponse($obj);
        }
    }

    /**
     * @Route("/getComponentListFamSubWater", name="get-Component-ListFamSubWater")
     */
    public function ComponentFamSubWaterAction(Request $request){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        // check idStudy already or not

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }

        if($request->getMethod()=="POST"){
            $idfam=$request->get('idfam');
            $idsub=$request->get('idsub');
            $water1=$request->get('water1');
            $water2=$request->get('water2');
            // get Component in Component table by idFamily, water,subFamily, idStudy
            $listComp = $this->getDoctrine()->getRepository(Component::class)->createQueryBuilder('c')
                        ->select('c.idComp,c.water,c.classType,c.subFamily,c.compRelease, t.idTranslation, t.label')
                        ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idComp')
                        ->leftJoin(Ln2user::class, 'u', 'WITH', 'u.idUser = c.idUser')
                        ->where('t.transType = :transType')
                        ->andWhere('c.compImpIdStudy = 0 OR c.compImpIdStudy = :idStudy')
                        ->andWhere('c.compRelease = 3 OR c.compRelease = 4 OR c.compRelease = 8 OR c.compRelease = 6 OR (c.compRelease = 2 AND c.idUser = :idUser)')
                        ->andWhere('c.classType = :classType')
                        ->andWhere('c.subFamily = :subFamily')
                        ->setParameter('transType', Post::TRANSTYPE_SUBFAMILY)
                        ->andWhere('c.water >= :water1 AND c.water <= :water2')
                        ->setParameter('classType', $idfam)
                        ->setParameter('subFamily', $idsub)
                        ->setParameter('water1', $water1)
                        ->setParameter('water2', $water2)
                        ->setParameter('idStudy',$idStudy)
                        ->setParameter('idUser',$user->getIdUser())
                        ->getQuery()->getResult();
            $arrComp=[];

            for ($i=0; $i<count($listComp);$i++){
                $ret =[
                    'idComp' => $listComp[$i]['idComp'],
                    'label'  => $listComp[$i]['label'],
                    'idTranslation'  => $listComp[$i]['idTranslation'],
                    'water' => $listComp[$i]['water'],
                    'classType' => $listComp[$i]['classType'],
                    'subFamily' => $listComp[$i]['subFamily'],
                    'compRelease' => $listComp[$i]['compRelease'],
                ];
                array_push($arrComp,$ret);
            }
            $obj=[
                'compList'=>$arrComp
            ];

            return new JsonResponse($obj);
        }
    }

    /**
     * @Route("/getComponentListSubWater", name="get-Component-ListSubWater")
     */
    public function ComponentSubWaterAction(Request $request){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        // check idStudy already or not

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }

        if($request->getMethod()=="POST"){
            $idsub=$request->get('idsub');
            $water1=$request->get('water1');
            $water2=$request->get('water2');
            // get Component in Component table by water,subFamily, idStudy
            $listComp = $this->getDoctrine()->getRepository(Component::class)->createQueryBuilder('c')
                        ->select('c.idComp,c.water,c.classType,c.subFamily,c.compRelease, t.idTranslation, t.label')
                        ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idComp')
                        ->leftJoin(Ln2user::class, 'u', 'WITH', 'u.idUser = c.idUser')
                        ->where('t.transType = :transType')
                        ->andWhere('c.compImpIdStudy = 0 OR c.compImpIdStudy = :idStudy')
                        ->andWhere('c.compRelease = 3 OR c.compRelease = 4 OR c.compRelease = 8 OR c.compRelease = 6 OR (c.compRelease = 2 AND c.idUser = :idUser)')
                        ->andWhere('c.subFamily = :subFamily')
                        ->setParameter('transType', Post::TRANSTYPE_COMPONENT)
                        ->andWhere('c.water >= :water1 AND c.water <= :water2')
                        ->setParameter('subFamily', $idsub)
                        ->setParameter('water1', $water1)
                        ->setParameter('water2', $water2)
                        ->setParameter('idStudy',$idStudy)
                        ->setParameter('idUser',$user->getIdUser())
                        ->getQuery()->getResult();
            $arrComp=[];

            for ($i=0; $i<count($listComp);$i++){
                $ret =[
                    'idComp' => $listComp[$i]['idComp'],
                    'label'  => $listComp[$i]['label'],
                    'idTranslation'  => $listComp[$i]['idTranslation'],
                    'water' => $listComp[$i]['water'],
                    'classType' => $listComp[$i]['classType'],
                    'subFamily' => $listComp[$i]['subFamily'],
                    'compRelease' => $listComp[$i]['compRelease'],
                ];
                array_push($arrComp,$ret);
            }
            $obj=[
                'compList'=>$arrComp
            ];

            return new JsonResponse($obj);
        }
    }

    /**
     * @Route("/getComponentListAll", name="get-Component-ListAll")
     */
    public function ComponentAllAction(Request $request){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        // check idStudy already or not

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        if($request->getMethod()=="POST"){
            // get all Component in Component table
            $listComp = $this->getDoctrine()->getRepository(Component::class)->createQueryBuilder('c')
                        ->select('c.idComp,c.water,c.classType,c.subFamily,c.compRelease, t.idTranslation, t.label')
                        ->leftJoin(Translation::class, 't', 'WITH', 't.idTranslation = c.idComp')
                        ->leftJoin(Ln2user::class, 'u', 'WITH', 'u.idUser = c.idUser')
                        ->where('t.transType = :transType')
                        ->andWhere('c.compImpIdStudy = 0 OR c.compImpIdStudy = :idStudy')
                        ->andWhere('c.compRelease = 3 OR c.compRelease = 4 OR c.compRelease = 8 OR c.compRelease = 6 OR (c.compRelease = 2 AND c.idUser = :idUser)')
                        ->setParameter('transType', Post::TRANSTYPE_COMPONENT)
                        ->setParameter('idStudy',$idStudy)
                        ->setParameter('idUser',$user->getIdUser())
                        ->getQuery()->getResult();
            $arrComp=[];

            for ($i=0; $i<count($listComp);$i++){
                $ret =[
                    'idComp' => $listComp[$i]['idComp'],
                    'label'  => $listComp[$i]['label'],
                    'idTranslation'  => $listComp[$i]['idTranslation'],
                    'water' => $listComp[$i]['water'],
                    'classType' => $listComp[$i]['classType'],
                    'subFamily' => $listComp[$i]['subFamily'],
                    'compRelease' => $listComp[$i]['compRelease'],
                ];
                array_push($arrComp,$ret);
            }
            // get all SubFamily in Translate table
            $listSubFamily = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType'=>Post::TRANSTYPE_SUBFAMILY,'codeLangue'=>$user->getCodeLangue()]);
            $arrSubFam=[];

            for ($i=0; $i<count($listSubFamily);$i++){
                $ret =[
                    'label' => $listSubFamily[$i]->getLabel(),
                    'idTranslation' => $listSubFamily[$i]->getIdTranslation(),
                    'transType' => $listSubFamily[$i]->getTransType(),
                    'codeLangue' => $listSubFamily[$i]->getCodeLangue()
                ];
                array_push($arrSubFam,$ret);
            }
            $obj=[
                'compList'=>$arrComp,
                'listSubFamily'=>$arrSubFam,
            ];

            return new JsonResponse($obj);
        }
    }

    /**
     * @Route("/getComponentToEml", name="get-ComponentToEml")
     */
    public function ComponentToEmlAction(Request $request, MeshService $mesh){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idProduct=$session->get('idProd');

        if($idProduct == null || $idProduct == 0 || $idProduct == ""){

            return $this->redirectToRoute('Product-Characteristic');
        }

        if($request->getMethod()=="POST"){
            $productEml =  new ProductElmt();
            $idComp=$request->get('_component-list');
            $idShape=$session->get('idShape');
            $Dim1=$session->get('dim1');
            $Dim3=$session->get('dim3');
            // get real mass and mass default in MinMax table
            $thick=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_DIM2])[0]->getDefaultValue();
            $objShape = $this->getDoctrine()->getRepository(Shape::class);
            $rsShape = $objShape->findBy(['idShape'=> $idShape]);
            $objProduct = $this->getDoctrine()->getRepository(Product::class);
            $rsProduct = $objProduct->findBy(['idProd'=>$idProduct]);
            $objComponent = $this->getDoctrine()->getRepository(Component::class);
            $rsComponent = $objComponent->findBy(['idComp'=>$idComp]);
            //  create item ProductElmt
            $productEml->setIdComp($rsComponent[0]);
            $productEml->setIdProd($rsProduct[0]);
            $productEml->setIdShape($rsShape[0]);
            $productEml->setShapeParam1($Dim1);
            $productEml->setShapeParam3($Dim3);
            $productEml->setShapeParam2($thick);
            $productEml->setOriginalThick($thick);
            $productEml->setProdElmtIso(0);
            $productEml->setProdElmtName("");
            $em = $this->getDoctrine()->getManager();
            $em->persist($productEml);
            $em->flush();
            // calculate dim 1, 3
            $calDim1=0;
            $calDim3=0;

            if($idShape==9){
                // get ProductElmt by idProduct
                 $listProductChar=$this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$rsProduct[0]->getIdProd()]);

                 if(count($listProductChar) !=0){

                    for($i=0;$i<count($listProductChar);$i++){

                        if($i==0){
                            $calDim1= $listProductChar[$i]->getShapeParam1();
                            $calDim3= $listProductChar[$i]->getShapeParam3();
                        }else{
                            $calDim1+=2 * $listProductChar[$i]->getOriginalThick();
                            $calDim3+=2 * $listProductChar[$i]->getOriginalThick();
                        }
                    }
                    // update Dim1, 3 to ProductElmt by idProduct
                    $qbDim1=$em->createQueryBuilder();
                    $q=$qbDim1->update(ProductElmt::class,'p')
                    ->set('p.shapeParam1',$qbDim1->expr()->literal($calDim1))
                    ->set('p.shapeParam3',$qbDim1->expr()->literal($calDim3))
                    ->where('p.idProd= ?1')
                    ->setParameter(1, $rsProduct[0]->getIdProd())->getQuery()->execute();
                }
            }
            // calculate dim2
            if($idShape==6 || $idShape==7 || $idShape==8 || $idShape==9){
                $listProductChar=$this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$rsProduct[0]->getIdProd()]);
                $sumDim2=0;

                for($i=0;$i<count($listProductChar);$i++){

                    if($i==0){
                        $sumDim2= $listProductChar[$i]->getOriginalThick();
                    }else{
                        $sumDim2+=2 * $listProductChar[$i]->getOriginalThick();
                    }
                }
            }else{
                $qbthick = $em->createQueryBuilder();
                $qbthick->select('sum(pe.originalThick)');
                $qbthick->from(ProductElmt::class,'pe');
                $qbthick->where('pe.idProd = :idProd');
                $qbthick->setParameter('idProd',$rsProduct[0]->getIdProd());
                $sumDim2 = $qbthick->getQuery()->getSingleScalarResult();
            }
            // update Dim2 to ProductEtml by idProduct
            $qbDim2=$em->createQueryBuilder();
            $q=$qbDim2->update(ProductElmt::class,'p')
                    ->set('p.shapeParam2',$qbDim2->expr()->literal($sumDim2))
                    ->where('p.idProd= ?1')
                    ->setParameter(1, $rsProduct[0]->getIdProd())->getQuery()->execute();
            // get ProductElmt by idProduct. Calculate weight, real weight Product
            $listComponentList = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$rsProduct[0]->getIdProd()]);

            if(count($listComponentList) != 0){
                $prodWeight =0;
                $prodRealweight=0;

                for ($i=0; $i<count($listComponentList);$i++){
                    $prodWeight += $listComponentList[$i]->getProdElmtWeight();
                    $prodRealweight += $listComponentList[$i]->getProdElmtRealweight();
                }
                // update weight, real weight Product in Product table
                $rsProduct[0]->setProdWeight($prodWeight);
                $rsProduct[0]->setProdRealweight($prodRealweight);
                $em->flush();
            }
            // set defaut value prodIso and delete initialTemp
            $mesh->getProdTempDef($request);
            $mesh->startMeshGenerate($idStudy);
            $mesh->startMeshPosition($idStudy);
            
            return $this->redirectToRoute('Product-Characteristic');
        }
    }

    /**
     * @Route("/updateThickness", name="update-Thickness")
     */
    public function updateThicknessAction(Request $request){
         $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');

        if($idStudy == null || $idStudy == 0 || $idStudy == ""){

            return $this->redirectToRoute('load-study');
        }
        $idProduct=$session->get('idProd');

        if($idProduct == null || $idProduct == 0 || $idProduct == ""){

            return $this->redirectToRoute('Product-Characteristic');
        }  
        if($request->getMethod()=="POST"){
            $idProdEml=$request->get('_idProdEml');
            $idShape=$session->get('idShape');
            $thick=$request->get('_specific-Dim');
            $description=$request->get('_description');
            $objProdEml=$this->getDoctrine()->getRepository(ProductElmt::class)->find($idProdEml);
            $objProdEml->setOriginalThick($thick);
            $objProdEml->setProdElmtName($description);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $objProdEml = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProductElmt'=>$idProdEml]);
            $objProduct = $this->getDoctrine()->getRepository(Product::class)->findBy(['idProd'=>$objProdEml[0]->getIdProd()]);
            // process dim2
            $sumDim2=0;

            if($idShape==6 || $idShape==7 || $idShape==8 || $idShape==9){
                 $listProductChar=$this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$objProduct[0]->getIdProd()]);

                for($i=0;$i<count($listProductChar);$i++){

                    if($i==0){
                        $sumDim2+= $listProductChar[$i]->getOriginalThick();
                    }else{
                        $sumDim2+=2 * $listProductChar[$i]->getOriginalThick();
                    }
                }
            }else{
                $qbthick = $em->createQueryBuilder();
                $qbthick->select('sum(pe.originalThick)');
                $qbthick->from(ProductElmt::class,'pe');
                $qbthick->where('pe.idProd = :idProd');
                $qbthick->setParameter('idProd',$objProduct[0]->getIdProd());
                $sumDim2 = $qbthick->getQuery()->getSingleScalarResult();
            }
            $qbDim2=$em->createQueryBuilder();
            $q=$qbDim2->update(ProductElmt::class,'p')
                    ->set('p.shapeParam2',$qbDim2->expr()->literal($sumDim2))
                    ->where('p.idProd= ?1')
                    ->setParameter(1, $objProduct[0]->getIdProd())->getQuery()->execute();

            return $this->redirectToRoute('Product-Characteristic');
        }
    }

    /**
     * @Route("/update-realweight-prod", name="update-realweight-prod")
     */
    public function updateRealWeightProdAction(Request $request){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idProd=$session->get('idProd');

        if($idProd == null || $idProd=="" || $idProd==0){

            return $this->redirectToRoute('Product-Characteristic');
        }
        $realweight=$request->get('realweight');
        $em=$this->getDoctrine()->getManager();
        $objProd=$this->getDoctrine()->getRepository(Product::class)->find($idProd);

        if(count($objProd)!=0){
            $objProd->setProdRealweight($realweight);
            $em->flush();
        }

        return new JsonResponse([]);
    }

    /**
     * @Route("/update-realweight-comp", name="update-realweight-comp")
     */
    public function updateRealWeightCompAction(Request $request){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $idProdEml=$request->get('_in-idProdEml-comp');
        $realweight=$request->get('_in-realweight-comp');
        $sumRealWeight=0;
        $em=$this->getDoctrine()->getManager();
        $objProdEml=$this->getDoctrine()->getRepository(ProductElmt::class)->find($idProdEml);

        if(count($objProdEml)!=0){
            $objProdEml->setProdElmtRealweight($realweight);
            $em->flush();
            $objProd=$this->getDoctrine()->getRepository(Product::class)->find(['idProd'=>$objProdEml->getIdProd()]);
            $objProdEml2=$this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$objProd->getIdProd()]);

            for($i=0;$i<count($objProdEml2);$i++){
                $sumRealWeight += $objProdEml2[$i]->getProdElmtRealweight();
            }
            $objProd->setProdRealweight($sumRealWeight);
            $em->flush();
        }

        return $this->redirectToRoute('Product-Characteristic');
    }

    /**
     * @Route("/delete-comp/{id}", name="delete-component")
     */
    public function deteleAction(Request $request,$id, MeshService $mesh){
        $em = $this->getDoctrine()->getManager();
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $objStudy = $em->find(Studies::class, $idStudy);
        $mesh->delAllMeshPosition( $objStudy);

        if ($idStudy == null) {

            return $this->redirectToRoute('load-study');
        }
        $idProd=$session->get('idProd');

        if($idProd == null || $idProd=="" || $idProd==0){

            return $this->redirectToRoute('Product-Characteristic');
        }
        $objProd=$this->getDoctrine()->getRepository(Product::class)->find($idProd);
        $objProdEmlCheck=$this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$objProd->getIdProd()]);

        if(count($objProdEmlCheck) == 1 ){
            $session->set('dim1check', $objProdEmlCheck[0]->getShapeParam1());
            $session->set('dim3check', $objProdEmlCheck[0]->getShapeParam3());
            $session->set('nameproductcheck', $objProd->getProdname());
            $session->set('idShapeCheck', $objProdEmlCheck[0]->getIdShape()->getIdShape());
        }
        $em=$this -> getDoctrine() -> getManager();
        $objProdEml2= $em->getRepository(ProductElmt::class)->find($id);
        $idProd=$objProdEml2->getIdProd();
        $sumRealWeight=0;$sumWeight=0;
        $em-> remove($objProdEml2);
        $em ->flush();
        $objProd=$this->getDoctrine()->getRepository(Product::class)->find(['idProd'=>$idProd]);
        $objProdEml2=$this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$objProd->getIdProd()]);

        if(count($objProdEml2) ==0){
            $session->set('checkProductOnce', 1);
        }

        if(count($objProdEml2) >0){

            for($i=0;$i<count($objProdEml2);$i++){
                $sumWeight += $objProdEml2[$i]->getProdElmtWeight();
                $sumRealWeight += $objProdEml2[$i]->getProdElmtRealweight();
            }
            $objProd->setProdWeight($sumWeight);
            $objProd->setProdRealweight($sumRealWeight);
            $em->flush();
            $objShape=$this->getDoctrine()->getRepository(Shape::class)->find($objProdEml2[0]->getIdShape());
            $idShape=$objShape->getIdShape();
            $sumDim2=0;

            if($idShape==6 || $idShape==7 || $idShape==8 || $idShape==9){

                for($i=0;$i<count($objProdEml2);$i++){

                    if($i==0){
                        $sumDim2+= $objProdEml2[$i]->getOriginalThick();
                    }else{
                        $sumDim2+=2 * $objProdEml2[$i]->getOriginalThick();
                    }
                }
            }else{
                $qbthick = $em->createQueryBuilder();
                $qbthick->select('sum(pe.originalThick)');
                $qbthick->from(ProductElmt::class,'pe');
                $qbthick->where('pe.idProd = :idProd');
                $qbthick->setParameter('idProd',$objProd->getIdProd());
                $sumDim2 = $qbthick->getQuery()->getSingleScalarResult();
            }

            $qbDim2=$em->createQueryBuilder();
            $q=$qbDim2->update(ProductElmt::class,'p')
                    ->set('p.shapeParam2',$qbDim2->expr()->literal($sumDim2))
                    ->where('p.idProd= ?1')
                    ->setParameter(1, $objProd->getIdProd())->getQuery()->execute();
        }else{
            $objProd->setProdWeight(0);
            $objProd->setProdRealweight(0);
            $em->flush();
        }
        // set defaut value prodIso and delete initialTemp
        $mesh->getProdTempDef($request);
        $mesh->startMeshPosition($objStudy); 
        $mesh->startMeshGenerate($objStudy);
        
        return $this->redirectToRoute('Product-Characteristic');
    }
    /**
     * @Route("/getAllMinMaxProductChar", name="getAllMinMaxProductChar")
     */
    public function getAllMinMaxProductCharAction(){
        $user=$this->getUser();

        if($user== NULL){

            return $this->redirectToRoute('login');
        }
        $limitDim1=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_DIM1]);
        $limitDim2=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_DIM2]);
        $limitDim3=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_DIM3]);
        $realProd=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_PROD_WEIGHT]);

        $ret =[
            'limitmin1'=>$limitDim1[0]->getLimitMin(),
            'limitmax1'=>$limitDim1[0]->getLimitMax(),
            'defaultvalue1'=>$limitDim1[0]->getDefaultValue(),
            'limitmin2'=>$limitDim2[0]->getLimitMin(),
            'limitmax2'=>$limitDim2[0]->getLimitMax(),
            'defaultvalue2'=>$limitDim2[0]->getDefaultValue(),
            'limitmin3'=>$limitDim3[0]->getLimitMin(),
            'limitmax3'=>$limitDim3[0]->getLimitMax(),
            'defaultvalue3'=>$limitDim3[0]->getDefaultValue(),
            'limitminprod'=>$realProd[0]->getLimitMin(),
            'limitmaxprod'=>$realProd[0]->getLimitMax(),
            'limitminmass'=>$realProd[0]->getLimitMin(),
            'limitmaxmass'=>$realProd[0]->getLimitMax(),
            ];

        return new JsonResponse($ret);
    }
}
