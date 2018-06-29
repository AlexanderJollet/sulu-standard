<?php
/**
 * Created by PhpStorm.
 * User: jollet
 * Date: 18/06/18
 * Time: 10:24
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ContactMessageManager
 *
 * @package AppBundle\Manager
 *
 * @author Alexander jollet <alexander.jollet3@gmail.com>
 */
class ContactMessageManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * contactMessageManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */

    public function read($id)
    {
        return $this->entityManager->getRepository(Message::class)->find($id);

    }

    /**
     * @return array NewsContactMessageManager[]
     */

    public function readAll()
    {
        return $this->entityManager->getRepository(Message::class)->findAll();

    }

    /**
     * @param  int $id
     * @param array $data []
     * @return mixed
     */

    public function update($id, array $data)
    {
        $entity = $this->read($id);

        return $this->bind($entity, $data);
    }

    /**
     * Delete a message with given id.
     *
     * @param int $id
     */
    public function delete($id)
    {
        $this->entityManager->remove($this->read($id));
    }

    /**
     * @param Message $entity
     * @param array $data
     * @return Message
     */

    protected function bind(Message $entity, array $data)
    {
        $entity->setPrenom($data['prenom']);
        $entity->setNom($data['nom']);
        $entity->setEmail($data['email']);
        $entity->setMessage($data['message']);

        return $entity;
    }
}
