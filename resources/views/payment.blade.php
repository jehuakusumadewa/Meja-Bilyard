<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Card dengan Modal Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
        {{-- <img class="w-full" src="https://via.placeholder.com/300" alt="Gambar Card" /> --}}
        <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">Meja Bilyard 01</div>
            <div class=" mb-4">
                <h2 id="name-display" class="text-xl font-semibold text-gray-800">
                    Nama: John Doe
                </h2>
                <h3 id="time-display" class="text-lg text-gray-600">Waktu: 01:30:00</h3>
                <h3 id="price-display" class="text-lg text-gray-600">
                    Harga: 35.000/jam
                </h3>
                <h3 id="total-price-display" class="text-lg text-gray-600">
                    Total Harga: 0
                </h3>
            </div>
            {{-- <div id="priceDisplay" class="text-lg font-semibold text-green-600 mb-4">
                Harga: Rp 100.000
            </div> --}}
        </div>




        <div class="px-6 pt-4 pb-2">
            <button onclick="openModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nyalakan
            </button>
            <button onclick="pay()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2">
                Bayar
            </button>
        </div>
    </div>

    <!-- Modal untuk Input Harga -->
    <div id="modal" class="modal-bg fixed inset-0 bg-black bg-opacity-50 items-center justify-center">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg p-6 w-80 shadow-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">
                Masukkan Nama, Waktu, dan Harga
            </h3>
            <input type="text" id="name-input" class="w-full border border-gray-300 p-2 rounded mb-4"
                placeholder="Isi nama..." />
            <input type="text" id="time-input" class="w-full border border-gray-300 p-2 rounded mb-4"
                placeholder="Isi waktu (hh:mm:ss)..." />

            <!-- Select Harga -->
            <label for="price-select" class="text-gray-600 mb-2 block">Pilih Harga:</label>
            <select id="price-select" class="w-full border border-gray-300 p-2 rounded mb-4">
                <option value="35000">35.000/jam</option>
                <option value="45000">45.000/jam</option>
            </select>

            <div class="flex justify-end space-x-2">
                <button onclick="closeModal()" class="bg-gray-300 text-gray-800 py-1 px-4 rounded hover:bg-gray-400">
                    Batal
                </button>
                <button onclick="saveDetailsAndStartCountdown()"
                    class="bg-blue-500 text-white py-1 px-4 rounded hover:bg-blue-600">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal untuk Pembayaran -->
    <div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Rincian Pembayaran</h2>
            <p id="itemDetails" class="mb-4">Item: Judul Card, total Harga: Rp 100.000</p>
            <div class="flex justify-end">
                <button onclick="processPayment('Bayar Langsung')"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-1">
                    Bayar Langsung
                </button>
                <button onclick="processPayment('Bayar Online')"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-1">
                    Bayar Online
                </button>
                <button onclick="processPayment('Bayar QRIS')"
                    class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded mr-1">
                    Bayar QRIS
                </button>
                <button onclick="toggleModal('paymentModal')"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal untuk Pembayaran Langsung -->
    <div id="cashPaymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Pembayaran Langsung</h2>
            <p id="itemPrice" class="mb-4">Harga: Rp 100.000</p>
            <input id="cashInput" type="number" placeholder="Masukkan uang pembeli"
                class="border border-gray-300 rounded p-2 w-full mb-4" oninput="calculateChange()" />
            <p id="changeDisplay" class="text-lg font-semibold text-green-600"></p>
            <div class="flex justify-end">
                <button onclick="toggleModal('cashPaymentModal')"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Tutup
                </button>
                <button onclick="printReceipt()"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Print
                </button>
                <button onclick="alert('Pembayaran berhasil!'); toggleModal('cashPaymentModal');"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Konfirmasi Pembayaran
                </button>
            </div>
        </div>
    </div>

    <!-- Modal untuk QRIS -->
    <!-- Modal untuk QRIS -->
    <div id="qrisModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Pembayaran QRIS</h2>
            <img src="https://via.placeholder.com/150" alt="QRIS" class="mb-4" />
            <p class="text-center">
                Silakan scan QRIS di atas untuk melakukan pembayaran.
            </p>
            <div class="flex justify-end">
                <button onclick="toggleModal('qrisModal')"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Tutup
                </button>
                <button onclick="alert('Pembayaran dengan QRIS berhasil'); toggleModal('qrisModal');"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Berhasil
                </button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/payment.js') }}"></script>
</body>

</html>
