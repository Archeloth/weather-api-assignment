<?php

namespace App\Controller;

use App\Entity\Notice;
use App\Form\NoticeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $notice = new Notice;
        $form = $this->createForm(NoticeType::class, $notice);

        return $this->render('index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/notices", name="notices")
     */
    public function notice(ManagerRegistry $doctrine): Response
    {
        $notices = $doctrine->getRepository(Notice::class)->findAll();
        
        return $this->render('notices.html.twig', [
            'notices' => $notices
        ]);
    }
}
