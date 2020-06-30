<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CompanyType;
use App\Form\UserType;
use App\Repository\CompanyRepository;
use App\Repository\DriveRepository;
use App\Repository\OrderedRepository;
use App\Repository\StockRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * 
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user/gestion", name="user_index", methods={"GET"})
     * isGranted ("ROLE_ADMIN")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    

    /**
     * @Route("/profil", name="profil_index", methods={"GET"})
     */
    public function profil(StockRepository $stockRepository, DriveRepository $driveRepository, OrderedRepository $orderedRepository)
    {
        if (in_array("ROLE_PRODUCTEUR", $this->getUser()->getRoles()) and is_null($this->getUser()->getCompany())) {
            return $this->redirectToRoute('profil_edit', ['user' => $this->getUser()->getId()]);
        }

        return $this->render('user/profil.html.twig', [
            'stocks' => $stockRepository->findCompanyStocks($this->getUser()), // PRODUCTEUR : affichage des stocks pour chaque producteur
            'drives' => $driveRepository->findCompanyDrives($this->getUser()), // PRODUCTEUR : affichage des drives pour chaque producteur
            'ordereds' => $orderedRepository->findUserOrdereds($this->getUser()) // CLIENT : affichage des commandes pour chaque utilisateur
        ]);
    }

    /**
     * @Route("/gestion", name="user_gestion", methods={"GET"})
     */
    public function gestion()
    {

        return $this->render('user/gestion.html.twig');
    }


    /**
     * @Route("/user/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_edit", methods={"GET"})
     */
    public function editUser(User $user): Response
    {
        return $this->render('user/edit.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route ("/user/modify-role", name="user_modify_role", methods={"POST"})
     *
     */
    public function modifyRole(UserRepository $userRepository, Request $request)
    {
        if (isset($_POST['roles'])) {

            foreach ($_POST['roles'] as $id => $role) {


                $users = $userRepository->findById($id);

                foreach ($users as $user) {
                    $user->setRoles([$role]);
                    $this->getDoctrine()->getManager()->flush();
                }
            }
        }
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/profil/edit/{user}", name="profil_edit", methods={"GET","POST"})
     * 
     */
    public function edit(Request $request, User $user, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(UserType::class, $user);

        if (in_array("ROLE_PRODUCTEUR", ($this->getUser()->getRoles()))) {

            $form->add('company', CompanyType::class);
        }

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            if (($form->get('photoupload')->getData()) !== null) {

                /**
                 * @var UploadedFile $photoFile
                 */
                $photoFile = $form->get('photoupload')->getData();

                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                if ($photoFile) {
                    $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $photoFile->move(
                            $this->getParameter('photo_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $user->setPhoto($newFilename);
                }
            }


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {


        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('app_logout');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
    }
}
