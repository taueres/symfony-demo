<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPostData
{
    public static function load(ObjectManager $manager, ContainerInterface $container)
    {
        foreach (range(1, 30) as $i) {
            $post = new Post();

            $title = (1 === $i) ? 'Morbi tempus commodo mattis' : self::getRandomPostTitle();

            $post->setTitle($title);
            $post->setSummary(self::getRandomPostSummary());
            $post->setSlug($container->get('slugger')->slugify($post->getTitle()));
            $post->setContent(self::getPostContent());
            $post->setAuthorEmail('anna_admin@symfony.com');
            $post->setPublishedAt(new \DateTime('now - '.$i.'days'));

            foreach (range(1, 5) as $j) {
                $comment = new Comment();

                $comment->setAuthorEmail('john_user@symfony.com');
                $comment->setPublishedAt(new \DateTime('now + '.($i + $j).'seconds'));
                $comment->setContent(self::getRandomCommentContent());
                $comment->setPost($post);

                $manager->persist($comment);
                $post->addComment($comment);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    private static function getPostContent()
    {
        return <<<MARKDOWN
Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut labore et **dolore magna aliqua**: Duis aute irure dolor in
reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
deserunt mollit anim id est laborum.

  * Ut enim ad minim veniam
  * Quis nostrud exercitation *ullamco laboris*
  * Nisi ut aliquip ex ea commodo consequat

Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
luctus dolor.

Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
nulla vitae est.

Ut posuere aliquet tincidunt. Aliquam erat volutpat. **Class aptent taciti**
sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi
arcu orci, gravida eget aliquam eu, suscipit et ante. Morbi vulputate metus vel
ipsum finibus, ut dapibus massa feugiat. Vestibulum vel lobortis libero. Sed
tincidunt tellus et viverra scelerisque. Pellentesque tincidunt cursus felis.
Sed in egestas erat.

Aliquam pulvinar interdum massa, vel ullamcorper ante consectetur eu. Vestibulum
lacinia ac enim vel placerat. Integer pulvinar magna nec dui malesuada, nec
congue nisl dictum. Donec mollis nisl tortor, at congue erat consequat a. Nam
tempus elit porta, blandit elit vel, viverra lorem. Sed sit amet tellus
tincidunt, faucibus nisl in, aliquet libero.
MARKDOWN;
    }

    private static function getPhrases()
    {
        return array(
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
        );
    }

    private static function getRandomPostTitle()
    {
        $titles = self::getPhrases();

        return $titles[array_rand($titles)];
    }

    private static function getRandomPostSummary()
    {
        $phrases = self::getPhrases();

        $numPhrases = rand(6, 12);
        shuffle($phrases);

        return implode(' ', array_slice($phrases, 0, $numPhrases-1));
    }

    private static function getRandomCommentContent()
    {
        $phrases = self::getPhrases();

        $numPhrases = rand(2, 15);
        shuffle($phrases);

        return implode(' ', array_slice($phrases, 0, $numPhrases-1));
    }
}
