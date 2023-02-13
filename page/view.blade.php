@extends('layouts.back.app')

@section('page_title', '| Register')

@section('css')
{{-- Page css files --}}

<style>
    .desc_css {
        background-color: #e9ecef;
    border: 1px solid #e9d9d9;
    padding: 11px;
    border-radius: 5px;
    }
</style>

@endsection

@section('content')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">


            <div class="container-fluid py-4">
                <div class="card">
                    <div class="card-header pb-0 px-3">
                        <h2 class="mb-0">{{ __('View Blog') }}</h2>
                    </div>
                    <div class="card-body pt-4 p-3">
                    <form method="POST" action="{{ route('admin.blog.update', $blog->id) }}" enctype="multipart/form-data">
                    @csrf
                    @include('alerts')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="">Title*</label>
                            <input type="text" name="title" value="{{ old('title', $blog->title) }}"
                            class="form-control" placeholder="title" required disabled>
                        </div> 
                        <div class="col-md-12 mb-3">
                            <label for="">Image*</label><br>
                            <img 
                            src="{{ App\Helpers\Helpers::getImg(config('globals.BLOG_IMAGES_PATH'), $blog->image) }}"
                            id="bss_image_preview"
                            class="img-thumbnail img-fluid rounded"
                            alt="Image"
                            style="width: 150px !important; height: 150px !important;"
                            >
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Description*</label>
                            <div class="desc_css">{!! old('description', $blog->description) !!}</div>
                        </div> 
                        <div class="col-md-12 mb-3">
                            <label for="">Category*</label>
                            <input type="text" name="category_id" value="{{ old('category_id', $blog->category->name) }}"
                            class="form-control" required disabled>
                        </div> 
                        <div class="col-md-12 mb-3">
                            <label for="">Status*</label>
                            <input type="text" name="status" value="{{ old('status', $blog->status) ? 'Active' : 'Inactive'}}"
                            class="form-control"  required disabled>
                        </div> 
                    </div>
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
