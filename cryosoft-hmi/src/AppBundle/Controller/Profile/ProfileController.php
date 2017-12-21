<?php
/**
 * Created by PhpStorm.
 * User: thangnd
 * Date: 9/7/17
 * Time: 9:14 AM
 */

namespace AppBundle\Controller\Profile;
use AppBundle\Entity\Language;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\MonetaryCurrency;
use AppBundle\Entity\Unit;
use AppBundle\Entity\Equipment;
use AppBundle\Entity\Equipseries;
use AppBundle\Entity\Post;
use AppBundle\Entity\Translation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="userprofile")
     */
    public function listAction(Request $request)
    {
//        Array list of Long name
        $language_list = array(
            'EN' => 'English',
            'FR' => 'France',
            'DE' => 'German',
            'IT' => 'Italian',
            'ES' => 'Spanish'
        );
//        List of LANGUAGE
        $listLanguage = $this->getDoctrine()
            ->getRepository(Language::class)
            ->findAll();
//        List of MonetaryCurrency
        $listMonetary = $this->getDoctrine()
            ->getRepository(MonetaryCurrency::class)
            ->findAll();
//        Save language and monetary by ID
        $user = $this->getUser();
        $userList= $this->getDoctrine()->getRepository(Ln2user::class);
        $rs = $userList->findBy(array('idUser'=>$user->getidUser()));
        $rs = $rs[0];
        $em = $this->getDoctrine()->getManager();
        if($request->getMethod() == 'POST') {
            $changeLang = $request->get('_lang');
            $changeMonetary = $request->get('_money');
            $languages =$this->getDoctrine()->getRepository(Language::class)->find($changeLang);
            $request->getSession()->set('_locale', strtolower($languages->getLangName()));
            $monetary =$this->getDoctrine()->getRepository(MonetaryCurrency::class)->find($changeMonetary);
            $rs->setCodeLangue($languages->getCodeLangue());
            $rs->setIdMonetaryCurrency($monetary->getIdMonetaryCurrency());
            $em->flush(); //save
            return $this->redirectToRoute('userprofile');
        }

        return $this->render('Profile/Profile.html.twig', array(
            //            Array List of LANGUAGE
            'listLang' => $listLanguage,
//            Array List of MonetaryCurrency
            'listMone' => $listMonetary,
//            Array List of Long Name
            'listLangLong' => $language_list,
//            Get id language and monetary
            'valuelang' =>$rs->getCodeLangue(),
            'valuemoney'=>$rs->getIdMonetaryCurrency()
        ));
    }

    /**
     * @Route("/change", name="change_password")
     */
    public function changepassAction(Request $request)
    {
        $session = $request->getSession();

        if($request->getMethod() == 'POST') {
            $oldpassword = $request->get('_oldPass');
            $newpassword = $request->get('_newPass');
            $confirm     = $request->get('_confirmPass');
            $user = $this->getUser();
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);

            $oldpass_encoder = $encoder->encodePassword($oldpassword, $user->getSalt());
//            Set old password in database
            if($user->getPassword() != $oldpass_encoder) {
                $session->getFlashBag()->set('error', "The old password is incorrect!");
            }
//            Validate newPassword and confirmPassword
            elseif ($newpassword != $confirm){
                $session->getFlashBag()->set('error', "New password or confirm password do not match!!");
            }
//            Validate newPassword not duplicate oldPassword
            elseif ($newpassword  ==  $oldpassword){
                $session->getFlashBag()->set('error', "The new password not duplicate old password");
            }
//            update and generate encoder for newPassword
            else{
                $new_pwd_encoded = $encoder->encodePassword($newpassword, $user->getSalt());
                $user->setUserpass($new_pwd_encoded);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user); //insert
                $manager->flush(); //save
                $session->getFlashBag()->set('success', "The password changed!!");
            }
        }
        return $this->render('Profile/ChangePass.html.twig');
    }

    /**
     * @Route("/unit", name="units")
     */
    public function unitAction(Request $request)
    {
      $user = $this->getUser();
      // dump($user);die;
      if($user == null){
          return $this->redirectToRoute('login');
      }
      $em= $this->getDoctrine()->getManager();
        //        List of Unit
        // $listUnit = $this->getDoctrine()->getRepository(Unit::class)->findBy(['idUnit'=>$user]);
    		$conductivity = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_CONDUCTIVITY]);
    		$consumptionUnit = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' =>Post::TYPE_UNIT_CONSUMPTION_UNIT]);
    		$consumptionUnitCO2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' =>Post::TYPE_UNIT_CONSUMPTION_UNIT_CO2]);
    		$consumptionUnitLN2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' =>Post::TYPE_UNIT_CONSUMPTION_UNIT_LN2]);
    		$consumpMainTien = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' =>Post::TYPE_UNIT_CONSUM_MAINTIEN]);
    		$consumpMainTienCo2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' =>Post::TYPE_UNIT_CONSUM_MAINTIEN_CO2]);
    		$consumpMainTienLN2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' =>Post::TYPE_UNIT_CONSUM_MAINTIEN_LN2]);
    		$consumpMef = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' =>Post::TYPE_UNIT_CONSUM_MEF]);
    		$consumpMefCo2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_CONSUM_MEF_CO2]);
    		$consumpMefLN2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_CONSUM_MEF_LN2]);
    		$convCoeff = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_CONV_COEFF]);
    		$convSpeed = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_CONV_SPEED]);
    		$density = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNITDENSITY]);
    		$enthalpy = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_ENTHALPY]);
    		$equipDemension = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_EQUIP_DIMENSION]);
    		$evaporation = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_EVAPORATION]);
    		$fluidFlow = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_FLUID_FLOW]);
    		$length = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_LENGTH]);
    		$line = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_LINE]);
    		$losser1 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_LOSSES1]);
    		$losser2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_LOSSES2]);
    		$mass = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' =>Post::TYPE_UNIT_MASS]);
    		$massPerUnit = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_MASS_PER_UNIT]);
    		$materialRise = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_MATERIAL_RISE]);
    		$meshCut = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_MESH_CUT]);
    		$pressure = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_PRESSURE]);
    		$productChartDemension = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' =>Post::TYPE_UNIT_PRODCHART_DIMENSION]);
    		$productFlow = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_PRODUCT_FLOW]);
    		$productDemension = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_PROD_DIMENSION]);
    		$tankCapacity = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_RESERVOIR_CAPACITY_CO2]);
    		$tankCapacityLN2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_RESERVOIR_CAPACITY_LN2]);
    		$slopesPosistion = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_SLOPES_POSITION]);
    		$specificHeat = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_SPECIFIC_HEAT]);
    		$temperature = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_TEMPERATURE]);
    		$thickPacking = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_THICKNESS_PACKING]);
    		$time = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_TIME]);
    		$caper = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit' => Post::TYPE_UNIT_W_CARPET_SHELVES]);
        // fillter type
        $typeCond = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$conductivity, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeCons = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$consumptionUnit, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeConsumptionUnitCO2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$consumptionUnitCO2, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeConsumptionUnitLN2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$consumptionUnitLN2, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeConsumpMainTien = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$consumpMainTien, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeConsumpMainTienCo2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$consumpMainTienCo2, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeConsumpMainTienLN2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$consumpMainTienLN2, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeConsumpMef = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$consumpMef, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeConsumpMefCo2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$consumpMefCo2, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeConsumpMefLN2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$consumpMefLN2, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeConvCoeff = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$convCoeff, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeConvSpeed = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$convSpeed, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeDensity = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$density, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeEnthalpy = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$enthalpy, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeEquipDemension = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$equipDemension, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeFluidFlow = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$fluidFlow, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeLength = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$length, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeLine = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$line, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeLosser1 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$losser1, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeLosser2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$losser2, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeMass = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$mass, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeMassPerUnit = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$massPerUnit, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeMaterialRise = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$materialRise, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeMeshCut = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$meshCut, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typePressure = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$pressure, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeProductChartDemension = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$productChartDemension, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeProductFlow = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$productFlow, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeProductDemension = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$productDemension, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeTankCapacity = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$tankCapacity, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeTankCapacityLN2 = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$tankCapacityLN2, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeSlopesPosistion = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$slopesPosistion, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeSpecificHeat = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$specificHeat, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeTemperature = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$temperature, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeThickPacking = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$thickPacking, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeTime = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$time, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        $typeCaper = $this->getDoctrine()->getRepository(Unit::class)->findBy(['typeUnit'=>$caper, 'idUnit'=> $user->getIdUnit()->getValues()])[0];
        // $showUnit = $user->getIdUnit()->getValues();
        // dump($typeCond);die;
        return $this->render('Profile/Unit.html.twig',array(
//            Array list of Unit
          'typeConsumptionUnitCO2' =>$typeConsumptionUnitCO2,
          'typeConsumptionUnitLN2'=>$typeConsumptionUnitLN2,
          'typeConsumpMainTien'=>$typeConsumpMainTien,
          'typeConsumpMainTienCo2'=>$typeConsumpMainTienCo2,
          'typeConsumpMainTienLN2'=>$typeConsumpMainTienLN2,
          'typeConsumpMef'=>$typeConsumpMef,
          'typeConsumpMefCo2'=>$typeConsumpMefCo2,
          'typeConsumpMefLN2'=>$typeConsumpMefLN2,
          'typeConvCoeff'=>$typeConvCoeff,
          'typeConvSpeed'=>$typeConvSpeed,
          'typeDensity'=>$typeDensity,
          'typeEnthalpy'=>$typeEnthalpy,
          'typeEquipDemension'=>$typeEquipDemension,
          'typeFluidFlow'=>$typeFluidFlow,
          'typeLength'=>$typeLength,
          'typeLine'=>$typeLine,
          'typeLosser1'=>$typeLosser1,
          'typeLosser2'=>$typeLosser2,
          'typeMass'=>$typeMass,
          'typeMassPerUnit'=>$typeMassPerUnit,
          'typeMaterialRise'=>$typeMaterialRise,
          'typeMeshCut'=>$typeMeshCut,
          'typePressure'=>$typePressure,
          'typeProductChartDemension'=>$typeProductChartDemension,
          'typeProductFlow'=>$typeProductFlow,
          'typeProductDemension'=>$typeProductDemension,
          'typeTankCapacity'=>$typeTankCapacity,
          'typeTankCapacityLN2'=>$typeTankCapacityLN2,
          'typeSlopesPosistion'=>$typeSlopesPosistion,
          'typeSpecificHeat'=>$typeSpecificHeat,
          'typeTemperature'=>$typeTemperature,
          'typeThickPacking'=>$typeThickPacking,
          'typeTime'=>$typeTime,
          'typeCaper'=>$typeCaper,
          'conductivity' => $conductivity,
          'typeCond' =>$typeCond,
          'consumptionUnit' => $consumptionUnit,
          'typeCons' =>$typeCons,
          'consumptionUnitCO2' => $consumptionUnitCO2,
          'consumptionUnitLN2' => $consumptionUnitLN2,
          'consumpMainTien' => $consumpMainTien,
          'consumpMainTienCo2' => $consumpMainTienCo2,
          'consumpMainTienLN2' => $consumpMainTienLN2,
          'consumpMef' => $consumpMef,
          'consumpMefCo2' => $consumpMefCo2,
          'consumpMefLN2' => $consumpMefLN2,
          'convCoeff' => $convCoeff,
          'convSpeed' => $convSpeed,
          'density' => $density,
          'enthalpy' => $enthalpy,
          'equipDemension' => $equipDemension,
          'evaporation' => $evaporation,
          'fluidFlow' => $fluidFlow,
          'length' => $length,
          'line' => $line,
          'losser1' => $losser1,
          'losser2' => $losser2,
          'mass' => $mass,
          'massPerUnit' => $massPerUnit,
          'materialRise' => $materialRise,
          'meshCut' => $meshCut,
          'pressure' => $pressure,
          'productChartDemension' => $productChartDemension,
          'productFlow' => $productFlow,
          'productDemension' => $productDemension,
          'tankCapacity' => $tankCapacity,
          'tankCapacityLN2' => $tankCapacityLN2,
          'slopesPosistion' => $slopesPosistion,
          'specificHeat' => $specificHeat,
          'temperature' => $temperature,
          'thickPacking' => $thickPacking,
          'time' => $time,
          'caper' => $caper,
          // 'showUnit' => $showUnit,
        ));
    }

    /**
     * @Route("/profileEquipment", name="profileEquipments")
     */
    public function equipmentAction(Request $request)
    {

        $user = $this->getUser();
        if($user == null){
            return $this->redirectToRoute('login');
        }
        // get Type of Refrigeration
        $userEnergies = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType' => 2]);
        // get Manufacturer
        $userConstructors = $this->getDoctrine()->getRepository(Equipseries::class)->createQueryBuilder('es')
                            ->select('es.constructor')
                            ->leftJoin(Equipment::class, 'e', 'WITH', 'e.idEquipseries = es.idEquipseries')
                            ->distinct(true)
                            ->orderBy("es.constructor", 'ASC')
                            ->getQuery()->getResult();
        //get Equipment Series
        $userFamilies = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType' => 5]);
        //get Equipment Origin
        $userOrigines = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType' => 17]);
        //get Process Type
        $userProcesses = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType' => 13]);
        //get Model
        $userSeries = $this->getDoctrine()->getRepository(Translation::class)->findBy(['transType' => 7]);
        //get info in this
        $userEquipment = $this->getDoctrine()->getRepository(Ln2User::class)->findBy(['idUser' => $user]);
        if($request->getMethod() == 'POST') {

        $energies = $request->get('_refrigeration');
        $contructor = $request->get('_contructor');
        $family = $request->get('_series');
        $origin = $request->get('_origin');
        $processes = $request->get('_process');
        $series = $request->get('_model');
        // update value fllowing user
        $userEquipment[0]->setUserEnergy($energies);
        $userEquipment[0]->setUserConstructor($contructor);
        $userEquipment[0]->setUserFamily($family);
        $userEquipment[0]->setUserOrigine($origin);
        $userEquipment[0]->setUserProcess($processes);
        $userEquipment[0]->setUserModel($series);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
    }
        return $this->render('Profile/Equipment.html.twig',[
            'refrigeration' => $userEnergies,
            'manufacturer' => $userConstructors,
            'series' => $userFamilies,
            'origin' => $userOrigines,
            'process' => $userProcesses,
            'model' => $userSeries,
            'value_energies' => $userEquipment[0]->getUserEnergy(),
            'value_contructor' => $userEquipment[0]->getUserConstructor(),
            'value_family' => $userEquipment[0]->getUserFamily(),
            'value_origin' => $userEquipment[0]->getUserOrigine(),
            'value_processes' => $userEquipment[0]->getUserProcess(),
            'value_series' => $userEquipment[0]->getUserModel(),
            ]);
    }

    /**
     * @Route("/createUnit", name="createUnits")
     */
    public function createUnit(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getUser();
      // $kernelUser = $em->find(Ln2user::class, $user->getIdUser());
      $unit = $request->get('userunit');
        dump($unit);die;
      for ($i=0; $i < count($unit); $i++) { 

        $userUnit = $user->setIdUnit($user->setIdUnit($unit[$i]));
        dump($userUnit);
      }
      die;
        $em->flush();
      return $this->redirectToRoute('units');

    }

}
