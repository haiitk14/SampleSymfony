<?php
namespace AppBundle\Controller\AdminUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Connection;
use AppBundle\Entity\DateTimeInID;
use AppBundle\Entity\Ln2user;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        //dump($request->getSession()->all());die;
        $user = $this->getUser(); 
        if($user != null){
            return $this->redirect($this->generateUrl('welcome', array('fromlogin' => 'fromlogin')));
            // return $this->render('admintration/home.html.twig');
        }
            $helper = $this->get('security.authentication_utils');
            return $this->render(
                'admintration/login.html.twig',
                array(
                    'last_username' => $helper->getLastUsername(),
                    'error'         => $helper->getLastAuthenticationError(),
                )
            );
    }
    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route("/logoutNow", name="logoutNow")
     */
    public function logoutNowAction(Request $request) 
    {
        // update Diconnected time
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $lastConnection = $this->getDoctrine()->getRepository(Connection::class)->createQueryBuilder('e')    
        ->where('e.idUser = ?1')
        ->orderBy('e.dateConnection', 'DESC')     
        ->setMaxResults(1) 
        ->setParameter(1, $user->getIdUser())
        ->getQuery()
        ->getOneOrNullResult();
        $lastConnection->setDateDisconnection(time());
        $lastConnection->setTypeDisconnection(2);
        $em->flush();
        return $this->redirect($this->generateUrl('logout'));
    }
}