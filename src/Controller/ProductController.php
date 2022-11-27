<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ProductService;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
    * @Route("/addnew", name="addnew")
    */
    public function addNewProduct()
    {
        $response = $this->service->addNewProduct();
        
        return $this->render('product/addnew.html.twig', $response);
    }

    /**
    * @Route("/out/{id}", name="out")
    */
    public function outFromWarehouse(int $id)
    {
        $response = $this->service->outFromWarehouse($id);
        
        return $this->render('product/out.html.twig', $response);
    }

    /**
    * @Route("/add/{id}", name="add")
    */
    public function addToWarehouse(int $id)
    {
        $response = $this->service->addToWarehouse($id);
        
        return $this->render('product/add.html.twig', $response);
    }
}