<?php

namespace APIBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use APIBundle\Entity\Counters;


class IndexController extends FOSRestController
{
        /**
        * @Rest\Post("/counters/")
        */
        public function postAction(Request $request)
    {
            $count = new Counters();
            $day = $request->get('day');
            $country = $request->get('country');
            $event = $request->get('event');
            $counter = $request->get('counter');
            
                if($day === $count->getDay() && $country === $country->getCountry() && $event === $event->getEvent())
                {
                    $count->setCount($counter);
                }else
                {
                    $count->setDay($day);
                    $count->setCountry($country);
                    $count->setEvent($event);
                    $count->setCount($counter);
                }
                 $em = $this->getDoctrine()->getManager();
                 $em->persist($count);
                 $em->flush();
            
            return new View("Data Added Successfully", Response::HTTP_OK);
    }
            
        
        /**
        * @Rest\Get("/counters}")
        */
        public function getAction() 
    {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery
            (
                    
                    'SELECT DISTINCT Country 
                     FROM 
                    (
                        SELECT SUM(count)
                        FROM APIBundle:counters 
                        WHERE event:views AND event:clicks AND event:plays
                        ORDER BY country DESC 
                        
                    )sub
                     ORDER BY day DESC
                     LIMIT 7'         
            );

            $result = $query->getResult();
            json_encode($result);
    }                                   
}
//        $params = array();
//        $content = $this->get("request")->getContent();
//            if (!empty($content))
//            {
//                $params = json_decode($content, true); 
//            }
//            
        

