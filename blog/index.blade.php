@extends('layouts.back.app')

@section('title', 'Home')

<!-- CSS -->
@section('css')


@endsection


@section('content')




<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            @include('alerts')

            <div class="d-flex justify-content-between">
                <div>
                    <h1 class="mt-4"> All Blogs</h1>
                    <a href="{{ route('admin.blog.create') }}"
                        class="btn bg-primary btn-sm mb-0" type="button">+&nbsp; New Blog</a>
                </div>


            </div>

            <div class="row">
                <table id="datatablesSimple" class="datatable-table">
                    <thead>
                        <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Image</th>
                        <!-- <th scope="col">Category</th> -->
                        <th scope="col">Status</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Action</th>

                        </tr>
                    </thead>

                    <tbody>
                    @forelse ($blogs as $blog)
                        <tr>
                            <td class="ps-4">
                                <p class="text-xs font-weight-bold mb-0">{{ $blog->id }}</p>
                            </td>
                            <td class="text-center">
                                <p class="text-xs font-weight-bold mb-0">{{ $blog->title }}</p>
                            </td>
                            <td class="text-center">
                                <img 
                                    src="{{ App\Helpers\Helpers::getImg(config('globals.BLOG_IMAGES_PATH'), $blog->image) }}"
                                    {{-- src="{{ asset('/storage/blog_images21/'. $blog->image) }}" --}}
                                    {{-- src="{{ asset('/blog_images21/'. $blog->image) }}" --}}
                                    class="img-thumbnail img-fluid rounded-circle"
                                    style="width: 100px !important; height: 100px !important;"
                                    >
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch " style="display:flex; justify-content:center;">
                                    <input class="form-check-input change_status" type="checkbox" id="mySwitch"
                                        name="darkmode" value="yes" data-id="{{ $blog->id }}"
                                        data-href="{{ route('admin.blog.status.update', $blog->id) }}"
                                        {{ ($blog->status) ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="text-center">
                                <span
                                    class="text-secondary text-xs font-weight-bold">{{ \Carbon\Carbon::parse($blog->created_at)->format('d M, Y') }}</span>
                            </td>
                            <td>
                                <div class="dropdown remove">
                                    <button type="button" class="dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route('admin.blog.view', $blog->id) }}"
                                                class="dropdown-item mx-1" data-bs-toggle="tooltip" data-bs-original-title="View blog">
                                                View
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.blog.edit', $blog->id) }}"
                                                class="dropdown-item mx-1" data-bs-toggle="tooltip" data-bs-original-title="Edit blog">
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.blog.destroy', $blog->id) }}"
                                                class="dropdown-item mx-1" data-bs-toggle="tooltip" data-bs-original-title="Delete blog"
                                                onclick="event.preventDefault(); document.getElementById('delete-blog').submit();">
                                                Delete
                                            </a>
                                        </li>
                                        <form id="delete-blog"
                                            action="{{ route('admin.blog.destroy', $blog->id) }}"
                                            method="POST" class="d-none">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No Records Found</td>
                        </tr>
                        @endforelse



                    </tbody>
                </table>
            </div>

        </div>
    </main>


    @endsection

    @section('js')
    {{-- Page js files --}}

    <script>
        $(document).on('change', '.change_status', function (e) {
            // e.preventDefault();
            var href = $(this).attr('data-href');
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: href,
                data: {
                    id: id,
                    _token: $("meta[name='csrf-token']").attr("content"),
                },
                success: function (data) {
                    // console.log(data);
                    if (data.code == '200')
                        successtoast(data.message);
                    else {
                        errortoast(data.message);
                    }
                }
            });
        });

    </script>
    @endsection
