<?php

$post = new TimberPost();
$context = Timber::get_context();

if($post->post_type == "post") {
    $context['posts'] = new Timber\PostQuery(array(
        'posts_per_page' => 3,
        'orderby' => array('post_date' => 'DESC')
    ));
} else if($post->post_type == "ecr_event") {
    $eventsObj = new ecr_Events;

    $eventsObj->setEventInfo($post); // Grab event data

    $eventsQuery = $eventsObj->buildQuery('', '', '', '', array(
        'posts_per_page' => '3'
    ));
    $events = $eventsObj->getEvents($eventsQuery);

    $context['events'] = $events;
}

$context['post'] = $post;

$templates = array('single-' . $post->post_type . '.twig', 'single.twig');
Timber::render($templates, $context);
