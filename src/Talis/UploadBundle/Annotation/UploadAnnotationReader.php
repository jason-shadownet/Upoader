<?php
/**
 * Created by PhpStorm.
 * User: wassia
 * Date: 24/05/2017
 * Time: 02:16
 */

namespace Talis\UploadBundle\Annotation;


use Doctrine\Common\Annotations\AnnotationReader;

class UploadAnnotationReader
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     *
     * Check whatever the class is uploadable and return true if it is
     *
     * @param $entity
     * @return bool
     */
    public function isUploadable($entity)
    {
        $reflexion = new \ReflectionClass(get_class($entity));

        return $this->reader->getClassAnnotation($reflexion, Uploadable::class) !== null;
    }

    /**
     * @param $entity
     * @return array  of uploadable fields
     */
    public function getUploadableFields($entity)
    {
        $reflection = new \ReflectionClass(get_class($entity));

        if (!$this->isUploadable($entity)) {
            return [];
        }
        $properties = [];
        foreach ($reflection->getProperties() as $property) {

            $annotation = $this->reader->getPropertyAnnotation($property, UploadableField::class);

            if ($annotation !== null) {
                $properties[$property->getName()] = $annotation;
            }
        }
        return $properties;
    }
}