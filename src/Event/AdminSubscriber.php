<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminSubscriber implements EventSubscriberInterface
{
    private $encoder;

    public function __construct( UserPasswordEncoderInterface $encoder )
    {
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_persist' => array('encodeUserPassword'),
        );
    }

    public function encodeUserPassword( GenericEvent $event )
    {
        $entity = $event->getSubject();

        if (!($entity instanceof User)) {
            return;
        }

        $plain = $entity->getPlainPassword();
        $password = $this->encoder->encodePassword( $entity, $plain );
        $entity->setPassword( $password );

        $event['entity'] = $entity;
    }
}