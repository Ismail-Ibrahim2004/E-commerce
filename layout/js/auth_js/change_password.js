function goBack() {
    // استخدم هذه الدالة للعودة إلى الصفحة السابقة
    window.history.back();
  }
  
  const form = document.getElementById("change-password-form");
  
  form.addEventListener("submit", function (event) {
    event.preventDefault();
  
    const email = document.getElementById("email").value;
    if (email) {
      alert(`A reset link has been sent to ${email}`);
    }
  });
  