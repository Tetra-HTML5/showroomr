@extends('frontend.master')

@section('page')
<div data-role="header" data-theme="d">
    <h1>showroomr</h1>
</div>

<div style="margin:10px;">

    <h1>Welkom</h1>
    <p style="font-size:16pt;">Vul je e-mailadres in om <strong>showroomr</strong> te kunnen gebruiken.</p>

    {{ Form::open(array('url' => '/login', 'data-ajax'=>'false')) }}
    <label for="email">E-mailadres:</label>
    {{ $errors->first('email', '<span class="error">:message</span>') }}
    <div{{ $errors->first('email', ' class="error"') }}>
    <input type="email" name="email" placeholder="voorbeeld@mail.com" value="{{ Input::old('email') }}"/>
</div>
<input type="submit" value="Ga verder" data-icon="arrow-r" />
{{ Form::close() }}
</div>
@endsection