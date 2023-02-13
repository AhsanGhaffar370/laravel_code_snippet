<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HomeSetting;
use App\Category;
use App\User;
use App\Country;
use App\State;
use App\Image;
use App\Page;
use App\PageSection;
use App\Charity;
use App\CampaignCategory;
use App\CampaignDonation;
use Illuminate\Support\Facades\{Validator, DB, Auth};
use App\Traits\{UploadTrait};


class PageController extends Controller
{
    use UploadTrait;
    public function index()
    {
        $pages= Page::orderBy('created_at', 'desc')->get();
        
        return view('admin.page.index', compact('pages'));
    }

    public function edit($id)
    {
        $page = Page::where('id', $id)->firstOrFail();

        return view('admin.page.edit', compact('page'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'title' => "required",
            // 'image' => "required",
            // 'image' => "required",
            // 'description' => "required",
            // 'status' => "required",
        ]);

        // dd($request);
        if ($validator->fails()) {
            return back()->withInput()->with('errors', $validator->errors());
        } else {
            try{
                DB::beginTransaction();
                $page=Page::with(['pageSection'])->where('id',$id)->first();


                if($page->url != 'video-gallery'){
                    foreach($page->pageSection as $page_section) {
                        if (isset($request->get('old_description')[$page_section->id])) {
                        
                        // Get Path of previous image to delete from local directory
                        $img_path = public_path(config('globals.STORAGE_PATH') . config('globals.PAGE_IMAGES_PATH') . $page_section->image);
                
                        if (isset($request->file('old_image')[$page_section->id])) {
                            // store and set file name in database
                            $fileNameToStore = $this->uploadFile($request->file('old_image')[$page_section->id], config('globals.PAGE_IMAGES_PATH'), '');
                
                            // If page have image then delete from local directory
                            if($page_section->image != null && $page_section->image != '' && file_exists($img_path))
                                unlink($img_path); // Delete image from directory
                        }
                        elseif (!isset($request->file('old_image')[$page_section->id])) {
                            // If page have image then delete from local directory
                            if($page_section->image != null && $page_section->image != '' && file_exists($img_path))
                                unlink($img_path); // Delete image from directory
                
                            $fileNameToStore = $page_section->image;
                        }
                
                        PageSection::find($page_section->id)->update([
                            'page_id' => $page->id, 
                            'url' => $request->get('old_url')[$page_section->id] ?? null, 
                            'description' => $request->get('old_description')[$page_section->id] ?? null, 
                            'image' => $fileNameToStore
                        ]);
                        }
                        elseif (!isset($request->get('old_description')[$page_section->id]) && !isset($request->get('old_description_exist')[$page_section->id])) {
                        // If page have image then delete from local directory
                        if($page_section->image != null && $page_section->image != '' && file_exists($img_path))
                            unlink($img_path); // Delete image from directory
                
                        // Delete image from database
                        PageSection::find($page_section->id)->delete();
                        }
                        
                    }
                    if ($request->has('new_description')) {
                        foreach($request->get('new_description') as $key => $new_description) {
                
                            if (isset($request->file('new_image')[$key])) {
                                // store and set file name in database
                                $img_name = $this->uploadFile($request->file('new_image')[$key], config('globals.PAGE_IMAGES_PATH'), '');
                            }
                
                            PageSection::create([
                                'page_id' => $page->id, 
                                'description' => $request->get('new_description')[$key],  
                                'url' => null, 
                                'image' => $img_name
                            ]);
                        }
                    } 
                }
                // for video gallery
                else {
                    foreach($page->pageSection as $page_section) {
                        if (isset($request->get('old_url')[$page_section->id])) {
                        
                        // Get Path of previous image to delete from local directory
                        $img_path = public_path(config('globals.STORAGE_PATH') . config('globals.PAGE_IMAGES_PATH') . $page_section->image);
                
                        if (isset($request->file('old_image')[$page_section->id])) {
                            // store and set file name in database
                            $fileNameToStore = $this->uploadFile($request->file('old_image')[$page_section->id], config('globals.PAGE_IMAGES_PATH'), '');
                
                            // If page have image then delete from local directory
                            if($page_section->image != null && $page_section->image != '' && file_exists($img_path))
                                unlink($img_path); // Delete image from directory
                        }
                        elseif (!isset($request->file('old_image')[$page_section->id])) {
                            // If page have image then delete from local directory
                            if($page_section->image != null && $page_section->image != '' && file_exists($img_path))
                                unlink($img_path); // Delete image from directory
                
                            $fileNameToStore = $page_section->image;
                        }
                
                        PageSection::find($page_section->id)->update([
                            'page_id' => $page->id, 
                            'url' => $request->get('old_url')[$page_section->id] ?? null, 
                            'description' => null, 
                            'image' => $fileNameToStore
                        ]);
                        }
                        elseif (!isset($request->get('old_url')[$page_section->id]) && !isset($request->get('old_description_exist')[$page_section->id])) {
                        // If page have image then delete from local directory
                        if($page_section->image != null && $page_section->image != '' && file_exists($img_path))
                            unlink($img_path); // Delete image from directory
                
                        // Delete image from database
                        PageSection::find($page_section->id)->delete();
                        }
                        
                    }

                    if ($request->has('new_url')) {
                        foreach($request->get('new_url') as $key => $new_url) {
                
                            if (isset($request->file('new_image')[$key])) {
                                // store and set file name in database
                                $img_name = $this->uploadFile($request->file('new_image')[$key], config('globals.PAGE_IMAGES_PATH'), '');
                            }
                
                            PageSection::create([
                                'page_id' => $page->id, 
                                'description' => null,  
                                'url' => $request->get('new_url')[$key] ?? null, 
                                'image' => $img_name
                            ]);
                        }
                    } 
                }
            
                

                DB::commit();
                return back()->with('success', 'Page Update Successfully!');
                return redirect()->route('admin.page.index')->with('success', 'Page Update Successfully!');
            }
            catch (\Exception $e){
                DB::rollback();
                dd($e);
                return redirect()->route('admin.page.index')->with('error', 'Something went wrong!');
            }
        }
    }

    

