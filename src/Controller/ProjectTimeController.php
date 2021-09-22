<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectTime;
use App\Form\ProjectTimeType;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
        $projects = $this->entityManager->getRepository(ProjectTime::class)->find(1);

//        /** @var ProjectReport $projects */
//        die(dump(implode( ', ',$projects->getTimeOfProject())));
        return $this->render('project_time/index.html.twig');
    }

    /**
     * @Route("/app/project/{id}/edit", name="project_time_edit")
     * @Route("/app/project/new", name="project_time_new")
     */
    public function edit($id = null, Request $request, SerializerInterface $serializer): Response
    {
        if ($id != null) {
            $project = $this->entityManager->getRepository(Project::class)->find($id);
        } else {
            $project = new Project();
        }

        $projects = $this->entityManager->getRepository(Project::class)->findAll();
        $projectsTime = $this->entityManager->getRepository(ProjectTime::class)->findAll();

        $projectsJson = $serializer->serialize($projects, 'json', ['groups' => 'show_project']);
        $projectsTimeJson = $serializer->serialize($projectsTime, 'json', ['groups' => 'show_project']);

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($id == null) {
                $project->setUser($this->getUser());
                $this->entityManager->persist($project);
            }
            $this->entityManager->flush();
        }


        return $this->render('project_time/form.html.twig', [
            'form' => $form->createView(),
            'projects' => $projectsJson,
            'projectsTime' => $projectsTimeJson
        ]);
    }

}
