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
                    <h1 class="mt-4"> All Pages</h1>
                </div>


            </div>

            <div class="row">
                <table id="datatablesSimple" class="datatable-table">
                    <thead>
                        <tr>
                        <th scope="col">#ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Action</th>

                        </tr>
                    </thead>

                    <tbody>
                    @forelse ($pages as $page)
                        <tr>
                            <td class="ps-4">
                                <p class="text-xs font-weight-bold mb-0">{{ $page->id }}</p>
                            </td>
                            <td class="text-center">
                                <p class="text-xs font-weight-bold mb-0">{{ $page->title }}</p>
                            </td>
                            <td class="text-center">
                                <span
                                    class="text-secondary text-xs font-weight-bold">{{ \Carbon\Carbon::parse($page->created_at)->format('d M, Y') }}</span>
                            </td>
                            <td>
                                <div class="dropdown remove">
                                    <button type="button" class="dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route($page->url) }}"
                                                target="_blank"
                                                class="dropdown-item mx-1" data-bs-toggle="tooltip" data-bs-original-title="View page">
                                                View
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.page.edit', $page->id) }}"
                                                class="dropdown-item mx-1" data-bs-toggle="tooltip" data-bs-original-title="Edit page">
                                                Edit
                                            </a>
                                        </li>
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
        
    </script>
    @endsection
