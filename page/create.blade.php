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
                        <h2 class="mb-0">{{ __('Create Blog') }}</h2>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
                            @csrf
                            @include('alerts')
                            <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="">Title*</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                            class="form-control" placeholder="title" required>
                        </div> 
                        <div class="col-md-12 mb-3">
                            <label for="">Image*</label>
                            <input type="file" name="image"  class=" form-control mt-1"  required/>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="">Description*</label>
                            <textarea name="description" id="long_desc" class="form-control" placeholder="Description"
                            >{{ old('description') }}</textarea>
                        </div> 
                        <div class="col-md-12 mb-3">
                            <label for="">Category*</label>
                            <select name="category_id" class="form-control bss_select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ (old('category_id') == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div> 
                        <div class="col-md-12 mb-3">
                            <label for="">Status*</label>
                            <select name="status" class="form-control bss_select" required>
                                <option value="" selected disabled>Select Status</option>
                                <option value="1" {{ (old('status') == 1) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (old('status') == 0) ? 'selected' : '' }}>InActive</option>
                            </select>
                        </div> 
                    </div>
                            <button class="btn bg-primary btn-sm mb-0" type="submit">Submit</button>
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
