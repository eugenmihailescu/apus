<?php
namespace Apus\Controller;

use Apus\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    /**
     *
     * @Route("/comment", name="comment")
     */
    public function index()
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     *
     * @Route("/comment/{postSlug}/new", name="comment_new")
     */
    function new (Request $request, $postSlug) {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy([
                'slug' => $postSlug,
            ]);

        if (!$post) {
            throw $this->createNotFoundException();
        }
    }
}
