@extends('frontend.master')

@section('page')
<div data-role="header" data-theme="d">
    <h1>showroomr</h1>
</div>
<div style="margin:10px;">   
        <div class="article" style="position:absolute; z-index:1;">
            <h1>Oeps,</h1>
            <h2>Er is een fout opgetreden.</h2>
        </div>
        <img src="{{ url('/assets/img/error-general.png') }}" style="position:absolute; bottom:0; width:100%; max-height:300px; margin-left:-16px;" alt="error"/>
        
@endsection