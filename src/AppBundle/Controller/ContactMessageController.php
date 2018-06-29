<?php
/**
 * Created by PhpStorm.
 * User: jollet
 * Date: 19/06/18
 * Time: 13:53
 */

namespace AppBundle\Controller;

use AppBundle\Manager\ContactMessageManager;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineFieldDescriptor;
use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContactMessageController
 * @package AppBundle\Controller
 *
 * @author Alexander jollet <alexander.jollet3@gmail.com>
 */
class ContactMessageController extends FOSRestController
{
    const ENTITY_NAME = 'AppBundle:Message';
    /**
     * Returns array of existing field-descriptors.
     *
     * @return array
     */
    private function getFieldDescriptors()
    {
        return [
            'id' => new DoctrineFieldDescriptor(
                'id',
                'id',
                static::ENTITY_NAME,
                'public.id',
                [],
                true
            ),
            'prenom' => new DoctrineFieldDescriptor(
                'prenom',
                'prenom',
                static::ENTITY_NAME,
                'Prenom'
            ),
            'nom' => new DoctrineFieldDescriptor(
                'nom',
                'nom',
                static::ENTITY_NAME,
                'Nom'
            ),
             'email' => new DoctrineFieldDescriptor(
                'email',
                'email',
                static::ENTITY_NAME,
                'Email'
            ),
            'message' => new DoctrineFieldDescriptor(
                'message',
                'message',
                static::ENTITY_NAME,
                'Message'
            )
        ];
    }

    /**
     * Returns all fields that can be used by list.
     *
     * @FOS\RestBundle\Controller\Annotations\Get("messages/fields")
     *
     * @return Response
     */
    public function getMessagesFieldsAction()
    {
      return $this->handleView($this->view(array_values($this->getFieldDescriptors())));
    }


    /**
     * Shows all news-items
     *
     * @param Request $request
     *
     * @Get("messages")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMessagesListAction(Request $request)
    {
        $restHelper = $this->get('sulu_core.doctrine_rest_helper');
        $factory = $this->get('sulu_core.doctrine_list_builder_factory');
        $listBuilder = $factory->create(static::ENTITY_NAME);
        $restHelper->initializeListBuilder($listBuilder, $this->getFieldDescriptors());
        $results = $listBuilder->execute();
        $list = new ListRepresentation(
            $results,
            'messages-items',
            'get_messages_list',
            $request->query->all(),
            $listBuilder->getCurrentPage(),
            $listBuilder->getLimit(),
            $listBuilder->count()
        );
        $view = $this->view($list, 200);
        return $this->handleView($view);
    }

    /**
     * Return a single message identified by id.
     * @param $id
     * @return Response
     */
    public function getMessagesAction($id)
    {
        $message = $this->getManager()->read($id);
        return $this->handleView($this->view($message));
    }

    /**
     * Update a message with given id and returns it.
     *
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function putMessagesAction($id, Request $request)
    {
        $message = $this->getManager()->update($id, $request->request->all());
        $this->flush();

        return $this->handleView($this->view($message));
    }

    /**
     * Delete a message.
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteMessageAction($id)
    {
        $this->getManager()->delete($id);
        $this->flush();
        return $this->handleView($this->view());
    }

    /**
     * Returns service for news-items.
     *
     * @return ContactMessageManager
     */
    private function getManager()
    {
        return $this->get('AppBundle\Manager\ContactMessageManager');
    }
    /**
     * Flushes database.
     */
    private function flush()
    {
        $this->get('doctrine.orm.entity_manager')->flush();
    }

}
