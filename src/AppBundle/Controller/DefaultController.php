<?php
/**
 * Created by PhpStorm.
 * User: jollet
 * Date: 07/06/18
 * Time: 10:26
 */

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use AppBundle\Entity\Message;
use Sulu\Bundle\WebsiteBundle\Controller\WebsiteController;
use Sulu\Component\Content\Compat\StructureInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class DefaultController
 * @package AppBundle\Controller
 *
 * @author Alexander jollet <alexander.jollet3@gmail.com>
 */
class DefaultController extends WebsiteController
{
    /**
     * Afficher la page temporaire
     *
     * @return Response
     */

    public function index(StructureInterface $structure, $preview = false, $partial = false)
    {
        $message = null;
        $attributes = [];
        $request = $this->getRequest();

        $form = $this->createForm(ContactType::class, null, ['data_class' => Message::class]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($contact);

            $entityManager->flush();

            $message = 'Vous êtes bien enregistré avec cet email : ' . $contact->getEmail();
        }

        if (!$preview) {
            $requestFormat = $request->getRequestFormat();
        } else {
            $requestFormat = 'html';
        }

        $viewTemplate = $structure->getView() . '.' . $requestFormat . '.twig';

        // get attributes to render template
        $data = $this->getAttributes($attributes, $structure, $preview);

        $formData = [
            'form' => $form->createView(),
            'message' => $message,

        ];

        $content = parent::renderView($viewTemplate, array_merge($data, $formData));


        return new Response($content);
    }
}



