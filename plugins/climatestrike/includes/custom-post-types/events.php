<?php

/*
 * Stolen from the climatestrike wordpress plugin.
 */

class climatestrike_Events
{
    private $postType = 'climatestrike_event';
    private $taxonomyCategory = 'climatestrike_event_category';
    private $taxonomyTag = 'climatestrike_event_tag';
    private $taxonomyType = 'climatestrike_event_type';

    function __construct() {}

    public function registerPostType()
    {
        register_post_type($this->postType,
            array(
                'labels' => array(
                    'name' => 'Events',
                    'singular_name' => 'Event',
                    'add_new_item' => 'Add new',
                    'edit_item' => 'Edit'
                ),
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'show_ui' => true,
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
                'taxonomies' => array($this->taxonomyCategory, $this->taxonomyType, $this->taxonomyTag),
                'query_var' => $this->postType,
                'hierarchical' => false,
                'show_in_rest' => true,
                'menu_icon'   => 'dashicons-calendar-alt',
                'rewrite' => array(
                    'slug' => 'event',
                    'with_front' => false
                )
            )
        );
    }

    function registerTaxonomies()
    {
        register_taxonomy(
            $this->taxonomyCategory,
            $this->postType,
            array(
                'label' => 'Category',
                'hierarchical' => true,
                'show_in_rest' => true,
                'rewrite' => array(
                    'slug' => 'event-category',
                )
            )
        );

        register_taxonomy(
            $this->taxonomyType,
            $this->postType,
            array(
                'label' => 'Type',
                'hierarchical' => true,
                'show_in_rest' => true,
                'rewrite' => array(
                    'slug' => 'event-type',
                )
            )
        );

        register_taxonomy(
            $this->taxonomyTag,
            $this->postType,
            array(
                'label' => 'Search Keywords',
                'hierarchical' => false,
                'show_in_rest' => true,
                'rewrite' => array(
                    'slug' => 'event-keyword',
                    'with_front' => false
                )
            )
        );
    }

    public function getEventsInCategory($categorySlug, $wpQueryArgs = array(), $fallback = true)
    {
        $defaultWpQueryArgs = array(
            'tax_query' => array(
                array(
                    'taxonomy' => $this->taxonomyCategory,
                    'field' => 'slug',
                    'terms' => $categorySlug,
                )
            )
        );
        $wpQueryArgs = array_merge($defaultWpQueryArgs, $wpQueryArgs);

        $events = $this->getEvents($wpQueryArgs);

        if (!$events && $fallback) {
            // fallback to any latest events
            unset($wpQueryArgs['tax_query']);
            $events = $this->getEvents($wpQueryArgs);
        }

        return $events;
    }

    public function getEvents($wpQueryArgs = array())
    {
        $defaultWpQueryArgs = array(
            'post_type' => $this->postType,
            'posts_per_page' => -1,
            'meta_key' => 'climatestrike_event_details_block_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'climatestrike_event_details_block_start_time',
                        'value' => date('Y-m-d'),
                        'compare' => '>=',
                        'type' => 'date'
                    ),
                    array(
                        'key' => 'climatestrike_event_details_block_end_time',
                        'value' => date('Y-m-d'),
                        'compare' => '>=',
                        'type' => 'date'
                    ),
                ),
                array(
                    'key' => 'climatestrike_event_facebook_is_canceled',
                    'value' => 'true',
                    'compare' => '!='
                ),
            ),
        );
        $wpQueryArgs = array_merge($defaultWpQueryArgs, $wpQueryArgs);

        $events = Timber::get_posts($wpQueryArgs);

        foreach($events as $event) {
            $event = $this->setCustomData($event);
        }

        return $events;
    }

    public function setCustomData(Timber\Post $post)
    {
        $post = $this->setFeaturedImage($post);
        $post = $this->setOwner($post);
        $post = $this->setDates($post);

        return $post;
    }

    /*
        Get all the event dates and set them in "Europe/London"
        timezone
    */
    public function setDates(Timber\Post $post)
    {
        date_default_timezone_set("Europe/London");
        $post->multiDayEvent = false;

        if (isset($post->custom['climatestrike_event_details_block_date'])) {
            $eventDate = strtotime($post->custom['climatestrike_event_details_block_date']);
            $post->eventDate = date("Y-m-d\TH:i", $eventDate);
            $post->utcTimezone = 'UTC ' . date("P", $eventDate);
        }

        if (isset($post->custom['climatestrike_event_details_block_start_time'])) {
            $eventStart = strtotime($post->custom['climatestrike_event_details_block_start_time']);
            $post->eventStart = date("Y-m-d\TH:i", $eventStart);
        }

        if (isset($post->custom['climatestrike_event_details_block_end_time'])) {
            $eventEnd = strtotime($post->custom['climatestrike_event_details_block_end_time']);
            $post->eventEnd = date("Y-m-d\TH:i", $eventEnd);
        }

        if (
            isset($post->eventStart)
            &&
            isset($post->eventEnd)
        ) {
            $startDate = date('d F Y', strtotime($post->eventStart));
            $endDate = date('d F Y', strtotime($post->eventEnd));

            if ($startDate !== $endDate) {
                $post->multiDayEvent = true;
            }
        }

        date_default_timezone_set("UTC");

        return $post;
    }

    public function setOwner(Timber\Post $post)
    {
        if (isset($post->climatestrike_event_facebook_owner)) {
            $owner = json_decode($post->climatestrike_event_facebook_owner, true);
            $post->facebook_owner_name = $owner['name'];
            $post->facebook_owner_id = $owner['id'];
        }

        return $post;
    }

    public function setFeaturedImage(Timber\Post $post)
    {
        $image = false;

        // check for thumbnail image first
        $image = get_the_post_thumbnail_url($post->id);

        // fallback to facebook image
        if (!$image && isset($post->climatestrike_event_facebook_cover_photo)) {
            $fbImg = json_decode($post->climatestrike_event_facebook_cover_photo, true);
            // sometimes the field is not a json string but simply an image url
            $fbImg = ($fbImg) ? $fbImg['source'] : $post->climatestrike_event_facebook_cover_photo;
            $image = $fbImg;
        }

        if ($image) {
            $post->event_image = $image;
        }

        return $post;
    }

    /*
        Return a list of all event types
    */
    public function getEventTypes()
    {
        return get_terms(array(
            'taxonomy' => $this->taxonomyType,
            'hide_empty' => true
        ));
    }

    public function getTaxonomyType()
    {
        return $this->taxonomyType;
    }

    public function getTaxonomyTag()
    {
        return $this->taxonomyTag;
    }
}

add_action('init', function() {
    $events = new climatestrike_Events;
    $events->registerTaxonomies();
}, 10);

add_action('init', function() {
    $events = new climatestrike_Events;
    $events->registerPostType();
}, 20);
