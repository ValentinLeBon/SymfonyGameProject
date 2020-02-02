<?php

namespace App\Controller;

use App\Entity\VideoGame;
use App\Form\VideoGameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class VideoGameController extends AbstractController
{
    /**
     * @Route("/videoGame/list", name="videoGame_list")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(EntityManagerInterface $em): Response
    {
        $editors = $em->getRepository('App:VideoGame')->findAll();

        return $this->render('video_game/index.html.twig', [
            'editors' => $editors,
        ]);
    }

    /**
     * @Route("/videoGame/infos/{id}", name="videoGame_infos")
     * @ParamConverter("videoGame", options={"id"="id"})
     * @param VideoGame $videoGame
     * @return Response
     */
    public function getInfos(VideoGame $videoGame): Response
    {
        return $this->render('video_game/infos.html.twig', [
            'videoGame' => $videoGame
        ]);
    }

    /**
     * @Route("/videoGame/new", name="videoGame_new")
     * @param Request $request
     * @return Response
     */
    public function addVideoGame(Request $request): Response
    {
        $videoGame = new VideoGame();

        $form = $this->createForm(VideoGameType::class, $videoGame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($videoGame);
            $this->em->flush();

            $this->addFlash('success', 'Video Game created');
        }

        return $this->render('video_game/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/videoGame/edit/{id}", name="videoGame_edit")
     * @ParamConverter("videoGame", options={"id"="id"})
     * @param Request $request
     * @param VideoGame $videoGame
     * @return Response
     */

    public function editVideoGame(Request $request, VideoGame $videoGame): Response
    {
        $form = $this->createForm(VideoGameType::class, $videoGame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($videoGame);
            $this->em->flush();

            $this->addFlash('success', 'Video Game updated');
        }

        return $this->render('video_game/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

        /**
         * @Route("/videoGame/delete/{id}", name="videoGame_delete")
         * @ParamConverter("videoGame", options={"id"="id"})
         * @param VideoGame $videoGame
         * @return Response
         */

        public function deleteVideoGame(VideoGame $videoGame): Response
    {
        $this->em->remove($videoGame);
        $this->em->flush();

        $this->addFlash('success', 'Video Game deleted');

        return $this->redirectToRoute('videoGame_list');
    }
}
