@foreach ($appointments as $appointment)
    <div>
        <p>Year: {{ $appointment->year }}</p>
        <p>Month: {{ $appointment->month }}</p>
        <p>Appointment Count: {{ $appointment->appointment_count }}</p>
        <p>Total Payable Amount: {{ $appointment->total_payable_amount }}</p>
    </div>
@endforeach