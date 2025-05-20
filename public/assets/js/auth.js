$(document).ready(function () {
  const container = document.getElementById("container");
  const loginBtn = document.getElementById("toggleLogin");
  const registerBtn = document.getElementById("toggleRegister");
  const signInForm = container.querySelector(".sign-in form");
  const signUpForm = container.querySelector(".sign-up form");

  $(".auth-form__control-back").on("click", function (e) {
    e.preventDefault();
    window.location.href = "/ShoesStore/public/index.php";
  });

  function disableForm(form) {
    form.querySelectorAll("input, button").forEach((e) => {
      e.preventDefault();
      e.disabled = true;
    });
  }

  function enableForm(form) {
    form.querySelectorAll("input, button").forEach((e) => {
      e.preventDefault();
      e.disabled = false;
    });
  }

  function clearForm(form) {
    form.querySelectorAll("input").forEach((input) => {
      input.value = "";
    });
  }

  $("#loginForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: "/ShoesStore/user/controller/AuthController.php",
      method: "POST",
      data: $(this).serialize() + "&action=login",
      dataType: "json",
      success: function (res) {
        if (res.success) {
          alert(res.message);
          $("#ajaxLink").remove();
          // history.pushState({}, "", res.redirect);
          window.location.href = res.redirect;
        } else {
          alert(res.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Lỗi AJAX:", xhr, status, error);
        console.error("error:", xhr.responseText);
      },
      complete: function () {
        setTimeout(function () {
          enableForm(signInForm);
        }, 1000);
      },
    });
  });

  $("#registerForm").submit(function (e) {
    e.preventDefault();
    $.ajax({
      url: "/ShoesStore/user/controller/AuthController.php",
      method: "POST",
      data: $(this).serialize() + "&action=register",
      dataType: "json",
      success: function (res) {
        if (res.success) {
          clearForm(signUpForm);
          alert(res.message);
        }
        if (res.error) {
          alert(res.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Lỗi AJAX:", xhr, status, error);
        console.error("error:", xhr.responseText);
      },
      complete: function () {
        setTimeout(function () {
          enableForm(signUpForm);
        }, 1000);
      },
    });
  });

  registerBtn.addEventListener("click", () => {
    container.classList.add("active");
    enableForm(signUpForm);
    disableForm(signInForm);
    clearForm(signInForm);
  });

  loginBtn.addEventListener("click", () => {
    container.classList.remove("active");
    enableForm(signInForm);
    disableForm(signUpForm);
    clearForm(signUpForm);
  });
});
