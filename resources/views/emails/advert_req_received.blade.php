@component('mail::message')
# Advert Request By {{ $ad['name'] }}

Dear {{ $ad['name'] }}, <br>
Your request to place your business adverts on our platform has been sent.<br>
We will get back to you within the shortest possible time through your phone number **{{ $ad['phone'] }}** or email address **{{ $ad['email'] }}.<br>
Incase you have further enquiries, you can reach us through our phone numbers 08023572852 or through email address **admin@tipexpats.com**.<br>
Thank you for contacting tipexpats.<br>

Thanks,<br>
Steve.<br>
{{ config('app.name') }}
@endcomponent
