<?php
namespace AppBundle\Controller\AdminUser;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Cryosoft\AdminUserService;
class AdminUserController extends Controller {
    /**
     * @Route("/newUser", name="register")
     */
    public function newUserAction(Request $request, AdminUserService $admin) 
    {
        
        $Iduser = $this->getUser();
        if($Iduser == null) {
            return $this->redirectToRoute('login');
        }
        $session = $request->getSession();
        $user = new Ln2user();
        $errors = array();
        if ($request->getMethod() == 'POST') {
            // get parameter from view form
            $username = $request->request->get("_name");
            $pass = $request->request->get("_pass");
            $confirm = $request->request->get("_repass");
            $email = $request->get('_emailuser');
            // create var validator
            $validator = $this->get('validator');
            // set value from view
            $user->setUsernam($username );
            $user->setUserpass($pass);
            $user->setUsermail($email);
            $user->setUserprio(2);
            $user->setTraceLevel(0);
            $user->setUserEnergy(-1);
            $user->setUserConstructor(-1);
            $user->setUserFamily(-1);
            $user->setUserOrigine(-1);
            $user->setUserProcess(-1);
            $user->setUserModel(-1);
            // encoder password
            $encoder = $this->get('security.password_encoder');
            // execute encode from user set password
            $password_encoder = $encoder->encodePassword($user, $user->getPassword());
            $user->setUserpass($password_encoder);
            // set default id language = 1
            $lang = $this->getDoctrine()->getRepository(Language::class)->find(1);
            // dump($lang->getCodeLangue();die();
            $user->setCodeLangue($lang->getCodeLangue());
            // set default id MonetaryCurrency = 1
            $money = $this->getDoctrine()->getRepository(MonetaryCurrency::class)->find(1);
            $user->setIdMonetaryCurrency($money->getIdMonetaryCurrency());
            // execute insert action into database
            // create var error to check valid from view form
            $errors = $validator->validate($user);
            // check password and confirm password match
            if ($pass != $confirm) {
                $session->getFlashBag()->set('Noitce', "Password do not match");
                return $this->redirectToRoute('register');
            }
            // check password is null
            if($pass == null) {
                $session->getFlashBag()->set('Noitce', "The user login is mandatory");
                return $this->redirectToRoute('register');
            }
            // check empty
            if (count($errors) == 0) {
                $em = $this->getDoctrine()->getManager();
                // add new user
                $em->persist($user);
                $em->flush();
                // add new CalculParamDefault
                $calParamsDef = new CalculationParametersDef();
                $calParamsDef->setIdUser($user);
                $admin->getCalculParamDefaultValue($calParamsDef);
                // add new TempRecordPtsDefault
                $trpd = new TempRecordPtsDef();
                $trpd->setIdUser($user);
                $admin->getTempRecordPtsDef($trpd);
                // add new MeshParamDefault
                $meshParam = new MeshParamDef();
                $meshParam->setIdUser($user);
                $admin->getMeshParamDefaultValue($meshParam);
                //add new ProdCharColorDef
                $admin->initProdCharColorWithKernel($user);
                //add new unit
                $admin->initUserUnitsWithKernelU($user);
                // update calcParamsdef and temprecord by iduser
                $user->setIdCalcParamsdef($calParamsDef->getIdCalcParamsdef());
                $user->setIdTempRecordPtsDef($trpd->getIdTempRecordPtsDef());
                $em->flush();
                $session->getFlashBag()->set('success', " User creation succeed. You can create another user.");
                return $this->redirectToRoute('Admin-Load');
            }
        }
        return $this->render('admintration/newUser.html.twig', array('errors' => $errors, 'user_info' => $user));
    }
   
