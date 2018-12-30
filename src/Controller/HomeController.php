<?php
namespace Apus\Controller;

use Apus\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    const MAX_RESULTS = 10;

    /**
     *
     * @Route("/home", name="home")
     */
    public function index(Request $request)
    {
        $criteria = [];

        $orderBy = [
            'publishedAt' => 'DESC',
        ];

        $offset = $request->get('offset', 0);

        $limit = $request->get('limit', self::MAX_RESULTS);

        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy($criteria, $orderBy, $limit, $offset);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'posts' => $posts,
        ]);
    }
}
