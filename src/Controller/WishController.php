<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route("/wishes", name: "wish_")]
class WishController extends AbstractController
{
    #[Route("", name: "all", methods: ["GET"])]
    public function all(WishRepository $wishRepository) : Response {
        $wishes = $wishRepository->findBy(["isPublished" => true], ["dateCreated" => "DESC"]);

        return $this->render('wish/list.html.twig', [ 'wishes' => $wishes ]);
    }

    #[Route("/{id}", name: "details", requirements: ['id' => '\d+'], methods: ["GET"])]
    public function details(int $id, WishRepository $wishRepository) : Response
    {
        $wish = $wishRepository->find($id);

        if (!$wish) {
            throw $this->createNotFoundException("Souhait non trouvé");
        }

        return $this->render('wish/detail.html.twig', [ 'wish' => $wish ]);
    }

    #[Route("/create", name: "create")]
    public function create(
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/images')] string $imagesDirectory
    ) : Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            try {
                $imageFile=$wishForm->get('image')->getData();
                if ($imageFile){
                    $originalFileName= pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFileName);
                    $newFileName = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                    try {
                        $imageFile->move($imagesDirectory, $newFileName);
                    } catch (FileException $exception) {
                        $this->addFlash('warning', $exception->getMessage());
                    }
                    $wish->setImageFilename($newFileName);
                }
                $wish->setDateCreated(new \DateTime());
                $entityManager->persist($wish);
                $entityManager->flush();
                $this->addFlash('success', "Idea successfully added!");
                return $this->redirectToRoute('wish_details', ["id" => $wish->getId()]);
            } catch (Exception $exception) {
                $this->addFlash('warning', $exception->getMessage());
            }
        }

        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm
        ]);
    }

    #[Route("/{id}/update", name: "update", requirements: ["id" => "\d+"])]
    public function update(
        int $id,
        WishRepository $wishRepository,
        EntityManagerInterface $entityManager,
        Request $request,
         SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/images')] string $imagesDirectory
    ) : Response {
        $wish = $wishRepository->find($id);

        if (!$wish) {
            throw $this->createNotFoundException("Le souhait n'existe pas");
        }

        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            if ($wish->getImageFilename()){
                if($wishForm->get('image_delete')==1){
                    $wish->setImageFilename(null);
                }
            }
            $imageFile=$wishForm->get('image')->getData();
            if ($imageFile){
                $originalFileName= pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFileName);
                $newFileName = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move($imagesDirectory, $newFileName);
                } catch (FileException $exception) {
                    $this->addFlash('warning', $exception->getMessage());
                }
                $wish->setImageFilename($newFileName);
            }
            try {
                $wish->setDateUpdated(new \DateTime());
                $entityManager->persist($wish);
                $entityManager->flush();
                $this->addFlash('success', "Idea successfully updated!");
                return $this->redirectToRoute('wish_details', ["id" => $wish->getId()]);
            } catch (Exception $exception) {
                $this->addFlash('warning', $exception->getMessage());
            }
        }

        return $this->render("wish/update.html.twig", [
            'wishForm' => $wishForm,
            'wish' => $wish
        ]);
    }

    #[Route("/{id}/delete", name: "delete", requirements: ["id" => "\d+"])]
    public function delete(
        int $id,
        EntityManagerInterface $entityManager,
        WishRepository $wishRepository
    ) : Response {
        $wish = $wishRepository->find($id);

        if (!$wish) {
            throw $this->createNotFoundException("Le souhait n'existe pas");
        }

        try {
            $entityManager->remove($wish);
            $entityManager->flush();

            return $this->redirectToRoute('wish_all');
        } catch (Exception $exception) {
            $this->addFlash('warning', $exception->getMessage());
        }

        return $this->redirectToRoute('wish_details', ["id" => $wish->getId()]);
    }

}
