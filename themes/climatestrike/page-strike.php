<?php

$context = Timber::get_context();
$context['post'] = new TimberPost();

/*
 * Get Latest Events with 'strike' type
 */
$eventsObj = new ecr_Events; // Require ECR's Things plugin
$eventsQuery = $eventsObj->buildQuery('', '', 'strike');
$events = $eventsObj->getEvents($eventsQuery);

if(isset($_GET['near'])) {
    try {
        $eventsObj->addDistance($events, $_GET['near']);
        usort($events, function($a, $b){ return $a->distance - $b->distance; }); // Assume we want to sort by closest
    } catch(ecr_PostcodeNotFoundException $e) {
        $context['error'] = "Postcode not found. Please enter a valid postcode.";
    } catch(Exception $e) {
        $context['error'] = "Something went wrong, sorry.";
    }
}

$context['events'] = $events;

$templates = array('page-events.twig');
Timber::render($templates, $context);

