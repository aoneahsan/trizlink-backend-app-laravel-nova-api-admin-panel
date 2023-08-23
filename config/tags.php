<?php

// more info: https://spatie.be/docs/laravel-tags/v4/installation-and-setup

return [

    /*
     * The given function generates a URL friendly "slug" from the tag name property before saving it.
     * Defaults to Str::slug (https://laravel.com/docs/master/helpers#method-str-slug)
     */
    'slugger' => null,

    /*
     * The fully qualified class name of the tag model.
     */
    'tag_model' => Spatie\Tags\Tag::class,
];
