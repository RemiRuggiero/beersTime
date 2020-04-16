<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Place;
use App\Form\EventType;
use App\Service\EventService;
use App\Service\MediaService;
use App\Repository\UserRepository;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    private $eventService;
    private $mediaService;

    public function __construct( EventService $eventService, MediaService $mediaService ){
        $this->eventService = $eventService;
        $this->mediaService = $mediaService;
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
    public function new( Request $request, EntityManagerInterface $em, UserRepository $userRepository )
    {
        $event = new event();
        $form = $this->createForm( EventType::class, $event);

        $form->handleRequest($request); // reprends toutes les données saisi dans le formulaire
        if( $form->isSubmitted()&& $form->isValid() ) {
            $owner = $userRepository->find( 1 );
            $event->setOwner( $owner );

            $file = $event->getPictureFile();
            $filename = $this->mediaService->upload( $file );
            $event->setPicture( $filename );

            $this->addFlash( 'success', "Votre événement \"" . $event->getName() . "\" à bien été créé" );
            $em->persist( $event );
            $em->flush();

            return $this->redirectToRoute( 'event_show', array(
                'id' => $event->getId(),
            ));
        }

        return $this->render('event/form.html.twig', array(
            'form' => $form->createView(),
            'isNew' => true,
        ));
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
     * @Route("/event/{id}/update", name="event_update")
     */
    public function update( Event $event, Request $request, EntityManagerInterface $em )
    {
        $form = $this->createForm( EventType::class, $event );

        $form->handleRequest( $request );
        if( $form->isSubmitted() && $form->isValid() ){
            $file = $event->getPictureFile();
            if( !empty( $file ) ){
                $filename = $this->mediaService->upload( $file );
                $event->setPicture( $filename );
            }

            $em->flush();

            $this->addFlash( 'success', "Votre événement \"" . $event->getName() . "\" à bien été modifié" );
            return $this->redirectToRoute( 'event_show', array(
                'id' => $event->getId(),
            ));
        }

        return $this->render( 'event/form.html.twig', array(
            'form' => $form->createView(),
            'isNew' => false,
        ));
    }

    /**
     * @Route("/event/{id}/remove", name="event_remove")
     */
    public function remove( Event $event, EntityManagerInterface $em )  // EntityManager gère la bdd
    {
        $em->remove( $event );
        $em->flush();

        $this->addFlash( 'success', "Votre événement \"" . $event->getName() . "\" à bien été supprimé" );
        return $this->redirectToRoute( 'event_list' );
    }

}