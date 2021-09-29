<?php

namespace App\Controller;
use App\Entity\Project;
use App\Entity\ProjectTime;
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

        $projectsTime = $this->entityManager->getRepository(ProjectTime::class)->findAll();
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
            'projectsTime' => $projectsTimeJson
        ]);
    }

    /**
     * @Route("/app/project/delete", name="project_time_delete", methods={"POST"})
     */
    public function delete(Request $request): Response
    {
        $content = json_decode($request->getContent());
        $projectTime =  $this->entityManager->getRepository(ProjectTime::class)->find($content->projectId);

        $this->entityManager->remove($projectTime);
        $this->entityManager->flush();
    }

}
