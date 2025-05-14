function showDetails(id, name, email, phone, address, note, status, createdAt) {
document.getElementById("order_id").innerText = id;
document.getElementById("customer_name").innerText = name;
document.getElementById("customer_email").innerText = email;
document.getElementById("customer_phone").innerText = phone;
document.getElementById("customer_address").innerText = address;
document.getElementById("order_note").innerText = note;
document.getElementById("order_status").innerText = status;
document.getElementById("order_date").innerText = createdAt;

document.getElementById("orderModal").style.display = "block";
}

function closeModal() {
document.getElementById("orderModal").style.display = "none";
}

// Đóng modal khi click bên ngoài
window.onclick = function(event) {
let modal = document.getElementById("orderModal");
if (event.target == modal) {
modal.style.display = "none";
}
};