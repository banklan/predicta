@component('mail::message')
#Daily tips for {{ $tip_summary->published }} from tipexpats.com

Dear **{{ $user->f_name }}**,<br/>
Please find below our daily tips for today, {{ $tip_summary->published }}.
(Please view on a wide screen)

@component('mail::table')
|League                                   |Game                                                   |Tip                                             |Time                                |
| :-------------------------------------- | :---------------------------------------------------- | :--------------------------------------------: | :--------------------------------: |
@foreach($tips as $tip)
|{{ $tip->league}}                        |{{ $tip->home }} Vs {{ $tip->away }}                   |{{ $tip->tip }}                                 |{{ $tip->time }}                    |
@endforeach
@endcomponent

Thanks,<br>
Steve<br>
{{ config('app.name') }}
@endcomponent
