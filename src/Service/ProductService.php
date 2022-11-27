<?php
namespace App\Service;

use Symfony\Component\Form\FormFactoryInterface;
use App\Entity\Product;
use App\Form\ProductAddType;
use App\Form\ProductOutType;
use App\Form\ProductNewType;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProductService
{
    protected $formFactory;
    protected $requestStack;
    protected $em;
    protected $security;
    protected $slugger;

    public function __construct(
        FormFactoryInterface $formFactory,
        RequestStack $requestStack,
        EntityManagerInterface $entityManager,
        Security $security,
        SluggerInterface $slugger,
        ParameterBagInterface $parameterBag
    ) {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->em = $entityManager;
        $this->security = $security;
        $this->slugger = $slugger;
        $this->parameterBag = $parameterBag;
    }

    public function addNewProduct()
    {
        $form = $this->formFactory->create(ProductNewType::class);
        $request = $this->requestStack->getCurrentRequest();
        $form->handleRequest($request);
        $user = $this->security->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $brochureFile = $form->get('brochure')->getData();
            $entity = new Product();
                    
                if ($brochureFile) {
                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $this->slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                    $finalFilename = 'http://127.0.0.1/ekookna/public/uploads/brochures/'.$newFilename;
                    $entity->setBrochureFilename($finalFilename);

                    try {
                        $brochureFile->move(
                            $this->parameterBag->get('brochures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
                }

            $entity->setName($formData['name']);
            $entity->setCurrent($formData['value']);
            $entity->setUser($user);
            $this->em->persist($entity);
            $this->em->flush();            
        }  

        return [
            'form' => $form->createView()
        ];
    }
    
    public function outFromWarehouse(int $id)
    {
        $product = $this->em
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw new NotFoundHttpException();
        }

        $form = $this->formFactory->create(ProductOutType::class, null, ['maxValue' => $product->getCurrent()]);

        $request = $this->requestStack->getCurrentRequest();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $value = $form->get('value')->getData();
            $product->setCurrent($product->getCurrent() - $value);
            $this->em->persist($product);
            $this->em->flush();
        }
        return [
            'form' => $form->createView(),
            'product' => $product
        ];
    }
    
    public function addToWarehouse(int $id)
    {
        $product = $this->em
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw new NotFoundHttpException();
        }

        $form = $this->formFactory->create(ProductAddType::class, null, ['maxValue' => $product->getCurrent()]);

        $request = $this->requestStack->getCurrentRequest();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $value = $form->get('value')->getData();
            $product->setCurrent($product->getCurrent() + $value);
            $this->em->persist($product);
            $this->em->flush();
        }
        return [
            'form' => $form->createView(),
            'product' => $product
        ];
    }
}