<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class EventController extends AbstractController
{
    /**
     * @Route("/events", name="event_list")
     */
    public function list()
    {
        return new Response('Liste des events');
    }

    /**
     * @Route("/event/new", name="event_new")
     */
    public function new()
    {
        return new Response('Création d\'un event');
    }

    /**
     * @Route("/event/{id}", name="event_show", requirements={"id"="\d+"})
     */
    public function show()
    {
        return new Response('Affichage d\'un event');
    }

    /**
     * @Route("/event/{id}/join", name="event_join", requirements={"id"="\d+"})
     */
    public function join()
    {
        return new Response('Rejoindre un event');
    }
}