<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Advert;
use Doctrine\ORM\EntityManager;

class AppManager extends BaseManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadAdvert($advertId)
    {
        return $this->getRepository()
            ->findOneBy(array('id' => $advertId));
    }

    public function saveAdvert(Advert $advert)
    {
        $this->persistAndFlush($advert);
    }

    public function removeAdvert(Advert $advert)
    {
        $this->removeAndFlush($advert);
    }

//    public function isAuthorized(Advert $advert, $membreId)
//    {
//        return ($advert->getMember()->getId() == $membreId) ? true : false;
//    }

    public function getRepository()
    {
        return $this->em->getRepository('AppBundle:Advert');
    }
}