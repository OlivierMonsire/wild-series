<?php
// src/Account/AccountController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/my-account", name="account_index")
     * @param UserInterface $user
     * @return Response
     */
    public function index(UserInterface $user): Response
    {
        return $this->render('account/index.html.twig', [
            'user'=>$user
        ]);

    }
}
