@extends('v_layouts.app')

@section('content')

<section class="py-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-5">

                        <h2 class="fw-bold mb-4">
                            Checkout
                        </h2>

                        <!-- Cart Summary -->
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading">Ringkasan Pesanan</h6>
                            <div id="cart-summary">
                                <!-- Cart items will be loaded here -->
                            </div>
                        </div>

                        <form id="checkout-form" action="{{ route('frontend.checkout.store') }}"
                            method="POST">

                            @csrf

                            <!-- Customer Information -->
                            <h5 class="mb-3">Informasi Customer</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_customer" class="form-label">Nama Lengkap</label>
                                        <input type="text"
                                            id="nama_customer"
                                            name="nama_customer"
                                            class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email"
                                            id="email"
                                            name="email"
                                            class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telepon" class="form-label">No Telepon</label>
                                        <input type="text"
                                            id="telepon"
                                            name="telepon"
                                            class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea id="alamat"
                                    name="alamat"
                                    class="form-control"
                                    rows="3"
                                    required></textarea>
                            </div>

                            <!-- Shipping Information -->
                            <h5 class="mb-3 mt-4">Informasi Pengiriman</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="province_id" class="form-label">Provinsi</label>
                                        <select id="province_id" name="province_id" class="form-control" required>
                                            <option value="">Pilih Provinsi</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province['province_id'] }}">
                                                    {{ $province['province'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="city_id" class="form-label">Kota/Kabupaten</label>
                                        <select id="city_id" name="city_id" class="form-control" required disabled>
                                            <option value="">Pilih Kota</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city['city_id'] }}" 
                                                        data-province="{{ $city['province_id'] }}"
                                                        style="display: none;">
                                                    {{ $city['city_name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Courier Selection -->
                            <div class="mb-3">
                                <label class="form-label">Pilih Kurir</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="courier" id="courier_jne" value="jne" checked>
                                            <label class="form-check-label" for="courier_jne">JNE</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="courier" id="courier_tiki" value="tiki">
                                            <label class="form-check-label" for="courier_tiki">TIKI</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="courier" id="courier_pos" value="pos">
                                            <label class="form-check-label" for="courier_pos">POS Indonesia</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Services -->
                            <div class="mb-3">
                                <label class="form-label">Layanan Pengiriman</label>
                                <div id="shipping-services" class="border rounded p-3 bg-light">
                                    <p class="text-muted mb-0">Pilih alamat dan kurir untuk melihat layanan yang tersedia</p>
                                </div>
                            </div>

                            <!-- Hidden fields for shipping data -->
                            <input type="hidden" name="shipping_service" id="shipping_service">
                            <input type="hidden" name="shipping_cost" id="shipping_cost">
                            <input type="hidden" name="weight" id="total_weight">

                            <!-- Order Summary -->
                            <div class="card mt-4">
                                <div class="card-body">
                                    <h5 class="card-title">Ringkasan Pembayaran</h5>
                                    <div class="row">
                                        <div class="col-8">Subtotal Produk:</div>
                                        <div class="col-4 text-end" id="subtotal">Rp 0</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">Biaya Pengiriman:</div>
                                        <div class="col-4 text-end" id="shipping-display">Rp 0</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-8"><strong>Total:</strong></div>
                                        <div class="col-4 text-end" id="total"><strong>Rp 0</strong></div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-tintaku w-100 mt-3" id="submit-btn" disabled>
                                Buat Pesanan
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<script>
    console.log('asdasd')
document.addEventListener('DOMContentLoaded', function() {
    const cart = @json(session()->get('cart', []));
    let subtotal = 0;
    let totalWeight = 0;
    
    // Load cart summary
    function loadCartSummary() {
        const summaryDiv = document.getElementById('cart-summary');
        let html = '';
        
        for (const [id, item] of Object.entries(cart)) {
            const itemSubtotal = item.harga * item.qty;
            subtotal += itemSubtotal;
            totalWeight += item.qty * 1000; // 1kg per item
            
            html += `
                <div class="d-flex justify-content-between">
                    <span>${item.nama_produk} (${item.qty}x)</span>
                    <span>Rp ${itemSubtotal.toLocaleString('id-ID')}</span>
                </div>
            `;
        }
        
        html += `<hr><div class="d-flex justify-content-between"><strong>Subtotal:</strong><strong>Rp ${subtotal.toLocaleString('id-ID')}</strong></div>`;
        summaryDiv.innerHTML = html;
        
        document.getElementById('subtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        document.getElementById('total_weight').value = totalWeight;
    }
    
    loadCartSummary();
    
    // Test that elements exist and event listeners are attached
    console.log('Checkout form loaded');
    console.log('Province select:', document.getElementById('province_id'));
    console.log('City select:', document.getElementById('city_id'));
    console.log('City select initial disabled state:', document.getElementById('city_id').disabled);
    
    // Province change handler
    console.log(document.getElementById('province_id'))
    document.getElementById('province_id').addEventListener('change', function() {
        const provinceId = this.value;
        const citySelect = document.getElementById('city_id');
        
        console.log('Province changed to:', provinceId);
        
        if (provinceId) {
            // Show cities for selected province
            const cityOptions = citySelect.querySelectorAll('option');
            cityOptions.forEach(option => {
                const optionProvinceId = option.getAttribute('data-province');
                if (optionProvinceId && String(optionProvinceId) === String(provinceId)) {
                    option.style.display = 'block';
                    console.log('Showing city option:', option.textContent);
                } else if (optionProvinceId) {
                    option.style.display = 'none';
                }
            });
            
            // Enable city dropdown and reset selection
            citySelect.disabled = false;
            citySelect.value = '';
            console.log('City select enabled for province:', provinceId);
        } else {
            // Hide all cities and disable dropdown
            const cityOptions = citySelect.querySelectorAll('option[data-province]');
            cityOptions.forEach(option => {
                option.style.display = 'none';
            });
            
            citySelect.disabled = true;
            citySelect.value = '';
            console.log('City select disabled');
        }
        
        resetShipping();
    });
    
    // City change handler
    document.getElementById('city_id').addEventListener('change', function() {
        resetShipping();
        if (this.value) {
            loadShippingCost();
        }
    });
    
    // Courier change handler
    document.querySelectorAll('input[name="courier"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (document.getElementById('city_id').value) {
                loadShippingCost();
            }
        });
    });
    
    function resetShipping() {
        document.getElementById('shipping-services').innerHTML = '<p class="text-muted mb-0">Pilih alamat dan kurir untuk melihat layanan yang tersedia</p>';
        document.getElementById('shipping_service').value = '';
        document.getElementById('shipping_cost').value = '0';
        document.getElementById('shipping-display').textContent = 'Rp 0';
        document.getElementById('total').innerHTML = '<strong>Rp ' + subtotal.toLocaleString('id-ID') + '</strong>';
        document.getElementById('submit-btn').disabled = true;
    }
    
    function loadShippingCost() {
        const cityId = document.getElementById('city_id').value;
        const courier = document.querySelector('input[name="courier"]:checked').value;
        const weight = totalWeight;
        
        // Default origin (Jakarta)
        const originCity = 152; // Jakarta city ID
        
        if (!cityId || !courier || !weight) return;
        
        fetch('{{ url('/api/shipping-cost') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                origin: originCity,
                destination: cityId,
                weight: weight,
                courier: courier
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                displayShippingServices(data[0]);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    function displayShippingServices(courierData) {
        const servicesDiv = document.getElementById('shipping-services');
        let html = '';
        
        courierData.costs.forEach((service, index) => {
            const serviceId = `${courierData.code}-${service.service}`;
            html += `
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="shipping_option" 
                           id="${serviceId}" value="${service.cost[0].value}" 
                           data-service="${service.service}">
                    <label class="form-check-label" for="${serviceId}">
                        <strong>${service.service}</strong> - ${service.description} 
                        <span class="float-end">Rp ${service.cost[0].value.toLocaleString('id-ID')}</span>
                        <br><small class="text-muted">Estimasi: ${service.cost[0].etd} hari</small>
                    </label>
                </div>
            `;
        });
        
        servicesDiv.innerHTML = html;
        
        // Add event listeners to shipping options
        servicesDiv.querySelectorAll('input[name="shipping_option"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('shipping_service').value = this.dataset.service;
                document.getElementById('shipping_cost').value = this.value;
                document.getElementById('shipping-display').textContent = 'Rp ' + parseInt(this.value).toLocaleString('id-ID');
                
                const total = subtotal + parseInt(this.value);
                document.getElementById('total').innerHTML = '<strong>Rp ' + total.toLocaleString('id-ID') + '</strong>';
                document.getElementById('submit-btn').disabled = false;
            });
        });
    }
});
</script>

@endsection