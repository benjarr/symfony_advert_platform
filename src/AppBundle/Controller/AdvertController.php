<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Advert;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Advert controller.
 */
class AdvertController extends Controller
{
    /**
     * Lists all Advert entities.
     */
    public function indexAction($page)
    {
        if ($page < 1) throw new NotFoundHttpException('Page inexistante.');
        $em = $this->getDoctrine()->getManager();

        $listAdverts = $em->getRepository('AppBundle:Advert')->findAll();

        return $this->render('AppBundle:advert:index.html.twig', array(
            'listAdverts' => $listAdverts,
        ));
    }

    /**
     * Creates a new Advert entity.
     */
    public function newAction(Request $request)
    {
        $advert = new Advert();
        $form = $this->createForm('AppBundle\Form\AdvertType', $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $this->get('app.advert_manager')->saveAdvert($advert);

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Annonce bien enregistrée')
            );

            return $this->redirectToRoute('advert_show', array('id' => $advert->getId()));
        }

        return $this->render('AppBundle:advert:new.html.twig', array(
            'advert' => $advert,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Advert entity.
     */
    public function showAction($id)
    {
        if (!$advert = $this->get('app.advert_manager')->loadAdvert($id)) {
            // This advert does not exist.
            throw new NotFoundHttpException($this->get('translator')->trans('Cette annonce n\'existe pas.'));
        }

        return $this->render('AppBundle:advert:show.html.twig', array(
            'advert' => $advert,
        ));
    }

    /**
     * Displays a form to edit an existing Advert entity.
     */
    public function editAction($id, Request $request)
    {
        if (!$advert = $this->get('app.advert_manager')->loadAdvert($id)) {
            throw new NotFoundHttpException(
                $this->get('translator')->trans('Cette annonce n\'existe pas.')
            );
        }

        $editForm = $this->createForm('AppBundle\Form\AdvertType', $advert);

        if ($request->isMethod('POST') && $editForm->handleRequest($request)->isValid()) {
            $this->get('app.advert_manager')->saveAdvert($advert);

            $this->get('session')->getFlashBag()->add('notice',
                $this->get('translator')->trans('Annonce bien modifiée')
            );

            return $this->redirectToRoute('advert_show', array('id' => $advert->getId()));
        }

        return $this->render('AppBundle:advert:edit.html.twig', array(
            'advert' => $advert,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Advert entity.
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$advert = $this->get('app.advert_manager')->loadAdvert($id)) {
            throw new NotFoundHttpException(
                $this->get('translator')->trans('Cette annonce n\'existe pas.')
            );
        }

        $this->get('app.advert_manager')->removeAdvert($advert);

        return $this->redirectToRoute('advert_index');
    }

    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listAdverts = $em->getRepository('AppBundle:Advert')->findAll();

        return $this->render('AppBundle:advert:menu.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }
}
