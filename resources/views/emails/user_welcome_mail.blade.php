<h2>Thank you for signing up at tipexpats!</h2><br/>
<p>Dear <strong>{{ $user->first_name }}</strong>,</p>
<p>We are delighted to have you join us as a user here on tipexpats.com.</p>
<p>To confirm your email address, kindly click on the link below. </p>
<p>http://localhost:8000/email-confirmation?token={{ $conf->token }}</p>
<p>You can reach us on <strong>steve@tipexpats.com</strong> or <strong>08023572852</strong> for any enquiry.</p>
<p>Sincerely,</p>
<p>Steve</p>
<p><a href="#">www.tipexpats.com</a></p>
