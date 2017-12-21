<?php
/**
 * Created by PhpStorm.
 * User: haidt
 * Date: 9/22/17
 * Time: 8:53 AM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NewProductController extends Controller
{
    /**
     * @Route("/product-new", name="product_new")
     */
    public function productionAction(Request $request)
    {
        $shape = $this->getDoctrine()->getRepository('AppBundle:Shape')->findAll();
        return $this->render('production/product_new.html.twig',[
            'shape'=>$shape
        ]);



    }

}