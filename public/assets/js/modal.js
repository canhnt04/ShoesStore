const modalCreate = document.getElementById("modal-create");
const modalEdit = document.getElementById("modal-edit");

const openCreateBtn = document.getElementById("open_modal-create-btn");
const openEditBtns = document.querySelectorAll(".open_modal-edit-btn");
const openEditDetailProductBtns = document.querySelectorAll(
  ".open_modal-edit-btn-detail"
);

const closeBtns = document.querySelectorAll(".modal .close");

openCreateBtn?.addEventListener("click", () => {
  modalCreate.classList.add("show");
  modalCreate.style.display = "flex";
  setTimeout(() => {
    modalCreate.style.opacity = "1";
  }, 10);
});

// Mở modal sửa chi tiết sản phẩm
openEditDetailProductBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    modalEdit.classList.add("show");
    modalEdit.style.display = "flex";
    setTimeout(() => {
      modalEdit.style.opacity = "1";
    }, 100);

    // Nếu cần lấy dữ liệu từ hàng chứa nút được click
    const row = btn.closest("tr");

    const id = row.children[0].innerText;
    const name = row.children[1].innerText;
    const quantity = row.children[2].innerText;
    const size = row.children[3].innerText;
    const color = row.children[4].innerText;
    const material = row.children[5].innerText;
    const price = row.children[6].innerText;

    // Gán dữ liệu vào form cập nhật
    document.getElementById("edit-id").value = id;
    document.getElementById("product-name").value = name;
    document.getElementById("edit-quantity").value = quantity;
    document.getElementById("edit-size").value = size;
    document.getElementById("edit-color").value = color;
    document.getElementById("edit-material").value = material;
    document.getElementById("edit-price").value = price;
  });
});

// Mở modal sửa sản phẩm
openEditBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    modalEdit.classList.add("show");
    modalEdit.style.display = "flex";
    setTimeout(() => {
      modalEdit.style.opacity = "1";
    }, 100);

    // Nếu cần lấy dữ liệu từ hàng chứa nút được click
    const row = btn.closest("tr");

    const id = row.dataset.productId;
    const categoryId = row.dataset.categoryId;

    // Lấy các giá trị khác từ DOM
    const name = row.children[1].innerText;
    const imgSrc = row.querySelector("img").getAttribute("src");
    const thumbnail = imgSrc.split("/").pop();

    // Gán dữ liệu vào form
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_name").value = name;
    document.getElementById("edit-category").value = String(categoryId);
    document.getElementById("current_thumbnail").src = imgSrc;
    document.getElementById("old_thumbnail").value = thumbnail;
  });
});

// Đóng modal
closeBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    const modal = btn.closest(".modal");
    modal.style.opacity = "0";
    setTimeout(() => {
      modal.style.display = "none";
      modal.classList.remove("show");
    }, 300);
  });
});

// Đóng modal khi click bên ngoài
window.onclick = function (event) {
  [modalCreate, modalEdit].forEach((modal) => {
    if (event.target === modal) {
      modal.style.opacity = "0";
      setTimeout(() => {
        modal.style.display = "none";
        modal.classList.remove("show");
      }, 300);
    }
  });
};

document
  .getElementById("thumbnail")
  .addEventListener("change", function (event) {
    const input = event.target;
    const preview = document.getElementById("preview_thumbnail");
    const label = document.getElementById("thumbnail_label");

    if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = "block";
        label.style.display = "none"; // Ẩn label khi có hình
      };

      reader.readAsDataURL(input.files[0]);
    } else {
      preview.src = "#";
      preview.style.display = "none";
      label.style.display = "block"; // Hiện lại label nếu không có hình
    }
  });
