@extends('Layout.app')

@section('body')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('Layout.menu')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- users list start -->
                <section class="users-list-wrapper">
                    <div class="users-list-filter">
                        @if(session()->has('error') && !session()->get('error'))
                        <div class="alert alert-success alert-dismissible mb-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-like"></i>
                                <span>
                                    {{ session()->get('message') }}
                                </span>
                            </div>
                        </div>
                        @endif
                        @if(session()->has('error') && session()->get('error'))
                        <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-error"></i>
                                <span>
                                    {{ session()->get('message') }}
                                </span>
                            </div>
                        </div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add New Category</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/v1/addNewCategory') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-lg-5">
                                                <input type="text" class="form-control" name="category_title" id="category_title" placeholder="Enter category title" required>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-5">
                                                <fieldset class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="category_image" class="custom-file-input" id="inputGroupFile04" accept=".jpg,.png" required>
                                                        <label class="custom-file-label" for="inputGroupFile04">Choose category image</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-2">
                                                <button type="submit" class="btn btn-block btn-success glow">Add</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="restaurant-list-table">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- datatable start -->
                                    <div class="table-responsive">
                                        <table id="category-manager-datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th class="text-center">Preview</th>
                                                    <th>Title</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($categoryList as $key => $category)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td class="text-center"><img src="{{ asset($category->image) }}" height="40px" /></td>
                                                    <td>{{ $category->category }}</td>
                                                    <td class="text-center">
                                                        <a href="#" data-toggle="tooltip" data-placement="left" title="Edit Category" onclick="editCategory('{{ $category->id }}','{{ $category->category }}','{{ asset($category->image) }}')">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- datatable ends -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users list ends -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="modal fade text-left" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Update Category Info</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form action="{{ url('/v1/editCategoryInfo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <img id="category_image" src="{{ asset('/images/dummy/200x200.png') }}" height="180px" style="padding: 20px" />
                            </div>
                            <input type="text" name="_category_id" id="_category_id" value="" required hidden>
                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label>Restaurant Name</label>
                                    <input type="text" class="form-control" name="_category_title" placeholder="Enter restaurant name" id="_category_title" required>
                                </fieldset>
                            </div>
                            <div class="col-sm-12">
                                <fieldset class="form-group">
                                    <label>Restaurant Logo</label>
                                    <div class="custom-file">
                                        <input type="file" name="_category_image" class="custom-file-input" id="inputGroupFile04" accept=".jpg,.png">
                                        <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('Layout.footer')

    @include('Layout.scripts')

    <script>
        function editCategory(id, title, image) {
            $("#_category_id").val(id);
            $("#_category_title").val(title);
            $("#category_image").attr("src", image);
            $("#editCategoryModal").modal('show');
        }
    </script>
</body>
@endsection