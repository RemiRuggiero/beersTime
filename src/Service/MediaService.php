<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MediaService{
    private $config;

    public function __construct( ParameterBagInterface $params )
    {
        $this->config = $params->get('media');
    }

    public function upload( UploadedFile $file )
    {
        $name = $this->generateName( $file->guessExtension() );
        $file->move( $this->config['directory'], $name );
        return $name;
    }

    private function generateName( $extension )
    {
        return 'media_' . md5( uniqid() ) . '.' . $extension;
    }
} 