@extends('layouts.back.app')

@section('page_title', '| Register')

@section('css')
{{-- Page css files --}}

@endsection

@section('content')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">


            <div class="container-fluid py-4">
                <div class="card">
                    <div class="card-header pb-0 px-3">
                        <h2 class="mb-0">{{ __('Edit Blog') }}</h2>
                    </div>
                    <div class="card-body pt-4 p-3">
                       
                <form method="POST" action="{{ route('admin.blog.update', $blog->id) }}" enctype="multipart/form-data">
                    @csrf
                    @include('alerts')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="">Title*</label>
                            <input type="text" name="title" value="{{ old('title', $blog->title) }}"
                            class="form-control" placeholder="title" required>
                        </div> 
                        <div class="col-md-12 mb-3">
                            <label for="">Image*</label><br>
                            <img 
                            src="{{ App\Helpers\Helpers::getImg(config('globals.BLOG_IMAGES_PATH'), $blog->image) }}"
                            {{-- src="{{ asset('/storage/blog_images21/'. $blog->image) }}" --}}
                            {{-- src="{{ asset('/blog_images21/'. $blog->image) }}" --}}
                            id="bss_image_preview"
                            class="img-thumbnail img-fluid rounded"
                            alt="Image"
                            style="width: 150px !important; height: 150px !important;"
                            >
                            <input type="file" name="image" class="bss_image_change form-control mt-1" />
                            <small>*select new image if u want to raplace, otherwise leave it blank</small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Description*</label>
                            <textarea name="description" id="long_desc" class="form-control" placeholder="Description"
                            >{{ old('description', $blog->description) }}</textarea>
                        </div> 
                        <div class="col-md-12 mb-3">
                            <label for="">Category*</label>
                            <select name="category_id" class="form-control bss_select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ (old('category_id', $blog->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div> 
                        <div class="col-md-12 mb-3">
                            <label for="">Status*</label>
                            <select name="status" class="form-control bss_select" required>
                                <option value="" selected disabled>Select Status</option>
                                <option value="1" {{ (old('status', $blog->status) == 1) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (old('status', $blog->status) == 0) ? 'selected' : '' }}>InActive</option>
                            </select>
                        </div> 
                    </div>
                    <button type="submit" style="width: fit-content;" class="btn btn-primary donate" href="#">Save</button>
                </form>
                    </div>
                </div>
            </div>


        </div>
    </main>
    @endsection

    @section('js')
    {{-- Page js files --}}

    @endsection
