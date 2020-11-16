@component('mail::message')
{{-- Greeting --}}
# {{ $greeting }}

@lang('email.newJobApplication.subject')

@component('mail::text', ['text' => $content])

@endcomponent
@if($buttonUrl)
@component('mail::extrabutton', ['buttonurl' => $buttonUrl])
    {{ $extraButtonText }}
@endcomponent
@endif

@component('mail::button', ['url' => $url])
    {{ $buttonText }}
@endcomponent

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($url)
@component('mail::subcopy')
@lang(
"If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
'into your web browser: [:actionURL](:actionURL)',
[
'actionText' => $buttonText,
'actionURL' => $url
]
)
@endcomponent
@endisset
@endcomponent

