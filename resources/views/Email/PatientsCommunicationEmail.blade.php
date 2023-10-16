@component('mail::message')
    {{ $mailData['title'] }}

{{--The body of your message.--}}
    {{ $mailData['body'] }}

{{--@component('mail::button', ['url' => ''])--}}
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
