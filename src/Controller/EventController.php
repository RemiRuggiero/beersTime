<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\EventService;
use App\Entity\Event;
use App\Repository\PlaceRepository;
use App\Repository\UserRepository;
use App\Entity\Place;

class EventController extends AbstractController
{
    private $eventService;

    public function __construct( EventService $eventService ){
        $this->eventService = $eventService;
    }

    /**
     * @Route("/events", name="event_list")
     */
    public function list( Request $request ) // Pour chercher dans la barre de recherche
    {
        $query = $request->query->get('DEZER');

        if( !empty( $query ) ){
            $events = $this->eventService->search( $query );
        }else{
            $events = $this->eventService->getAll();
        }

        return $this->render( 'event/list.html.twig', array(
            'events' => $events,
            'nIncomingEvents' => $this->eventService->countIncomingEvent(),
        ));
    }

    /**
     * @Route("/event/new", name="event_new")
     */
    public function new()
    {
        return new Response('Création d\'un event');
    }

    /**
     * @Route("/event/random", name="event_random")
     */
    public function random() // Trouver l'inspiration
    {
        //TODO
        // Redirection vers la page show avec l'id trouvé aléatoirement
        return $this->redirectToRoute( 'event_show', array(
            'id' => $this->eventService->getRandom()
        ));
    }

    /**
     * @Route("/event/{id}", name="event_show", requirements={"id"="\d+"})
     */
    public function show( $id )
    {
        return $this->render( 'event/show.html.twig', array(
            'event' => $this->eventService->get( $id ),
        ));
    }

    /**
     * @Route("/event/{id}/join", name="event_join", requirements={"id"="\d+"})
     */
    public function join()
    {
        return new Response('Rejoindre un event');
    }

    /**
     * @Route("/event/{id}/remove", name="event_remove")
     */
    public function remove( Event $event, EntityManagerInterface $em )
    {
        $em->remove( $event );
        // $em->flush();

        return $this->redirectToRoute( 'event_list' );
    }

}