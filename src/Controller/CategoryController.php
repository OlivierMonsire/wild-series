<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * Show categories
     *
     * @Route("/category/index", name="category_index")
     */
    public function index()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Category::class);
        $categories = $repository->findAll();

        return $this->render(
            'category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Add a new category
     *
     * @Route("/category/add", name="category_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Category::class);
        $categories = $repository->findAll();

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->HandleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $category = $this->getDoctrine()->getManager();
            $category->persist($data);
            $category->flush();

        }

        return $this->render(
            'category/add.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }
}
