<?php
//getDoctrine i EntityManagerInterface to serwisy obsługujące bazy danych jest to to samo. Tutaj używam EMI. Jeżeli uzywam //getDoctrine to dziedziczę Abstract Controller
namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserService
{
    protected $requestStack;
    protected $em;
    protected $security;


    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->requestStack = $requestStack;
        $this->em = $entityManager;
        $this->security = $security;
    }
    
    public function list()
    {
        $user = $this->security->getUser();

        $product = $this->em
        ->getRepository(Product::class)
        ->findBy(['user' => $user], []);

        return [
            'products' => $product
        ];
    }
}