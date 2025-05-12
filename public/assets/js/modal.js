const modal = document.getElementById("myModal");
const openBtn = document.getElementById("add_user-btn");
const closeBtn = document.querySelector(".close");

openBtn.onclick = function () {
  modal.classList.add("show");
  modal.style.display = "flex";
  setTimeout(() => {
    modal.style.opacity = "1";
  }, 10);
};

closeBtn.onclick = function () {
  modal.style.opacity = "0";
  setTimeout(() => {
    modal.style.display = "none";
    modal.classList.remove("show");
  }, 300);
};

window.onclick = function (event) {
  if (event.target === modal) {
    modal.style.opacity = "0";
    setTimeout(() => {
      modal.style.display = "none";
      modal.classList.remove("show");
    }, 300);
  }
};
