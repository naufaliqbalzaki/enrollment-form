<?php
/**
 * Membersihkan input dari karakter berbahaya & XSS
 */
function sanitize($input) {
  return htmlspecialchars(strip_tags(trim($input)));
}

/**
 * Sanitasi seluruh array data POST
 */
function sanitize_array($data) {
  $result = [];
  foreach ($data as $key => $value) {
    $result[$key] = sanitize($value);
  }
  return $result;
}

/**
 * Validasi email (contoh fungsi validasi)
 */
function is_valid_email($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Upload file aman ke folder tertentu
 * @return string|null - path file jika berhasil, null jika gagal
 */
function upload_file($field_name, $target_dir = "../uploads/") {
  if (!isset($_FILES[$field_name]) || $_FILES[$field_name]['error'] !== UPLOAD_ERR_OK) {
    return null;
  }

  $tmp_name = $_FILES[$field_name]['tmp_name'];
  $original_name = basename($_FILES[$field_name]['name']);
  $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
  $allowed_ext = ['jpg', 'jpeg', 'png'];

  if (!in_array($extension, $allowed_ext)) {
    return null;
  }

  // Generate nama unik
  $new_name = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $original_name);
  $upload_path = $target_dir . $new_name;

  if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
  }

  if (move_uploaded_file($tmp_name, $upload_path)) {
    return $upload_path;
  }

  return null;
}

/**
 * Redirect ke halaman lain
 */
function redirect($url) {
  header("Location: $url");
  exit();
}
?>