    /**
     * @Route("/loadUser", name="Admin-Load")
     */
    public function loadAction(Request $request) 
    {
        $listUser = $this->getDoctrine()->getRepository(Ln2user::class)->findAll();
        // get current user login
        $userLogon = $this->getUser()->getIdUser();
        // get user connecting
        $getListUserConnected = $this->getDoctrine()->getRepository(Ln2user::class)->createQueryBuilder('LN')->select('(LN.idUser)', '(LN.usernam)')->leftJoin(Connection::class, 'conn', 'WITH', 'conn.idUser = LN.idUser')->where('conn.dateConnection is not NULL')->andWhere('conn.dateDisconnection is Null')->andWhere('conn.idUser <> :userLogon')->setParameter('userLogon', $userLogon)->getQuery()->getResult();
        if ($request->getMethod() == 'POST') {
              $idUser = $request->get('id');
              $getListUser = $this->getDoctrine()->getRepository(Ln2user::class)->findBy(['idUser' => $idUser]);
              $ret = array('username' => count($getListUser) ? $getListUser[0]->getUsernam() : null,
               'email' => count($getListUser) ? $getListUser[0]->getUsermail() : null,
               'updateUser' => $idUser, 
               'deleteUser' => $idUser, 
               'diconnect' => $idUser,);
              return new JsonResponse($ret);
        }
        return $this->render('admintration/loadUserAdmin.html.twig', ['listUser' => $listUser, 'getListUserConnected' => $getListUserConnected]);
    }

    /**
     * @Route("/saveAdmin", name="update-exsiting-user")
     */
    public function saveAction(Request $request) 
    {
        $session = $request->getSession();
        $this->loadAction($request);
        $em = $this->getDoctrine()->getManager();
        // get idUser from save button get value form Ajax
        $idUser = $request->get('updateUser');
        // find userID in Ln2user table
        $saveUser = $em->getRepository(Ln2user::class)->find($idUser);
        //get param form loadUserAdim.twig.html
        $name = $request->get('_loginUsername');
        $email = $request->get('_loginUsermail');
        $password = $request->get('_loginUserpass');
        $confirm = $request->get('_loginConUserpass');
        // check when user not null
        if ($idUser != null) {
            // check password
            if($password == null){
                $session->getFlashBag()->set('error', "Please fill in the new password field !!");
                
            }
            if ($password != $confirm) {
                    $session->getFlashBag()->set('error', "The new password or confirm password do not match!!!! ");
            } else{
                $saveUser->setUserpass($saveUser->getUserpass());
                $saveUser->setUsernam($name);
                $saveUser->setUsermail($email);
                $saveUser->setUserprio(2);
                $saveUser->setUserpass($password);
                $saveUser->setUserpass($new_pwd_encoded);
                $em->flush(); 
            }
            
        } else {
                $session->getFlashBag()->set('error', "Please choose user(Login) !!!");
            }
        return $this->redirectToRoute('Admin-Load');
    }

