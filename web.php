<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// use App\Page;


Auth::routes();
Route::post('/login-submit', 'AuthController@loginCheck')->name('user.login.submit');
Route::get('/register', 'AuthController@register')->name('register');

// website route
Route::get('/', 'FrontController@index')->name('home');
Route::get('/blog/search', 'FrontController@blogSearch')->name('blog.search');
Route::get('/blog/search_ajax', 'FrontController@blogSearchAjax')->name('blog_search_ajax');
Route::get('/blog/{id}/view', 'FrontController@blogShow')->name('blog.show');

Route::get('/appointment-booking', 'Patient\AppointmentController@appointmentBook')->name('appointment.book');
Route::get('/appointment-schedule-ajax', 'Patient\AppointmentController@appointmentScheduleAjax')->name('appointment.schedule.ajax');
Route::post('/appointment-booking', 'Patient\AppointmentController@appointmentStore')->name('appointment.store');
Route::get('/appointment-thanku', 'Patient\AppointmentController@appointmentThanku')->name('appointment.thanku');

Route::post('/contact-store', 'CommonController@contactStore')->name('contact.store');

Route::group(['middleware' => ['auth']], function ()
{
    Route::get('/appointment/{id}/print', 'Consultant\AppointmentController@printPrescription')->name('appointment.print_prescription');

    Route::get('/notification-list', 'CommonController@notification_list')->name('notification_list');
    Route::post('/notification-destroy/{id}', 'CommonController@notification_destroy')->name('notification_destroy');

    Route::get('/change_password', 'CommonController@changePassword')->name('change_password');
    Route::post('/update_password', 'CommonController@updatePassword')->name('update_password');
    
});



Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth','role:admin']], function ()
{
    Route::get('/dashboard', 'Admin\AdminController@dashboard')->name('dashboard');
    
    
    Route::get('/profile', 'Admin\AdminController@profile')->name('profile');
    Route::post('/update_profile', 'Admin\AdminController@updateProfile')->name('update_profile');
    
    Route::get('/setting', 'Admin\AdminController@setting')->name('setting');
    Route::post('/update_setting', 'Admin\AdminController@updateSetting')->name('update_setting');

    Route::post('/consultant/update_status/{id}', 'Admin\ConsultantController@updateStatus')->name('consultant.update_status');
    Route::get('/consultant/due-payments', 'Admin\ConsultantController@due_payment')->name('consultant.due_payment');
    Route::post('/consultant/{id}/due-payments', 'Admin\ConsultantController@due_payment_submit')->name('consultant.due_payment.submit');
    Route::resource('consultant', 'Admin\ConsultantController');

    
    Route::get('/contact-list', 'Admin\AdminController@contactList')->name('contact.list');


    

    // Category Routes
    Route::get('/category-list', 'Admin\CategoryController@list')->name('category.list');
    Route::post('/category/create', 'Admin\CategoryController@store')->name('category.store');
    Route::post('/category/{id}/edit', 'Admin\CategoryController@update')->name('category.update');
    Route::post('/category/{id}/destroy', 'Admin\CategoryController@destroy')->name('category.destroy');

    
    // Blog Routes
    Route::get('/blogs', 'Admin\BlogController@index')->name('blog.index');
    Route::get('/blog/{id}/view', 'Admin\BlogController@view')->name('blog.view');
    Route::get('/blog/create', 'Admin\BlogController@create')->name('blog.create');
    Route::post('/blog/store', 'Admin\BlogController@store')->name('blog.store');
    Route::get('/blog/{id}/edit', 'Admin\BlogController@edit')->name('blog.edit');
    Route::post('/blog/{id}/update', 'Admin\BlogController@update')->name('blog.update');
    Route::delete('/blog/{id}/destroy', 'Admin\BlogController@destroy')->name('blog.destroy');
    Route::post('/blog/{id}/status/update', 'Admin\BlogController@statusUpdate')->name('blog.status.update');

    
    // Testimonial Routes
    Route::get('/testimonials', 'Admin\TestimonialController@index')->name('testimonial.index');
    Route::get('/testimonial/{id}/view', 'Admin\TestimonialController@view')->name('testimonial.view');
    Route::get('/testimonial/create', 'Admin\TestimonialController@create')->name('testimonial.create');
    Route::post('/testimonial/store', 'Admin\TestimonialController@store')->name('testimonial.store');
    Route::get('/testimonial/{id}/edit', 'Admin\TestimonialController@edit')->name('testimonial.edit');
    Route::post('/testimonial/{id}/update', 'Admin\TestimonialController@update')->name('testimonial.update');
    Route::delete('/testimonial/{id}/destroy', 'Admin\TestimonialController@destroy')->name('testimonial.destroy');
    Route::post('/testimonial/{id}/status/update', 'Admin\TestimonialController@statusUpdate')->name('testimonial.status.update');
    
    // Page Routes
    Route::get('/pages', 'Admin\PageController@index')->name('page.index');
    Route::get('/page/{id}/edit', 'Admin\PageController@edit')->name('page.edit');
    Route::post('/page/{id}/update', 'Admin\PageController@update')->name('page.update');


    //admin ui edits
    Route::get('/home-settings', 'Admin\PageController@editHomeSetting')->name('home-settings');
    Route::post('/home-settings', 'Admin\PageController@updateHomeSetting')->name('update-home-settings');
    // Route::get('/privacy-policy', 'Admin\PageController@privacy')->name('privacy');

    Route::get('/social-details', 'Admin\PageController@editSocial')->name('social-details');
    Route::post('/social-details', 'Admin\PageController@updateSocial')->name('social-details-update');

    Route::get('/contact-details', 'Admin\PageController@editContact')->name('contact-details');
    Route::post('/contact-details', 'Admin\PageController@updateContact')->name('contact-details-update');

    // Route::get('/video-gallery', 'Admin\PageController@video_gallery')->name('video');



    // Route::get('/aboutus1', 'Admin\ConsultantController@aboutus1')->name('aboutus1');
    // Route::get('/labpage', 'Admin\ConsultantController@labpage')->name('labpage');
    // Route::get('/radiology', 'Admin\ConsultantController@radiology')->name('radiology');
    // Route::get('/microbiologysection', 'Admin\ConsultantController@microbiologysection')->name('microbiologysection');
    // Route::get('/medicalprocedures', 'Admin\ConsultantController@medicalprocedures')->name('medicalprocedures');
    // Route::get('/cardiovascularstudiessection', 'Admin\ConsultantController@cardiovascularstudiessection')->name('cardiovascularstudiessection');
    // Route::get('/pathologysection', 'Admin\ConsultantController@pathologysection')->name('pathologysection');
    // Route::get('/vaccinessection', 'Admin\ConsultantController@vaccinessection')->name('vaccinessection');
    // Route::get('/consentssection', 'Admin\ConsultantController@consentssection')->name('consentssection');
});



