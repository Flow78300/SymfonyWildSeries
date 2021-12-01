<?php

// src/Controller/CategoryController.php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
     /**
      * @Route("/category/", name = "category_index")
      */

    public function index(): Response
    {
        $categorys = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAll();

    return $this->render(
        'category/index.html.twig', 
        ['categorys' => $categorys]
    ); 
    }

    /**
     * @Route("/{categoryName}", requirements={"categoryName"="\D+"}, methods={"GET"}, name="show")
     */

    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name : '.$categoryName.' found in category\'s table.'
            );
        } else {
            $categoryId = $category->getId();
            $programs = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findBy(['category' => $categoryId], ['id' => 'DESC'], 3);
        }
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'programs' => $programs
        ]);
    }
}

