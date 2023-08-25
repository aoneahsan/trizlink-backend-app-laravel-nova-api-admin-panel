<x-mail::message>
# You are invited in '`{{$team->title}}`' team.

{{$user->name}} send you invitation to join '`{{$team->title}}`' team of '`{{$workspace->title}}`' worksapce.

<div class="" style="width: 30%;"><a href="{{$redirectUrl}}" target="blank">{{$redirectUrl}}</a></div>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
