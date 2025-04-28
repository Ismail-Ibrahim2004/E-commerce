function validateForm(event) {
  // إيقاف الإرسال الفوري للنموذج
  event.preventDefault();

  // الحصول على القيم المدخلة من المستخدم
  const username = document.getElementById("username").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("pass").value.trim();

  // التحقق من وجود قيم في الحقول
  if ( !email || !password ||!username ) {
      // إذا كان هناك حقل فارغ
      document.getElementById("formMessage").innerHTML =
          '<div class="error-message">Please complete all fields before submitting the form.</div>';
      return false;
  }

  // إذا كانت جميع الحقول مملوءة
  document.getElementById("formMessage").innerHTML =
      '<div class="message">Success !</div>';

  // تأخير لمدة نصف ثانية (500 مللي ثانية) قبل إرسال النموذج
  setTimeout(function () {
      // إرسال النموذج بعد نصف ثانية
      document.getElementById("registerForm").submit();
  }, 2000);

  return false; // تأكد من أن النموذج لا يتم إرساله مباشرة
}
