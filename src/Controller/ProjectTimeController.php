<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectTimeController extends AbstractController
{
    /**
     * @Route("/app/project/time", name="project_time")
     */
    public function index(): Response
    {
        return $this->render('project_time/index.html.twig');
    }
}
