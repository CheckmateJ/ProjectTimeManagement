<?php

namespace App\Controller;

use App\Entity\ProjectReport;
use App\Form\ProjecTimeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectTimeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/app/project/time", name="project_time")
     */
    public function index(): Response
    {
        $projects = $this->entityManager->getRepository(ProjectReport::class)->find(1);

//        /** @var ProjectReport $projects */
//        die(dump(implode( ', ',$projects->getTimeOfProject())));
        return $this->render('project_time/index.html.twig');
    }

    /**
     * @Route("/app/project/{id}/edit", name="project_time_edit")
     * @Route("/app/project/new", name="project_time_new")
     */
    public function edit($id = null, Request $request): Response
    {
        if($id !=null){
            $project = $this->entityManager->getRepository(ProjectReport::class)->find($id);
        }else{
            $project = new ProjectReport();
        }

        $projects = $this->entityManager->getRepository(ProjectReport::class)->findAll();


        $form = $this->createForm(ProjecTimeType::class, $project);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $project->setUser($this->getUser());
            $project->setTimeOfProject([$request->get('projec_time')['timeOfProject']]);
            $this->entityManager->persist($project);
            $this->entityManager->flush();
        }

        return $this->render('project_time/form.html.twig', [
            'form' => $form->createView(),
            'projects' => $projects
        ]);
    }

}
