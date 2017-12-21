<?php
namespace AppBundle\Controller\Production;

use AppBundle\Entity\Production;
use AppBundle\Entity\Studies;
use AppBundle\Entity\MinMax;
use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



class ProductionController extends Controller
{
    private function getMinMaxProduction(Request $request, &$rs){
        $DAILY_PRODUCTION=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_PRODUCTION_DURATION]);
        $WEEKLY_PRODUCTION=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_WEEKLY_PRODUCTION]);
        $ANNUAL_PRODUCTION=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_ANNUAL_PRODUCTION]);
        $NUMBER_OF_EQUIPMENT_COOLDOWNS=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_DAILY_STARTUP]);
        $FACTORY_AIR_TEMPERATURE=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_AMBIENT_TEMPERATURE]);
        $RELATIVE_HUMIDITY_OF_FACTORY_AIR=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_AMBIENT_HUMIDITY]);
        $REQUIRED_AVERAGE_TEMPERATURE=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMMTI_ITEM_AVG_TEMPERATURE_DES]);
        $REQUIRED_PRODUCTION_RATE=$this->getDoctrine()->getRepository(MinMax::class)->findBy(['limitItem'=>Post::LIMIT_ITEM_FLOW_RATE]);

        $rs=[
            'daily_prod'=>$DAILY_PRODUCTION,
            'weekly_prod'=>$WEEKLY_PRODUCTION,
            'annual_prod'=>$ANNUAL_PRODUCTION,
            'equip_cool'=>$NUMBER_OF_EQUIPMENT_COOLDOWNS,
            'temperature'=>$FACTORY_AIR_TEMPERATURE,
            'relative_humidity'=>$RELATIVE_HUMIDITY_OF_FACTORY_AIR,
            'average_temperature'=>$REQUIRED_AVERAGE_TEMPERATURE,
            'prod_rate'=>$REQUIRED_PRODUCTION_RATE
        ];
    }
    /**
     * @Route("/production", name="show-production")
     */
    public function showAction(Request $request){
        $session = $request->getSession();
        $doc=$this->getDoctrine();
// check user login
        $user=$this->getUser();
        if($user== NULL){
            return $this->redirectToRoute('login');
        }
// check idStudy already exists
        $idStudy=$session->get('idStudy');
        if($idStudy == null || $idStudy==0 || $idStudy ==""){
            return $this->redirectToRoute('load-study');
        }
// get object Study
        $objStudy= $this->getDoctrine()->getRepository(Studies::class)->find($idStudy);
// get object Production by idStudy
        $production = $this->getDoctrine()->getRepository(Production::class)->findBy(['idStudy' => $objStudy->getIdStudy()]);
        if(count($production) > 0){
            $session->set('idProduction', $production[0]->getIdProduction());
        }else{
            $session->set('idProduction', null);
        }
        $rs=array();
        $this->getMinMaxProduction($request,$rs);


        return $this->render('production/production.html.twig', [
           'objProduction' => count($production)!=0 ? $production[0] : null,
           'studyName' => $objStudy,
           'p1' => $rs['daily_prod'][0]->getDefaultValue(),
           'p2' => $rs['weekly_prod'][0]->getDefaultValue(),
           'p3' => $rs['annual_prod'][0]->getDefaultValue(),
           'p4' => $rs['equip_cool'][0]->getDefaultValue(),
           'p5' => $rs['temperature'][0]->getDefaultValue(),
           'p6' => $rs['relative_humidity'][0]->getDefaultValue(),
           'p7' => $rs['average_temperature'][0]->getDefaultValue(),
           'p8' => $rs['prod_rate'][0]->getDefaultValue(),
        ]);
    }
    /**
     * @Route("/createProduction", name="create-production")
     */
    public function productionAction(Request $request)
    {
         // check user login
        $user=$this->getUser();
        if($user== NULL){
            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $studyId=$session->get('idStudy');
        if($studyId == NULL || $studyId==0 || $studyId ==""){
            return $this->redirectToRoute('load-study');
        }
        $objStudy=$this->getDoctrine()->getRepository(Studies::class)->find($studyId);
        $em = $this->getDoctrine()->getManager();

        if($request->getMethod()=='POST')
        {
// get parameter from view form
                $DAILY_PROD = $request->get('_DAILY_PROD');
                $WEEKLY_PROD = $request->get('_WEEKLY_PROD');
                $NB_PROD_WEEK_PER_YEAR = $request->get('_NB_PROD_WEEK_PER_YEAR');
                $DAILY_STARTUP = $request->get('_DAILY_STARTUP');
                $AMBIENT_TEMP = $request->get('_AMBIENT_TEMP');
                $AMBIENT_HUM = $request->get('_AMBIENT_HUM');
                $AVG_T_DESIRED = $request->get('_AVG_T_DESIRED');
                $PROD_FLOW_RATE = $request->get('_PROD_FLOW_RATE');
                
                $idProduction = $session->get('idProduction');

            if($idProduction !=0 || $idProduction !=NULL || $idProduction !=""  ){// idProduction already exists
                $production = $em->getRepository(Production::class)->find($idProduction);
// set value from view to Production table
                $production->setDailyProd($DAILY_PROD);
                $production->setWeeklyProd($WEEKLY_PROD);
                $production->setNbProdWeekPerYear($NB_PROD_WEEK_PER_YEAR);
                $production->setDailyStartup($DAILY_STARTUP);
                $production->setAmbientTemp($AMBIENT_TEMP);
                $production->setAmbientHum($AMBIENT_HUM);
                $production->setAvgTDesired($AVG_T_DESIRED);
                $production->setProdFlowRate($PROD_FLOW_RATE);
                $em->flush();
                $session->getFlashBag()->set('mess-success', "Update production successful !!");
                return $this->redirectToRoute('show-production');
            }else{
// new Production
                $production=new Production();
// set value from view to Production table
                $production->setDailyProd($DAILY_PROD);
                $production->setWeeklyProd($WEEKLY_PROD);
                $production->setNbProdWeekPerYear($NB_PROD_WEEK_PER_YEAR);
                $production->setDailyStartup($DAILY_STARTUP);
                $production->setAmbientTemp($AMBIENT_TEMP);
                $production->setAmbientHum($AMBIENT_HUM);
                $production->setAvgTDesired($AVG_T_DESIRED);
                $production->setProdFlowRate($PROD_FLOW_RATE);
                $production->setIdStudy($objStudy);
                
                $em->persist($production);
                $em->flush();
                $production = $em->getRepository(Production::class)->findBy(['idStudy'=>$objStudy->getIdStudy()]);
                $session->set('idProduction', $production[0]->getIdProduction());
                $session->getFlashBag()->set('mess-success', "New production successful !!");
                return $this->redirectToRoute('show-production');
            }
        }
    }
    /**
     * @Route("/getAllMinMaxProduction", name="getAllMinMaxProduction")
     */
    public function getAllMinMaxProductionAction(Request $request){
        $user=$this->getUser();
        if($user== NULL){
            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $idStudy=$session->get('idStudy');
        if($idStudy == null || $idStudy==0 || $idStudy ==""){
            return $this->redirectToRoute('load-study');
        }
        $rs =[];
        $this->getMinMaxProduction($request,$rs);

        $ret =[
              'p1min' => $rs['daily_prod'][0]->getLimitMin(),
              'p1max' => $rs['daily_prod'][0]->getLimitMax(),
               'p2min' => $rs['weekly_prod'][0]->getLimitMin(),
               'p2max' => $rs['weekly_prod'][0]->getLimitMax(),
               'p3min' => $rs['annual_prod'][0]->getLimitMin(),
               'p3max' => $rs['annual_prod'][0]->getLimitMax(),
               'p4min' => $rs['equip_cool'][0]->getLimitMin(),
               'p4max' => $rs['equip_cool'][0]->getLimitMax(),
               'p5min' => $rs['temperature'][0]->getLimitMin(),
               'p5max' => $rs['temperature'][0]->getLimitMax(),
               'p6min' => $rs['relative_humidity'][0]->getLimitMin(),
               'p6max' => $rs['relative_humidity'][0]->getLimitMax(),
               'p7min' => $rs['average_temperature'][0]->getLimitMin(),
               'p7max' => $rs['average_temperature'][0]->getLimitMax(),
               'p8min' => $rs['prod_rate'][0]->getLimitMin(),
               'p8max' => $rs['prod_rate'][0]->getLimitMax(),
            ];

        return new JsonResponse($ret);
    }
}
