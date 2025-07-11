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
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Product Name</label>
                                                <input type="text" class="form-control" name="product_name" value="{{ $productDetails->product_name }}" placeholder="Enter product name" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Trade Price</label>
                                                <input type="number" class="form-control" name="trade_price" value="{{ $productDetails->trade_price }}" placeholder="Enter trade price" required>
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <fieldset class="form-group">
                                                    <select name="product_category" class="form-control" id="basicSelect" required>
{{--                                                        <option disabled selected>Select product category</option>--}}
{{--                                                        @foreach($categoryList as $key => $item)--}}
                                                            @if($categoryList->category == 'Green tea')
                                                                <option value="{{ $categoryList->category }}">Green tea</option>
                                                            @elseif($categoryList->category == 'Natural Herbs')
                                                                <option value="{{ $categoryList->category }}">Natural Herbs</option>
                                                            @elseif($categoryList->category == 'Organic GHEE')
                                                                <option value="{{ $categoryList->category }}">Organic GHEE</option>
                                                            @elseif($categoryList->category == 'Health Care')
                                                                <option value="{{ $categoryList->category }}">Health Care</option>
                                                            @elseif($categoryList->category == 'Nuts & Fruits')
                                                                <option value="{{ $categoryList->category }}">Nuts & Fruits</option>
                                                            @elseif($categoryList->category == 'Natural Honey')
                                                                <option value="{{ $categoryList->category }}">Natural Honey</option>
                                                            @else
                                                                <option value="{{ $categoryList->category }}">{{ $categoryList->category }}</option>
                                                            @endif
{{--                                                        @endforeach--}}
                                                    </select>
                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <fieldset class="form-group">
                                                    <select name="product_type" class="form-control" id="product_type" onclick="offerProduct()" required>
                                                        @if($productDetails->product_type == 3)
                                                            <option value="3" selected>Offer Product</option>
                                                        @elseif($productDetails->product_type == 2)
                                                            <option value="2">Feature + Regular Product</option>
                                                        @else
                                                            <option value="1">Regular Product</option>
                                                        @endif
                                                            <option value="1">Regular Product</option>
                                                            <option value="2">Feature + Regular Product</option>
                                                            <option value="3">Offer + Regular Product</option>
                                                    </select>
                                                </fieldset>
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
                                                <label>Custom URL (Slug)</label>
                                                <input type="text" class="form-control" name="url" value="{{ $productDetails->url }}" placeholder="Enter custom url">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Short Description</label>
                                                <textarea type="text" class="form-control" name="short_description" placeholder="Enter short Description">{{ $productDetails->short_description }}</textarea>
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Meta Description &nbsp; &nbsp; <span style="color: #96271A">*SEO</span></label>
                                                <textarea type="text" class="form-control" name="meta_description" placeholder="Enter Description">{{ $productDetails->meta_description }}</textarea>
                                            </div>

{{--                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">--}}
{{--                                                <label>Stock In/Out</label>--}}
{{--                                                <input type="text" class="form-control" name="stock" value="{{ $productDetails->stock }}" placeholder="Enter stock">--}}
{{--                                            </div>--}}

                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Meta Title &nbsp;&nbsp; *(SEO)</label>
                                                <input type="text" class="form-control" name="meta_title" value="{{ $productDetails->meta_title }}" placeholder="Enter Title">
                                            </div>


                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Meta Keywords &nbsp; &nbsp; <span style="color: #96271A">*SEO(Example: Test Best Product)</span></label>
                                                    <textarea type="text" class="form-control" name="meta_keywords" placeholder="Enter Keywords">{{ $productDetails->meta_keywords }}</textarea>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                <label>Product Description</label>
                                                    <textarea class="form-control" name="product_description" id="basicTextarea" rows="3" placeholder="Product Description" required>{{ $productDetails->product_description }}</textarea>
                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <label>Base Image For Specific Product</label>
                                                <fieldset class="form-group">
                                                    <input type="file" class="form-control-file" name="product_thumbnail" id="product_thumbnail">
                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <fieldset class="form-group">
                                                <label>Product Image</label>
                                                    <div class="dropzone" id="document-dropzone"></div>
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

    <script>
        let uploadedDocumentMap = {};
        Dropzone.autoDiscover = false;
        let myDropzone = new Dropzone("div#document-dropzone",{
            url: '{{ route('uploadImageViaAjax') }}',
            autoProcessQueue: true,
            uploadMultiple: true,
            addRemoveLinks: true,
            parallelUploads: 10,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            successmultiple: function(data, response) {
                $.each(response['name'], function (key, val) {
                    $('form').append('<input type="hidden" name="images[]" value="' + val + '">');
                    uploadedDocumentMap[data[key].name] = val;
                });
            },
            removedfile: function (file) {
                file.previewElement.remove()
                let name = '';
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name;
                } else {
                    name = uploadedDocumentMap[file.name];
                }
                $('form').find('input[name="images[]"][value="' + name + '"]').remove()
            }
        });
    </script>

</body>
@endsection
