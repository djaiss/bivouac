<x-mail::message>
# @lang('Please join Bivouac')

@lang(':userName invites you to join Bivouac, a great way to manage projects.', ['userName' => $userName])

@component('mail::button', ['url' => $url])
@lang('Accept invitation and create your account')
@endcomponent

@lang('Thanks,')<br>
{{ config('app.name') }}
</x-mail::message>
