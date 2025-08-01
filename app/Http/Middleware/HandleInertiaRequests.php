<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use App\Models\Common\Customization;
use App\Models\Systems\Master\MasterParameter;
use App\Models\Systems\Tenant\TenantParameter;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = "react.App";

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $style = '';
        $colors = collect();


        $colors = Customization::where('type', 'style')->get();
        $contents = Customization::where('type', 'content')->get();
        $parameters = collect();
        $ifTenant = tenancy()->initialized;

        if ($ifTenant) {
            $parameters = TenantParameter::find(1);
        } else {
            $parameters = MasterParameter::find(1);
        }

        $customColors = collect();
        foreach ($colors as $color) {
            // Ex: 'primary_color' => '#ff0000' vira 'primaryColor' => '#ff0000'
            $key = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $color->key))));
            $customColors[$key] = $color->value;
        }

        $customContents = collect();
        foreach ($contents as $content) {
            $key = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $content->key))));
            $customContents[$key] = $content->value;
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'customizations' => [
                'styles' => $customColors,
                'contents' => $customContents
            ],
            'parameters' => $parameters
        ];
    }
}