Route::group(['prefix' => 'patient', 'as' => 'patient.', 'middleware' => ['auth','role:patient']], function ()
{

    Route::get('/dashboard', 'Patient\PatientController@dashboard')->name('dashboard');
    
    Route::get('/profile', 'Patient\PatientController@profile')->name('profile');
    Route::post('/update_profile', 'Patient\PatientController@updateProfile')->name('update_profile');

    
    Route::get('/appointments', 'Patient\AppointmentController@index')->name('appointment.index');
    Route::get('/appointment/{id}', 'Patient\AppointmentController@show')->name('appointment.show');
    Route::get('/appointment/{id}/timeline', 'Patient\AppointmentController@timeline')->name('appointment.timeline');
    Route::post('/appointment-report/{id}', 'Patient\AppointmentController@report')->name('appointment.report');
    
});



Route::group(['prefix' => 'consultant', 'as' => 'consultant.', 'middleware' => ['auth','role:consultant']], function ()
{

    Route::get('/dashboard', 'Consultant\ConsultantController@dashboard')->name('dashboard');
    
    Route::get('/profile', 'Consultant\ConsultantController@profile')->name('profile');
    Route::post('/update_profile', 'Consultant\ConsultantController@updateProfile')->name('update_profile');

    Route::post('/schedule/update_status/{id}', 'Consultant\ScheduleController@updateStatus')->name('schedule.update_status');
    Route::resource('schedule', 'Consultant\ScheduleController');

    Route::post('/patient/update_status/{id}', 'Consultant\PatientController@updateStatus')->name('patient.update_status');
    Route::resource('patient', 'Consultant\PatientController');
    
    Route::get('/appointments', 'Consultant\AppointmentController@index')->name('appointment.index');
    Route::get('/appointment/{id}', 'Consultant\AppointmentController@show')->name('appointment.show');
    Route::get('/appointment/{id}/timeline', 'Consultant\AppointmentController@timeline')->name('appointment.timeline');
    Route::post('/appointment-meeting/{id}', 'Consultant\AppointmentController@meeting')->name('appointment.meeting');
    Route::post('/appointment-assign/{id}', 'Consultant\AppointmentController@assign')->name('appointment.assign');
    Route::post('/appointment-prescription/{id}', 'Consultant\AppointmentController@prescription')->name('appointment.prescription');
    Route::post('/appointment/update_approval_status/{id}', 'Consultant\AppointmentController@updateApprovalStatus')->name('appointment.update_approval_status');
    Route::post('/appointment/update_payment_status/{id}', 'Consultant\AppointmentController@updatePaymentStatus')->name('appointment.update_payment_status');
    Route::post('/appointment/update_meeting_payment_status/{id}', 'Consultant\AppointmentController@updateMeetingPaymentStatus')->name('appointment.update_meeting_payment_status');
});


