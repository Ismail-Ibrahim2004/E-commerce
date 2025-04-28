document.addEventListener("DOMContentLoaded", () => {
  const openCartBtn = document.getElementById("open_cart_btn");
  const closeCartBtn = document.getElementById("close_btn");
  const sideCart = document.getElementById("sidecart");

  // فتح السلة
  if (openCartBtn && sideCart) {
    openCartBtn.addEventListener("click", (e) => {
      e.preventDefault();
      sideCart.classList.add("sidecart-open");
    });
  }

  // غلق السلة
  if (closeCartBtn && sideCart) {
    closeCartBtn.addEventListener("click", () => {
      sideCart.classList.remove("sidecart-open");
    });
  }

  // حذف وتعديل محتوى السلة
  document.addEventListener("click", function(e) {
    // حذف منتج
    if (e.target.classList.contains("remove-from-cart")) {
      const id = e.target.dataset.id;

      fetch("remove_from_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "cart_id=" + id
      })
      .then(res => res.json())
      .then(() => refreshCart());
    }

    // زيادة/نقصان
    if (e.target.classList.contains("update-cart")) {
      const id = e.target.dataset.id;
      const action = e.target.dataset.action;

      fetch("update_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `cart_id=${id}&action=${action}`
      })
      .then(res => res.json())
      .then(() => refreshCart());
    }
  });

  // تحديث محتوى السلة
  function refreshCart() {
    fetch("sidecart.php")
      .then(res => res.text())
      .then(html => {
        sideCart.innerHTML = html;
      });
  }
});
