@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="font-weight-bold mb-0">Kelola Produk</h3>
        <a href="#" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Menu</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead style="background-color: #f8fafc;">
                    <tr>
                        <th class="py-3 px-4 text-uppercase small text-muted font-weight-bold">Nama Produk</th>
                        <th class="py-3 px-4 text-uppercase small text-muted font-weight-bold">Harga</th>
                        <th class="py-3 px-4 text-uppercase small text-muted font-weight-bold">Stok</th>
                        <th class="py-3 px-4 text-center text-uppercase small text-muted font-weight-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 mr-3 text-center" style="width:40px; height:40px;">
                                    <i class="fa fa-drumstick-bite text-warning"></i>
                                </div>
                                <span class="font-weight-bold">Ayam Geprek Spesial</span>
                            </div>
                        </td>
                        <td class="px-4 font-weight-bold text-primary">Rp 15.000</td>
                        <td class="px-4"><span class="badge badge-success px-3 py-2 rounded-pill">Tersedia</span></td>
                        <td class="px-4 text-center">
                            <a href="#" class="btn btn-sm btn-light text-primary"><i class="fa fa-edit"></i></a>
                            <a href="#" class="btn btn-sm btn-light text-danger"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 mr-3 text-center" style="width:40px; height:40px;">
                                    <i class="fa fa-glass-martini-alt text-info"></i>
                                </div>
                                <span class="font-weight-bold">Es Teh Manis</span>
                            </div>
                        </td>
                        <td class="px-4 font-weight-bold text-primary">Rp 5.000</td>
                        <td class="px-4"><span class="badge badge-danger px-3 py-2 rounded-pill">Habis</span></td>
                        <td class="px-4 text-center">
                            <a href="#" class="btn btn-sm btn-light text-primary"><i class="fa fa-edit"></i></a>
                            <a href="#" class="btn btn-sm btn-light text-danger"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
