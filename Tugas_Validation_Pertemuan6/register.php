<?php
// register.php
session_start();

// simple CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$values = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'birthdate' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = "Token CSRF tidak valid. Coba muat ulang halaman.";
    } else {
        // sanitize & collect
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $birthdate = trim($_POST['birthdate'] ?? '');

        $values['name'] = htmlspecialchars($name);
        $values['email'] = htmlspecialchars($email);
        $values['phone'] = htmlspecialchars($phone);
        $values['birthdate'] = htmlspecialchars($birthdate);

        // name
        if ($name === '') {
            $errors['name'] = "Nama wajib diisi.";
        } elseif (mb_strlen($name) < 3) {
            $errors['name'] = "Nama minimal 3 karakter.";
        }

        // email
        if ($email === '') {
            $errors['email'] = "Email wajib diisi.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Format email tidak valid.";
        }

        // password
        if ($password === '') {
            $errors['password'] = "Password wajib diisi.";
        } elseif (strlen($password) < 8) {
            $errors['password'] = "Password minimal 8 karakter.";
        } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errors['password'] = "Password harus mengandung setidaknya 1 huruf besar dan 1 angka.";
        }

        // confirm password
        if ($confirm !== $password) {
            $errors['confirm_password'] = "Konfirmasi password tidak cocok.";
        }

        // phone (optional, but if present must be digits)
        if ($phone !== '' && !preg_match('/^[0-9+\-\s]{6,20}$/', $phone)) {
            $errors['phone'] = "Nomor telepon tidak valid.";
        }

        // birthdate (optional, but check format YYYY-MM-DD)
        if ($birthdate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthdate)) {
            $errors['birthdate'] = "Format tanggal lahir harus YYYY-MM-DD.";
        }

        // file upload example (optional)
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                $errors['avatar'] = "Kesalahan upload file.";
            } else {
                $fsize = $_FILES['avatar']['size'];
                $ftype = mime_content_type($_FILES['avatar']['tmp_name']);
                $allowed = ['image/jpeg', 'image/png', 'image/webp'];
                if ($fsize > 2 * 1024 * 1024) {
                    $errors['avatar'] = "File maksimal 2MB.";
                } elseif (!in_array($ftype, $allowed)) {
                    $errors['avatar'] = "Hanya JPG/PNG/WEBP yang diizinkan.";
                }
            }
        }

        // If no errors, process (e.g., save to DB). Here we simulate success.
        if (empty($errors)) {
            // Example: hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // If you want to save uploaded avatar:
            $avatar_path = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $target = __DIR__ . '/uploads/avatar_' . bin2hex(random_bytes(8)) . '.' . $ext;
                if (!is_dir(__DIR__ . '/uploads')) mkdir(__DIR__ . '/uploads', 0755, true);
                move_uploaded_file($_FILES['avatar']['tmp_name'], $target);
                $avatar_path = $target;
            }

            // TODO: simpan $name, $email, $password_hash, $phone, $birthdate, $avatar_path ke DB (gunakan prepared statements)
            // contoh (PDO) disarankan, tapi di sini kita hanya kirim pesan sukses:

            // Clear CSRF token after successful submit to prevent replay
            unset($_SESSION['csrf_token']);

            echo "<!doctype html><html lang='id'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Berhasil</title></head><body>";
            echo "<h2>Pendaftaran Berhasil</h2>";
            echo "<p>Nama: " . htmlspecialchars($name) . "</p>";
            echo "<p>Email: " . htmlspecialchars($email) . "</p>";
            echo "<p><strong>Catatan:</strong> Password disimpan sebagai hash. Jangan simpan password plain text.</p>";
            echo "<p><a href='register.php'>Kembali</a></p>";
            echo "</body></html>";
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Form Registrasi — Contoh Validasi PHP</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; padding:20px; background:#f7f7f9}
    .card{max-width:680px;margin:0 auto;background:#fff;padding:18px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,.06)}
    label{display:block;margin-top:12px;font-weight:600}
    input[type=text], input[type=email], input[type=password], input[type=date], input[type=file]{
      width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;margin-top:6px;
    }
    .error{color:#b00020;margin-top:6px;font-size:.95em}
    .btn{margin-top:14px;padding:10px 14px;border:none;border-radius:8px;background:#2563eb;color:#fff;cursor:pointer}
    .small{font-size:.9em;color:#666}
    .field-row{display:flex;gap:12px}
    @media (max-width:600px){ .field-row{flex-direction:column} }
  </style>
</head>
<body>
  <div class="card">
    <h1>Registrasi</h1>
    <p class="small">Contoh validasi client & server dengan PHP.</p>

    <?php if (!empty($errors) && is_array($errors) && isset($errors[0])): ?>
      <div class="error"><?=htmlspecialchars($errors[0])?></div>
    <?php endif; ?>

    <form id="registerForm" method="post" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">

      <label for="name">Nama</label>
      <input id="name" name="name" type="text" value="<?=$values['name']?>">
      <?php if (!empty($errors['name'])) echo "<div class='error'>".htmlspecialchars($errors['name'])."</div>"; ?>

      <label for="email">Email</label>
      <input id="email" name="email" type="email" value="<?=$values['email']?>">
      <?php if (!empty($errors['email'])) echo "<div class='error'>".htmlspecialchars($errors['email'])."</div>"; ?>

      <div class="field-row">
        <div style="flex:1">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" autocomplete="new-password">
          <?php if (!empty($errors['password'])) echo "<div class='error'>".htmlspecialchars($errors['password'])."</div>"; ?>
        </div>
        <div style="flex:1">
          <label for="confirm_password">Konfirmasi Password</label>
          <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password">
          <?php if (!empty($errors['confirm_password'])) echo "<div class='error'>".htmlspecialchars($errors['confirm_password'])."</div>"; ?>
        </div>
      </div>

      <label for="phone">Telepon (opsional)</label>
      <input id="phone" name="phone" type="text" value="<?=$values['phone']?>">
      <?php if (!empty($errors['phone'])) echo "<div class='error'>".htmlspecialchars($errors['phone'])."</div>"; ?>

      <label for="birthdate">Tanggal Lahir (YYYY-MM-DD)</label>
      <input id="birthdate" name="birthdate" type="date" value="<?=$values['birthdate']?>">
      <?php if (!empty($errors['birthdate'])) echo "<div class='error'>".htmlspecialchars($errors['birthdate'])."</div>"; ?>

      <label for="avatar">Avatar (jpg/png/webp max 2MB, opsional)</label>
      <input id="avatar" name="avatar" type="file" accept="image/*">
      <?php if (!empty($errors['avatar'])) echo "<div class='error'>".htmlspecialchars($errors['avatar'])."</div>"; ?>

      <button class="btn" type="submit">Daftar</button>
    </form>
  </div>

  <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      const errors = [];
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const pwd = document.getElementById('password').value;
      const confirm = document.getElementById('confirm_password').value;

      if (name.length < 3) errors.push("Nama minimal 3 karakter.");
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push("Email terlihat tidak valid.");
      if (pwd.length < 8) errors.push("Password minimal 8 karakter.");
      if (!(/[A-Z]/.test(pwd) && /[0-9]/.test(pwd))) errors.push("Password harus mengandung 1 huruf besar dan 1 angka.");
      if (pwd !== confirm) errors.push("Konfirmasi password tidak cocok.");

      if (errors.length) {
        e.preventDefault();
        alert(errors.join("\\n"));
      }
    });
  </script>
</body>
</html>
