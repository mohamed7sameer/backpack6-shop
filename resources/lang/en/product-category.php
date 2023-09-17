<?php

return [

    /**
     * CRUD
     */
    'crud' => [
        'singular' => 'product category',
        'plural' => 'product categories',
        'tabs' => [
            'info' => 'Information',
            'media' => 'Media',
            'seo' => 'SEO'
        ],
        'name' => [
            'label' => 'Category title',
        ],
        'slug' => [
            'label' => 'Slug',
            'hint' => 'Used for the category URL',
        ],
        'description' => [
            'label' => 'Description',
        ],
        'cover' => [
            'label' => 'Cover image',
        ],
        'meta-title' => [
            'label' => 'SEO/Meta title',
            'hint' => 'Title of the page to be shown when sharing the page using social media, as well as in search engine results (blue underlined title in Google).',
        ],
        'meta-description' => [
            'label' => 'SEO/Meta description',
            'hint' => 'Brief introduction to the page to be shown when sharing the page using social media, as well as in search engines (black text in Google).',
        ],
        'meta-image' => [
            'label' => 'SEO/Meta image',
            'hint' => 'This image is shown when sharing the page on social media.',
        ],
    ],


    /**
     * Request / validation
     */
    'request' => [
        'name' => [
            'required' => 'You have not given this category a title.',
            'min' => 'The title should be at least 3 characters.',
            'max' => 'The title can not exceed 255 characters.',
        ],
        'slug' => [
            'required_with' => 'A slug of at least 3 characters is required.',
            'min' => 'The slug should be at least 3 characters.',
            'max' => 'The slug can not exceed 255 characters.',
        ],
    ],
];
