@extends('layouts.app')
@section('title')
    {{__('messages.doctors')}}
@endsection
@section('content')
    
<!-- edit-services.blade.php -->

<form method="POST" action="{{ route('doctor.update-services', $doctor->id) }}" style="margin-left: 230px;">
    @csrf
    @method('PUT')

    @foreach($services as $service)
    <div class="form-group" style="margin-bottom: 15px; border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
        <label for="service-{{ $service->id }}" style="font-weight: bold;">{{ $service->name }}</label>
        <input type="checkbox" name="services[{{ $service->id }}][activated]" id="service-{{ $service->id }}"
               value="1" {{ $service->pivot->activated ? 'checked' : '' }} style="margin-left: 10px;">
        <input type="text" name="services[{{ $service->id }}][price]" id="price-{{ $service->id }}"
               value="{{ $service->pivot->price }}" style="margin-left: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 5px;">
    </div>
    @endforeach

    <button type="submit" style="background-color: #007bff; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Save</button>
</form>


@endsection