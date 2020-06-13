<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\User;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use App\Service\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/program")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="program_index", methods={"GET"})
     * @param ProgramRepository $programRepository
     * @return Response
     */
    public function index(ProgramRepository $programRepository): Response
    {
        return $this->render('program/index.html.twig', [
            'programs' => $programRepository->findAllWithCategoriesAndActors(),
        ]);
    }

    /**
     * @Route("/new", name="program_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param Slugify $slugify
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();
            $from = $this->getParameter("mailer_from");
            $email = (new Email())
                ->from($from)
                ->to('testwildcodeschool032020@gmail.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/email/notification.html.twig',
                    ['program' => $program]
                ));
            $mailer->send($email);
            $this->addFlash('success', 'La nouvelle série a bien été  à été ajoutée');
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="program_show", methods={"GET"})
     * * @IsGranted("ROLE_SUBSCRIBER")
     * @param Program $program
     * @return Response
     */
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="program_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param Program $program
     * @param Slugify $slugify
     * @return Response
     */
    public function edit(Request $request, Program $program, Slugify $slugify): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La série a bien été modifiée');
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="program_delete", methods={"DELETE"})
     * @param Request $request
     * @param Program $program
     * @return Response
     */
    public function delete(Request $request, Program $program): Response
    {
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
            $this->addFlash('danger', 'La série a bien été supprimée');
        }

        return $this->redirectToRoute('program_index');
    }

    /**
     * @Route("/{id}/watchlist", name="program_watchlist", methods={"GET"})
     * @param Request $request
     * @param Program $program
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function addToWatchlist(Request $request, Program $program, EntityManagerInterface $manager): Response
    {
        if ($this->getUser()->getPrograms()->contains($program)) {
            $this->getUser()->removeProgram($program);
        } else {
            $this->getUser()->AddProgram($program);
        }
        $manager->flush();
        return $this->json([
            'isInWatchlist' => $this->getUser()->isInWatchlist($program)
        ]);
    }
}