// foreach(Page::all() as $page) {
//     Route::get($page->url, 'FrontController@aboutus')->name($page->url);
// }


Route::get('/about-us', 'FrontController@aboutus')->name('about-us');
Route::get('/lab', 'FrontController@lab')->name('lab');
Route::get('/radiology-pacs-system', 'FrontController@radiologysystem')->name('radiology-pacs-system');
Route::get('/microbiology', 'FrontController@microbiology')->name('microbiology');
Route::get('/medical-procedures-surgeries', 'FrontController@medicalproceduressurgeries')->name('medical-procedures-surgeries');
Route::get('/cardiovascular-studies', 'FrontController@cardiovascularstudies')->name('cardiovascular-studies');
Route::get('/pathology', 'FrontController@pathology')->name('pathology');
Route::get('/vaccines', 'FrontController@vaccines')->name('vaccines');
Route::get('/consents', 'FrontController@consents')->name('consents');
Route::get('/meet-expert', 'FrontController@meetexpert')->name('meet-expert');
Route::get('/privacy-policy', 'FrontController@privacypolicy')->name('privacy-policy');
Route::get('/terms-conditions', 'FrontController@termsConditions')->name('terms-conditions');
Route::get('/our-services', 'FrontController@ourservices')->name('our-services');
Route::get('/monthly-tips', 'FrontController@monthlytips')->name('monthly-tips');
Route::get('/video-gallery', 'FrontController@videogallery')->name('video-gallery');
Route::get('/contact-us', 'FrontController@contactus')->name('contact-us');







//admin route

Route::get('/admindashboard', function () { 
    return view('admin.dashboard'); 
});

Route::get('/adminuser', function () {
    return view('admin.adminuser');
});
Route::get('/pricing', function () {
    return view('admin.pricing');
});
Route::get('/adminappointment', function () {
    return view('admin.adminappointment');
});
Route::get('/noofpatients', function () {
    return view('admin.noofpatients');
});
Route::get('/noofslots', function () {
    return view('admin.noofslots');
});
Route::get('/adminquery', function () {
    return view('admin.adminquery');
});
Route::get('/adminprescription', function () {
    return view('admin.adminprescription');
});
Route::get('/adminlabreport', function () {
    return view('admin.adminlabreport');
});
Route::get('/adminallappointment', function () {
    return view('admin.adminallappointment');
});








// patient route

Route::get('/patientdashboard', function () {
    return view('patient.dashboard');
});
Route::get('/appointment', function () {
    return view('patient.appointment');
});
Route::get('/labreport', function () {
    return view('patient.labreport');
});
Route::get('/payment', function () {
    return view('patient.payment');
});
Route::get('/postquery', function () {
    return view('patient.postquery');
});









// consultant route

Route::get('/consultantdashboard', function () {
    return view('consultant.dashboard');
});
Route::get('/patient', function () {
    return view('consultant.patient');
});
Route::get('/prescription', function () {
    return view('consultant.prescription');
});
Route::get('/allreports', function () {
    return view('consultant.allreports');
});
Route::get('/discussionwithpatients', function () {
    return view('consultant.discussionwithpatients');
});
Route::get('/user', function () {
    return view('consultant.user');
});
Route::get('/allappointments', function () {
    return view('consultant.allappointments');
});





