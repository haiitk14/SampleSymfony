<?php
namespace  AppBundle\Cryosoft;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Product;
use AppBundle\Entity\Production;
use AppBundle\Entity\InitialTemperature;
use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\ProdcharColorsDef;
use AppBundle\Entity\ProdcharColors;
use AppBundle\Entity\Studies;
use AppBundle\Entity\MeshGeneration;
use AppBundle\Entity\Translation;
use AppBundle\Entity\ColorPalette;
use AppBundle\Entity\MeshPosition;
use AppBundle\Entity\Post;


class MeshService {
	private $doctrine;

	public function __construct(\Doctrine\ORM\EntityManager $doctrine)
	{
		$this->doctrine = $doctrine;
	}

	private function getDoctrine() 
	{
		return $this->doctrine;
	}

	public function getNumberofMeshPoints() 
	{
		// @TODO: to be done with kernel
		return 10;
	}

	public function getProdTempDef(Request $request) 
	{
		$em = $this->getDoctrine();
		$session = $request->getSession();
		$idStudy = $session->get('idStudy');
		// dump($idStudy);die;
		$prodIso = empty($request->get('checkIsoProd')) ? 0 : 1;
		$prod = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy]) [0];
		$rsProduction = $this->getDoctrine()->getRepository(Production::class)->findBy(['idStudy' => $prod]);
		$rsProdEmlt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $prod]);
		$prod->setProdIso(1);
			for ($i = 0; $i < count($rsProdEmlt); $i++) {
				$rsProdEmlt[$i]->setProdElmtIso(0);
			}
		if($rsProduction != null) {
			$initialTemps = $this->getDoctrine()->getRepository(InitialTemperature::class)->findBy(['idProduction' => $rsProduction[0]->getIdProduction() ]);
				foreach($initialTemps as $each) {
					$em->remove($each);
				}
				$em->flush();
			}
			
		}

	public function initProdCharColorWithUserDef( $idUser, $idProd) 
	{
		if (count($idProd) > 0) {
			$em = $this->getDoctrine();
			$kernelUser = $this->getDoctrine()->getRepository(ProdcharColorsDef::class)->findBy(['idUser' => $idUser]);
			$prodColorCheck = $this->getDoctrine()->getRepository(ProdcharColors::class)->findBy(['idProd' => $idProd]);
			if ($prodColorCheck == null) {
				for ($i = 0; $i < count($kernelUser); $i++) {
					$prodColor = new ProdcharColors();
					$IDColor = $this->getDoctrine()->getRepository(ColorPalette::class)->find($kernelUser[$i]->getIdColor());
					$prodColor->setIdProd($idProd);
					$prodColor->setLayerOrder($kernelUser[$i]->getLayerOrder());
					$prodColor->setIdColor($IDColor);
					$em->persist($prodColor);
					}
				$em->flush();
				} 
			}
	  else {
		$session->getFlashBag()->set('error', "Error while trying to create prodchar default colors !!");
		}
	}

	public function startMeshPosition($idStudy) 
	{
		// @TODO: to be done with kernel
		$em = $this->getDoctrine();
		$objStudy = $em->find(Studies::class , $idStudy);

		$idProduct = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy]);

		$idProductElmt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $idProduct], ['idComp' => 'DESC']);
		
		for ($i = 0; $i < count($idProductElmt); $i++) {
			$prodElmts = $idProductElmt[$i];
			$meshPosition = $this->getDoctrine()->getRepository(MeshPosition::class)->findBy(['idProductElmt' => $idProductElmt]);
			if ($meshPosition == null) {
				for ($j = 0; $j < 3; $j++) {
					$axis = $j + 1;
					for ($b = 0; $b < 10; $b++) {
						$meshPositions = new MeshPosition();
						$meshPositions->setIdProductElmt($prodElmts);
						$meshPositions->setMeshAxis($axis);
						$meshPositions->setMeshOrder(0);
						$meshPositions->setMeshAxisPos(($b+1)+$i);
						$em->persist($meshPositions);
						}
					}
				} else {
					foreach($meshPosition as $each) {
						$em->remove($each);
					}
					$em->flush();
				for ($j = 0; $j < 3; $j++) {
					$axis = $j + 1;
					for ($b = 0; $b < 10; $b++) {
						$meshPositions = new MeshPosition();
						$meshPositions->setIdProductElmt($prodElmts);
						$meshPositions->setMeshAxis($axis);
						$meshPositions->setMeshOrder(0);
						$meshPositions->setMeshAxisPos(($b+1)+$i);
						$em->persist($meshPositions);
						}
					}
				}
			}
		$em->flush();
		}

	public function delAllMeshPosition($idStudy) 
	{
		$em = $this->getDoctrine();
		$objStudy = $em->find(Studies::class , $idStudy);
		$idProduct = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy]);
		$idProductElmt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $idProduct], ['idComp' => 'DESC']);
		$meshPosition = $this->getDoctrine()->getRepository(MeshPosition::class)->findBy(['idProductElmt' => $idProductElmt]);
		if(count($meshPosition) > 0){
			foreach($meshPosition as $each) {
			$em->remove($each);
			}
		$em->flush();
		}
	}

	public function startMeshGenerate($idStudy) 
	{
		// @TODO: to be done with kernel
		$em = $this->getDoctrine();
		$objStudy = $em->find(Studies::class , $idStudy);
		$idProduct = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy])[0];
		$meshGenerates = $this->getDoctrine()->getRepository(MeshGeneration::class)->findBy(['idProd'=>$idProduct]);
        $calcultype = 1;
        $cal = 0;
		if ($idProduct->getIdMeshGeneration() == 0 || $idProduct->getIdMeshGeneration() == null) {
            $meshGenerate = new MeshGeneration();
            $meshGenerate->setIdProd($idProduct);
            $meshGenerate->setMesh1Fixed($calcultype);
            $meshGenerate->setMesh2Fixed($calcultype);
            $meshGenerate->setMesh3Fixed($calcultype);
            $meshGenerate->setMesh1Mode($cal);
            $meshGenerate->setMesh2Mode($cal);
            $meshGenerate->setMesh3Mode($cal);
            $meshGenerate->setMesh1Ratio(0);
            $meshGenerate->setMesh2Ratio(0);
            $meshGenerate->setMesh3Ratio(0);
            $em->persist($meshGenerate);
            $em->flush();
            $idProduct->setIdMeshGeneration($meshGenerate->getIdMeshGeneration());
            $em->flush();
        } else {
   //          foreach($meshGenerates as $each) {
			// 	$em->remove($each);
			// }
			// $em->flush();
			// $meshGenerate = new MeshGeneration();
            $meshGenerates[0]->setIdProd($idProduct);
            $em->flush();
            $idProduct->setIdMeshGeneration($meshGenerates[0]->getIdMeshGeneration());
            $em->flush();
        }
	}

	public function modifMeshParam( $idStudy) 
	{
		$em = $this->getDoctrine();
		$objStudy = $em->find(Studies::class , $idStudy);
		$idProduct = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy])[0];
		$meshGenerate = $this->getDoctrine()->getRepository(MeshGeneration::class)->findBy(['idProd'=>$idProduct])[0];
		if(($meshGenerate->getMesh1Size() == null) ) {
			$meshGenerate->setMesh1Size(0);
			$em->flush();
		} 
		if(($meshGenerate->getMesh2Size() == null) ) {
			$meshGenerate->setMesh2Size(0);
			$em->flush();
		} 
		if(($meshGenerate->getMesh3Size() == null) ) {
			$meshGenerate->setMesh3Size(0);
			$em->flush();
		}
		if(($meshGenerate->getMesh1Int() == null)) {
			$meshGenerate->setMesh1Int(0);
			$em->flush();
		}
		if(($meshGenerate->getMesh2Int() == null)) {
			$meshGenerate->setMesh2Int(0);
			$em->flush();
		}
		if(($meshGenerate->getMesh3Int() == null)) {
			$meshGenerate->setMesh3Int(0);
			$em->flush();
		}
	}

	public function loadMeshGeneration( $idStudy) 
	{
		$em = $this->getDoctrine();
		$objStudy = $em->find(Studies::class , $idStudy);
		$idProduct = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $objStudy])[0];
		$meshGenerate = $this->getDoctrine()->getRepository(MeshGeneration::class)->findBy(['idProd'=>$idProduct])[0];
		if($meshGenerate == null) {
			$this->DefaultMeshV2($meshGenerate);
		} 
	}

	public function defaultMeshV2( $idMeshGenrate) 
	{
		$em = $this->getDoctrine();
		$getIdMeshGenerate = $em->find(MeshGeneration::class, $idMeshGenrate);
		$meshSize1 = -1.0;
        $meshSize2 = -1.0;
        $meshSize3 = -1.0;
        $getIdMeshGenerate->setMesh1Size($meshSize1);
        $getIdMeshGenerate->setMesh2Size($meshSize2);
        $getIdMeshGenerate->setMesh3Size($meshSize3);
        $em->flush();
	}

	public function defaultMeshParam($idStudy, $user)
	{
		$em = $this->getDoctrine();
		$objStudy = $em->find(Studies::class , $idStudy);
		$objUser = $this->getDoctrine()->getRepository(Ln2User::class)->findBy(['idStudy'=>$objStudy]);
		$idProduct = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $objStudy])[0];
		$meshParamDefault = $this->getDoctrine()->getRepository(MeshParamDef::class)->findBy(['idUser'=>$objUser]);
		$meshGenerate = $this->getDoctrine()->getRepository(MeshGeneration::class)->findBy(['idProd'=>$idProduct])[0];
		if($meshGenerate->getMesh1Mode() == 0) {
			$meshSize1 = $meshParamDefault->getMesh1Size();
	        $meshSize2 = $meshParamDefault->getMesh2Size();
	        $meshSize3 = $meshParamDefault->getMesh3Size();
		}
	}

	public function productShape($idStudy, $user) 
	{
		$em = $this->getDoctrine();
		$objStudy = $em->find(Studies::class , $idStudy);
		$idProduct = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy])[0];
		$idProEmlt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd'=>$idProduct])[0];
		$shapeName = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType'=>Post::TRANSTYPE_SHAPE, 'idTranslation'=>$idProEmlt->getIdShape()->getIdShape(), 'codeLangue'=>$user->getCodeLangue()])[0];
		$shape = $idProEmlt->getIdShape();
		$statuses = [0 => 0, 1 => 1, 2 => 2];
		return compact(
			'shape',
			'shapeName',
			'statuses'
		);
	}

	public function modifyProdIso(Request $request, $idStudy)
	{
		$em = $this->getDoctrine();
		$session = $request->getSession();
		$objStudy = $em->find(Studies::class , $idStudy);
        $idProd = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $objStudy])[0];
        $idProd->setProdIso(0);
        $rsProduction = $this->getDoctrine()->getRepository(Production::class)->findBy(['idStudy' => $idProd->getIdStudy()]);
        if($rsProduction != null) {
	        $initialTemps = $this->getDoctrine()->getRepository(InitialTemperature::class)->findBy(['idProduction' => $rsProduction[0]->getIdProduction() ]);
	        if($initialTemps != null) {
	            foreach($initialTemps as $each) {
	                $em->remove($each);
	            }
	        }
        	
        }else {
        	$session->getFlashBag()->set('error', "Production not defined !!");
        }
	}

	public function setDefValues($idStudy)
	{
		$em = $this->getDoctrine();
		$objStudy = $em->find(Studies::class , $idStudy);
		$idProduct = $this->getDoctrine()->getRepository(Product::class)->findBy(['idStudy' => $idStudy])[0];
		$rsProduction = $this->getDoctrine()->getRepository(Production::class)->findBy(['idStudy' => $idProduct->getIdStudy()]);
		$rsProdEmlt = $this->getDoctrine()->getRepository(ProductElmt::class)->findBy(['idProd' => $idProduct]);
		$idProduct->setProdIso(1);
		for ($i = 0; $i < count($rsProdEmlt); $i++){
	            $rsProdEmlt[$i]->setProdElmtIso(0);
	        }
	    $em->flush();
	    $initialTemps = $this->getDoctrine()->getRepository(InitialTemperature::class)->findBy(['idProduction' => $rsProduction[0]->getIdProduction() ]);
        foreach($initialTemps as $each) {
            $em->remove($each);
        }
        $em->flush();
	}
}