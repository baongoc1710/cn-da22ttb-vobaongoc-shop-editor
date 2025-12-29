@extends('layouts.app')
@section('title', 'Thông tin cá nhân')
@section('content')
    <section class="section has-background-white-ter">
        <div class="container">
            <div class="level">
                <div class="level-left">
                    <h1 class="title is-4">Thêm sản phẩm mới</h1>
                </div>
                <div class="level-right">
                    <a href="{{ route('admin.products.index') }}" class="button">
                        <span class="icon"><i class="fas fa-arrow-left"></i></span>
                        <span>Quay lại</span>
                    </a>
                </div>
            </div>

            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <div class="box">
                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="field">
                                <label class="label">Tên sản phẩm</label>
                                <div class="control">
                                    <input class="input" type="text" name="name" placeholder="Ví dụ: Áo Thun Cotton"
                                        required>
                                </div>
                            </div>

                            <div class="columns">
                                {{-- CỘT 1: GIÁ NHẬP --}}
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Giá nhập (Cost)</label>
                                        <div class="control has-icons-left">
                                            <input class="input" type="number" name="import_price" placeholder="100000"
                                                required>
                                            <span class="icon is-small is-left"><i
                                                    class="fas fa-file-invoice-dollar"></i></span>
                                        </div>
                                        <p class="help">Giá vốn nhập hàng</p>
                                    </div>
                                </div>

                                {{-- CỘT 2: GIÁ BÁN --}}
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">Giá bán (Price)</label>
                                        <div class="control has-icons-left">
                                            <input class="input" type="number" name="base_price" placeholder="150000"
                                                required>
                                            <span class="icon is-small is-left"><i class="fas fa-tag"></i></span>
                                        </div>
                                        <p class="help">Giá bán cho khách</p>
                                    </div>
                                </div>
                            </div>


                            <div class="field">
                                <label class="label">Mô tả</label>
                                <div class="control">
                                    <textarea class="textarea" name="description" placeholder="Mô tả chi tiết về sản phẩm..."></textarea>
                                </div>
                            </div>

                            <div class="field mt-5">
                                <button type="submit" class="button is-primary is-fullwidth">Lưu sản phẩm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
