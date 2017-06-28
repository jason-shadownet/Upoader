<?php

namespace Talis\UploadBundle\Annotation;


use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class UploadableField{

    /**
     * @var String
     */
    private $filename;

    /**
     * @var String
     */
    private $path;


    /**
     * UploadableField constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {

        if(empty($options['filename'])){
            throw new \InvalidArgumentException("L'annotation UploadableField doit avoir un attribue 'filename");
        }
        if(empty($options['path'])){
            throw new \InvalidArgumentException("L'annotation UploadableField doit avoir un attribue 'path");
        }
       $this->filename = $options['filename'];
       $this->path = $options['path'];
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

}





