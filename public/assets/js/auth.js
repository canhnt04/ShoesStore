$(document).ready(function () {
  const container = document.getElementById("container");
  const loginBtn = document.getElementById("toggleLogin");
  const registerBtn = document.getElementById("toggleRegister");

  $(document).on("click", ".btn-back", function () {
    window.location.href = "/ShoesStore/user/Route.php";
  });

  const signInForm = container.querySelector(".sign-in form");
  const signUpForm = container.querySelector(".sign-up form");

  function disableForm(form) {
    form.querySelectorAll("input, button").forEach((e) => {
      e.disabled = true;
    });
  }

  function enableForm(form) {
    form.querySelectorAll("input, button").forEach((e) => {
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
      success: function (response) {
        if (response.success) {
          $("#ajaxLink").remove();
          alert(response.message);
          if (response.redirectUser) {
            history.pushState(null, "", response.redirectUser);
          } else {
            window.location.href = response.redirectAdmin;
          }
        }
        if (response.error) {
          alert(response.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Lỗi AJAX:", xhr, status, error);
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
      success: function (response) {
        if (response.success) {
          clearForm(signUpForm);
          alert(response.message);
        }
        if (response.error) {
          alert(response.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Lỗi AJAX:", xhr, status, error);
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
