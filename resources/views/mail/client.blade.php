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

    {{ $subject }}
    <br>
    Please contact your <strong>
    @if($assignee_role == 'rm')
        {{ 'Relationship Manager' }}
        @else
            {{ $assignee_role }}
        @endif
    </strong>.
    <br>
    Phone:{{ $assignee_mobile }}
    <br>
    Email:{{ $assignee_email }}
    <br>
    For more information give us a call at <strong>01778-000400</strong> or visit our office at 1st Floor, Plot 56/b, Road No 132, Gulshan 1, Dhaka 1212.
    <br>
    <br>
    Thank you.
</p>