<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/wild", name="wild_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="wild_show")
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * Show an array with three series of a category
     *
     * @param string $categoryName
     * @Route("/category/{categoryName}", name="show_category")
     * @return Response
     */
    public function showByCategory(string $categoryName): Response
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Category::class);

        $category = $repository->findOneByName($categoryName);
        $id = $category->getId();
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Program::class);
        $programs = $repository->findByCategory(($id),
            array('id' => 'desc'),
            3, 0);

        if (!$categoryName) {
            throw $this->createNotFoundException(
                'No category found.'
            );
        }
        return $this->render(
            'wild/category.html.twig', [
            'categoryName' => $categoryName,
            'category' => $category,
            'programs' => $programs
        ]);
    }

    /**
     * Show an array with three series of a category
     *
     * @param string $programName
     * @Route("/program/{programName}", name="show_program")
     * @return Response
     */
    public function showByProgram(string $programName): Response
    {
        if (!$programName) {
            throw $this->createNotFoundException(
                'No category found.'
            );
        }
        $slugName = $programName;
        $programName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($programName)), "-")
        );

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Program::class);
        $program = $repository->findOneBy(['title' => mb_strtolower($programName)]);
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Season::class);
        $id = $program->getId();
        $seasons = $repository->findByProgram(($id),
            array('id' => 'asc'));
        return $this->render(
            'wild/program.html.twig', [
            'seasons' => $seasons,
            'program' => $program,
            'slugName' => $slugName
        ]);
    }

    /**
     * Show an array with three series of a season
     *
     * @param integer $seasonNb
     * @Route("/program/{programName}/{seasonNb}", name="show_season")
     * @return Response
     */
    public function showBySeason(int $seasonNb): Response
    {
        if (!$seasonNb) {
            throw $this->createNotFoundException(
                'This season doesn\'t exist!'
            );
        }
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Season::class);
        $season = $repository->findOneBy(['number' => ($seasonNb)]);
        $program = $season-> getProgram();
        $episodes = $season-> getEpisodes();
        return $this->render(
            'wild/season.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }

    /**
     * Show an array with three series of an episode
     *
     * @param Episode $episode
     * @return Response
     * @Route("/program/{programName}/episode/{id}", name="show_episode")
     */
    public function showByEpisode(Episode $episode): Response
    {
        $season = $episode-> getseason();
        $program = $season->getprogram();
        return $this->render(
            'wild/episode.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

}

