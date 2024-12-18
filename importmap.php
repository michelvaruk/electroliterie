<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@hotwired/turbo' => [
        'version' => '8.0.4',
    ],
    'file-loader' => [
        'version' => '6.2.0',
    ],
    'loader-utils' => [
        'version' => '3.3.1',
    ],
    'schema-utils' => [
        'version' => '4.2.0',
    ],
    'json5' => [
        'version' => '2.2.3',
    ],
    'big.js' => [
        'version' => '6.2.1',
    ],
    'emojis-list' => [
        'version' => '3.0.0',
    ],
    'ajv' => [
        'version' => '8.17.1',
    ],
    'ajv-keywords' => [
        'version' => '5.1.0',
    ],
    'uri-js' => [
        'version' => '4.4.1',
    ],
    'fast-deep-equal' => [
        'version' => '3.1.3',
    ],
    'json-schema-traverse' => [
        'version' => '1.0.0',
    ],
    'fast-json-stable-stringify' => [
        'version' => '2.1.0',
    ],
    'quill' => [
        'version' => '2.0.2',
    ],
    'lodash-es' => [
        'version' => '4.17.21',
    ],
    'parchment' => [
        'version' => '3.0.0',
    ],
    'quill-delta' => [
        'version' => '5.1.0',
    ],
    'eventemitter3' => [
        'version' => '5.0.1',
    ],
    'fast-diff' => [
        'version' => '1.3.0',
    ],
    'lodash.clonedeep' => [
        'version' => '4.5.0',
    ],
    'lodash.isequal' => [
        'version' => '4.5.0',
    ],
    'quill/dist/quill.snow.css' => [
        'version' => '2.0.2',
        'type' => 'css',
    ],
    'quill/dist/quill.bubble.css' => [
        'version' => '2.0.2',
        'type' => 'css',
    ],
    'axios' => [
        'version' => '1.7.2',
    ],
    'quill2-emoji' => [
        'version' => '0.1.2',
    ],
    'quill2-emoji/dist/style.css' => [
        'version' => '0.1.2',
        'type' => 'css',
    ],
    'quill-resize-image' => [
        'version' => '1.0.4',
    ],
    'ajv-formats' => [
        'version' => '2.1.1',
    ],
    'fast-uri' => [
        'version' => '3.0.1',
    ],
    'ajv/dist/compile/codegen' => [
        'version' => '8.12.0',
    ],
    'tom-select' => [
        'version' => '2.3.1',
    ],
    'tom-select/dist/css/tom-select.default.css' => [
        'version' => '2.3.1',
        'type' => 'css',
    ],
    'flowbite' => [
        'version' => '2.5.2',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'flowbite-datepicker' => [
        'version' => '1.3.0',
    ],
    'flowbite/dist/flowbite.min.css' => [
        'version' => '2.5.2',
        'type' => 'css',
    ],
    'intl-tel-input/build/css/intlTelInput.min.css' => [
        'version' => '24.6.0',
        'type' => 'css',
    ],
    'intl-tel-input' => [
        'version' => '24.6.0',
    ],
    'leaflet' => [
        'version' => '1.9.4',
    ],
    'leaflet/dist/leaflet.min.css' => [
        'version' => '1.9.4',
        'type' => 'css',
    ],
    '@symfony/ux-leaflet-map' => [
        'path' => './vendor/symfony/ux-leaflet-map/assets/dist/map_controller.js',
    ],
    '@stripe/stripe-js' => [
        'version' => '4.8.0',
    ],
];
