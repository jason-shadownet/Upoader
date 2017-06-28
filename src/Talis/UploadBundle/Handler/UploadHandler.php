<?php

namespace Talis\UploadBundle\Handler;


use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UploadHandler{


    /**
     * UploadHandler constructor.
     *
     * Initialize the accessor
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }


    /**
     *
     * Handle uploading, and perform the prePersist subscriber action
     * @param $entity
     * @param $property
     * @param $annotation
     */
    public function uploadFile($entity, $property, $annotation){
        $file = $this->accessor->getValue($entity, $property);
        if($file instanceof UploadedFile){
            $filename = $file->getClientOriginalName();
            $file->move($annotation->getPath(), $filename);
            $this->accessor->setValue($entity, $annotation->getFilename(), $filename);
        }
    }


    /**
     *
     * Pre
     *
     * @param $entity
     * @param $property
     * @param $annotation
     */
    public function setFileFromFilename($entity, $property, $annotation){

         $file = $this->getFileFromFilename($entity, $annotation);
        $this->accessor->setValue($entity, $property, $file);
    }


    /**
     * @param $entity
     * @param $annotation
     */
    public function removeOldFile($entity, $annotation)
    {
        $file = $this->getFileFromFilename($entity, $annotation);
        if($file !== null){
            @unlink($file->getRealPath());
        }
    }


    /**
     * @param $entity
     * @param $annotation
     * @return null|File
     */
    private function getFileFromFilename($entity, $annotation){

        $filename = $this->accessor->getValue($entity, $annotation->getFilename());
        if(empty($filename)){
            return null;
        }else{
            return  new File($annotation->getPath() . DIRECTORY_SEPARATOR . $filename);
        }
    }

    /**
     * @param $entity
     * @param $property
     */
    public function removeFile($entity, $property)
    {
        $file = $this->accessor->getValue($entity, $property);

        if( $file instanceof File){
            @unlink($file->getRealPath());
        }

    }
}


