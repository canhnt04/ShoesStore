$(document).ready(function () {
  $(document).on("click", "#btn-info", function (e) {
    e.preventDefault();
    $("#form-info").show();
    $("#form-password").hide();
    $("#btn-info").addClass("active");
    $("#btn-password").removeClass("active");
  });

  $(document).on("click", "#btn-password", function (e) {
    e.preventDefault();
    $("#form-info").hide();
    $("#form-password").show();
    $("#btn-password").addClass("active");
    $("#btn-info").removeClass("active");
  });
});
