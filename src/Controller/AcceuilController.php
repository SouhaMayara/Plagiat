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

use App\Entity\Compare;
use App\Form\CompareType;
use App\Entity\Generate;
use App\Form\GenerateType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AcceuilController extends AbstractController
{
    /**
     * @Route("/", name="acceuil")
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
     * @Route("/acceuil/{id}" , name="acceuil2")
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
        return new RedirectResponse('/');
        //$response = $this->forward('App\Controller\AcceuilController::index',[]);
        //return $response;
    }
    /**
     * @Route("/infoSite/{id}/{nom}" ,name="info")
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
     * @Route("/url_plagiat" , name="url")
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

    /**
     * @Route("/compare" , name="compare")
     */
    public function compare(Request $request): Response
    {
        //$text1=$this->$request->get('text1');
        //$text2=$this->getRequest()->request->get('text2');
        //$result = shell_exec('python plagiarism\calcul_similarite.py'. escapeshellarg($text1,$text2));
        //return $this->render('acceuil/compare_text.html.twig', ['text1'=>$text1,'text2'=>$text2,'result' => $result,]);
        $compare = new Compare();
        $form = $this->createForm(CompareType::class, $compare);
        $form->handleRequest($request);
        $result='';
        $r=new Compare();
        $id=0;
        if ($form->isSubmitted() && $form->isValid()) {
            $compare->setCalcul('0');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($compare);
            $entityManager->flush();
            set_time_limit(500);
            ini_set('max_execution_time', 0);
            $result = shell_exec('python plagiarism\calcul_similarite.py');
            $list = $this->getDoctrine()->getRepository(Compare::class)->findAll();
            foreach ($list as $p) {
                $id = $p->getId(); }
            $r = $this->getDoctrine()->getRepository(Compare::class)->find($id);
            }
        return $this->render('acceuil/compare_text.html.twig', [
            'form' => $form->createView(),
            'r' => $r,
            'res' =>$result
        ]);
    }


  /**
     * @Route("/generate" , name="generate")
     */
    public function generate(Request $request): Response
    {
        $generate = new Generate();
        $form = $this->createForm(GenerateType::class, $generate);
        $form->handleRequest($request);
        $result='';
        $r=new Generate();
        $id=0;
         if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($generate);
            $entityManager->flush();
            set_time_limit(500);
            ini_set('max_execution_time', 0);
            $result = shell_exec('python plagiarism\sitemap.py');
            /*$list = $this->getDoctrine()->getRepository(Compare::class)->findAll();
            foreach ($list as $p) {
                $id = $p->getId(); }
            $r = $this->getDoctrine()->getRepository(Compare::class)->find($id);*/
            }
        return $this->render('acceuil/generate.html.twig', [
            'form' => $form->createView(),
            'res' =>$result
        ]);
        }

  
}
