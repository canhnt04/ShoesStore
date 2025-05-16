const modalCreate = document.getElementById("modal-create");
const modalEdit = document.getElementById("modal-edit");

const openCreateBtn = document.getElementById("open_modal-create-btn");
const openEditBtns = document.querySelectorAll(".open_modal-edit-btn");

const closeBtns = document.querySelectorAll(".modal .close");

openCreateBtn?.addEventListener("click", () => {
  modalCreate.classList.add("show");
  modalCreate.style.display = "flex";
  setTimeout(() => {
    modalCreate.style.opacity = "1";
  }, 10);
});

// Mở modal sửa
openEditBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    modalEdit.classList.add("show");
    modalEdit.style.display = "flex";
    setTimeout(() => {
      modalEdit.style.opacity = "1";
    }, 10);
    // Nếu cần lấy dữ liệu từ hàng chứa nút được click

    const row = btn.closest("tr");
    const id = row.children[0].innerText;
    const name = row.children[1].innerText;
    const imgSrc = row.querySelector("img").getAttribute("src");
    const thumbnail = imgSrc.split("/").pop();

    // Gán dữ liệu vào form cập nhật
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_name").value = name;
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
