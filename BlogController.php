<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\User;
use App\Country;
use App\State;
use App\Image;
use App\Blog;
use App\Charity;
use App\CampaignCategory;
use App\CampaignDonation;
use Illuminate\Support\Facades\{Validator, DB, Auth};
use App\Traits\{UploadTrait};


class BlogController extends Controller
{
    use UploadTrait;
    public function index()
    {
        $blogs= Blog::orderBy('created_at', 'desc')->get();
        
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->get();

        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => "required",
            'image' => "required",
            'category_id' => "required",
            'description' => "required",
            'status' => "required",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('errors', $validator->errors());
        }

        try{
            $blog=new Blog();
            $blog->title=$request->title;
            $blog->description=$request->description;
            $blog->category_id=$request->category_id;
            $blog->status=$request->status;
            // store and set file name in database
            if ($request->hasFile('image')) {
                // $image = $request->image;              
                // $imageName = time() . rand(1,200) . '.' . $image->getClientOriginalExtension(); 
                       
                // 1) Store in Storage folder -- (Path => storage/app/images/file.png)
                // $image->storeAs('public/blog_images21', $imageName);
               
                // 2) Store in Public Folder -- (Path => public/images/file.png)
                // $image->move(public_path('blog_images21'), $imageName);
                
                // 3) Store in AMAZON S3 -- (Path => public/images/file.png)
                // $image->storeAs('images', $imageName, 's3');

                // $blog->image = $imageName;
                $blog->image = $this->uploadFile($request->file('image'), config('globals.BLOG_IMAGES_PATH'));
            }

            $blog->save();

            return redirect()->route('admin.blog.index')->with('success', 'Blog Created Successfully!');
        }
        catch (\Exception $e){
            // dd($e);
            return redirect()->route('admin.blog.index')->with('error', 'Something went wrong!');
        }
    }

    public function view($id)
    {
        $blog = Blog::where('id', $id)->firstOrFail();

        return view('admin.blog.view', compact('blog'));
    }

    public function edit($id)
    {
        $blog = Blog::where('id', $id)->firstOrFail();
        $categories = Category::where('status', 1)->get();

        return view('admin.blog.edit', compact('blog', 'categories'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => "required",
            // 'image' => "required",
            'category_id' => "required",
            'description' => "required",
            'status' => "required",
        ]);

        if ($validator->fails()) {
            return back()->withInput()->with('errors', $validator->errors());
        } else {
            try{
                $blog=Blog::find($id);
                $blog->title=$request->title;
                $blog->description=$request->description;
                $blog->category_id=$request->category_id;
                $blog->status=$request->status;
                
                $blog_image = $blog->image;

                // Get Path of previous image to delete from local directory
                $img_path = public_path(config('globals.STORAGE_PATH') . config('globals.BLOG_IMAGES_PATH') . $blog_image);

                // store and set file name in database
                if ($request->hasFile('image')) {
                    
                    // If user have image then delete from local directory
                    if($blog_image != null && $blog_image != '' && file_exists($img_path)) {
                        unlink($img_path); // Delete image from directory
                    } 

                    $blog->image = $this->uploadFile($request->file('image'), config('globals.BLOG_IMAGES_PATH'));   
                }

                $blog->save();
                return redirect()->route('admin.blog.index')->with('success', 'Blog Update Successfully!');
            }
            catch (\Exception $e){
                // dd($e);
                return redirect()->route('admin.blog.index')->with('error', 'Something went wrong!');
            }
        }
    }

    public function statusUpdate($id)
    {
        try{
            $blog=Blog::find($id);
            $blog->status = !$blog->status;
            $blog->save();
            $msg = $blog->status ? 'Activated' : 'Deactivated';
            return response()->json(['code' => '200', 'message'=> 'Blog '. $msg .' successfully!']);
        }
        catch (\Exception $e){
            return response()->json(['code' => '500','message'=> 'Something went wrong!']);
            // return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function destroy($id)
    {
        try{
            $blog=Blog::findOrFail($id);

            $blog->delete();

            return redirect()->route('admin.blog.index')->with('success', 'Blog deleted Successfully!');
        }
        catch (\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
}
