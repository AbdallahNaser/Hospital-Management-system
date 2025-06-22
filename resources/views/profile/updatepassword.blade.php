@extends('layouts.app')
@section('title')
    {{ __('messages.user.profile_details') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <h1>{{ __('messages.user.edit_profile') }}</h1>
        </div>

        <div class="col-12">
            @include('layouts.errors')
            @include('flash::message')
        </div>
        <div class="card"> 
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ route('update.password') }}">
                                    @csrf
            
                                    <div class="form-group">
                                        <input placeholder="New Password" id="password" type="password" name="password" class="form-control" required>
                                    </div>
            <br>
                                    <div class="form-group" >
                                        <input placeholder="Confirm Password" id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                                    </div>
            
                                    <button type="submit" class="btn btn-primary" style="margin: 20px 20px 20px 0px;">Update Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection