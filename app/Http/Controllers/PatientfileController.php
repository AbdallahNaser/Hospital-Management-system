<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patientfile;
use App\Models\Appointment;

use Illuminate\Support\Facades\Storage;
class PatientfileController extends Controller
{
    
    public function uploadFile(Request $request)
{
    $appointmentId = $request->input('appointment_id');
    // Validate the request, including file upload validation rules.
    $validatedData = $request->validate([
        'patientfile' => 'nullable|file|max:2048', // Adjust the file validation rules as needed.
    ]);

   

    // Handle the file upload
    if ($request->hasFile('patientfile')) {
        $file = $request->file('patientfile');
        $fileIdentifier = time() . '_' . $file->getClientOriginalName(); // Unique identifier for the file.

        // Save the file to the storage directory (e.g., public or S3).
        $file->storeAs('public/appointment_files', $fileIdentifier); // Adjust the storage path as needed.

        // Create a new patientfile record to associate with the appointment.
        $patientFile = new PatientFile();
        $patientFile->appointment_id = $appointmentId;
        $patientFile->file_path = $fileIdentifier;
        $patientFile->save();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'file added successfully']);
        }
    } else {
        // Handle the case where no file was uploaded.
    }

    // Redirect or return a response as needed.
}

public function showFilesForAppointment(Request $request, $appointmentId)
    {
        // Retrieve the appointment.
        $appointment = Appointment::findOrFail($appointmentId);

        // Retrieve all patient files associated with the specified appointment.
        $files = PatientFile::where('appointment_id', $appointmentId)->get();


        if (request()->expectsJson()) {
         return response()->json(['appointment' => $appointment, 'files' => $files]);
        }

        // Return the view with the appointment and files data.
        return view('appointments.files', compact('appointment', 'files'));
    }



    public function downloadFile(Request $request, $appointmentId, $fileId)
    {
        // Check if the appointment exists
        $patientFile = PatientFile::where('id', $fileId)
            ->where('appointment_id', $appointmentId)
            ->first();

        if (!$patientFile) {
            // Handle the case where the patient file does not exist or is not associated with the appointment.
            abort(404); // You can customize this error response.
        }

        // Retrieve the file path from the database.
        $filePath = $patientFile->file_path;

        // Define the storage path to the file.
        $storagePath = 'public/appointment_files/' . $filePath; // Adjust the storage path as needed.





        if (request()->expectsJson()) {

           // Check if the file exists in storage.
    if (!Storage::exists($storagePath)) {
        // Handle the case where the file does not exist in storage.
        return response()->json(['error' => 'File not found in storage'], 404);
    }

    // You can return the file details as JSON here.
    // For example, you might return the file name and path.
    return response()->json(['file_name' => $patientFile->file_path, 'file_path' => $storagePath]);
        }





        // Check if the file exists in storage.
        if (!Storage::exists($storagePath)) {
            // Handle the case where the file does not exist in storage.
            abort(404); // You can customize this error response.
        }

        // Serve the file for download.
        return Storage::download($storagePath, $filePath); // This will trigger the file download.
    }


    public function displayImage($file)
{
    $filePath = 'public/appointment_files/' . $file;
    

    if (request()->expectsJson()) {
        if (Storage::exists($filePath)) {
            $fileContents = Storage::get($filePath);
            $mimeType = Storage::mimeType($filePath);
    
            // Return the image details as JSON.
            return response()->json([
                'file_name' => $file,
                'mime_type' => $mimeType,
                'file_contents' => base64_encode($fileContents), // Encode binary data as base64 if needed.
            ]);
        } else {
            // Handle the case where the file does not exist.
            return response()->json(['error' => 'Image not found'], 404);
        }

    }
    if (Storage::exists($filePath)) {
        $fileContents = Storage::get($filePath);
        $mimeType = Storage::mimeType($filePath);

        return response($fileContents)
            ->header('Content-Type', $mimeType);
    } else {
        // Handle the case where the file does not exist.
        abort(404); // You can customize this error response.
    }
}

  
}