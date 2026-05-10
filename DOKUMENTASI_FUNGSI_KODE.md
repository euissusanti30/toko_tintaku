# DOKUMENTASI FUNGSI-FUNGSI KODE TOKO TINTAKU
Untuk Presentasi di Depan Kelas

---

## DAFTAR ISI
1. [RajaOngkirService.php - Layanan Pengiriman](#rajaongkirservicephp)
2. [ProdukController.php - Controller Produk](#produkcontrollerphp)
3. [test_checkout.php - File Testing](#test_checkoutphp)
4. [checkout.blade.php - Tampilan Checkout](#checkoutbladephp)

---

## RajaOngkirService.php - Layanan Pengiriman

### **FUNGSI UTAMA:**
Menghubungkan aplikasi dengan API RajaOngkir untuk menghitung ongkos kirim

### **FUNGSI-FUNGSI PENTING:**

#### 1. `__construct()` - Konstruktor
- **Tujuan:** Inisialisasi service dengan API key dan base URL
- **Fitur:** Auto demo mode jika tidak ada API key

#### 2. `getProvinces()` - Ambil Data Provinsi
- **Tujuan:** Mendapatkan semua provinsi di Indonesia
- **Return:** Array provinsi (ID dan nama)
- **Cache:** Disimpan 1 jam untuk performa

#### 3. `getCities($provinceId)` - Ambil Data Kota
- **Tujuan:** Mendapatkan kota berdasarkan provinsi
- **Parameter:** ID provinsi (opsional)
- **Return:** Array kota dengan ID dan nama

#### 4. `getShippingCost()` - Hitung Ongkir
- **Tujuan:** Menghitung biaya pengiriman
- **Parameter:** 
  - `$origin` - Kota asal
  - `$destination` - Kota tujuan  
  - `$weight` - Berat (gram)
  - `$courier` - Kurir (jne/tiki/pos)
- **Return:** Data ongkir dengan layanan dan harga

#### 5. `getMockProvinces()` & `getMockCities()` - Data Demo
- **Tujuan:** Data dummy untuk demo mode
- **Keunggulan:** Tidak perlu API key untuk testing

#### 6. `getAvailableCouriers()` - Daftar Kurir
- **Tujuan:** Menampilkan kurir yang tersedia
- **Return:** JNE, TIKI, POS Indonesia

#### 7. `formatCost()` - Format Rupiah
- **Tujuan:** Mengubah angka ke format Rp 1.000.000
- **Parameter:** Angka biaya

#### 8. `getCityName()` & `getProvinceName()` - Nama Lokasi
- **Tujuan:** Mengambil nama kota/provinsi berdasarkan ID
- **Return:** String nama lokasi

---

## ProdukController.php - Controller Produk

### **FUNGSI UTAMA:**
Mengatur semua halaman dan logika toko online

### **FUNGSI-FUNGSI PENTING:**

#### **HALAMAN UTAMA**
1. `frontend()` - Halaman Beranda
   - **Tujuan:** Tampilkan 8 produk terbaru
   - **Data:** Produk dengan kategori, pagination 8 item

2. `shop()` - Halaman Toko
   - **Tujuan:** Tampilkan semua produk
   - **Data:** Produk dengan kategori, pagination 12 item

3. `detail($id)` - Detail Produk
   - **Tujuan:** Tampilkan detail 1 produk
   - **Parameter:** ID produk

4. `kategori($id)` - Filter Kategori
   - **Tujuan:** Tampilkan produk per kategori
   - **Parameter:** ID kategori

#### **KERANJANG BELANJA**
5. `cart()` - Halaman Keranjang
   - **Tujuan:** Tampilkan isi keranjang

6. `addCart($id)` - Tambah ke Keranjang
   - **Tujuan:** Menambah produk ke keranjang
   - **Logic:** Cek jika sudah ada, tambah qty

7. `updateCart()` - Update Qty Keranjang
   - **Tujuan:** Mengubah jumlah produk
   - **Parameter:** ID produk, qty baru

8. `deleteCart($id)` - Hapus dari Keranjang
   - **Tujuan:** Menghapus produk dari keranjang

#### **CHECKOUT**
9. `checkout()` - Halaman Checkout
   - **Tujuan:** Tampilkan form checkout
   - **Data:** Provinsi, kota dari RajaOngkir

10. `checkoutStore()` - Proses Checkout
    - **Tujuan:** Simpan transaksi ke database
    - **Logic:** 
      - Validasi form
      - Hitung total harga
      - Simpan transaksi
      - Simpan detail transaksi
      - Kosongkan keranjang

#### **API PENGIRIMAN**
11. `getCities($provinceId)` - API Kota
    - **Tujuan:** Endpoint API untuk ambil kota
    - **Return:** JSON data kota

12. `getShippingCost()` - API Ongkir
    - **Tujuan:** Endpoint API untuk hitung ongkir
    - **Parameter:** origin, destination, weight, courier
    - **Return:** JSON data ongkir

#### **INVOICE**
13. `invoice($id)` - Halaman Invoice
    - **Tujuan:** Tampilkan invoice pesanan
    - **Data:** Transaksi dengan detail produk

---

## test_checkout.php - File Testing

### **FUNGSI UTAMA:**
Testing data checkout sebelum implementasi

### **FUNGSI-FUNGSI PENTING:**

#### 1. **Bootstrap Laravel**
- **Tujuan:** Load framework Laravel
- **Logic:** Inisialisasi aplikasi dan kernel

#### 2. **Test RajaOngkir Service**
- **Tujuan:** Test semua fungsi RajaOngkir
- **Testing:**
  - Ambil semua provinsi
  - Ambil semua kota
  - Kelompokkan kota per provinsi
  - Tampilkan hasil di console

#### 3. **Error Handling**
- **Tujuan:** Tangkap dan tampilkan error
- **Logic:** Try-catch dengan trace lengkap

---

## checkout.blade.php - Tampilan Checkout

### **FUNGSI UTAMA:**
Halaman frontend untuk proses checkout

### **BAGIAN-BAGIAN PENTING:**

#### **FORM INPUT**
1. **Informasi Customer**
   - Nama lengkap
   - Email
   - No telepon
   - Alamat lengkap

2. **Informasi Pengiriman**
   - Dropdown provinsi (dinamis)
   - Dropdown kota (dinamis per provinsi)
   - Pilihan kurir (JNE, TIKI, POS)

#### **JAVASCRIPT LOGIC**

1. **`loadCartSummary()`** - Ringkasan Keranjang
   - **Tujuan:** Tampilkan produk di keranjang
   - **Logic:** Hitung subtotal dan total berat

2. **Province Change Handler**
   - **Tujuan:** Filter kota berdasarkan provinsi
   - **Logic:** Show/hide option kota

3. **City Change Handler**
   - **Tujuan:** Trigger load ongkir
   - **Logic:** Reset shipping, load cost

4. **Courier Change Handler**
   - **Tujuan:** Reload ongkir saat ganti kurir
   - **Logic:** Fetch API ongkir baru

5. **`loadShippingCost()`** - API Ongkir
   - **Tujuan:** Ambil data ongkir dari server
   - **Endpoint:** `/api/shipping-cost`
   - **Method:** POST dengan JSON

6. **`displayShippingServices()`** - Tampilan Layanan
   - **Tujuan:** Tampilkan pilihan layanan kurir
   - **Data:** Service, description, cost, ETA

7. **Shipping Option Handler**
   - **Tujuan:** Update total harga saat pilih layanan
   - **Logic:** Enable submit button, update display

#### **FITUR INTERAKTIF**
- Real-time cost calculation
- Dynamic city filtering
- Form validation
- Responsive design
- Auto-calculation of total

---

## ALUR KERJA SISTEM

### **1. User Add to Cart**
```
Produk → addCart() → Session Cart → cart() page
```

### **2. Checkout Process**
```
cart() → checkout() → Form → checkoutStore() → Database → invoice()
```

### **3. Shipping Cost**
```
Form → JavaScript → API → RajaOngkir → Display → Submit
```

### **4. Data Flow**
```
Frontend → Controller → Service → API → Cache → Frontend
```

---

## TIPS PRESENTASI

### **Yang Harus Ditekankan:**
1. **Modular Design** - Pemisahan service dan controller
2. **Error Handling** - Demo mode untuk fallback
3. **User Experience** - Real-time calculation
4. **Data Management** - Session untuk cart, database untuk transaksi
5. **API Integration** - RajaOngkir untuk real shipping cost

### **Demo yang Bisa Ditunjukkan:**
1. Add produk ke keranjang
2. Pilih provinsi → kota muncul
3. Pilih kurir → ongkir muncul
4. Submit checkout → invoice generated

---

## SEKIAN!