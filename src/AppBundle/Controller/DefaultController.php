<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    public function selectLangAction(Request $request, $langue)
    {
        $url = $this->container->get('router')->generate('advert_index', array('_locale'=>$langue));

        $router = $this->get('router');
        $context = $router->getContext();
        $frontControllerName = basename($_SERVER['SCRIPT_FILENAME']);

        if($request->hasSession())
        {
            $session = $request->getSession();
            $session->set('_locale', $langue);
            $context->setParameter('_locale', $langue);

            //reconstructs a routing path and gets a routing array called $route_params
            $url = $request->headers->get('referer');
            $urlElements = parse_url($url);
            $routePath = str_replace('/' . $frontControllerName, '', $urlElements['path']); //eliminates the front controller name from the url path
            $route_params = $router->match($routePath);

            // Get the route name
            $route = $route_params['_route'];

            // Some parameters are not required to be used, filter them
            // by using an array of ignored elements.
            $ignore_params = array('_route' => true, '_controller' => true, '_locale' => true);
            $route_params = array_diff_key($route_params, $ignore_params);

            $url = $this->get('router')->generate($route, $route_params);
        }

        return $this->redirect($url);
    }
}
