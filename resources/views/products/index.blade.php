@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/products.css') }}"rel="stylesheet">

<div id="main-content">
    <input type="hidden" id="currentUserEmail" value="{{ $currentEmail }}">
    <div class="container-fluid">
        <div class="row">
            <div id="region-main" class="content-col col-md-12">
                <div id="page-content">
                    <span class="notifications" id="user-notifications"></span>
                    <div role="main">
                        <span id="maincontent"></span>
                        <div class="container">
                            <h2>Danh sách sản phẩm</h2>
                            <div class="message-product hidden">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <div id="content_message">Cập nhật sản phẩm thành công!</div>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                            <div class="product-page">
                                @include('products.left.product_left_part', [
                                'name1' => 'Tên sản phẩm',
                                'products' => $products
                                ])
                                @include('products.right.product_right_part')
                            </div>
                            <div id="test"></div>
                            <input type="hidden" id="product_id" value="0">
                            <input type="hidden" id="product_children" value="[]">
                            <input type="hidden" id="mode_change_course_parent" value="0">
                            <input type="hidden" id="mode_change_product_parent" value="0">
                        </div>
                        <div class="modal fade" id="deleteOrArchiveModal" tabindex="-1" aria-labelledby="deleteOrArchiveModalLabel" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="yui_3_17_2_1_1731588442249_357">
                                <div class="modal-content" id="yui_3_17_2_1_1731588442249_356">
                                    <div class="modal-header" id="yui_3_17_2_1_1731588442249_355">
                                        <h5 class="modal-title" id="deleteOrArchiveModalLabel">Xóa sản phẩm</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="yui_3_17_2_1_1731588442249_354">
                                            <span aria-hidden="true" id="yui_3_17_2_1_1731588442249_358">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div style="text-align: center">
                                            Bạn muốn xóa sản phẩm <br>"<b id="modal_name_product">Sản phẩm 120</b>" ?
                                        </div>
                                    </div>
                                    <div class="modal-footer" id="yui_3_17_2_1_1731588442249_374">
                                        <button type="button" class="btn btn-primary delete-product" data-product-id="" data-product-type="">Xóa</button>
                                        <!-- <button type="button" class="btn btn-primary archive-product" data-product-id="" data-product-type="">Lưu trữ</button> -->
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="yui_3_17_2_1_1731588442249_373">Hủy bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('js/products/products.js') }}"></script>
@endsection