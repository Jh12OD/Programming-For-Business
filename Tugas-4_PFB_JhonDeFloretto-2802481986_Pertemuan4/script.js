function hitung(operasi) {
  let a = parseFloat(document.getElementById("angka1").value);
  let b = parseFloat(document.getElementById("angka2").value);
  let hasil = 0;

  switch (operasi) {
    case "+":
      hasil = a + b;
      break;
    case "-":
      hasil = a - b;
      break;
    case "*":
      hasil = a * b;
      break;
    case "/":
      hasil = b !== 0 ? a / b : "Tidak bisa bagi 0";
      break;
  }

  document.getElementById("hasil").innerText = hasil;
}
