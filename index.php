<?php
return [
    'name' => 'unsplash',

    'events' => [
        'view.scripts' => function ($event, $scripts) {
            $scripts->register('unsplash-finder', 'unsplash:app/bundle/unsplash-finder.js', ['~multi-finder']);
        }
    ]
];
