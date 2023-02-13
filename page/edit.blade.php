@extends('layouts.back.app')

@section('page_title', '| Register')

@section('css')
{{-- Page css files --}}
<style>
    .give_bord {
        border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0px 0px 0px 15px;
    margin-bottom: 10px;
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
                        <h2 class="mb-0">{{ __('Edit Page') }}</h2>
                    </div>
                    <div class="card-body pt-4 p-3">
                       
                <form method="POST" action="{{ route('admin.page.update', $page->id) }}" enctype="multipart/form-data">
                    @csrf
                    @include('alerts')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="">Title</label>
                            <input type="text" name="title" value="{{ old('title', $page->title) }}"
                            class="form-control" placeholder="title" required disabled>
                        </div> 
                    </div>

                    <div class="prescription_main_parent">
                        <label for="">Page Sections</label>
                        <div class="prescription_parent">
                            
                            @foreach($page->pageSection as $key=> $page_section)
                            <div class="prescription_row p-2 mb-2">
                            <input type="hidden" name="old_description_exist[{{ $page_section->id }}]" value="1">
                                <div class="row give_bord">
                                    
                                    @if($page->url != 'terms-conditions' && $page->url != 'consents' && $page->url != 'privacy-policy' && $page->url != 'monthly-tips')
                                    <div class="col-md-11 mb-4 mt-3 ">
                                    @else
                                    <div class="col-md-12 mb-4 mt-3 ">
                                    @endif
                                        @if($page->url != 'terms-conditions' && $page->url != 'consents' && $page->url != 'privacy-policy' && $page->url != 'monthly-tips')
                                            <label for="" >Image </label><br>
                                            <img 
                                            src="{{ App\Helpers\Helpers::getImg(config('globals.PAGE_IMAGES_PATH'), $page_section->image, 'non_user','' ) }}"
                                            id="bss_image_preview"
                                            class="img-thumbnail img-fluid rounded"
                                            alt="Image"
                                            style="width: 100px !important; height: 100px !important;"
                                            >
                                            <input type="file" name="old_image[{{ $page_section->id }}]" class="bss_image_change form-control mt-1" />
                                            <small>*select new image if u want to raplace, otherwise leave it blank</small>
                                        @endif
                                        @if($page->url != 'video-gallery')
                                        <label for="" class="d-block mt-3">Description*</label>
                                        <textarea name="old_description[{{ $page_section->id }}]"  class="form-control long_desc{{$key}}"
                                        >{{ old('old_description', $page_section->description) }}</textarea>
                                        @else
                                        <label for="" class="d-block mt-3">URL*</label>
                                        <input type="text" name="old_url[{{ $page_section->id }}]" class="form-control" value="{{ old('old_url', $page_section->url) }}" />
                                        @endif
                                    </div>
                                    
                                    @if($page->url != 'terms-conditions' && $page->url != 'consents' && $page->url != 'privacy-policy' && $page->url != 'monthly-tips')
                                    <div class="col-md-1 text-center d-flex align-items-center justify-content-center" style="border-left: 1px solid #e9ecef;">
                                        <a href="javascript:;" class="delete_row">
                                            <i class="fas fa-trash fa-2x text-secondary"></i>
                                        </a>
                                    </div> 
                                    @endif
                                </div>
                            </div>
                            
                            @endforeach
                        </div>
                        @if($page->url != 'terms-conditions' && $page->url != 'consents' && $page->url != 'privacy-policy' && $page->url != 'monthly-tips')
                        <div class="text-right" style="text-align: right;">
                            <a href="javascript:;" class="add_section btn btn-sm btn-success">Add New</a>
                        </div>
                        @endif
                    </div>
                    <button type="submit" style="width: fit-content;" class="btn btn-primary donate" href="#">Save</button>
                </form>
                    </div>
                </div>
            </div>


        </div>
    </main>



    
    <div class="prescription_add_new_row d-none">
        <div class="prescription_row p-2 mb-2">
            <div class="row give_bord">
                <div class="col-md-11 mb-4 mt-3 ">
                    <label for="" >Image </label><br>
                    <img 
                    src="{{ App\Helpers\Helpers::getImg(config('globals.PAGE_IMAGES_PATH'), '') }}"
                    id="bss_image_preview"
                    class="img-thumbnail img-fluid rounded"
                    alt="Image"
                    style="width: 100px !important; height: 100px !important;"
                    >
                    <input type="file" name="new_image[]" class="bss_image_change form-control mt-1" />
                    <small>*select new image if u want to raplace, otherwise leave it blank</small>
                    
                    @if($page->url != 'video-gallery')
                    <label for="" class="d-block mt-3">Description*</label>
                    <textarea name="new_description[]"  class="form-control long_desc"
                    ></textarea>
                    @else
                    <label for="" class="d-block mt-3">URL*</label>
                    <input type="text" name="new_url[]" class="form-control" />
                    @endif
                </div>
                <div class="col-md-1 text-center d-flex align-items-center justify-content-center" style="border-left: 1px solid #e9ecef;">
                    <a href="javascript:;" class="delete_row">
                        <i class="fas fa-trash fa-2x text-secondary"></i>
                    </a>
                </div> 
            </div>
        </div>
    </div>
    @endsection

    @section('js')
    {{-- Page js files --}}


    @for($i=0; $i<count($page->pageSection) ; $i++)
    <script>
        ClassicEditor.create( document.querySelector( '.long_desc{{$i}}' ), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', '|', 'undo', 'redo' ],
        })
        .catch( error => {
            console.error( error );
        });
    </script>
    @endfor

    <script>
        let total_desc = "{{count($page->pageSection)}}";




        function count_prescription_rows() {
            
            if($('.prescription_row').length < 3) {
                $('.delete_row').addClass('d-none');
            } else {
                $('.delete_row').removeClass('d-none');
            }
        }

        count_prescription_rows();
        $(document).on('click', '.add_section', function (e) {
            console.log(total_desc);
            total_desc=total_desc+1;
            console.log(total_desc);
            $('.prescription_add_new_row').find('.long_desc').addClass('long_desc'+total_desc);
            $('.prescription_parent').append($('.prescription_add_new_row').html());


            ClassicEditor
            .create( document.querySelector( '.long_desc'+total_desc ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', '|', 'undo', 'redo' ],
            })
            .catch( error => {
                console.error( error );
            });

            count_prescription_rows();
        });

        $(document).on('click', '.delete_row', function (e) {
            $(this).closest('.prescription_row').remove();
            
            count_prescription_rows();
        });

    </script>
    @endsection
