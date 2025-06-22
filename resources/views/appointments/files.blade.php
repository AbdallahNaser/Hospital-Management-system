@extends('layouts.app') // Use your own layout if you have one.

@section('content')
    <h1>Files for Appointment #{{ $appointment->id }}</h1>

    <ul>
        @foreach ($files as $file)
            <li>
                <span>{{ $file->file_path }}</span>
                <a href="{{ route('displayImage', ['file' => $file->file_path]) }}" target="_blank" class="btn btn-primary btn-sm">View Image</a>
                <a href="{{ route('file.download', ['appointmentId' => $appointment->id, 'fileId' => $file->id]) }}" class="btn btn-success btn-sm">Download</a>
            </li>
        @endforeach
    </ul>
@endsection