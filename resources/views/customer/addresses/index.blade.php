@extends('layouts.master')

@section('title', 'Sổ địa chỉ - Electro')

@section('content')
<div class="container-fluid page-header py-5 mb-5">
    <div class="container py-5">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Sổ Địa Chỉ</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb text-uppercase mb-0">
                <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a class="text-white" href="{{ route('profile.edit') }}">Tài khoản</a></li>
                <li class="breadcrumb-item text-primary active" aria-current="page">Địa chỉ</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <img src="{{ asset('img/avatar.jpg') }}" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                <small class="text-muted">Thành viên</small>
                            </div>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action border-0"><i class="fas fa-user-circle me-2"></i> Thông tin tài khoản</a>
                            <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action border-0"><i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi</a>
                            <a href="{{ route('addresses.index') }}" class="list-group-item list-group-item-action border-0 active text-primary fw-bold bg-light"><i class="fas fa-map-marker-alt me-2"></i> Sổ địa chỉ</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action border-0 text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0 text-dark fw-bold">Danh sách địa chỉ</h4>
                    <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addressModal" onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i> Thêm địa chỉ mới
                    </button>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="row g-4">
                    @forelse($addresses as $address)
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm {{ $address->is_default ? 'border border-primary' : '' }}">
                            <div class="card-body position-relative">
                                @if($address->is_default)
                                <span class="badge bg-primary position-absolute top-0 end-0 m-3">Mặc định</span>
                                @endif

                                <h5 class="card-title fw-bold mb-1">{{ $address->fullname ?? Auth::user()->name }}</h5>
                                <p class="text-muted small mb-3">{{ $address->phone }}</p>

                                <p class="card-text mb-1"><i class="fas fa-home me-2 text-secondary"></i> {{ $address->address }}</p>
                                <p class="card-text text-muted small ps-4">
                                    {{ $address->ward ? $address->ward . ', ' : '' }}
                                    {{ $address->district ? $address->district . ', ' : '' }}
                                    {{ $address->city }}
                                </p>

                                <hr class="my-3">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button class="btn btn-sm btn-outline-secondary me-2"
                                            onclick="editAddress({{ json_encode($address) }})"
                                            data-bs-toggle="modal" data-bs-target="#addressModal">
                                            <i class="fas fa-pen"></i> Sửa
                                        </button>

                                        @unless($address->is_default)
                                        <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endunless
                                    </div>

                                    @unless($address->is_default)
                                    <form action="{{ route('addresses.default', $address->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link text-decoration-none">Đặt làm mặc định</button>
                                    </form>
                                    @endunless
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <img src="{{ asset('img/empty-address.png') }}" class="mb-3" style="width: 100px; opacity: 0.5;">
                        <p class="text-muted">Bạn chưa lưu địa chỉ nào.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addressForm" action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div id="methodField"></div> {{-- Chứa @method('PUT') khi edit --}}

                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="modalTitle">Thêm địa chỉ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Họ tên & SĐT --}}
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Họ và tên</label>
                            <input type="text" name="fullname" id="fullname" class="form-control" required value="{{ old('fullname', Auth::user()->name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control" required value="{{ old('phone', Auth::user()->phone) }}">
                        </div>

                        {{-- Địa chỉ cụ thể --}}
                        <div class="col-12">
                            <label class="form-label small text-muted">Địa chỉ nhận hàng (Số nhà, đường...)</label>
                            <input type="text" name="address" id="address" class="form-control" placeholder="Ví dụ: 123 Đường Nguyễn Văn Cừ" required value="{{ old('address') }}">
                        </div>

                        {{-- Tỉnh/Thành - Quận/Huyện - Phường/Xã --}}
                        {{-- Lưu ý: Nếu bạn chưa có API chọn tỉnh thành, User sẽ nhập tay --}}
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Tỉnh / Thành phố</label>
                            <input type="text" name="city" id="city" class="form-control" required value="{{ old('city') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Quận / Huyện</label>
                            <input type="text" name="district" id="district" class="form-control" value="{{ old('district') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Phường / Xã</label>
                            <input type="text" name="ward" id="ward" class="form-control" value="{{ old('ward') }}">
                        </div>

                        {{-- Checkbox Default --}}
                        <div class="col-12 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1">
                                <label class="form-check-label" for="is_default">
                                    Đặt làm địa chỉ mặc định
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary px-4">Lưu địa chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script xử lý Modal (Fill data khi bấm Edit) --}}
<script>
    function resetForm() {
        document.getElementById('modalTitle').innerText = 'Thêm địa chỉ mới';
        document.getElementById('addressForm').action = "{{ route('addresses.store') }}";
        document.getElementById('methodField').innerHTML = '';

        // Reset inputs
        document.getElementById('fullname').value = "{{ Auth::user()->name }}";
        document.getElementById('phone').value = "{{ Auth::user()->phone }}";
        document.getElementById('address').value = "";
        document.getElementById('city').value = "";
        document.getElementById('district').value = "";
        document.getElementById('ward').value = "";
        document.getElementById('is_default').checked = false;
    }

    function editAddress(address) {
        document.getElementById('modalTitle').innerText = 'Cập nhật địa chỉ';
        // Update Action URL: /my-addresses/{id}
        let url = "{{ route('addresses.update', ':id') }}";
        url = url.replace(':id', address.id);
        document.getElementById('addressForm').action = url;

        // Thêm method PUT
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        // Fill data
        document.getElementById('fullname').value = address.fullname || "{{ Auth::user()->name }}"; // Fallback name
        document.getElementById('phone').value = address.phone;
        document.getElementById('address').value = address.address;
        document.getElementById('city').value = address.city;
        document.getElementById('district').value = address.district || '';
        document.getElementById('ward').value = address.ward || '';

        if (address.is_default) {
            document.getElementById('is_default').checked = true;
        } else {
            document.getElementById('is_default').checked = false;
        }
    }
</script>
@endsection