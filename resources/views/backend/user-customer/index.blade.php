@extends('backend.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Management Customer</h3>
    <a href="{{ route('backend.user-customer.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Customer
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('backend.user-customer.index') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nama, Email, atau Telepon">
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label><br>
                    <button type="submit" class="btn btn-secondary">Cari</button>
                    <a href="{{ route('backend.user-customer.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Customer Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $customer)
                        <tr>
                            <td>{{ $customers->firstItem() + $index }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td>
                                @if($customer->address)
                                    {{ Str::limit($customer->address, 30) }}
                                    @if($customer->city) <br>{{ $customer->city }} @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $customer->status == 'active' ? 'success' : 'danger' }}">
                                    {{ $customer->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('backend.user-customer.show', $customer) }}" 
                                       class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('backend.user-customer.edit', $customer) }}" 
                                       class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('backend.user-customer.destroy', $customer) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?')"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data customer</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Menampilkan {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} 
                dari {{ $customers->total() }} data
            </div>
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
