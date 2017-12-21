<?php
/**
 * Created by PhpStorm.
 * User: haidt
 * Date: 9/22/17
 * Time: 10:31 AM
 */

namespace AppBundle\Controller\ReferenceData;


use AppBundle\Entity\Compenth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DataGenerate extends Controller
{
    /**
     * @Route("/reference-data", name="data_generate")
     */
    public function dataGenerateAction(Request $request)
    {
        $compenth = $this->getDoctrine()->getRepository('AppBundle:Compenth')->findAll();

        return $this->render('ReferenceData/component_data_generate.html.twig',[
            'compenth'=>$compenth
        ]);
    }




}