@php
    {{--['user'=>..., 'size'=>64] 처럼 아바타의 크기를 변수로 넘겼을 때를 대비--}}
    $size = isset($size) ? $size : 48;
@endphp

{{--아바타 조각 뷰에 $user 변수가 넘어오지 않거나 null 값이 넘어왔을때를 위한 예외 처리--}}
@if(isset($user) and $user)
    <a class="pull-left" href="{{ gravatar_profile_url($user->email) }}">
        <img class="media-object img-thumbnail" src="{{ gravatar_url($user->email, $size) }}" alt="{{ $user->name }}">
    </a>
@else
    <a class="pull-left" href="{{ gravatar_profile_url('unknown@example.com') }}">
        <img class="media-object img-thumbnail" src="{{ gravatar_url("unknown@example.com", $size) }}" alt="Uknown User">
    </a>
@endif