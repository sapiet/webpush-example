<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
    /**
     * @Route("/", name="home")
     * @Template("home/index.html.twig")
     */
    public function __invoke()
    {
        return [];
    }
}
