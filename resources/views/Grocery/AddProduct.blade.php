@extends('Layout_Grocery.app')

@section('body')

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('Layout_Grocery.menu')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

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
                                <h4 class="card-title">Add New Product</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ url('/grocery/products/create') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Enter product name" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <fieldset class="form-group">
                                                    <select name="product_category" class="form-control" id="basicSelect" required>
                                                        <option disabled selected>Select product category</option>
                                                        @foreach($categoryList as $key => $item)
                                                        <option value="{{ $item->category }}">{{ $item->category }}</option>
                                                        @endforeach
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <input type="number" class="form-control" name="trade_price" placeholder="Enter trade price" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <input type="number" class="form-control" name="product_price" placeholder="Enter product price" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6">
                                                <fieldset class="form-group">
                                                    <select name="product_type" class="form-control" id="product_type" onclick="offerProduct()" required>
                                                        <option disabled selected>Select product Type</option>
                                                        <option value="1">Reqular Product</option>
                                                        <option value="2">Feature + Reqular Product</option>
                                                        <option value="3">Offer Product</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-1">
                                                <input type="text" class="form-control" name="measuring_unit_new" placeholder="Enter measuring unit" required>
                                            </div>


                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Custom URL (Slug)</label>
                                                <input type="text" class="form-control" name="url" placeholder="Enter custom url">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Short Description</label>
                                                <textarea type="text" class="form-control" name="short_description" placeholder="Enter short Description"></textarea>
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Stock In/Out</label>
                                                <input type="text" class="form-control" name="stock" placeholder="Enter stock">

                                            </div>


                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Meta Title &nbsp;&nbsp; <span style="color: #96271A">*SEO</span></label>
                                                <input type="text" class="form-control" name="meta_title" placeholder="Enter Title">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Meta Description &nbsp; &nbsp; <span style="color: #96271A">*SEO</span></label>
                                                <textarea type="text" class="form-control" name="meta_description" placeholder="Enter Description"></textarea>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-6 mb-2">
                                                <label>Meta Keywords &nbsp; &nbsp; <span style="color: #96271A">*SEO(Example: Test Best Product)</span></label>
                                                <textarea type="text" class="form-control" name="meta_keywords" placeholder="Enter Keywords"></textarea>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <label>Description</label>
                                                <fieldset class="form-group">
                                                    <textarea class="form-control" name="product_description" id="basicTextarea" rows="3" placeholder="Product Description" required></textarea>
                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <label>Base Image For Specific Product</label>
                                                <fieldset class="form-group">
                                                    <input type="file" class="form-control-file" name="product_thumbnail" id="product_thumbnail" required>
                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <label>Choose product multiple images</label>
                                                <fieldset class="form-group">
                                                    <div class="dropzone" id="document-dropzone"></div>
                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-12 col-lg-6 mb-1 product_old_price_for_offer" style="display:none;">
                                                <input type="number" class="form-control" name="product_old_price" placeholder="Enter product old price" value="0" required>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-8">
                                                <p>Select City Coverage</p>
                                                <ul class="list-unstyled mb-0">
{{--                                                    @foreach($cityList as $key => $item)--}}
                                                    <li class="d-inline-block mr-2 mb-1">
                                                        <fieldset>
                                                            <div class="checkbox">
                                                                <input type="checkbox" class="checkbox-input" name="city_coverage[]" id="checkbox{{ $key }}" value="{{ $item->id }}">
                                                                <label for="checkbox{{ $key }}">{{ $cityList[0]->city_name }}</label>
                                                            </div>
                                                        </fieldset>
                                                    </li>
{{--                                                    @endforeach--}}
                                                </ul>
                                            </div>
                                            <div class="col-12 col-sm-12" style="margin-top: 10px">
                                                <button type="submit" class="btn btn-block btn-success glow">Create Product</button>
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
        function offerProduct() {
            if ($("#product_type").val() == "3") {
                $('.product_old_price_for_offer').show();

            }else{
                $('.product_old_price_for_offer').hide();
            }
        }
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
