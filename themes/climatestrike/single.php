<?php
/**
  * This is WIP for now
  **/
$context = Timber::get_context();
$context['post'] = new TimberPost();

if($post->post_type == "post") {
    $context['posts'] = new Timber\PostQuery(array(
        'posts_per_page' => 3,
        'orderby' => array('post_date' => 'DESC')
    ));
} elseif ($post->post_type == "climatestrike_event") {
    $eventsObj = new climatestrike_Events;
    $eventsObj->setInfo(array(&$post));
    $eventsQuery = $eventsObj->buildQuery('', '', '', '', array(
        'posts_per_page' => '3'
    ));
    $events = $eventsObj->getEvents($eventsQuery);
    $eventsObj->setInfo($events);
    $eventsObj->addThumbnails($events);

    $context['events'] = $events;
}

$templates = array('single-' . $post->post_type . '.twig', 'single.twig');
Timber::render($templates, $context);
