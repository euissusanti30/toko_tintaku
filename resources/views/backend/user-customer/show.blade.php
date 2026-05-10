@extends('backend.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Detail Customer</h3>
    <div>
        <a href="{{ route('backend.user-customer.edit', $userCustomer) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('backend.user-customer.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Customer</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Nama Lengkap:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $userCustomer->name }}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Email:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $userCustomer->email }}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Telepon:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $userCustomer->phone ?? '-' }}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-{{ $userCustomer->status == 'active' ? 'success' : 'danger' }}">
                            {{ $userCustomer->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Alamat</h5>
            </div>
            <div class="card-body">
                @if($userCustomer->address || $userCustomer->city || $userCustomer->province)
                    @if($userCustomer->address)
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <strong>Alamat Lengkap:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $userCustomer->address }}
                            </div>
                        </div>
                    @endif
                    
                    @if($userCustomer->city || $userCustomer->province)
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <strong>Kota/Provinsi:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $userCustomer->city }}{{ $userCustomer->city && $userCustomer->province ? ', ' : '' }}{{ $userCustomer->province }}
                            </div>
                        </div>
                    @endif
                    
                    @if($userCustomer->postal_code)
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Kode Pos:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $userCustomer->postal_code }}
                            </div>
                        </div>
                    @endif
                @else
                    <p class="text-muted">Alamat tidak tersedia</p>
                @endif
            </div>
        </div>

        <!-- Transaction History -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Riwayat Transaksi</h5>
            </div>
            <div class="card-body">
                @if($userCustomer->transaksi->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userCustomer->transaksi as $transaksi)
                                    <tr>
                                        <td>{{ $transaksi->invoice }}</td>
                                        <td>{{ $transaksi->created_at->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaksi->status == 'selesai' ? 'success' : ($transaksi->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ $transaksi->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada transaksi</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('backend.user-customer.edit', $userCustomer) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Customer
                    </a>
                    
                    <form action="{{ route('backend.user-customer.destroy', $userCustomer) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?')">
                            <i class="bi bi-trash"></i> Hapus Customer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Customer Statistics -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Statistik</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Total Transaksi:</strong><br>
                    <span class="h4">{{ $userCustomer->transaksi->count() }}</span>
                </div>
                <div class="mb-3">
                    <strong>Total Belanja:</strong><br>
                    <span class="h4">Rp {{ number_format($userCustomer->transaksi->sum('total'), 0, ',', '.') }}</span>
                </div>
                <div>
                    <strong>Tanggal Daftar:</strong><br>
                    <span class="h6">{{ $userCustomer->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
