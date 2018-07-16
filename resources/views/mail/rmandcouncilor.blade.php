<p>
    Dear {{ $name }},
    <br>
    <br>
    The following tasks:
    <br>
<ul>
    @foreach($tasks as $task)
        <li><strong>{{ $task }}</strong></li>
    @endforeach
</ul>

Of your client's : {{ $client_name }}  {{ $subject }} for tasks.
<br>
Please contact your <strong> Client </strong>.
<br>
Phone:{{ $client_mobile }}
<br>
Email:{{ $client_email }}
<br>
For more information give us a call at <strong>01778-000400</strong> or visit our office at 1st Floor, Plot 56/b, Road No 132, Gulshan 1, Dhaka 1212.
<br>
<br>
Thank you.
</p>