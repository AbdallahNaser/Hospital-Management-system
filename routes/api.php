<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

/* ------------------------Start register && login Patient-----------------------*/

Route::post('registerpatient', [App\Http\Controllers\PatientController::class, 'store']);
Route::post('loginpatient', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

/* ------------------------End register && login Patient-----------------------*/



/* ------------------------Start register && login Doctor-----------------------*/

Route::post('registerdoctor', [App\Http\Controllers\UserController::class, 'storeDoctor']);
Route::post('logindoctor', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

/* ------------------------End register && login Doctor-----------------------*/


Route::middleware('auth:api', 'xss', 'checkUserStatus')->group(function () {



  Route::post('/update-password', [App\Http\Controllers\UserController::class,'updatePassword']);



  
//-------------------months analysis route--------------------
Route::get('/monthsanalysis', [App\Http\Controllers\AppointmentController::class, 'getmonthsnumber']);






  // Update profile 
  Route::get('/profile/edit', [App\Http\Controllers\UserController::class, 'editProfile']);
  Route::post('/profile/update', [App\Http\Controllers\UserController::class, 'updateProfile']);
  //  Route::put('/change-user-password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('user.changePassword');
  //   Route::put('/email-notification', [UserController::class, 'emailNotification'])->name('emailNotification');

  //---------------------------view all Doctors-------------------------------------------
  Route::get('/doctorspatient', [App\Http\Controllers\ViewDoctorController::class, 'jsonindex']);

  //---------------------show details of specific Doctor-------------------------------------
  Route::get('/doctorpatientdetails/{doctor}', [App\Http\Controllers\ViewDoctorController::class, 'show'])->name('doctorpatientdetails');

 
  //-----------------------------Edit and Update services------------------------------------
  Route::get('/doctors/{doctor}/edit-services', [App\Http\Controllers\UserController::class, 'neweditServices'])->name('doctor.edit-services');
  Route::put('/doctors/{doctor}/update-services', [App\Http\Controllers\UserController::class, 'updateServices'])->name('doctor.update-services');

  //--------------------------filter Doctors services------------------------------------------
  Route::get('/doctors/activated-service/{serviceId}', [App\Http\Controllers\UserController::class, 'doctorsWithActivatedService']);

  //--------------------------filter Doctors specialization------------------------------------
  Route::get('/specializationfilter/{id}', [App\Http\Controllers\ViewDoctorController::class, 'jsonspecializationfilter'])->name('specializationfilter');
 

  //--------------------------search on doctors------------------------------------------------
  Route::get('/searchdoctor/{search}', [App\Http\Controllers\ViewDoctorController::class, 'searchfilter'])->name('searchdoctor');


  //--------------------------NewPrescription------------------------
  Route::get('newpres/show/{appointmentId}', [App\Http\Controllers\NewPresController::class, 'show'])->name('newpres.show');
  Route::get('newpres/create', [App\Http\Controllers\NewPresController::class, 'create'])->name('newpres.create');
  Route::post('newpres/store', [App\Http\Controllers\NewPresController::class, 'store'])->name('newpres.store');
 
  Route::get('newpres/edit/{id}', [App\Http\Controllers\NewPresController::class, 'edit'])->name('newpres.edit');
  Route::put('newpres/edit/{id}', [App\Http\Controllers\NewPresController::class, 'update'])->name('newpres.update');



  Route::get('getDrugsForAppointment/{appointmentid}', [App\Http\Controllers\NewPresController::class, 'getDrugsForAppointment']);
  Route::get('pres/delete/{id}', [App\Http\Controllers\NewPresController::class, 'destroy']);



  //-------------------------------Doctor Slots------------------------------------------------

  Route::resource('doctor-day', App\Http\Controllers\DoctorDayController::class);
  Route::match(['get', 'post'], '/generatedoctortimedays/{doctor_id}/{selecteddate}', [App\Http\Controllers\DoctorDayController::class, 'generateTimeSlots']);
  // file upload for appointment
  Route::post('upload', [App\Http\Controllers\PatientfileController::class, 'uploadFile']);
  Route::get('appointmentfiles/{appointmentId}', [App\Http\Controllers\PatientfileController::class, 'showFilesForAppointment']);
  Route::get('download/{appointmentId}/{fileId}', [App\Http\Controllers\PatientfileController::class, 'downloadFile']);
  Route::get('image/{file}', [App\Http\Controllers\PatientfileController::class, 'displayImage']);



//-------------------------------------Logout----------------------------

  Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout']);



});
//https://zoom.us/oauth/authorize?response_type=code&client_id=tedJfPWShalaPC2bqWshQ&redirect_uri=https%3A%2F%2Flocalhost%2Foauth%2Fcallback


Route::prefix('doctors')->name('doctors.')->middleware('auth:api', 'xss', 'checkUserStatus', 'role:doctor')->group(function () {








  Route::post('/updateInvitationLink', [AppointmentController::class, 'updateInvitationLink']);
  

 //------------------------------update appointment status---------------------------

 Route::post('/updatepaymentstatus/{status}/{appointment_id}', [AppointmentController::class, 'updatepaymentstatus']);
        


  
//-------------------months analysis route for a specific doctor--------------------
Route::get('/doctormonthsanalysis', [App\Http\Controllers\AppointmentController::class, 'getdoctormonthsnumber']);



  //Doctor Appointment route

  // Doctor Session Routes
  Route::resource('appointments', AppointmentController::class)->except(['index', 'edit', 'update']);

  Route::get('appointments', [AppointmentController::class, 'doctorAppointment'])->name('appointments');
  Route::get('{doctorId}/allappointments', [AppointmentController::class, 'getAllAppointmentsForDoctor'])->name('allappointments');
  Route::get('appointments-calendar', [AppointmentController::class, 'doctorAppointmentCalendar'])->name('appointments.calendar');
  Route::get('appointments/{appointment}', [AppointmentController::class, 'appointmentDetail'])->name('appointment.detail');
  Route::get('appointment-pdf/{id}', [AppointmentController::class, 'appointmentPdf'])->name('appointmentPdf');

  // bills routes 
  Route::get('transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions');
  Route::get('transactions/{transaction}', [App\Http\Controllers\TransactionController::class, 'show'])->name('transactions.show');
  Route::get('alltransactions', [App\Http\Controllers\TransactionController::class, 'showAllBillsDoctor'])->name('alltransactions');
  Route::get('getAllComingAppointmentsForDoctor',[AppointmentController::class, 'getAllComingAppointmentsForDoctor'])->name('getAllComingAppointmentsForDoctor');
  Route::get('getAllPassedAppointmentsForDoctor',[AppointmentController::class, 'getAllPassedAppointmentsForDoctor'])->name('getAllPassedAppointmentsForDoctor');
  Route::get('getTodayAppointmentsForDoctor',[AppointmentController::class, 'getTodayAppointmentsForDoctor'])->name('getTodayAppointmentsForDoctor');

  //----------------------all appointments and summation of prices------------------------------------
  Route::get('appointmentscount', [App\Http\Controllers\DoctorDayController::class, 'countAppointmentsDoctor'])->name('appointmentscount');
  Route::get('summationpayable', [App\Http\Controllers\DoctorDayController::class, 'sumPayableAmountsDoctor'])->name('summationpayable');


//----------------------monthly appointments and summation of prices------------------------------------
Route::get('countAppointmentsDoctorMonthly', [App\Http\Controllers\DoctorDayController::class, 'countAppointmentsDoctorMonthly'])->name('countAppointmentsDoctorMonthly');
Route::get('sumPayableAmountsDoctorMonthly', [App\Http\Controllers\DoctorDayController::class, 'sumPayableAmountsDoctorMonthly'])->name('sumPayableAmountsDoctorMonthly');





//---------------------------------------Date's Appointments--------------------
Route::get('dateappointments/{date}', [AppointmentController::class, 'getDateAppointments']); 





//---------------------------------------cancelled appointment for doctor--------------------
Route::post('appointment-cancel/{appointmentId}', [AppointmentController::class, 'cancelDoctorStatus'])->name('cancel-status'); 

});



Route::prefix('patients')->name('patients.')->middleware('auth:api', 'xss', 'checkUserStatus', 'role:patient')->group(function () {

  Route::resource('appointments', App\Http\Controllers\AppointmentController::class)->except(['index', 'edit', 'update']);
  Route::get('{patientId}/allappointments', [App\Http\Controllers\AppointmentController::class, 'getallAppointmentsForPatient'])->name('allpatientappointments');
  Route::get('alltransactions', [App\Http\Controllers\TransactionController::class, 'showAllBills'])->name('alltransactions');


//------------------------appointments sections for Patients--------------------------
Route::get('getAllComingAppointmentsForPatient',[App\Http\Controllers\AppointmentController::class, 'getAllComingAppointmentsForPatient'])->name('getAllComingAppointmentsForPatient');
Route::get('getAllPassedAppointmentsForPatient',[App\Http\Controllers\AppointmentController::class, 'getAllPassedAppointmentsForPatient'])->name('getAllPassedAppointmentsForPatient');
Route::get('getTodayAppointmentsForPatient',[App\Http\Controllers\AppointmentController::class, 'getTodayAppointmentsForPatient'])->name('getTodayAppointmentsForPatient');




//---------------------------------------cancelled appointment for patient--------------------
Route::post('appointment-cancel/{appointmentId}', [AppointmentController::class, 'cancelStatus'])->name('cancel-status'); 


});
