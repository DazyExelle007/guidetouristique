<?php

namespace App\EventSubscriber;

use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutEventSubscriber implements EventSubscriberInterface
{    
    
    private $flashBag;
    private $urlGenerator;
    private $flashy;
  
    public function __construct( FlashBagInterface $flashBag, UrlGeneratorInterface $urlGenerator, FlashyNotifier $flashy)
    {
        $this->urlGenerator = $urlGenerator;
        $this->flashBag = $flashBag;
        $this->flashy = $flashy;    }

    public function onLogoutEvent (LogoutEvent $event)
    {
      //message flash
     //  $this->flashBag->add(
     //       'success',
    //       'Bye bye ' . $event->getToken()->getUser()->getFullName()
   //   );

        $this->flashy->success('Bye bye' . $event->getToken()->getUser()->getFullName() );
      //route redirection

      $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_home')));
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent'
        ];
    }
}
