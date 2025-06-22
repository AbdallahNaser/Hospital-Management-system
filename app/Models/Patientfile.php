<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Patientfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'file_name',
        // Add other fillable columns here
    ];

    // Define the relationship with the Appointment model
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}