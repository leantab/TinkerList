<x-mail::message>
    # New Event

    Hi {{ $user->name }},<br><br>

    You have been invited to a new event at TinkerList Calendar Events.<br>
    Please click the button below to view the event details and confirm your attendance.<br><br>

    <x-mail::button :url="$url">
        View Event
    </x-mail::button>

    @if ($newUser)
        ## Note:
        Since you don't have an account yet, we've created one for you. <br>
        You can use the following credentials to login:<br><br>
        <br><br>
        <strong>Username:</strong> {{ $user->email }}<br>
        <strong>Password:</strong> "password"<br>
    @endif

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
