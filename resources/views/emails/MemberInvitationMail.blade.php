<x-mail::message>
@if ($invitedUser->signUpType === \App\Zaions\Enums\SignUpTypeEnum::invite->value)
    # Welcome to ZLink.

    ## You have been invited by "{{$user->name}}" to join "{{$team->title}}" in "{{$workspace->title}}" worksapce.

    Please follow the link below to access your account.

    You will need to setup a password to complete the account on-boarding process and accept the invitation to "{{$team->title}}".
@endif

@if ($invitedUser->signUpType === \App\Zaions\Enums\SignUpTypeEnum::normal->value)
# You are invited in "{{$team->title}}" team.

{{$user->name}} send you invitation to join "{{$team->title}}" team of "{{$workspace->title}}" worksapce.
@endif


<x-mail::button :url="$redirectUrl">
Except invitation
</x-mail::button>

<div class="" style="width: 54rem; overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2;">
<p style="width: 100%"><a href="{{$redirectUrl}}" target="blank">{{$redirectUrl}}</a></p>
</div>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
