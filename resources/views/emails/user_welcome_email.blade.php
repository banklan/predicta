@component('mail::message')
# Thank you for signing up at tipexpats!

Dear **{{ $user->first_name }}**,<br>
We are delighted to have you join us as a user here on tipexpats.com. We hope you enjoy your experience with us and wish you the best of luck while we ask you to gamble responsibly.<br>
To confirm your email address, kindly click on the link below. <br>

@component('mail::button', ['url' => 'https://surepredict.herokuapp.com/email-confirmation?token='.$conf->token])
Confirm Your Email
@endcomponent

Sincerely,<br>
{{ config('app.name') }}
@endcomponent
