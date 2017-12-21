<?php


namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Connection;
use AppBundle\Entity\DateTimeInID;
use AppBundle\Entity\Ln2user;
//use Symfony\Component\Validator\Constraints\DateTime;

class ListController extends Controller
{
	
    /**
     * @Route("/home/{fromlogin}", name="welcome")
     */
	public function showAction($fromlogin)
	{
	    $user = $this->getUser();
	    if($user == null){
            return $this->redirectToRoute('login');
        }
        if($fromlogin == 'fromlogin'){
            $connection = new Connection();
            $connection->setIdUser($user);
            $connection->setDateConnection(time());
            $em = $this->getDoctrine()->getManager();
            $em->persist($connection);
            $em->flush();
        }
        return $this->render('admintration/home.html.twig');

	}
    /**
     * @Route("/", name="indexLogin")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('login');
    }


}
