function validateForm(event) {
    // إيقاف الإرسال الفوري للنموذج
    event.preventDefault();

    // الحصول على القيم المدخلة من المستخدم
    const firstName = document.getElementById("FirstName").value.trim();
    const lastName = document.getElementById("LastName").value.trim();
    const email = document.getElementById("Email").value.trim();
    const phone = document.getElementById("PhoneNumber").value.trim();
    const message = document.getElementById("Message").value.trim();
  
    // التحقق من وجود قيم في الحقول
    if (!firstName || !lastName || !email || !phone || !message) {
        // إذا كان هناك حقل فارغ
        document.getElementById("formMessage").innerHTML =
            '<div class="error-message">Please complete all fields before submitting the form.</div>';
        return false;
    }

    // إذا كانت جميع الحقول مملوءة
    document.getElementById("formMessage").innerHTML =
        '<div class="message">Your message has been successfully sent!</div>';

    // تأخير لمدة نصف ثانية (500 مللي ثانية) قبل إرسال النموذج
    setTimeout(function () {
        // إرسال النموذج بعد نصف ثانية
        document.getElementById("contactForm").submit();
    }, 2000);

    return false; // تأكد من أن النموذج لا يتم إرساله مباشرة
}
