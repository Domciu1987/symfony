<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\UserService;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    /**
    * @Route("user", name="user")
    */
    public function list()
    {
        $response = $this->service->list();
        
        return $this->render('user/list.html.twig', $response);
    }
}