$(document).ready(function () {
  const container = document.getElementById("container");
  const loginBtn = document.getElementById("toggleLogin");
  const registerBtn = document.getElementById("toggleRegister");

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
    form.querySelectorAll(".alert-error").forEach((e) => {
      e.remove();
    });
  }

  signUpForm.addEventListener("submit", (e) => {
    if (!container.classList.contains("active")) {
      e.preventDefault();
    }
  });

  signInForm.addEventListener("submit", (e) => {
    if (container.classList.contains("active")) {
      e.preventDefault();
    }
  });

  registerBtn.addEventListener("click", () => {
    container.classList.add("active");
    disableForm(signInForm);
    enableForm(signUpForm);
    clearForm(signInForm);
  });

  loginBtn.addEventListener("click", () => {
    container.classList.remove("active");
    disableForm(signUpForm);
    enableForm(signInForm);
    clearForm(signUpForm);
  });

  function submitForm(form, action) {
    const formData = new FormData(form[0]);
    formData.append("action", action);

    $.ajax({
      url: "../../../user/controller/AuthController.php",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: (data) => {
        clearForm(form);

        if (data.error) {
          $.each(data.error, (key, message) => {});
        }
      },
    });
  }
});
