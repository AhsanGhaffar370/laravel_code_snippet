<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,MechanicDetail,UserDetail,UserPostRequest,Image, Country, State,
  Notification, Timezone, ServiceType, CarDetail};
use Illuminate\Support\Facades\{DB, Hash, URL, Storage, Validator, Auth};
use App\Traits\{UploadTrait,CompanySettingsTrait,NotificationTrait};

class AuthController extends Controller
{
  use UploadTrait,CompanySettingsTrait,NotificationTrait;

  public function adminLogin() {
    if (Auth::check())
      return redirect()->route('home');
      
    return view('back.login');
  }
  public function login() {
    if (Auth::check())
      return redirect()->route('home');
      
    return view('front.login');
  }

  public function loginCheck(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'email' => "required",
        'password' => "required",
    ]);
    if ($validator->fails()) {
        return back()->withInput()->withErrors($validator->errors());
    }

    try{
        $credentials = ['email' => $request->email, 'password' => $request->password, 'approval_status_id' => 1];

        if(!Auth::attempt($credentials))
        {
          return back()->withInput()->withErrors(['Email or password is incorrect']);
        }
        
        if(Auth::user()->hasRole('admin')){
          return redirect()->route('admin.dashboard');
        }
        if(Auth::user()->hasRole('mechanic')){
            return redirect()->route('mechanic.dashboard');
        }
        else if(Auth::user()->hasRole('user')){
            return redirect()->route('user.dashboard');
        }
        else {
          return redirect()->route('home');
        }
    }
    catch (\Exception $e){
        return redirect()->back()->withError('Something went wrong!');
    }
  }


  public function userRegister() {
    if (Auth::check())
      return redirect()->route('home');

    $countries = Country::all();
    // $states = State::all();
    $timezones = Timezone::all();
    $makes = CarDetail::select('make')->groupBy('make')->get();
    $mechanic_service_types = ServiceType::where('for_mechanic', 1)->get();
    $user_service_types = ServiceType::all();

    return view('front.user_register', compact('countries', 'timezones', 'mechanic_service_types', 'user_service_types', 'makes'));
  }

  public function storeUserRegister(Request $request)
  {  
    $rules = ([
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'firstname' => 'required',
        'lastname' => 'required',
        'country_id' => 'required',
        'state_id' => 'required',
        'city' => 'required',
        'zipcode' => 'required',
        'address' => 'required',
        'timezone_id' => 'required',
        'service_type_id' => 'required',
        'make' => 'required',
        'model' => 'required',
        'year' => 'required',
        // 'vin_no' => 'required',
        // 'miles' => 'required',
        // 'color' => 'required',
    ]);
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return back()->withInput()->withErrors($validator->errors());
    } 

    try {
      DB::beginTransaction();
      
      $data = $request->all();
      $user = User::create([
        'name' => $data['firstname'] . ' ' . $data['lastname'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'firstname' => $data['firstname'],
        'lastname' => $data['lastname'],
        'country_id' => $data['country_id'],
        'state_id' => $data['state_id'],
        'city' => $data['city'],
        'zipcode' => $data['zipcode'],
        'address' => $data['address'],
        'timezone_id' => $data['timezone_id'],
        'service_type_id' => $data['service_type_id'],
        'status' => '1'
      ]);

      UserDetail::create([
        'user_id' => $user->id,
        'make' => $data['make'],
        'model' => $data['model'],
        'year' => $data['year'],
        'vin_no' => $data['vin_no'] ?? null,
        'miles' => $data['miles'] ?? null,
        'color' => $data['color'] ?? null
      ]);
      
      $userRole = config('roles.models.role')::where('name', '=', "User")->first();
      $user->attachRole($userRole);

      DB::commit();
      
      // notify user
      $request_detail = [
        'greeting' => 'Hi '.$user->name . ',',
        'from_email' => env("MAIL_FROM_ADDRESS", "info@carma.com"),
        'from_name' => 'Admin',
        'reply_to' => 'For any query, please reply to this email: ' . env("SUPPORT_MAIL_ADDRESS", "support@carma.com"),
        'subject' => 'Registration Success',
        'message' => "Your registration is completed successfully.",
      ];
      $user->notify(new \App\Notifications\UserRegistrationNotification( $request_detail));

      //notify admin
      $user  = User::whereHas('roles', function($q){
        $q->where('name', 'Admin');
      })->first();
      $request_detail = [
        'greeting' => 'Hi '.$user->name . ',',
        'from_email' => env("MAIL_FROM_ADDRESS", "info@carma.com"),
        'from_name' => env("MAIL_FROM_ADDRESS", "info@carma.com"),
        'reply_to' => '',
        'subject' => 'New Registration',
        'message' => "New User Registered",
      ];
      $user->notify(new \App\Notifications\UserRegistrationNotification( $request_detail));
      
      // send notification
      $this->notify_user($user->id, 'New User Registered.');
  
      return redirect("login")->withSuccess('Great! You have Successfully registered');
    } 
    catch (\Exception $e) {
        DB::rollback();
        dd($e);
        return back()->withError('Error! Something went wrong');
    }
  }
    
  public function storeMechanicRegister(Request $request)
  {  
    $message = [
      'ase_certified.required_if' => 'Please provide your ASE Certificate document.'
    ];
    $rules = ([
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'firstname' => 'required',
        'lastname' => 'required',
        'country_id' => 'required',
        'state_id' => 'required',
        'city' => 'required',
        'zipcode' => 'required',
        'address' => 'required',
        'timezone_id' => 'required',
        'service_type_id' => 'required',
        // 'company' => 'required',
        'ase_certified' => 'required_if:service_type_id,==,1',
        'cra_certified' => 'required_if:service_type_id,==,2',
        // 'ase_doc' => 'required_if:ase_certified,==,1',
    ]);
    $validator = Validator::make($request->all(), $rules,$message);
    if ($validator->fails()) {
      return back()->withInput()->withErrors($validator->errors());
    } 
    
    try {
      DB::beginTransaction();

      $data = $request->all();
      $user = User::create([
        'name' => $data['firstname'] . ' ' . $data['lastname'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'firstname' => $data['firstname'],
        'lastname' => $data['lastname'],
        'country_id' => $data['country_id'],
        'state_id' => $data['state_id'],
        'city' => $data['city'],
        'zipcode' => $data['zipcode'],
        'address' => $data['address'],
        'company' => $data['company'] ?? null,
        'timezone_id' => $data['timezone_id'],
        'service_type_id' => $data['service_type_id'],
        'status' => '-1'
      ]);

      $mechanic_detail = new MechanicDetail();
      $mechanic_detail->mechanic_id = $user->id;

      if($user->service_type_id == 1) { // ase
        $mechanic_detail->ase_certified = '0';
        $mechanic_detail->cra_certified = '0';
        $mechanic_detail->cra_doc = null;

        // Handle file Upload
        if($data['ase_certified'] == '1'){
          // store and set file name in database
          $mechanic_detail->ase_doc = $this->uploadFile($request->file('ase_doc'), config('globals.ASE_CRA_DOC_IMAGES_PATH'));
        }
      }
      elseif($user->service_type_id == 2) { // cra
        $mechanic_detail->cra_certified = '0';
        $mechanic_detail->ase_certified = '0';
        $mechanic_detail->ase_doc = null;

        // Handle file Upload
        if($data['cra_certified'] == '1'){
          // store and set file name in databcra
          $mechanic_detail->cra_doc = $this->uploadFile($request->file('cra_doc'), config('globals.ASE_CRA_DOC_IMAGES_PATH'));
        }
      }

      $mechanic_detail->save();
      
      $userRole = config('roles.models.role')::where('name', '=', "Mechanic")->first();
      $user->attachRole($userRole);
      
      DB::commit();

      // notify mechanic
      $request_detail = [
        'greeting' => 'Hi '.$user->name . ',',
        'from_email' => env("MAIL_FROM_ADDRESS", "info@carma.com"),
        'from_name' => 'Admin',
        'reply_to' => 'For any query, please reply to this email: ' . env("SUPPORT_MAIL_ADDRESS", "support@carma.com"),
        'subject' => 'Registration In Progress',
        'message' => "Your registration is under review. We'll update you shortly after reviewing your registration form.",
      ];
      $user->notify(new \App\Notifications\MechanicRegistrationNotification( $request_detail));
      
      
      //notify admin
      $user  = User::whereHas('roles', function($q){
        $q->where('name', 'Admin');
      })->first();
      $request_detail = [
        'greeting' => 'Hi '.$user->name . ',',
        'from_email' => env("MAIL_FROM_ADDRESS", "info@carma.com"),
        'from_name' => env("MAIL_FROM_ADDRESS", "info@carma.com"),
        'reply_to' => '',
        'subject' => 'New Registration',
        'message' => "New User Registered",
      ];
      $user->notify(new \App\Notifications\UserRegistrationNotification( $request_detail));
      
      // send notification
      $this->notify_user($user->id, 'New Mechanic Registered.');

      session()->flash('mechanic_registered', 'success');

      return redirect()->route("thanku");
    } 
    catch (\Exception $e) {
      DB::rollback();
      dd($e);
      return back()->withError('Error! Something went wrong');
    }
  }
    
  public function userDashboard() {
    $user_id = auth()->id();
    $is_dashboard = true;
    $countries = Country::all();
    $states = State::all();
    $timezones = Timezone::all();
    $service_types = ServiceType::all();
    $user_details = User::with(['userDetail'])->find(Auth::id());

    $makes = CarDetail::select('make')->groupBy('make')->get();
    $models = CarDetail::where('make', $user_details->userDetail->make)->groupBy('model')->get();
    $years = CarDetail::where('model', $user_details->userDetail->model)->groupBy('year')->orderBy('year', 'Desc')->get();

      
    $completed_projects = UserPostRequest::with(['userPostReview'])
                                    ->where('user_post_requests.approval_status_id', 1)
                                    ->Join('user_posts', function($query) use($user_id) {
                                      $query->on('user_posts.id', '=', 'user_post_requests.user_post_id')
                                      ->where('user_posts.user_id', $user_id)
                                      ->where('user_posts.post_status_id', 2);
                                    })->count();
    $inprogress_projects = UserPostRequest::with(['userPostReview'])
                                    ->where('user_post_requests.approval_status_id', 1)
                                    ->Join('user_posts', function($query) use($user_id) {
                                      $query->on('user_posts.id', '=', 'user_post_requests.user_post_id')
                                      ->where('user_posts.user_id', $user_id)
                                      ->where('user_posts.post_status_id', 4);
                                    })->count();

      return view('front.user.account_details', compact('user_details',
                                                       'completed_projects',
                                                       'inprogress_projects',
                                                       'countries',
                                                       'states',
                                                       'timezones',
                                                       'service_types',
                                                       'makes',
                                                       'models',
                                                       'years',
                                                       'is_dashboard'));
    }
    
  public function accountDetails() {
    $is_dashboard = false;
    $countries = Country::all();
    $states = State::all();
    $timezones = Timezone::all();
    $service_types = ServiceType::all();
    $user_details = User::with(['userDetail'])->find(Auth::id());

    $makes = CarDetail::select('make')->groupBy('make')->get();
    $models = CarDetail::where('make', $user_details->userDetail->make)->groupBy('model')->get();
    $years = CarDetail::where('model', $user_details->userDetail->model)->groupBy('year')->orderBy('year', 'Desc')->get();

    return view('front.user.account_details', compact('user_details',
                                                      'countries',
                                                      'states',
                                                      'timezones',
                                                      'service_types',
                                                      'makes',
                                                      'models',
                                                      'years',
                                                      'is_dashboard'));
  }
  
  public function updateAccountDetails(Request $request)
  {  
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'firstname' => 'required',
        'lastname' => 'required',
        'country_id' => 'required',
        'state_id' => 'required',
        'city' => 'required',
        'zipcode' => 'required',
        'address' => 'required',
        'timezone_id' => 'required',
        'service_type_id' => 'required',
        'make' => 'required',
        'model' => 'required',
        'year' => 'required',
        // 'vin_no' => 'required',
        // 'miles' => 'required',
        // 'color' => 'required',
    ]);
    if ($validator->fails()) {
      return back()->withInput()->withErrors($validator->errors());
    }

    try {
      DB::beginTransaction();
      
      $input = $request->only(['name','firstname','lastname','country_id','state_id','city',
      'zipcode','address','timezone_id','service_type_id']);
      $input['chat_status'] = $request->has('chat_status') ? 1 : 0;
      $user = User::find(Auth::id());

      // Get Path of previous image to delete from local directory
      $profile_pic_path = public_path(config('globals.STORAGE_PATH') . config('globals.USER_IMAGES_PATH') . $user->profile_pic);

      // Check if a file exist
      if ($request->hasFile('profile_pic')) {
        // store and set file name in database
        $input['profile_pic'] = $this->uploadFile($request->file('profile_pic'), config('globals.USER_IMAGES_PATH'));
        
        // If user have image then delete from local directory
        if($user->profile_pic != null && $user->profile_pic != '' && file_exists($profile_pic_path))
            unlink($profile_pic_path); // Delete image from directory
      }
      $user->update($input);

      $input = $request->only(['make','model','year','vin_no','miles','color']);
      $user_detail = UserDetail::where('user_id', Auth::id())->first();
      $user_detail->update($input);

      DB::commit();
  
      return redirect()->route("user.dashboard")->withSuccess('Account updated successfully');
    } 
    catch (\Exception $e) {
        DB::rollback();
        return back()->withError('Error! Something went wrong');
    }
  }

  public function editPassword() {
    return view('front.user.edit_password');
  }

  public function updatePassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'old_password' => 'required',
      'new_password' => 'required',
      'confirm_password' => 'required',
    ]);
    if ($validator->fails()) {
      return back()->withInput()->withErrors($validator->errors());
    }

    try {
      DB::beginTransaction();

      if ($request->get('new_password')  == $request->get('confirm_password')) {
        $user = User::find(Auth::id());
        if (Hash::check($request->get('old_password'), $user->password)) {
            $user->password = Hash::make($request->get('new_password'));
            $user->update();
        } else {
          return back()->withInput()->withErrors(['Password mismatch']);
        }
      } else {
        return back()->withInput()->withErrors(['Confirm Password not matched with new password']);
      }

      DB::commit();
  
      return redirect()->route("user.edit_password")->withSuccess('Password reset successfully');
    } 
    catch (\Exception $e) {
        DB::rollback();
        return back()->withError('Error! Something went wrong');
    }  
  }

  
  public function mechanicDashboard() {
    $is_dashboard = true;
    $countries = Country::all();
    $states = State::all();
    $timezones = Timezone::all();
    $service_types = ServiceType::where('for_mechanic', 1)->get();
    $user_details = User::with(['mechanicPortfolioImages'])->find(Auth::id());

    $completed_projects = UserPostRequest::with(['userPostReview'])
                                    ->where('user_post_requests.mechanic_id', Auth::id())
                                    ->where('user_post_requests.approval_status_id', 1)
                                    ->Join('user_posts', function($query) {
                                      $query->on('user_posts.id', '=', 'user_post_requests.user_post_id')
                                      ->where('user_posts.post_status_id', 2);
                                    })->count();
    $inprogress_projects = UserPostRequest::with(['userPostReview'])
                                    ->where('user_post_requests.mechanic_id', Auth::id())
                                    ->where('user_post_requests.approval_status_id', 1)
                                    ->Join('user_posts', function($query) {
                                      $query->on('user_posts.id', '=', 'user_post_requests.user_post_id')
                                      ->where('user_posts.post_status_id', 4);
                                    })->count();
    
    return view('front.mechanic.account_details', compact('user_details', 
                                                          'completed_projects', 
                                                          'inprogress_projects', 
                                                          'countries', 
                                                          'states', 
                                                          'timezones', 
                                                          'service_types', 
                                                          'is_dashboard'));
  }

  public function mechanicAccountDetails() {
    $is_dashboard = false;
    $countries = Country::all();
    $states = State::all();
    $timezones = Timezone::all();
    $service_types = ServiceType::where('for_mechanic', 1)->get();
    $user_details = User::with(['mechanicPortfolioImages'])->find(Auth::id());

    return view('front.mechanic.account_details', compact('user_details', 'countries', 'states', 'timezones', 'service_types', 'is_dashboard'));
  }
  
  public function updateMechanicAccountDetails(Request $request)
  {  
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'firstname' => 'required',
        'lastname' => 'required',
        'country_id' => 'required',
        'state_id' => 'required',
        'city' => 'required',
        'zipcode' => 'required',
        'address' => 'required',
        'timezone_id' => 'required',
        'service_type_id' => 'required',
        // 'short_bio' => 'required',
        // 'portfolio_video' => "sometimes|mimes:mp4",
        // 'img' => "required",//|mimes:jpeg,jpg,png|max:10192",
        // 'img.*' => "required",//|mimes:jpeg,jpg,png|max:10192",
    ]);
    if ($validator->fails()) {
      return back()->withInput()->withErrors($validator->errors());
    }

    try {
      DB::beginTransaction();
      
      $input = $request->only(['name','firstname','lastname','country_id','state_id','city',
      'company','zipcode','address','timezone_id','service_type_id']);
      $input['chat_status'] = $request->has('chat_status') ? 1 : 0;
      $user = User::find(Auth::id());

      // Get Path of previous image to delete from local directory
      $profile_pic_path = public_path(config('globals.STORAGE_PATH') . config('globals.USER_IMAGES_PATH') . $user->profile_pic);

      // Check if a file exist
      if ($request->hasFile('profile_pic')) {
        // store and set file name in database
        $input['profile_pic'] = $this->uploadFile($request->file('profile_pic'), config('globals.USER_IMAGES_PATH'));
        
        // If user have image then delete from local directory
        if($user->profile_pic != null && $user->profile_pic != '' && file_exists($profile_pic_path))
            unlink($profile_pic_path); // Delete image from directory
      }

      $user->update($input);
      
      $user = User::with(['mechanicDetail', 'mechanicPortfolioImages'])->find(Auth::id());

      if ($user->hasStripeId()) {
        $user->syncStripeCustomerDetails();
      }

      $input = $request->only(['short_bio']);

      // Get Path of previous image to delete from local directory
      $portfolio_video_path = public_path(config('globals.STORAGE_PATH') . config('globals.MECHANIC_PORTFOLIO_IMAGES_PATH') . $user->mechanicDetail->portfolio_video);
      
      // Check if a file exist
      if ($request->hasFile('portfolio_video')) {
        // store and set file name in database
        $input['portfolio_video'] = $this->uploadFile($request->file('portfolio_video'), config('globals.MECHANIC_PORTFOLIO_IMAGES_PATH'));
        
        // If user have image then delete from local directory
        if($user->mechanicDetail->portfolio_video != null && $user->mechanicDetail->portfolio_video != '' && file_exists($portfolio_video_path))
            unlink($portfolio_video_path); // Delete image from directory
      }
      $mechanic_detail = MechanicDetail::where('mechanic_id', Auth::id())->first();
      $mechanic_detail->update($input);


      foreach($user->mechanicPortfolioImages as $user_image) {
        // Get Path of previous image to delete from local directory
        $img_path = public_path(config('globals.STORAGE_PATH') . config('globals.MECHANIC_PORTFOLIO_IMAGES_PATH') . $user_image->name);
  
        if (isset($request->file('old_img')[$user_image->id])) {
          // store and set file name in database
          $fileNameToStore = $this->uploadFile($request->file('old_img')[$user_image->id], config('globals.MECHANIC_PORTFOLIO_IMAGES_PATH'));

          // If user have image then delete from local directory
          if($user_image->name != null && $user_image->name != '' && file_exists($img_path))
              unlink($img_path); // Delete image from directory

          // Set name in database
          Image::find($user_image->id)->update([
            'model' => 'App\Models\User',
            'name' => $fileNameToStore,
          ]);
        }
        elseif (!isset($request->file('old_img')[$user_image->id]) && !isset($request->get('old_img_exist')[$user_image->id])) {
          // If user have image then delete from local directory
          if($user_image->name != null && $user_image->name != '' && file_exists($img_path))
              unlink($img_path); // Delete image from directory

          // Delete image from database
          Image::find($user_image->id)->delete();
        }
      }

      if ($request->hasFile('new_img')) {
        foreach($request->file('new_img') as $key => $image) {
          // store and set file name in database
          $img_name = $this->uploadFile($image, config('globals.MECHANIC_PORTFOLIO_IMAGES_PATH'));

          Image::create([
            'f_id' => $user->id,
            'model' => 'App\Models\User',
            'name' => $img_name,
          ]);
        }
      }



      DB::commit();
  
      return redirect()->route("mechanic.dashboard")->withSuccess('Account updated successfully');
    } 
    catch (\Exception $e) {
        DB::rollback();
        dd($e);
        return back()->withError('Error! Something went wrong');
    }
  }

  public function editMechanicPassword() {
    return view('front.mechanic.edit_password');
  }

  public function updateMechanicPassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'old_password' => 'required',
      'new_password' => 'required',
      'confirm_password' => 'required',
    ]);
    if ($validator->fails()) {
      return back()->withInput()->withErrors($validator->errors());
    }

    try {
      DB::beginTransaction();

      if ($request->get('new_password')  == $request->get('confirm_password')) {
        $user = User::find(Auth::id());
        if (Hash::check($request->get('old_password'), $user->password)) {
            $user->password = Hash::make($request->get('new_password'));
            $user->update();
        } else {
          return back()->withInput()->withErrors(['Password mismatch']);
        }
      } else {
        return back()->withInput()->withErrors(['Confirm Password not matched with new password']);
      }

      DB::commit();
  
      return redirect()->route("mechanic.edit_password")->withSuccess('Password reset successfully');
    } 
    catch (\Exception $e) {
        DB::rollback();
        return back()->withError('Error! Something went wrong');
    }  
  }


  public function adminAccountDetails() {
    $countries = Country::all();
    $states = State::all();
    $timezones = Timezone::all();
    $user_details = User::find(Auth::id());

    return view('back.account_details', compact('user_details', 'countries', 'states', 'timezones'));
  }
  
  public function updateAdminAccountDetails(Request $request)
  {  
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      // 'firstname' => 'required',
      //   'lastname' => 'required',
      //   'country_id' => 'required',
      //   'state_id' => 'required',
      //   'city' => 'required',
      //   'zipcode' => 'required',
      //   'address' => 'required',
      //   'timezone_id' => 'required',
    ]);
    if ($validator->fails()) {
      return back()->withInput()->withErrors($validator->errors());
    }

    try {
      DB::beginTransaction();
      
      $input = $request->only(['name','firstname','lastname','country_id','state_id','city','zipcode','address','timezone_id']);
      $user = User::find(Auth::id());

      // Get Path of previous image to delete from local directory
      $profile_pic_path = public_path(config('globals.STORAGE_PATH') . config('globals.USER_IMAGES_PATH') . $user->profile_pic);

      // Check if a file exist
      if ($request->hasFile('profile_pic')) {
        // store and set file name in database
        $input['profile_pic'] = $this->uploadFile($request->file('profile_pic'), config('globals.USER_IMAGES_PATH'));
        
        // If user have image then delete from local directory
        if($user->profile_pic != null && $user->profile_pic != '' && file_exists($profile_pic_path))
            unlink($profile_pic_path); // Delete image from directory
      }
      $user->update($input);

      DB::commit();
  
      return redirect()->route("admin.account_details")->withSuccess('Account updated successfully');
    } 
    catch (\Exception $e) {
        DB::rollback();
        return back()->withError('Error! Something went wrong');
    }
  }
}
