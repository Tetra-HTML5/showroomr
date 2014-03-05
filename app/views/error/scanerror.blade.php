@extends('frontend.master')

@section('page')
<div data-role="header" data-theme="d">
    <h1>showroomr</h1>
</div>

<div style="margin:10px;">
    <div class="article" style="position:absolute; z-index:1;">
        <h1>Foute QR code gescand?</h1>
        <h2>Dit is helaas geen geldige QR code voor een product.</h2>
        <p><a data-role="button"  style="display:inline;" href="{{ url('/route/scan/doScan') }}">Opnieuw scannen</a></p>
        <br />
        <p><a data-role="button"  style="display:inline;" href="{{ url('/') }}">Terug naar de app</a></p>
    </div>
    <img src="{{ url('/assets/img/error-404.png') }}" style="position:absolute; bottom:0; width:100%; max-height:300px; margin-left:-16px;" alt="error"/>

@endsection