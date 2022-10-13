@extends('Layout_Grocery.app')

    @section('meta_title', "$productDetails->meta_title")
    @section('meta_description', "$productDetails->meta_description")
    @section('meta_keywords', "$productDetails->meta_keywords")

@section('body')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    @include('Layout_Grocery.menu')

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
                                <h4 class="card-title">Edit Product</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/grocery/products/edit/confirm') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <input type="text" name="product_id" value="{{ $productID }}" required hidden />
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-12 mb-2">
                                                <label>Product Name</label>
                                                <input type="text" class="form-control" name="product_name" value="{{ $productDetails->product_name }}" placeholder="Enter product name" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Trade Price</label>
                                                <input type="number" class="form-control" name="trade_price" value="{{ $productDetails->trade_price }}" placeholder="Enter trade price" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Retail Product Price</label>
                                                <input type="number" class="form-control" name="product_price" value="{{ $productDetails->product_price }}" placeholder="Enter retail price" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Measuring unit</label>
                                                <input type="text" class="form-control" name="measuring_unit_new" value="{{ $productDetails->measuring_unit_new }}" placeholder="Enter Measuring unit" required>
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Meta Title &nbsp;&nbsp; *(SEO)</label>
                                                <input type="text" class="form-control" name="meta_title" value="{{ $productDetails->meta_title }}" placeholder="Enter Title">
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Meta Description &nbsp; &nbsp; <span style="color: #96271A">*SEO</span></label>
                                                <textarea type="text" class="form-control" name="meta_description" value="" placeholder="Enter Description">{{ $productDetails->meta_description }}</textarea>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Meta Keywords &nbsp; &nbsp; <span style="color: #96271A">*SEO(Example: Test Best Product)</span></label>
                                                    <textarea type="text" class="form-control" name="meta_keywords" value="" placeholder="Enter Keywords">{{ $productDetails->meta_keywords }}</textarea>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                <label>Product Description</label>
                                                    <textarea class="form-control" name="product_description" id="basicTextarea" rows="3" placeholder="Product Description" required>{{ $productDetails->product_description }}</textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                <label>Product Image</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="inputGroupFile02" name="product_thumbnail">
                                                        <label class="custom-file-label" for="inputGroupFile02">Choose product image</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="submit" class="btn btn-block btn-success glow">Update Product</button>
                                            </div>
                                        </div>
                                    </form>
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

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('Layout_Grocery.footer')

    @include('Layout_Grocery.scripts')

    <script>
          CKEDITOR.replace( 'product_description' );
    </script>

</body>
@endsection
