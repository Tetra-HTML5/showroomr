@extends('emails.default')

@section('content')
<p>Hello {{ $user->first_name }},</p>

<p>Please click on the following link to updated your password:</p>

<p><a href="{{ $forgotPasswordUrl }}">{{ $forgotPasswordUrl }}</a></p>

<p>Best regards,</p>

<p>Product Locator Team</p>
@stop
