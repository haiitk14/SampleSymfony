<?php
/**
 * Created by PhpStorm.
 * User: huytd
 * Date: 11/12/17
 * Time: 2:16 PM
 */

namespace AppBundle\Controller\AdminUser;

use AppBundle\Entity\MonetaryCurrency;
use AppBundle\Entity\Unit;
use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UnitsManagerController extends Controller
{
	/**
     * @Route("/unitsManager", name="show-units")
     */
	public function showAction(Request $request)
	{
		$user = $this->getUser();
        if($user == null){
            return $this->redirectToRoute('login');
        }
		$money = $this->getDoctrine()->getRepository(MonetaryCurrency::class)->findAll();
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

		if ($request->getMethod() == 'POST') {
			$idUnit = $request->get('id');
			$table = $request->get('table');
			//data-table 
			if($table == 'money'){
				$getMoneyId = $this->getDoctrine()->getRepository(MonetaryCurrency::class)->findBy(['idMonetaryCurrency'=>$idUnit]);
				$ret = array(
			        'symbol' => $getMoneyId[0]->getIdMonetaryCurrency(),
			       	'conCoeffA' => $getMoneyId[0]->getMoneySymb(),
			       	'conCoeffB' =>$getMoneyId[0]->getMoneyText(),
			        );
			}else{
				$getUnitId = $this->getDoctrine()->getRepository(Unit::class)->findBy(['idUnit'=>$idUnit]);
				$ret = array(
					'conCoeffA' => $getUnitId[0]->getCoeffA(),
					'conCoeffB' => $getUnitId[0]->getCoeffB(),
					'symbol' => $getUnitId[0]->getSymbol(),
				);
			}
			return new JsonResponse($ret);
		}
		return $this->render('admintration/unitsManager.html.twig',[
			'money'=> $money,
			'conductivity' => $conductivity,
			'consumptionUnit' => $consumptionUnit,
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

			]);
	}	
	
	/**
     * @Route("/createModiUnit", name="edit-Units")
     */
	public function createModifyAction(Request $request)
	{

		$em = $this->getDoctrine()->getManager();
		$type = $request->get('_type');
		$symbol = $request->get('_symbol');
		$coeffA = $request->get('_coeffA');
		$coeffB = $request->get('_coeffB');

		if ($request->get('create_unit') == '1') {
			$unit = new Unit();
			$unit->setTypeUnit($type);
			$unit->setSymbol($symbol);
			$unit->setCoeffA($coeffA);
			$unit->setCoeffB($coeffB);
			$em->persist($unit);
			$em->flush();
		} else {
			$modifyUnit = $em->find(Unit::class, $request->get('id_unit'));
			$modifyUnit->setSymbol($symbol);
			$modifyUnit->setCoeffA($coeffA);
			$modifyUnit->setCoeffB($coeffB);
			$em->flush();
		}
		return $this->redirectToRoute('show-units');
	}

	/**
     * @Route("/createModiMoney", name="edit-Money")
     */
	public function createModifyMoneyAction(Request $request)
	{

		$em = $this->getDoctrine()->getManager();
		$type = $request->get('_moneytext');
		$symbol = $request->get('_moneysym');

		if ($request->get('create_money') == '1') {
			$money = new MonetaryCurrency();
			$money->setMoneySymb($symbol);
			$money->setMoneyText($type);
			$em->persist($money);
			$em->flush();
		} else {
			$modifyMoney = $em->find(MonetaryCurrency::class, $request->get('id_money'));
			$modifyMoney->setMoneySymb($symbol);
			$modifyMoney->setMoneyText($type);
			$em->flush();
		}
		return $this->redirectToRoute('show-units');
	}

	/**
     * @Route("/modalUnitPage", name="load-unit-new-modify")
     */
	public function ajaxLoadUnitAction(Request $request)
	{
		$idUnit = $request->get('idunit');
		$getUnitId = $this->getDoctrine()->getRepository(Unit::class)->findBy(['idUnit'=>$idUnit]);
		$r = array(
	        'idunit' => $getUnitId[0]->getIdUnit(),
	        'type' => $getUnitId[0]->getTypeUnit(),
	        'symbol' => $getUnitId[0]->getSymbol(),
	        'unita' => $getUnitId[0]->getCoeffA(),
	        'unitb' => $getUnitId[0]->getCoeffB(),
	        );

	    return $this->render('admintration/modalCreateModify.html.twig',[
	    	'ret' => $r]);
	}

	/**
     * @Route("/modalMoneyPage", name="load-money-new-modify")
     */
	public function ajaxLoadMoneyAction(Request $request)
	{
		$idMoney = $request->get('idmoney');

		$getMoneyId = $this->getDoctrine()->getRepository(MonetaryCurrency::class)->findBy(['idMonetaryCurrency'=>$idMoney]);
		$m = array(
	        'idunit' => $getMoneyId[0]->getIdMonetaryCurrency(),
	        'symbol' => $getMoneyId[0]->getMoneySymb(),
	        'unita' =>$getMoneyId[0]->getMoneyText(),
	        );
		return $this->render('admintration/modalMoneyCreateModify.html.twig',[
	    	'money' => $m]);
	}
}