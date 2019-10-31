<?php

class climatestrike_Events
{
    private $postType = "climatestrike_event";
    private $taxonomyType = "climatestrike_event_type";

    function __construct(){}

    function registration() {
        // Post Type
        register_post_type($this->postType,
            array(
                'labels' => array(
                    'name' => "Events",
                    'singular_name' => "Event",
                    'add_new_item' => "Add new",
                    'edit_item' => "Edit"
                ),
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'show_ui' => true,
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
                'query_var' => $this->postType,
                'hierarchial' => false,
                'show_in_rest' => true,
                'menu_icon' => "dashicons-calendar-alt",
                'rewrite' => array(
                    'slug' => "event",
                    'with_front' => false,
                ),
            )
        );

        // Taxonomies
        register_taxonomy(
            $this->taxonomyType,
            $this->postType,
            array(
                'label' => "Event Type",
                'show_in_rest' => true,
                'hierarchal' => false,
                'rewrite' => array(
                    'slug' => "event-type"
                )
            )
        );
    }

    function buildQuery($from='', $to='', $type='', $keywords='', $args=array()) {
        $wpQueryArgs = array(
            'post_type' => $this->postType,
            'posts_per_page' => -1,
            'meta_key' => 'climatestrike_event_details_start',
            'orderby' => 'meta_value',
            'order' => 'ASC'
        );
        $metaQuery = array();
        $taxQuery = array();

        if($from === '') $from = current_time('mysql');
        $metaQuery[] = array(
            'key' => 'climatestrike_event_details_start',
            'value' => $from,
            'compare' => '>=',
            'type' => 'DATE'
        );

        if($to !== '') {
            $metaQuery[] = array(
                'key' => 'climatestrike_event_details_end',
                'value' => $to,
                'compare' => '<=',
                'type' => 'DATE'
            );
        }

        if($type !== '') {
            $taxQuery[] = array(
                'taxonomy' => $this->taxonomyType,
                'field' => 'slug',
                'terms' => $type
            );
        }

        if($keywords !== '') {
            $wpQueryArgs['s'] = $keywords;
        }

        if(count($metaQuery) > 1) $metaQuery['relation'] = "AND";
        if(count($taxQuery) > 1) $taxQuery['relation'] = "AND";

        $wpQueryArgs['meta_query'] = $metaQuery;
        $wpQueryArgs['tax_query'] = $taxQuery;

        $wpQueryArgs = array_merge($wpQueryArgs, $args);
        return $wpQueryArgs;
    }
    
    function addThumbnails(&$events) {
        foreach ($events as &$event) {
            $event->thumbnail = new TimberImage(get_the_post_thumbnail_url($event->id));
        }
        return $events;
    }

    function setInfo(&$events) {
        foreach ($events as &$event) {
            $event->location = $event->custom['climatestrike_event_details_location'];
            $event->postcode = $event->custom['climatestrike_event_details_postcode'];
            $event->start = $event->custom['climatestrike_event_details_start'];
            $event->end = $event->custom['climatestrike_event_details_end'];
        }
        return $events;
    }

    function addDistance(&$events, $fromPostcode) {
        foreach ($events as &$event) {
            $event->distance = distanceBetweenPostcodes($fromPostcode, $event->custom['climatestrike_event_details_postcode']);
        }
        return $events;
    }

    function getEvents($wpQueryArgs=null) {
        if($wpQueryArgs === null) $wpQueryArgs = $this->buildQuery();

        $events = Timber::get_posts($wpQueryArgs);
        $this->addThumbnails($events);
        $this->setInfo($events);

        return $events;
    }
}
add_action('init', function(){
    $events = new climatestrike_Events;
    $events->registration();
});