    /**
     * @Route("/delete", name="delete-exsiting-user")
     */
    public function deleteAction(Request $request, AdminUserService $admin) 
    {
        $this->loadAction($request);
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $idUser = $request->get('deleteUser');

        // check when user not null
        if ($idUser != null) {
          if ($admin->isUserHasDependencies($idUser)) {
            $session->getFlashBag()->set('error', "The user cannot be deleted ");
            return $this->redirectToRoute('Admin-Load');
            }
            // find userId form Ln2user table
            $user = $em->find(Ln2user::class, $idUser);
            // set null value
            $user->setIdUnit(new \Doctrine\Common\Collections\ArrayCollection());
            // insert null into UserUnit table by UserId like delete
            $em->persist($user);
            $em->flush();
            
            // execute query to delete userID form ProdcharColorsDef table
            $delProColor = $em->createQueryBuilder();
            $query = $delProColor->delete(ProdcharColorsDef::class, 'p')->where('p.idUser = :id')->setParameter('id', $idUser)->getQuery();
            $query->execute();
            $delCalPraDef = $em->createQueryBuilder();
            // execute query to delete userID form CalculationParametersDef table
            $query = $delCalPraDef->delete(CalculationParametersDef::class, 'c')->where('c.idUser = :id')->setParameter('id', $idUser)->getQuery();
            $query->execute();
            // execute query to delete userID form TempRecordPtsDef table
            $delTempRecordDef = $em->createQueryBuilder();
            $query = $delTempRecordDef->delete(TempRecordPtsDef::class, 't')->where('t.idUser = :id')->setParameter('id', $idUser)->getQuery();
            $query->execute();
            // execute query to delete userID form MeshParamDef table
            $delMeshDef = $em->createQueryBuilder();
            $query = $delMeshDef->delete(MeshParamDef::class, 'm')->where('m.idUser = :id')->setParameter('id', $idUser)->getQuery();
            $query->execute();
            // execute query to delete userID form Connection table
            $delConnection = $em->createQueryBuilder();
            $query = $delConnection->delete(Connection::class, 'conn')->where('conn.idUser = :id')->setParameter('id', $idUser)->getQuery();
            $query->execute();
            // finally remover user main Ln2user table
            $em->remove($user);
            $em->flush();
            $session->getFlashBag()->set('success', " The user has been deleted in the database. ");
          } else {
            $session->getFlashBag()->set('error', "Please choose user(Login) !!!");
          }
        return $this->redirectToRoute('Admin-Load');
    }
    
    /**
     * @Route("/refesh", name="refesh-connection")
     */
    public function refeshAction(Request $request) 
    {
        $this->loadAction($request);
        return $this->redirectToRoute('Admin-Load');
    }

    /**
     * @Route("/diconnect/{id}", name="diconnect-user")
     */
    public function disconnectUserAction(Request $request, $id, AdminUserService $admin) 
    {
        $session = $request->getSession();
        $this->loadAction($request);
        $em = $this->getDoctrine()->getManager();
        $admin->resetStudiesStatusLockedByUser($request, $id);
        $admin->releaseEltLockedByUser($id);
        $connection = $this->getDoctrine()->getRepository(Connection::class)->findBy(['idUser' => $id, 'dateDisconnection' => null]);
        $connection[0]->setDateDisconnection(time());
        $connection[0]->setTypeDisconnection(3);
        $em->flush();
        $session->getFlashBag()->set('Discsuccess', "The User disconected!!");

        return $this->redirectToRoute('Admin-Load');
    }

    /**
     * @Route("/disconnect", name="diconnect-user-btn")
     */
    public function disconnectionUserAction(Request $request, AdminUserService $admin) 
    {
        $session = $request->getSession();
        $this->loadAction($request);
        $em = $this->getDoctrine()->getManager();
        $idUser = $request->get('diconnect');
        if ($idUser != null) {
            // find userId form Ln2user table
            $user = $em->find(Ln2user::class, $idUser);
            $admin->resetStudiesStatusLockedByUser($request, $user);
            $admin->releaseEltLockedByUser($user);
            $connection = $this->getDoctrine()->getRepository(Connection::class)->findBy(['idUser' => $user, 'dateDisconnection' => null]);
            if ($connection != null) {
                $connection[0]->setDateDisconnection(time());
                $connection[0]->setTypeDisconnection(3);
                $em->flush();
                $session->getFlashBag()->set('Discsuccess', "The User disconected!!");
            } else {
                return $this->redirectToRoute('Admin-Load');
            }
        } else {
            $session->getFlashBag()->set('error', "Please choose user(Login) !!!");
        }
        return $this->redirectToRoute('Admin-Load');
    }
    
    /**
     * @Route("/connection", name="load-connection-user")
     */
    public function loadConnectUserAction(Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        $connections = $em->getRepository(Connection::class)->findBy([], ['dateConnection' => 'DESC'], 50);
        return $this->render('admintration/connectionUserAdmin.html.twig', ['connections' => $connections]);
    }
}
