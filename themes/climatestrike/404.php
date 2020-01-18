<?php

$context = Timber::get_context();
Timber::render(array('404.twig', 'base.twig'), $context);
