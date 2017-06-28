<?php

namespace Talis\UploadBundle\Listener;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Talis\UploadBundle\Annotation\UploadAnnotationReader;
use Talis\UploadBundle\Handler\UploadHandler;

class UploadSubscriber implements EventSubscriber
{


    /**
     * @var UploadAnnotationReader
     */
    private $reader;
    /**
     * @var UploadHandler
     */
    private $handler;

    public function __construct(UploadAnnotationReader $reader, UploadHandler $handler)
    {

        $this->reader = $reader;
        $this->handler = $handler;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
            'postLoad',
            'postRemove'
        ];
    }

    public function prePersist(EventArgs $eventArgs)
    {
        $this->preEvent($eventArgs);
    }


    public function preUpdate(EventArgs $eventArgs)
    {
        $this->preEvent($eventArgs);
    }

    private function preEvent(EventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        foreach($this->reader->getUploadableFields($entity) as $property => $annotation){

            $this->handler->removeOldFile($entity, $annotation);
            $this->handler->uploadFile($entity, $property, $annotation);
        }

    }

    public function postLoad(EventArgs $eventArgs){

        $entity = $eventArgs->getEntity();
        foreach($this->reader->getUploadableFields($entity) as $property => $annotation){
            $this->handler->setFileFromFilename($entity, $property, $annotation);
        }
    }


    public function postRemove(EventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        foreach($this->reader->getUploadableFields($entity) as $property => $annotation){
            $this->handler->removeFile($entity, $property);
        }

    }


}
