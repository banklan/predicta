<p>Dear <strong>{{ $user->f_name }}</strong>,</p>
<p>Please find below our daily tips for today {{ $tip_summary->published }}.</p>

<table class="table table-striped table-bordered table-condensed blade_table">
    <thead>
        <tr style="font-size: 10px">
            <th>League</th>
            <th>Game</th>
            <th>Tip</th>
            <th>Time</th>
        </tr>
    <thead>
    <tbody>
        @foreach($tips as $tip)
            <tr style="font-size: 9px">
                <td>{{ $tip->league }}</td>
                <td>{{ $tip->home }} Vs {{ $tip->away }}</td>
                <td>{{ $tip->tip }}</td>
                <td>{{ $tip->time }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>
<p>We wish you best of luck as you stake responsibly.</p>
<p>Sincerely,</p>
<p>Steve</p>
<p><a href="#">www.tipexpats.com</a></p>
