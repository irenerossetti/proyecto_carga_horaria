@include('partials.settings-heading')

<x-settings.layout
    :heading="__('Two Factor Authentication')"
    :subheading="__('Two-factor authentication has been removed from this application')"
>
    <div class="p-6 text-sm text-stone-700 dark:text-stone-200">
        <p>{{ __('Two-factor authentication (2FA) features were intentionally removed from this deployment. If you need to re-enable 2FA in the future, please re-add the configuration and related database columns.') }}</p>
    </div>

</x-settings.layout>
