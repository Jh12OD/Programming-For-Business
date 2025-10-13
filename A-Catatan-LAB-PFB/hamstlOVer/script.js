document.getElementById("registerForm").addEventListener("submit", function(event) {
  event.preventDefault();

  const username = document.getElementById("username").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirmPassword").value;
  const dob = document.getElementById("dob").value;
  const terms = document.getElementById("terms").checked;
  const gender = document.querySelector('input[name="gender"]:checked');
  const errorMsg = document.getElementById("errorMsg");

  let errors = [];

  if (username === "" || username.length < 4)
    errors.push("Username minimal 4 karakter dan tidak boleh kosong.");

  if (email === "" || email.startsWith("@") || email.startsWith(".") ||
      email.includes(" ") || !email.includes("@") || !email.includes(".") ||
      email.indexOf("@") > email.lastIndexOf(".") - 1 || email.endsWith("."))
    errors.push("Format email tidak valid.");

  if (password === "" || password.length < 6)
    errors.push("Password minimal 6 karakter.");

  if (confirmPassword === "" || confirmPassword !== password)
    errors.push("Konfirmasi password tidak cocok.");

  if (dob) {
    const birth = new Date(dob);
    const today = new Date();
    const age = today.getFullYear() - birth.getFullYear();
    if (age < 13 || (age === 13 && today < new Date(birth.setFullYear(birth.getFullYear() + 13))))
      errors.push("Usia minimal 13 tahun.");
  } else {
    errors.push("Tanggal lahir wajib diisi.");
  }

  if (!gender)
    errors.push("Pilih jenis kelamin.");

  if (!terms)
    errors.push("Harus menyetujui syarat & ketentuan.");

  if (errors.length > 0) {
    errorMsg.innerText = errors.join("\n");
    errorMsg.style.color = "red";
  } else {
    errorMsg.style.color = "green";
    errorMsg.innerText = "Pendaftaran berhasil!";
    document.getElementById("registerForm").reset();
  }
});