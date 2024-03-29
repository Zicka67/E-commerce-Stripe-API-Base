<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_get_products', methods: ['GET'])]
    public function getProducts(ProductRepository $productRepository, NormalizerInterface $normalizer): Response
    {
        $products = $productRepository->findAll();

        $serializedProducts = $normalizer->normalize($products, 'json', [
            'groups' => 'product:read'
        ]);

        return $this->json($serializedProducts);
    }
}
