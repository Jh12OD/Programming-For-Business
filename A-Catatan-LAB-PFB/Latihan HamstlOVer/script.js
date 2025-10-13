document
  .getElementById("register-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const username = document.getElementById("Username-input").value.trim();
    const email = document.getElementById("Email-input").value.trim();
    const password = document.getElementById("Password-input").value;
    const confirmpassword = document.getElementById(
      "Confirm-Password-input"
    ).value;
    const DOB = document.getElementById("DOB-input").value;
    const terms = document.getElementById("Terms").Checked;
    const gender = document.querySelector('input[name="gender"]:checked');
    const errormsg = document.getElementById("errormsg");

    let error = [];

    if (username === "" || username.lenght < 4)
      alert(
        "Username kamu harus berisikan lebih dari 4 dan tidak boleh ksosong"
      );

    if (
      email === "" ||
      email.startsWith("@") ||
      email.startsWith(".") ||
      !email.include("@") ||
      !email.include(".") ||
      email.endsWith(". ") ||
      email.include(" ")
    )
      alert(
        "It must not start with ‘@’ and ‘.’, must have at least one character between ‘@’ and ‘.’, must not end with ‘.’, and must not contain spaces."
      );

    if (password === "" || password.lenght < 6)
      alert("must not be empty and must more than 6 character");

    if (confirmpassword === "" || confirmpassword !== password)
      alert("Confirm Password must same as Password and can not be empty");

    if (DOB) {
      const birth = new Date(DOB);
      const today = new Date();
      const age = today.getFullYear() - birth.getFullYear();
      if (age < 13 || age === 13) error.push("usia harus lebih dari 13 tahun");
    } else {
      alert("Tanggal lahir wajib diisi");
    }

    if (!gender) alert("Pilih Jenis Kelamin !");

    if (!terms) alert("harus menyetujui syarat dan ketentuan !");
    else {
      alert("pendafataran berhasil !");
      document.getElementById("registerForm").reset();
    }
  });
