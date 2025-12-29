@extends('layouts.app')

@section('title', 'Thông tin cá nhân')
@section('content')
    <section class="section has-background-white-ter">
        <div class="container">
            <div class="level">
                <div class="level-left">
                    <h1 class="title is-4">Danh sách sản phẩm</h1>
                </div>
                <div class="level-right">
                    <a href="{{ route('admin.products.create') }}" class="button is-primary">
                        <span class="icon"><i class="fas fa-plus"></i></span>
                        <span>Thêm sản phẩm</span>
                    </a>
                </div>
            </div>

            <div class="box">
                <table class="table is-fullwidth is-hoverable is-striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="10%">Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá gốc</th>
                            <th>Giá bán</th>
                            <th>Danh mục</th>
                            <th class="has-text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <figure class="image is-48x48">
                                        {{-- Gọi thuộc tính ảo random_image --}}
                                        <img src="{{ $product->random_image ? asset($product->random_image) : 'https://via.placeholder.com/48?text=No+Img' }}"
                                            alt="{{ $product->name }}"
                                            style="object-fit:cover; border-radius:4px; width: 100%; height: 100%;">
                                    </figure>
                                </td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ number_format($product->import_price, 0, ',', '.') }}₫</td>
                                <td>{{ number_format($product->base_price, 0, ',', '.') }}₫</td>
                                <td>{{ $product->category ?? 'Chưa phân loại' }}</td>
                                <td class="has-text-right">
                                    <div class="buttons is-right">
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="button is-small is-info is-outlined">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="button is-small is-danger is-outlined">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Phân trang --}}
                <div class="mt-4">
                    {{ $products->links('pagination.bulma') }}
                </div>
            </div>
        </div>
    </section>
@endsection