    public function editHomeSetting()
    {
        $home_setting = HomeSetting::first();

        return view('admin.home_setting', compact('home_setting'));
    }

    public function updateHomeSetting(Request $request)
    {
        // dd('sdf');
        $validator = Validator::make($request->all(), [
            // 'title' => "required",
            // // 'image' => "required",
            // 'category_id' => "required",
            // 'description' => "required",
            // 'status' => "required",
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('errors', $validator->errors());
        } else {
            try{
                $input = $request->except(['s1_image']);
                $home_setting=HomeSetting::first();
                
                $home_setting_image = $home_setting->s1_image;

                // Get Path of previous image to delete from local directory
                $img_path = public_path(config('globals.PAGE_IMAGES_PATH') . $home_setting_image);

                // store and set file name in database
                if ($request->hasFile('s1_image')) {
                    
                    // If user have image then delete from local directory
                    // if($home_setting_image != null && $home_setting_image != '' && file_exists($img_path)) {
                    //     unlink($img_path); // Delete image from directory
                    // } 

                    $input['s1_image'] = $this->uploadFile($request->file('s1_image'), config('globals.PAGE_IMAGES_PATH'), '');   
                }

                $home_setting->update($input);
                return redirect()->route('admin.home-settings')->with('success', 'Settings Update Successfully!');
            }
            catch (\Exception $e){
                dd($e);
                return redirect()->route('admin.home-settings')->with('error', 'Something went wrong!');
            }
        }
    }

    public function editSocial()
    {
        $home_setting = HomeSetting::first();

        return view('admin.social_details', compact('home_setting'));
    }
    
    public function updateSocial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'title' => "required",
            // // 'image' => "required",
            // 'category_id' => "required",
            // 'description' => "required",
            // 'status' => "required",
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('errors', $validator->errors());
        } else {
            try{
                $input = $request->all();
                $home_setting=HomeSetting::first();
                
                $home_setting->update($input);
                return redirect()->route('admin.social-details')->with('success', 'Settings Update Successfully!');
            }
            catch (\Exception $e){
                // dd($e);
                return redirect()->route('admin.social-details')->with('error', 'Something went wrong!');
            }
        }
    }

    public function editContact()
    {
        $home_setting = HomeSetting::first();

        return view('admin.contact_details', compact('home_setting'));
    }
    
    public function updateContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'title' => "required",
            // // 'image' => "required",
            // 'category_id' => "required",
            // 'description' => "required",
            // 'status' => "required",
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('errors', $validator->errors());
        } else {
            try{
                $input = $request->all();
                $home_setting=HomeSetting::first();
                
                $home_setting->update($input);
                return redirect()->route('admin.contact-details')->with('success', 'Settings Update Successfully!');
            }
            catch (\Exception $e){
                return redirect()->route('admin.contact-details')->with('error', 'Something went wrong!');
            }
        }
    }
}