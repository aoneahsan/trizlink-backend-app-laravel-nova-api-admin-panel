<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Default\User' => 'App\Policies\UserPolicy',
        'App\Models\Default\Workspace' => 'App\Policies\WorkspacePolicy',

        'App\Models\ZLink\ShortLinks\CustomDomain' => 'App\Policies\CustomDomainPolicy',
        'App\Models\ZLink\ShortLinks\EmbededWidget' => 'App\Policies\EmbededWidgetPolicy',
        'App\Models\ZLink\ShortLinks\ShortLink' => 'App\Policies\ShortLinkPolicy',

        'App\Models\ZLink\LinkInBios\LinkInBio' => 'App\Policies\LinkInBioPolicy',
        'App\Models\ZLink\LinkInBios\LibBlock' => 'App\Policies\LibBlockPolicy',
        'App\Models\ZLink\LinkInBios\LibPredefinedData' => 'App\Policies\LibPreDefinedDataPolicy',

        'App\Models\ZLink\Analytics\Pixel' => 'App\Policies\PixelPolicy',
        'App\Models\ZLink\Analytics\UtmTag' => 'App\Policies\UtmTagPolicy',

        'App\Models\ZLink\Common\ApiKey' => 'App\Policies\ApiKeyPolicy',
        'App\Models\ZLink\Common\Folder' => 'App\Policies\FolderPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
