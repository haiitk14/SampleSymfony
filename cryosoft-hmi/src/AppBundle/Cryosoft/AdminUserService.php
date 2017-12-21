<?php

namespace AppBundle\Cryosoft;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Language;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\CalculationParametersDef;
use AppBundle\Entity\MonetaryCurrency;
use AppBundle\Entity\TempRecordPtsDef;
use AppBundle\Entity\MeshParamDef;
use AppBundle\Entity\MinMax;
use AppBundle\Entity\ProdcharColorsDef;
use AppBundle\Entity\ColorPalette;
use AppBundle\Entity\Post;
use AppBundle\Entity\Unit;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\Studies;
use AppBundle\Entity\Component;
use AppBundle\Entity\Equipment;
use AppBundle\Entity\LineElmt;
use AppBundle\Entity\Connection;
use AppBundle\Entity\PackingElmt;

class AdminUserService
 {
	private $doctrine;

	public function __construct(\Doctrine\ORM\EntityManager $doctrine)
    {
		$this->doctrine = $doctrine;
	}

	private function getDoctrine() {
		return $this->doctrine;
	}
	 // get value min max CalculParamDef to insert CalculParamDef table when execute new user
    public function getCalculParamDefaultValue($calParamsDef) 
    {
        $em = $this->getDoctrine();
        $mimmaxMaxItNbDef = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxMaxItNbDef]);
        $mimmaxTimeStepNbDef = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxTimeStepNbDef]);
        $mimmaxCoeffDef = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxCoeffDef]);
        $mimmaxRequestDef = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxRequestDef]);
        $mimmaxStopTopSurfDef = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxStopTopSurfDef]);
        $mimmaxStopIntDef = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxStopIntDef]);
        $mimmaxStopBottomSurfDef = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxStopBottomSurfDef]);
        $mimmaxStopAvgDef = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxStopAvgDef]);
        $mimmaxTimeStep = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxTimeStep]);
        $mimmaxStorageStep = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxStorageStep]);
        $mimmaxPrecLogStep = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxPrecLogStep]);
        $calParamsDef->setHorizScanDef(1);
        $calParamsDef->setVertScanDef(1);
        $calParamsDef->setMaxItNbDef($mimmaxMaxItNbDef[0]->getDefaultValue());
        $calParamsDef->setTimeStepsNbDef($mimmaxTimeStepNbDef[0]->getDefaultValue());
        $calParamsDef->setRelaxCoeffDef($mimmaxCoeffDef[0]->getDefaultValue());
        $calParamsDef->setTimeStepDef($mimmaxTimeStep[0]->getDefaultValue());
        $calParamsDef->setStorageStepDef($mimmaxStorageStep[0]->getDefaultValue());
        $calParamsDef->setPrecisionLogStepDef($mimmaxPrecLogStep[0]->getDefaultValue());
        $calParamsDef->setStopTopSurfDef($mimmaxStopTopSurfDef[0]->getDefaultValue());
        $calParamsDef->setStopIntDef($mimmaxStopIntDef[0]->getDefaultValue());
        $calParamsDef->setStopBottomSurfDef($mimmaxStopBottomSurfDef[0]->getDefaultValue());
        $calParamsDef->setStopAvgDef($mimmaxStopAvgDef[0]->getDefaultValue());
        $calParamsDef->setStudyAlphaTopFixedDef(0);
        $calParamsDef->setStudyAlphaTopDef(0);
        $calParamsDef->setStudyAlphaBottomFixedDef(0);
        $calParamsDef->setStudyAlphaBottomDef(0);
        $calParamsDef->setStudyAlphaLeftFixedDef(0);
        $calParamsDef->setStudyAlphaLeftDef(0);
        $calParamsDef->setStudyAlphaRightFixedDef(0);
        $calParamsDef->setStudyAlphaRightDef(0);
        $calParamsDef->setStudyAlphaFrontFixedDef(0);
        $calParamsDef->setStudyAlphaFrontDef(0);
        $calParamsDef->setStudyAlphaRearFixedDef(0);
        $calParamsDef->setStudyAlphaRearDef(0);
        $calParamsDef->setPrecisionRequestDef($mimmaxRequestDef[0]->getDefaultValue());
        $em->persist($calParamsDef);
        $em->flush();
    }

    // get default value TempRecordPtsDef to insert TempRecordPtsDef table when execute new user
    public function getTempRecordPtsDef($trpd) 
    {
        $em = $this->getDoctrine();
        $axis1TopSurf = 50;
        $axis2TopSurf = 100;
        $axis3TopSurf = 50;
        $axis1IntPt = 50;
        $axis2IntPt = 50;
        $axis3IntPt = 50;
        $axis1BotSurf = 50;
        $axis2BotSurf = 0;
        $axis3BotSurf = 50;
        $axis1Axe2 = 50;
        $axis1Axe3 = 50;
        $axis2Axe1 = 50;
        $axis2Axe3 = 50;
        $axis3Axe1 = 50;
        $axis3Axe2 = 50;
        $axis3PL12 = 50;
        $axis1PL23 = 50;
        $axis2PL13 = 50;
        $nbSteps = 10;
        $contour2DTempMin = 0;
        $contour2DTempMax = 0;
        // $trpd = new TempRecordPtsDef();
        $trpd->setAxis1PtTopSurfDef($axis1TopSurf);
        $trpd->setAxis2PtTopSurfDef($axis2TopSurf);
        $trpd->setAxis3PtTopSurfDef($axis3TopSurf);
        $trpd->setAxis1PtIntPtDef($axis1IntPt);
        $trpd->setAxis2PtIntPtDef($axis2IntPt);
        $trpd->setAxis3PtIntPtDef($axis3IntPt);
        $trpd->setAxis1PtBotSurfDef($axis1BotSurf);
        $trpd->setAxis2PtBotSurfDef($axis2BotSurf);
        $trpd->setAxis3PtBotSurfDef($axis3BotSurf);
        $trpd->setAxis2Ax1Def($axis2Axe1);
        $trpd->setAxis3Ax1Def($axis3Axe1);
        $trpd->setAxis1Ax2Def($axis1Axe2);
        $trpd->setAxis3Ax2Def($axis3Axe2);
        $trpd->setAxis1Ax3Def($axis1Axe3);
        $trpd->setAxis2Ax3Def($axis2Axe3);
        $trpd->setAxis1Pl23Def($axis1PL23);
        $trpd->setAxis2Pl13Def($axis2PL13);
        $trpd->setAxis3Pl12Def($axis3PL12);
        $trpd->setNbStepsDef($nbSteps);
        $trpd->setContour2dTempMinDef($contour2DTempMin);
        $trpd->setContour2dTempMaxDef($contour2DTempMax);
        $em->persist($trpd);
        $em->flush();
    }

    // get default value MeshParamDef to insert MeshParamDef table when execute new user
    public function getMeshParamDefaultValue($meshParam) 
    {
        $em = $this->getDoctrine();
        $mimmaxMesh1Size = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxMesh1Size]);
        $mimmaxMesh2Size = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxMesh2Size]);
        $mimmaxMesh3Size = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxMesh3Size]);
        $mimmaxMeshRatio = $this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem' => Post::LIMIT_ITEM_MimmaxMeshRatio]);
        // $meshParam = new MeshParamDef();
        $meshParam->setMesh1Size($mimmaxMesh1Size[0]->getDefaultValue());
        $meshParam->setMesh2Size($mimmaxMesh2Size[0]->getDefaultValue());
        $meshParam->setMesh3Size($mimmaxMesh3Size[0]->getDefaultValue());
        $meshParam->setMeshRatio($mimmaxMeshRatio[0]->getDefaultValue());
        $em->persist($meshParam);
        $em->flush();
    }

    // get default value initProdCharColorDef to insert initProdCharColorDef table when execute new user
    public function initProdCharColorWithKernel($user) 
    {
        $listPro_Char_color = $this->getDoctrine()->getRepository(ProdcharColorsDef::class)->createQueryBuilder('PCC')->select('(PCC.idColor)', '(PCC.layerOrder)')->leftJoin(Ln2user::class, 'Ln2user', 'WITH', 'Ln2user.idUser = PCC.idUser')->where('Ln2user.usernam = :KERNEL')->setParameter('KERNEL', 'KERNEL')->getQuery()->getResult();
        $proCharColor = new ProdcharColorsDef();
        for ($i = 0;$i < count($listPro_Char_color);$i++) {
            $em = $this->getDoctrine();
            $proCharColor->setIdUser($user);
            $IDColor = $this->getDoctrine()->getRepository(ColorPalette::class)->find($listPro_Char_color[$i][1]);
            $proCharColor->setLayerOrder($listPro_Char_color[$i][2]);
            $proCharColor->setIdColor($IDColor);
            $em->persist($proCharColor);
        }
        $em->flush();
    }

    // get default value initUserUnits to insert unitUser table when execute new user
    public function initUserUnitsWithKernelU($user) 
    {
        $em = $this->getDoctrine();
        // find to username Kenel
        $kernelUser = $em->find(Ln2user::class, 1);
        // set value of IdUnit
        $user->setIdUnit($kernelUser->getIdUnit());
        $em->persist($user);
        $em->flush();
    }

    // check idUser has already exsit 
    public function isUserHasDependencies($idUser)
    {
        $em = $this->getDoctrine();
        $user = $em->find(Ln2user::class, $idUser);
        $studies = count($user->getIdStudy());
        $equipments = count($user->getIdEquip());
        $components = count($user->getIdComp());
        $lineElmts = count($user->getIdPipelineElmt());
        $packingElmts = count($user->getIdPackingElmt());
        return ($studies + $equipments + $components + $lineElmts + $packingElmts ) > 0;
    }

    // check accept this condition to execute this function
    public function resetStudiesStatusLockedByUser(Request $request, $idUser) 
    {
        $em = $this->getDoctrine();
        $idStudy = $this->getDoctrine()->getRepository(Studies::class)->findBy(['idUser' => $idUser]);
        if ($idStudy != null) {
            $idStudyEquiq = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(['idStudy' => $idStudy[0]->getIdStudy(), 'equipStatus' => 100000]);
            if ($idStudyEquiq != null) {
                $idStudyEquiq[0]->setEquipStatus(0);
                $em->flush();
            } 
        } 
    }

    // this function has execute when admin disconnectes some one
    public function releaseEltLockedByUser($idUser)
    {
        $em = $this->getDoctrine();
        $getUserId = $this->getDoctrine()->getRepository(Ln2user::class)->findBy(['idUser' => $idUser]);
        // if ($idStudy != null) {
          $studyByOpenOwner =  $this->getDoctrine()->getRepository(Studies::class)->findBy(['idUser' => $getUserId[0]->getIdUser(), 'openByOwner'=>1]);
          if($studyByOpenOwner !=null){
            for($i = 0 ; $i< count($studyByOpenOwner); $i ++) {
            $studyByOpenOwner[$i]->setOpenByOwner(0);
              }
                $em->flush();
            }
          $componentByOpenOwner =  $this->getDoctrine()->getRepository(Component::class)->findBy(['idUser' => $getUserId[0]->getIdUser(), 'openByOwner'=>1]);
          if($componentByOpenOwner !=null) {
            for($i = 0 ; $i< count($componentByOpenOwner); $i ++) {
              $componentByOpenOwner[$i]->setOpenByOwner(0);
            }
            $em->flush();
          }
          $equiqmentByOpenOwner =  $this->getDoctrine()->getRepository(Equipment::class)->findBy(['idUser' => $getUserId[0]->getIdUser(), 'openByOwner'=>1]);
          if($equiqmentByOpenOwner !=null) {
            for($i = 0 ; $i< count($equiqmentByOpenOwner); $i ++) {
              $equiqmentByOpenOwner[$i]->setOpenByOwner(0);
            }
            $em->flush();
          }
          $lineElmByOpenOwner =  $this->getDoctrine()->getRepository(LineElmt::class)->findBy(['idUser' => $getUserId[0]->getIdUser(), 'openByOwner'=>1]);
          if($lineElmByOpenOwner !=null) {
            for($i = 0 ; $i< count($lineElmByOpenOwner); $i ++) {
              $lineElmByOpenOwner[$i]->setOpenByOwner(0);
            }
            $em->flush();
          }
          $packingElmtByOpenOwner =  $this->getDoctrine()->getRepository(PackingElmt::class)->findBy(['idUser' => $getUserId[0]->getIdUser(), 'openByOwner'=>1]);
          if($packingElmtByOpenOwner !=null) {
            for($i = 0 ; $i< count($packingElmtByOpenOwner); $i ++) {
              $packingElmtByOpenOwner[$i]->setOpenByOwner(0);
            }
            $em->flush();
          }
    }
}