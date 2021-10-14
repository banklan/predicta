@component('mail::message')
# Advert Request By {{ $ad['name'] }}

Name: {{ $ad['name'] }} <br>
Company: {{ $ad['company'] }} <br>
Email: {{ $ad['email'] }} <br>
Phone: {{ $ad['phone'] }} <br>
Alternative Phone: {{ $ad['phone2'] }} <br>
Website: {{ $ad['website'] }} <br>
Detail of Products/Services: {{ $ad['details'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
