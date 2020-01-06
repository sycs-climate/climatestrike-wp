<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */
$context = Timber::get_context();
$context['post'] = new TimberPost();

/*
 * Get Latest Events with 'strike' type
 */
$eventsObj = new ecr_Events; // Require ECR's Things plugin
$eventsQuery = $eventsObj->buildQuery('', '', 'strike');
$events = $eventsObj->getEvents($eventsQuery);

if(isset($_GET['near'])) {
    $eventsObj->addDistance($events, $_GET['near']);
    usort($events, function($a, $b){ return $a->distance - $b->distance; }); // Assume we want to sort by closest
}

$context['events'] = $events;

$templates = array('page-events.twig');
Timber::render($templates, $context);
