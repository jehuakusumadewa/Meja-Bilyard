let countdownInterval; // Variable untuk menyimpan interval countdown

// Function untuk membuka modal
function openModal() {
    document.getElementById("modal").classList.add("active");
}

// Function untuk menutup modal
function closeModal() {
    document.getElementById("modal").classList.remove("active");
}

// Function untuk mengonversi waktu (hh:mm:ss) menjadi detik
function convertTimeToSeconds(time) {
    const [hours, minutes, seconds] = time.split(":").map(Number);
    return hours * 3600 + minutes * 60 + seconds;
}

// Function untuk mengonversi detik kembali menjadi waktu (hh:mm:ss)
function convertSecondsToTime(seconds) {
    const hours = Math.floor(seconds / 3600)
        .toString()
        .padStart(2, "0");
    const minutes = Math.floor((seconds % 3600) / 60)
        .toString()
        .padStart(2, "0");
    const secs = (seconds % 60).toString().padStart(2, "0");
    return `${hours}:${minutes}:${secs}`;
}

// Function untuk menghitung total harga berdasarkan waktu dan harga per jam
function calculateTotalPrice(timeInSeconds, pricePerHour) {
    const hours = timeInSeconds / 3600;
    return Math.round(hours * pricePerHour);
}

// Function untuk memulai countdown
function startCountdown(timeInSeconds) {
    clearInterval(countdownInterval); // Menghapus countdown sebelumnya jika ada
    countdownInterval = setInterval(() => {
        if (timeInSeconds > 0) {
            timeInSeconds--;
            document.getElementById(
                "time-display"
            ).innerText = `Waktu: ${convertSecondsToTime(timeInSeconds)}`;
        } else {
            clearInterval(countdownInterval); // Menghentikan countdown saat mencapai 0
            document.getElementById("time-display").innerText =
                "Waktu: 00:00:00";
            alert("Waktu telah habis!");
        }
    }, 1000);
}

// Function untuk menyimpan nama, waktu, harga, dan menghitung total harga, lalu memulai countdown
function saveDetailsAndStartCountdown() {
    const nameInput = document.getElementById("name-input").value;
    const timeInput = document.getElementById("time-input").value;
    const priceInput = document.getElementById("price-select").value;

    // Update nama jika ada input
    if (nameInput) {
        document.getElementById(
            "name-display"
        ).innerText = `Nama: ${nameInput}`;
    }

    // Update waktu dan mulai countdown jika ada input
    if (timeInput) {
        const timeInSeconds = convertTimeToSeconds(timeInput);
        document.getElementById(
            "time-display"
        ).innerText = `Waktu: ${timeInput}`;
        startCountdown(timeInSeconds);

        // Hitung total harga
        const pricePerHour = parseInt(priceInput, 10);
        const totalPrice = calculateTotalPrice(timeInSeconds, pricePerHour);
        document.getElementById(
            "total-price-display"
        ).innerText = `Total Harga: ${totalPrice.toLocaleString()}`;
    }

    // Update harga per jam jika ada input
    if (priceInput) {
        const priceText = priceInput === "35000" ? "35.000/jam" : "45.000/jam";
        document.getElementById(
            "price-display"
        ).innerText = `Harga: ${priceText}`;
    }

    closeModal();
}

function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.toggle("hidden");
}

// function savePrice() {
//     const priceInput = document.getElementById("priceInput").value;
//     const priceDisplay = document.getElementById("priceDisplay");
//     priceDisplay.textContent = `Harga: Rp ${priceInput}`;
//     toggleModal("modal"); // Menutup modal setelah menyimpan
// }

function pay() {
    const paymentModal = document.getElementById("paymentModal");
    const totalPriceText = document.getElementById(
        "total-price-display"
    ).innerText;

    // Update payment modal details
    document.getElementById(
        "itemDetails"
    ).innerText = `Item: Meja Bilyard 01, ${totalPriceText}`;

    // Show payment modal
    // document.getElementById("paymentModal").classList.remove("hidden");
    toggleModal("paymentModal"); // Menampilkan modal pembayaran
}

function processPayment(method) {
    if (method === "Bayar Langsung") {
        const totalPriceText = document.getElementById(
            "total-price-display"
        ).innerText;

        // Update payment modal details
        document.getElementById("itemPrice").innerText = `${totalPriceText}`;
        toggleModal("cashPaymentModal"); // Menampilkan modal untuk pembayaran langsung
    } else if (method === "Bayar QRIS") {
        toggleModal("qrisModal"); // Menampilkan modal QRIS
    } else {
        alert(
            `Pembayaran untuk ${
                document.getElementById("priceDisplay").textContent
            } dengan metode ${method} sedang diproses...`
        );
        toggleModal("paymentModal"); // Menutup modal pembayaran
    }
}

function calculateChange() {
    const price = parseFloat(
        document
            .getElementById("itemPrice")
            .textContent.replace("Total Harga: ", "")
            .replace(".", "")
            .replace(",", ".")
    );
    const cashReceived = parseFloat(document.getElementById("cashInput").value);
    const change = cashReceived - price;
    const changeDisplay = document.getElementById("changeDisplay");

    if (change < 0) {
        changeDisplay.textContent = "Uang tidak cukup!";
    } else {
        changeDisplay.textContent = `Kembali: Rp ${change
            .toFixed(2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`;
    }
}

function printReceipt() {
    const itemDetails = document.getElementById("itemDetails").textContent;
    const price = document.getElementById("itemPrice").textContent;
    const cashReceived = document.getElementById("cashInput").value;
    const change = document.getElementById("changeDisplay").textContent;

    const printWindow = window.open("", "_blank");
    printWindow.document.write(`
        <html>
            <head>
                <title>Struk Pembayaran</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    h1 { text-align: center; }
                    p { font-size: 18px; }
                </style>
            </head>
            <body>
                <h1>Struk Pembayaran</h1>
                <p>${itemDetails}</p>
                <p>${price}</p>
                <p>Uang Masuk: Rp ${cashReceived}</p>
                <p>${change}</p>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
