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
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('project_time/index.html.twig');
    }

    /**
     * @Route("/app/project/new", name="project_time_new")
     */
    public function new(Request $request, SerializerInterface $serializer): Response
    {

        $content = json_decode($request->getContent());
        if (isset($content->projectName)) {
            $projects = $this->entityManager->getRepository(Project::class)->findBy(['name' => $content->projectName]);
            /** @var Project $project */
            foreach ($projects as $project) {
                $date = $project->getCreatedAt();
                $dateFormat = $date->format('Y-m-d');
                if ($dateFormat === $content->projectDate) {
                    $project->setName($content->newName);
                    $this->entityManager->persist($project);
                }
            }
            $this->entityManager->flush();
        } else if (isset($content->projectId)) {
            $projectTime = $this->entityManager->getRepository(ProjectTime::class)->find($content->projectId);
            /** @var ProjectTime $projectTime */
            $projectTime->setTimeOfProject([$content->newTime]);
            $this->entityManager->persist($projectTime);
            $this->entityManager->flush();
        }

        $project = new Project();

        $projectsTime = $this->entityManager->getRepository(ProjectTime::class)->findAll();
        $projectsTimeJson = $serializer->serialize($projectsTime, 'json', ['groups' => 'show_project']);


        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project->setUser($this->getUser());
            $this->entityManager->persist($project);
            $this->entityManager->flush();
            return $this->redirectToRoute('project_time_new');
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
        if (isset($content->projectId)) {
            $projectTime = $this->entityManager->getRepository(ProjectTime::class)->find($content->projectId);
            $project = $this->entityManager->getRepository(Project::class)->find($projectTime->getProjectName());
            $this->entityManager->remove($projectTime);
            $this->entityManager->remove($project);
        } else {
            $projects = $this->entityManager->getRepository(Project::class)->findBy(['name' => $content->projectName]);
            foreach ($projects as $project) {
                $date = $project->getCreatedAt();
                $dateFormat = $date->format('Y-m-d');
                if ($dateFormat === $content->projectDate) {
                    $projectTime = $this->entityManager->getRepository(ProjectTime::class)->findBy(['projectName' => $project->getId()]);
                    $this->entityManager->remove($projectTime[0]);
                    $this->entityManager->remove($project);
                }
            }
        }
        $this->entityManager->flush();
    }
}
