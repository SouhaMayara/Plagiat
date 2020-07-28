<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Site;
use App\Entity\SitePage;
use App\Entity\Content;
use App\Entity\ContentPlagiat;
use App\Form\SiteType;
use App\Form\SitePageType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AcceuilController extends AbstractController
{
    /**
     * @Route("/acceuil", name="acceuil")
     */
    public function index(Request $request): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // ajout à la BD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($site);
            $entityManager->flush();
        }
        $list = $this->getDoctrine()
            ->getRepository(Site::class)
            ->findAll();
        return $this->render('acceuil/acceuil.html.twig', [
            //'controller_name' => 'AcceuilController',
            'form' => $form->createView(),
            'list' => $list,
        ]);
    }
    /**
     * @Route("/acceuil/{id}")
     */
    public function supprimer($id, Request $request): Response
    {
        //$id=$request->getParameter('id');
        $entityManager = $this->getDoctrine()->getManager();
        $site = $this->getDoctrine()
            ->getRepository(Site::class)
            ->find($id);

        $pages = $this->getDoctrine()
            ->getRepository(SitePage::class)
            ->findBy(['site' => $id]);
        if ($pages) {
            foreach ($pages as $p) {
                $id = $p->getId();
                $contents = $this->getDoctrine()
                    ->getRepository(Content::class)
                    ->findBy(['page' => $id]);
                if ($contents) {
                    foreach ($contents as $c) {
                        $idc = $c->getId();
                        $contentsP = $this->getDoctrine()
                            ->getRepository(ContentPlagiat::class)
                            ->findBy(['content' => $idc]);
                        if ($contentsP) {
                            foreach ($contentsP as $cp) {
                                $entityManager->remove($cp);
                                $entityManager->flush($cp);
                            }
                        }
                        $entityManager->remove($c);
                        $entityManager->flush($c);
                    }
                }
                $entityManager->remove($p);
                $entityManager->flush($p);
            }
        }
        $entityManager->remove($site);
        $entityManager->flush($site);
        $this->addFlash('info', 'Site Supprimé !');
        return new RedirectResponse('/acceuil');
        //$response = $this->forward('App\Controller\AcceuilController::index',[]);
        //return $response;
    }
    /**
     * @Route("/infoSite/{id}/{nom}")
     */
    public function info($id, $nom, Request $request): Response
    {
        $listContent = [];
        $list_id=[];
        $idp = '';
        $listPages = $this->getDoctrine()->getRepository(SitePage::class)->findBy(['site' => $id], ['plagiat' => 'desc']);
        $listContent = $this->getDoctrine()->getRepository(Content::class)->findAll();
        $listCP = $this->getDoctrine()->getRepository(ContentPlagiat::class)->findAll();
        $np=count($listCP);
        for ($i = 0; $i < $np; $i++) {
            $list_id[$i] =$listCP[$i]->getContent()->getId();
        } 
        return $this->render('acceuil/info.html.twig', [
            'listP' => $listPages,
            'nom' => $nom,
            'listC' => $listContent,
            'listCP' => $listCP,
            'list_id'=>$list_id
        ]);
    }  
//---------------------------------------------------------------------------------
    /**
     * @Route("/url_plagiat")
     */
    public function affichage(Request $request): Response
    {   
        $page = new SitePage();
        $form = $this->createForm(SitePageType::class, $page);
        $form->handleRequest($request);
        $url=$form->get('url')->getData();
        $result='';
        $list_id=[];
        $listContent=[];
        $listCP=[];
        $p='';
        $np=0;
        if ($form->isSubmitted() && $form->isValid()) {
            $pages=$this->getDoctrine()->getRepository(SitePage::class)->findBy(['url' => $url]);
            if($pages){
                $p=$pages[0];
                $listContent = $this->getDoctrine()->getRepository(Content::class)->findBy(['page' => $p->getId()]);
                $listCP = $this->getDoctrine()->getRepository(ContentPlagiat::class)->findAll();
                $np=count($listCP);
                for ($i = 0; $i < $np; $i++) {
                    $list_id[$i] =$listCP[$i]->getContent()->getId();
                } 
            }
            else{
                $page->setPlagiat('0');
                $page->setStates('0');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($page);
                $entityManager->flush();
                //traitement
                $result = shell_exec('python plagiarism\url_plagia.py');
                $pages=$this->getDoctrine()->getRepository(SitePage::class)->findBy(['url' => $url],['id' => 'desc']);
                $p=$pages[0];
                $listContent = $this->getDoctrine()->getRepository(Content::class)->findBy(['page' => $p->getId()]);
                $n=count($listContent);
                $listCP = $this->getDoctrine()->getRepository(ContentPlagiat::class)->findAll();
                $np=count($listCP);
                for ($i = 0; $i < $np; $i++) {
                    $list_id[$i] =$listCP[$i]->getContent()->getId();
                } 
            }
        }
        return $this->render('acceuil/url_plagiat.html.twig', [
            'form' => $form->createView(),
            'listC' => $listContent,
            'listCP' => $listCP,
            'result' =>$result,
            'list_id'=>$list_id
        ]);
    }    
}
