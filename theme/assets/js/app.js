$(function () {
  $(".navbar-mobile").on("click", function () {
    $(".navbar-mobile button").toggleClass("active");
    $(".navbar-nav").toggleClass("active");
  });
});

// Sayfa Yüklenince Loadingi silelim
window.onload = function () {
  document.querySelector(".page-loading").remove();
};
