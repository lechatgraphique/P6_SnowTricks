<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Service\UploadPicture;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    /**
     * @Route("/trick/show/{slug}", name="trick.show")
     * @param Trick $trick
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function show(Trick $trick, Request $request, ObjectManager $manager): Response
    {
        $objectManager =  $this->getDoctrine()->getRepository('App:Trick');
        $trick = $objectManager->find($trick->getId());

        $objectManager =  $this->getDoctrine()->getRepository('App:Comment');
        $comments = $objectManager->findAll();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre commentaire a bien été enregistré !'
            );

            return $this->redirectToRoute('home.index', [
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/trick/edit/{slug}", name="trick.edit")
     * @IsGranted("ROLE_USER")
     * @param Trick $trick
     * @param Request $request
     * @param ObjectManager $manager
     * @param UploadPicture $uploadPicture
     * @return Response
     */
    public function edit(Trick $trick, Request $request, ObjectManager $manager, UploadPicture $uploadPicture): Response
    {
        $objectManager =  $this->getDoctrine()->getRepository('App:Trick');
        $trick = $objectManager->find($trick->getId());

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            foreach($trick->getPictures() as $picture)
            {
                $picture->setTrick($trick);
                $picture = $uploadPicture->saveImage($picture);

                $manager->persist($picture);
            }

            $trick->setUpdatedAt(new \DateTime());

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le trick <strong>' . $trick->getName() . '</strong> a bien été modifié !'
            );

            return $this->redirectToRoute('home.index', [
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/trick/delete/{slug}", name="trick.delete")
     * @IsGranted("ROLE_USER")
     * @param Trick $trick
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Trick $trick, ObjectManager $manager): Response
    {
        $objectManager =  $this->getDoctrine()->getRepository('App:Trick');
        $trick = $objectManager->find($trick->getId());

        $fileSystem = new Filesystem();

        foreach($trick->getImages() as $image)
        {
            $fileSystem->remove($image->getPath() . '/' . $image->getName());
            $fileSystem->remove($image->getPath() . '/cropped/' . $image->getName());
            $fileSystem->remove($image->getPath() . '/thumbnail/' . $image->getName());
        }

        $manager->remove($trick);
        $manager->flush();

        $this->addflash(
            'success',
            "Le trick <strong>{$trick->getName()}</strong> a été supprimé avec succès !"
        );

        return $this->redirectToRoute('home');
    }
}

