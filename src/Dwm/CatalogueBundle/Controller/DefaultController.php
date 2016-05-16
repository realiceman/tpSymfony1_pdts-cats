<?php

namespace Dwm\CatalogueBundle\Controller;

use Dwm\CatalogueBundle\Entity\Categorie;
use Dwm\CatalogueBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/somme/{a}/{b}")
     * @Template()
     */
    public function sommeAction($a,$b)
    {
        $s=$a+$b;
        return array('a' => $a, 'b'=> $b, 'somme'=>$s);
    }



    /**
     * @Route("/addCategorie/{nomCat}")
     * @Template()
     */
    public function addCatAction($nomCat)
    {
        $cat=new Categorie();
        $cat->setNomCategorie($nomCat);
        $em=$this->getDoctrine()->getManager();
        $em->persist($cat);
        $em->flush();
        return array('categorie' => $cat);
    }



    /**
     * @Route("/addProduit/{nom}/{prix}")
     * @Template()
     */
    public function addProduitAction($nom,$prix)
    {
        $p=new Produit();
        $p->setNom($nom);
        $p->setPrix($prix);
        $em=$this->getDoctrine()->getManager();
        $em->persist($p);
        $em->flush();
        return array('produit' => $p);
    }


    /**
     * @Route("/listProduits",name="list")
     * @Template()
     */
    public function listProduitsAction()
    {
        $produits=$this->getDoctrine()->getRepository("DwmCatalogueBundle:Produit")->findAll();
        return array('produits' => $produits);
    }



    /**
     * @Route("/formProduit")
     * @Template()
     */
    public function formProduitAction(Request $request)
    {
        $p = new Produit();
        $form = $this->createFormBuilder($p)
              ->add('nom','text')
              ->add('prix','text')
              ->add('categorie','entity',array(
                  "class"=>"Dwm\CatalogueBundle\Entity\Categorie",
                  "property"=>"nomCategorie"
              ))
              ->add('Add','submit')
              ->getForm();
        $form->handleRequest($request);
        if($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
            return $this->redirect($this->generateUrl("list"));
        }
        return array('f' => $form->createView());
    }


}
