<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\OrderedRepository;
use App\Repository\StockRepository;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AppController extends AbstractController
{

    /**
     * @Route ("/", name="app_index")
     *
     */

    public function index(CompanyRepository $companyRepository, OrderedRepository $orderedRepository)
    {
        return $this->render('app/index.html.twig' ,[
            "companies" => $companyRepository->findTenBestCompanies(),
            "Vcompanies" => $companyRepository->findNonValidatedCompanies(),
            "ordereds" => $orderedRepository->findCompanyNonValidatedOrdereds($this->getUser()),
            "vOrdereds" => $orderedRepository->findCompanyValidatedOrdereds($this->getUser()),
        ] );
    }


    /**
     * @Route ("/a-propos", name="app_propos")
     *
     */
    public function info()
    {
        return $this->render('app/a-propos.html.twig');
    }

    /**
     * @Route ("/mentions", name="app_mentions")
     *
     */
    public function mentions()
    {
        return $this->render('app/mentions.html.twig');
    }

    /**
     * @Route ("/rgpd", name="app_rgpd")
     *
     */
    public function rgpd()
    {
        return $this->render('app/rgpd.html.twig');
    }

    /**
     * @Route ("/cgu", name="app_cgu")
     *
     */
    public function cgu()
    {
        return $this->render('app/cgu.html.twig');
    }

    /**
     * @Route ("/validated-company", name="app_validated-company")
     *
     */
    public function validatedCompany(CompanyRepository $companyRepository, Request $request)
    {
        if (isset($_POST['companyValidated'])) {

            foreach ($_POST['companyValidated'] as $id) {
                
                $arrayOfNewCompany = $companyRepository->findById($id);

                foreach ($arrayOfNewCompany as $newCompany) {
                    $newCompany -> setValidated(1);
                    $this->getDoctrine()->getManager()->flush();
                }
            }
        }
        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route ("/validated-ordered", name="app_validated-ordered")
     *
     */
    public function validatedOrdered(OrderedRepository $orderedRepository, Request $request)
    {
        if (isset($_POST['orderedValidated'])) {

            foreach ($_POST['orderedValidated'] as $id) {
                
                $arrayOfNewOrdered = $orderedRepository->findById($id);

                foreach ($arrayOfNewOrdered as $newOrdered) {
                    $newOrdered -> setValidated(1);
                    $this->getDoctrine()->getManager()->flush();
                }
            }
        }
        return $this->redirectToRoute('app_index');
    }

    /**********
     * PARTIE POUR TEST à SUPPRIMER
     */

    /**
     * @Route("/testcommand", name="/testcommand")
     */
    public function testCommand(StockRepository $stockRepository)
    {
        $id_company = 1;
        $stocks = $stockRepository->findBy(['company' => $id_company]);
        $panier = []; // on démarre avec un panier vide
        $total = 0; //dont le total est vide aussi

        return $this->render('stock/ordered.html.twig', [
            'stocks' => $stocks,
            'items' => $panier,
            'total' =>  $total
            ]);

    }



}
