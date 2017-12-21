<?php
/**
 * Created by PhpStorm.
 * User: huytd
 * Date: 11/20/17
 * Time: 11:22 AM
 */
namespace AppBundle\Controller\MeshTemp;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use AppBundle\Entity\Production;
use AppBundle\Entity\Studies;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\Translation;
use AppBundle\Entity\MeshPosition;
use AppBundle\Entity\MeshGeneration;
use AppBundle\Entity\Post;
use AppBundle\Entity\MinMax;
use AppBundle\Cryosoft\MeshService;
use AppBundle\Entity\ProdcharColors;
use AppBundle\Entity\InitialTemperature;
use AppBundle\Entity\MeshParamDef;
use AppBundle\Cryosoft\KernelCalculateService;

class MeshController extends Controller
    {
    /**
     * @Route("/mesh", name="show-mesh")
     */
    public function showAction(Request $request, MeshService $mesh, KernelCalculateService $kernel)
        {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $objUser = $em->find(Ln2user::class , $user);
        if ($user == null) {
            return $this->redirectToRoute('login');
            }
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $objStudy = $em->find(Studies::class , $idStudy);
        if (($idStudy == null) || ($objStudy->getCalculationMode() == 0)) {
            $session->getFlashBag()->set('error', "Study do not defined !!");
            return $this->redirectToRoute('load-study');
            }
        $objStudy = $em->find(Studies::class , $idStudy);
        $idProd = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy]);
        $idProEmlt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $idProd], ['idComp' => 'DESC']);
        // dump($idProEmlt[0]->getIdComp()->getcompVersion());die;
        if (count($idProEmlt) <= 0) {
            return $this->redirectToRoute('Product-Characteristic');
            }
        $comps = [];
        for ($i = 0; $i < count($idProEmlt); $i++) {
            $comps[] = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType' => Post::TRANS_TYPE_COMP_LIST, 'idTranslation' => $idProEmlt[$i]->getIdComp() , 'codeLangue' => $objUser->getCodeLangue() ]) [0];
            $compsStatus[] = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType' => Post::TRANS_TYPE_STATUS_COMP, 'idTranslation' => $idProEmlt[$i]->getIdComp()->getCompRelease() , 'codeLangue' => $objUser->getCodeLangue() ])[0];
            }
        $prod = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy]) [0];
        $rsProduction = $this->getDoctrine()->getRepository(Production::class)->findBy(['idStudy' => $prod->getIdStudy()]);
        $initialT = $this->getDoctrine()->getRepository(InitialTemperature::class)->findBy(['idProduction' => $rsProduction[0]],['mesh2Order'=>'ASC']);
        $mesh->initProdCharColorWithUserDef($user, $idProd[0]);
        $mesh->startMeshGenerate( $objStudy);
        // $kernel->meshBuilder($objStudy->getIdStudy());
        $mesh->productShape($objStudy, $user);
        $mesh->startMeshPosition( $objStudy);
        return $this->render('mesh/meshTemp.html.twig', [
             'studyName' => $objStudy,
             'productName' => $idProd[0]->getProdname() ,
             'product' => $idProd[0],
             'productEmlt' => $idProEmlt,
             'ProdComponents' => $comps,
             'discription' => $idProEmlt,
             'initialT' => count($initialT) > 0 ? $initialT : null,
             'compsStatus' => $compsStatus, ]);
        }

    /**
     * @Route("/checkProdIso", name="check-product-Iso")
     */
    public function checkProdIsoAction(Request $request, MeshService $mesh)
        {
        $em = $this->getDoctrine()->getManager();
        $this->em = $em;
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $initialTemp = $request->get('initialTemp');
        $prodIso = empty($request->get('checkIsoProd')) ? 0 : 1;
        $prod = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy]) [0];
        // dump($prod);die;
        $rsProduction = $this->getDoctrine()->getRepository(Production::class)->findBy(['idStudy' => $prod->getIdStudy()]);
        // dump($rsProduction);die;
        $rsInitialTemp = $this->getDoctrine()->getRepository(InitialTemperature::class)->findBy(['idProduction' => $rsProduction]);
        if ($prodIso) {

            if ($initialTemp == null) {
                $session->getFlashBag()->set('error', "Initial Temperature do not null !!");
                }
            if (is_numeric($initialTemp)) {
                $initialTemp = (double)$initialTemp;
                $limits = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => POST::LIMMIT_ITEM_INITIAL_TEMP]);
                if ($initialTemp < $limits[0]->getLimitMin() || $initialTemp > $limits[0]->getLimitMax()) {
                    $session->getFlashBag()->set('error', "Out of range " . $limits[0]->getLimitMin() . " to " . $limits[0]->getLimitMax() . "!!");
                    } else {
                    $rsProdEmlt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $prod]);
                    $rsMeshPo = $this->getDoctrine()->getRepository(MeshPosition::class)->findBy(['idProductElmt' => $rsProdEmlt]);
                    if (count($rsMeshPo[0]->getIdProductElmt()) > 0) {
                        $prod->setProdIso($prodIso);
                            for ($i = 0; $i < count($rsProdEmlt); $i++){
                                $rsProdEmlt[$i]->setProdElmtIso(0);
                            }
                        $em->flush();
                            if($rsProduction !=null) {
                            $initialTemps = $this->getDoctrine()->getRepository(InitialTemperature::class)->findBy(['idProduction' => $rsProduction[0]->getIdProduction() ]);
                                foreach($initialTemps as $each) {
                                    $em->remove($each);
                                }
                                $em->flush();
                                for ($i = 0; $i < $mesh->getNumberofMeshPoints(); $i++) {
                                    $item = new InitialTemperature();
                                    $item->setInitialT($initialTemp);
                                    $item->setMesh2Order($i);
                                    $item->setIdProduction($rsProduction[0]);
                                    $em->persist($item);
                                }
                                $em->flush();
                            }else {
                                $session->getFlashBag()->set('error', "Production not defined !!");
                            }
                        
                        } else {
                        $session->getFlashBag()->set('error', "Initial Temperature not execute !!");
                        }
                    }
                } else {
                $session->getFlashBag()->set('error', "Initial Temperature must be number !!");
                }
            } else {
                $rsProdEmlt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $prod]);
                $rsMeshPo = $this->getDoctrine()->getRepository(MeshPosition::class)->findBy(['idProductElmt' => $rsProdEmlt]);

                if (count($rsMeshPo[0]->getIdProductElmt()) > 0) {
                    $prod->setProdIso($prodIso);
                    for ($i = 0; $i < count($rsProdEmlt); $i++){
                        $rsProdEmlt[$i]->setProdElmtIso(1);
                        }
                     $em->flush();
                    if($rsProduction != null) {
                        $initialTemps = $this->getDoctrine()->getRepository(InitialTemperature::class)->findBy(['idProduction' => $rsProduction[0]->getIdProduction() ]);
                        foreach($initialTemps as $each) {
                            $em->remove($each);
                            }
                        $em->flush();
                        // insert null initial temperature 
                        for ($i = 0; $i < count($rsProdEmlt)* $mesh->getNumberofMeshPoints() ; $i++){
                                $item = new InitialTemperature();
                                $item->setInitialT(null);
                                $item->setMesh2Order($i);
                                $item->setIdProduction($rsProduction[0]);
                                $em->persist($item);
                            }
                        $em->flush();
                    }else {
                        $session->getFlashBag()->set('error', "Production not defined !!");
                    }
            } else {
                $session->getFlashBag()->set('error', "Initial Temperature not execute !!");
                }
            }
        return $this->redirectToRoute('show-mesh');
        }

    /**
     * @Route("/modifyProdEmlIso", name="modify-productEmltIso")
     */
    public function checkProdElmtIso(Request $request, MeshService $mesh)
        {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $session = $request->getSession();
        $objUser = $em->find(Ln2user::class , $user);
        $idStudy = $session->get('idStudy');
        $idProdComp = $request->get('idProdcomp');
        $idProd = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy])[0];
        // $idProEmlt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $idProd]);
        $idProEmltIso = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idComp' => $idProdComp, 'idProd' => $idProd]);
        $rsProdEmlt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $idProd]);
        $rsProduction = $this->getDoctrine()->getRepository(Production::class)->findBy(['idStudy' => $idProd->getIdStudy()]);
        $initialProdComp = $request->get('initialProdComp');
        $prodElmt = empty($request->get('checkIsoProdEmlt')) ? 0 : 1;
        $initialTemps = $this->getDoctrine()->getRepository(InitialTemperature::class)->findBy(['idProduction' => $rsProduction[0]->getIdProduction() ]);
        if ($prodElmt) {

            if ($initialProdComp == null) {
                $session->getFlashBag()->set('error', "Initial Temperature do not null !!");
                return $this->redirectToRoute('show-mesh');
                }

            if (is_numeric($initialProdComp)) {
                $initialProdComp = (double)$initialProdComp;
                $limitInitialProdcomp = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => POST::LIMMIT_ITEM_INITIAL_TEMP]);

                if ($initialProdComp < $limitInitialProdcomp[0]->getLimitMin() || $initialProdComp > $limitInitialProdcomp[0]->getLimitMax()) {
                    $session->getFlashBag()->set('error', "Out of range " . $limitInitialProdcomp[0]->getLimitMin() . " to " . $limitInitialProdcomp[0]->getLimitMax() . "!!");
                    } else {
                        $idProEmltIso[0]->setProdElmtIso($prodElmt);
                        if($rsProduction != null) {
                            // get position start 
                            $position_start = $request->get('position') * $mesh->getNumberofMeshPoints();
                            // select count (idMeshPosition) where id productelmt  = ,meshaxi =2
                            $mpcount = $em->createQueryBuilder()
                                    ->select('count(meshPosition.idMeshPosition)')
                                    ->from(MeshPosition::class, 'meshPosition')
                                    ->where('meshPosition.idProductElmt = :idProductElmt')
                                    ->andWhere('meshPosition.meshAxis = :meshAxis')
                                    ->setParameter('idProductElmt', $rsProdEmlt[0]->getIdProductElmt())
                                    ->setParameter('meshAxis', 2)
                                    ->getQuery()->getSingleScalarResult();
                            $position_end = $position_start + intval($mpcount,10);

                            $delIinital = $em->createQueryBuilder()
                            ->delete(InitialTemperature::class , 'initialT')
                            ->where('initialT.idProduction = :idProduction')
                            ->andWhere('initialT.mesh2Order >= :position_start')
                            ->andWhere('initialT.mesh2Order <= :position_end')
                            ->setParameter('idProduction', $rsProduction[0]->getIdProduction())
                            ->setParameter('position_start', $position_start)
                            ->setParameter('position_end', $position_end)
                            ->getQuery()->execute();

                                for ($i = $position_start; $i <= $position_end; $i++){
                                    $item = new InitialTemperature();
                                    $item->setInitialT($initialProdComp);
                                    $item->setMesh2Order($i);
                                    $item->setIdProduction($rsProduction[0]);
                                    $em->persist($item);
                                    }
                                $em->flush();
                            }else {
                             $session->getFlashBag()->set('error', "Production not defined !!");   
                            }
                         }
                } else {
                    $session->getFlashBag()->set('error', "Initial Temperature must be number !!");
                    return $this->redirectToRoute('show-mesh');
                }
            } else {
                $idProEmltIso[0]->setProdElmtIso($prodElmt);
                $em->flush();
                $meshTempPoint = $request->get('meshTempPoints');
                if($rsProduction != null) {
                    $position_start = $request->get('position') * $mesh->getNumberofMeshPoints();
                    // select count (idMeshPosition) where id productelmt  = ,meshaxi =2
                    $mpcount = $em->createQueryBuilder()
                            ->select('count(meshPosition.idMeshPosition)')
                            ->from(MeshPosition::class, 'meshPosition')
                            ->where('meshPosition.idProductElmt = :idProductElmt')
                            ->andWhere('meshPosition.meshAxis = :meshAxis')
                            ->setParameter('idProductElmt', $rsProdEmlt[0]->getIdProductElmt())
                            ->setParameter('meshAxis', 2)
                            ->getQuery()->getSingleScalarResult();
                    $position_end = $position_start + intval($mpcount,10);
                    $delIinital = $em->createQueryBuilder()
                    ->delete(InitialTemperature::class , 'initialT')
                    ->where('initialT.idProduction = :idProduction')
                    ->andWhere('initialT.mesh2Order >= :position_start')
                    ->andWhere('initialT.mesh2Order <= :position_end')
                    ->setParameter('idProduction', $rsProduction[0]->getIdProduction())
                    ->setParameter('position_start', $position_start)
                    ->setParameter('position_end', $position_end)
                    ->getQuery()->execute();
                    $counter = 0;
                    // dump();die;
                    for ($i = $position_start; $i < $position_end; $i++) {
                        $item = new InitialTemperature();
                        $item->setInitialT(floatval($meshTempPoint[$counter]));
                        $item->setMesh2Order($i);
                        $item->setIdProduction($rsProduction[0]);
                        $em->persist($item);
                        $counter++;
                    }
                    $em->flush();
                    for ($i=0; $i < count($initialTemps); $i++) { 
                        if($initialTemps[$i]->getInitialT() == null) {
                            $initialTemps[$i]->setInitialT(0);
                            $em->flush();
                        }
                    }
                } else {
                    $session->getFlashBag()->set('error', "Production not defined !!");
                }
            }

        return $this->redirectToRoute('show-mesh');
        }

    /**
     * @Route("/modifyProdcomp", name="modal-product-component")
     */
    public function getModalInitialTempProdComp(Request $request)
        {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $objUser = $em->find(Ln2user::class , $user);

        $idProdComp = $request->get('id');
        $position = $request->get('pos');
        $idProd = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy])[0];
        $compName = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType' => 1, 
            'idTranslation' => $idProdComp,
            'codeLangue' => $objUser->getCodeLangue() ]);
        $idProEmltIso = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idComp' => $idProdComp, 'idProd' => $idProd])[0];

        $rsProduction = $this->getDoctrine()->getRepository(Production::class)->findBy(['idStudy' => $idProd->getIdStudy()]);
        $initialT = $this->getDoctrine()->getRepository(InitialTemperature::class)->findBy(['idProduction' => $rsProduction[0]],['mesh2Order'=>'ASC']);
        $meshPoint = $this->getDoctrine()->getRepository(MeshPosition::class)->findBy(['idProductElmt'=>$idProEmltIso, 'meshAxis' => 2], ['meshAxis'=>'DESC']);
        return $this->render('mesh/modalProductComp.html.twig',[
            'idProdComps' => $compName,
            'productEmlt' => $idProEmltIso,
            'idProdComponent' => $idProdComp, 
            'position' => $position,
            'initialT' => count($initialT) > 0 ? $initialT : null,
            'meshPoint' =>$meshPoint,
             ]);
        }

    /**
     * @Route("/modalMeshParam", name="modal-mesh-Parameter")
     */
    public function modalMeshParam(Request $request, MeshService $mesh) 
    {
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $user = $this->getUser();
        extract($mesh->productShape($idStudy, $user));
        $idProd = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy]);
        $meshGenerate = $this->getDoctrine()->getRepository(MeshGeneration::class)->findBy(['idProd'=>$idProd])[0];
        $mesh->loadMeshGeneration($idStudy);
        if($meshGenerate->getMesh1Mode() == 0) {
            $mesh1Size = $meshGenerate->getMesh1Size();
            $mesh2Size = $meshGenerate->getMesh2Size();
            $mesh3Size = $meshGenerate->getMesh3Size();
        }else {
            $mesh1Size = $meshGenerate->getMesh1Int();
            $mesh2Size = $meshGenerate->getMesh2Int();
            $mesh3Size = $meshGenerate->getMesh3Int();
        }
        return $this->render('mesh/modalMeshParam.html.twig',[
            'shape' =>$shape,
            'shapeName' => $shapeName,
            'statuses' => $statuses,
            'mesh1Size' => $mesh1Size,
            'mesh2Size' => $mesh2Size,
            'mesh3Size' => $mesh3Size,
            'meshGenerate'=>$meshGenerate,
        ]);
    }

    /**
     * @Route("/meshParam", name="mesh-Parameter")
     */
    public function meshParamAction(Request $request, MeshService $mesh)
    {
         $em = $this->getDoctrine()->getManager();
         $userId = $this->getUser();
         $session = $request->getSession();
         $idStudy = $session->get('idStudy');
         $idProd = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy])[0];
         $meshGenerate = $this->getDoctrine()->getRepository(MeshGeneration::class)->findBy(['idProd'=>$idProd])[0];
         $meshDef = $this->getDoctrine()->getRepository(MeshParamDef::class)->findBy(['idUser'=>$userId])[0];
         $meshSize1 = $request->get('meshSize1');
         $meshSize2 = $request->get('meshSize2');
         $meshSize3 = $request->get('meshSize3');
            if($request->get('meshMode') == 0) {
                if($meshSize1 != $meshGenerate->getMesh1Size() || $meshSize2 != $meshGenerate->getMesh2Size() 
                || $meshSize3 != $meshGenerate->getMesh3Size()){
                    $mesh->modifyProdIso($request, $idStudy );
                    $mesh->delAllMeshPosition($idStudy);
                    $calcultype = 1;
                    $cal = 0;
                    $meshGenerate->setMesh1Fixed($calcultype);
                    $meshGenerate->setMesh2Fixed($calcultype);
                    $meshGenerate->setMesh3Fixed($calcultype);
                    $meshGenerate->setMesh1Mode($cal);
                    $meshGenerate->setMesh2Mode($cal);
                    $meshGenerate->setMesh3Mode($cal);
                    $meshGenerate->setMesh1Size($meshSize1);
                    $meshGenerate->setMesh2Size($meshSize2);
                    $meshGenerate->setMesh3Size($meshSize3);
                    $mesh->modifMeshParam($idStudy);
                    $em->flush();
                }
            } else {
                if(($meshSize1 != $meshGenerate->getMesh1Int() || $meshSize2 != $meshGenerate->getMesh2Int() || $meshSize3 != $meshGenerate->getMesh3Int())){
                    $mesh->modifyProdIso($request, $idStudy);
                    $mesh->delAllMeshPosition($idStudy);
                    $calcultype = 0;
                    $cal = 1;
                    $meshGenerate->setMesh1Fixed($calcultype);
                    $meshGenerate->setMesh2Fixed($calcultype);
                    $meshGenerate->setMesh3Fixed($calcultype);
                    $meshGenerate->setMesh1Mode($cal);
                    $meshGenerate->setMesh2Mode($cal);
                    $meshGenerate->setMesh3Mode($cal);
                    $meshGenerate->setMesh1Size($calcultype);
                    $meshGenerate->setMesh2Size($calcultype);
                    $meshGenerate->setMesh3Size($calcultype);
                    $meshGenerate->setMesh1Int($meshSize1);
                    $meshGenerate->setMesh2Int($meshSize2);
                    $meshGenerate->setMesh3Int($meshSize3);
                    $meshGenerate->setMesh1Ratio($meshDef->getMeshRatio());
                    $meshGenerate->setMesh2Ratio($meshDef->getMeshRatio());
                    $meshGenerate->setMesh3Ratio($meshDef->getMeshRatio());
                    $mesh->modifMeshParam($idStudy);
                    $em->flush();
                }
            }
        
        return $this->redirectToRoute('show-mesh');
     }

     /**
     * @Route("/modalMeshDef", name="mesh-default-modal")
     */
     public function meshDefModal()
     {
        return $this->render('mesh/modalMeshDefaut.html.twig');
     }

     /**
     * @Route("/modalMeshBuilder", name="mesh-builder-modal")
     */
     public function meshBuilderModal(Request $request)
     {
         $em = $this->getDoctrine()->getManager();
         $userId = $this->getUser();
         $session = $request->getSession();
         $idStudy = $session->get('idStudy');
         $idProd = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy])[0];
         $meshGenerate = $this->getDoctrine()->getRepository(MeshGeneration::class)->findBy(['idProd'=>$idProd])[0];

        return $this->render('mesh/modalMeshBuilder.html.twig');
     }

     /**
     * @Route("/modalMeshRes", name="mesh-Result-modal")
     */
     public function meshResModal()
     {
        return $this->render('mesh/modalMeshResult.html.twig');
     }

     /**
     * @Route("/MeshDef", name="mesh-default")
     */
     public function meshDefAction(Request $request, MeshService $mesh)
     {
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $mesh->setDefValues($idStudy);
        $mesh->startMeshPosition($idStudy);
        $mesh->startMeshGenerate($idStudy);
        return $this->redirectToRoute('show-mesh');
     }

     /**
     * @Route("/MeshRes", name="mesh-Result")
     */
     public function meshResAction()
     {
        return $this->redirectToRoute('show-mesh');
     }

     /**
     * @Route("/meshBuilder", name="mesh-builder")
     */
     public function meshBuilderAction(Request $request, MeshService $mesh)
     {
        $session = $request->getSession();
        $idStudy = $session->get('idStudy');
        $mesh->startMeshPosition($idStudy);
        $mesh->startMeshGenerate($idStudy);
        return $this->redirectToRoute('show-mesh');
     }
    
}
