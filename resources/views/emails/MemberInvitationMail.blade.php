<x-mail::message>
# Welcome to ZLink.
@if ($invitedUser->signUpType === \App\Zaions\Enums\SignUpTypeEnum::invite->value)

    ## You have been invited by "{{$user->username}}" to join "{{$workspace->title}}" worksapce.

    Please follow the link below to access your account.

    You will need to setup a password to complete the account on-boarding process and accept the invitation to join "{{$workspace->title}}" worksapce.
@endif

@if ($invitedUser->signUpType === \App\Zaions\Enums\SignUpTypeEnum::normal->value)
    ## You are invited in "{{$workspace->title}}" worksapce.

    {{$user->name}} send you invitation to join "{{$workspace->title}}" worksapce.
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
