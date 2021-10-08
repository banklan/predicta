@component('mail::message')
# Thank you for signing up at tipexpats as an expert tipstar!

Dear **{{ $expert->fullname }}**,<br/>
We are excited to have you as one of our team of forecasters at tipexpats.com.
As you might have learnt, we are a team of talented, resourceful and effective game forecasters to help our users maximize profit when they bet on games.
When you join us as an expert forecaster, you turn your passion into earnings as we pay you weekly based on a percentage of the subscriptions that users make on your forecasts.
But first, you need to confirm this email address by clicking on the link below.

@component('mail::button', ['url' => 'https://surepredict.herokuapp.com//expert-email-confirmation?token={{ $conf->token }}'])
Confirm Email
@endcomponent

Sincerely,<br>
{{ config('app.name') }}
@endcomponent
