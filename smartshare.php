<?php
// ==================== PHP SETTINGS FOR LARGE UPLOADS ====================
ini_set('upload_max_filesize', '5120M');
ini_set('post_max_size', '5130M');
ini_set('memory_limit', '4096M');
ini_set('max_execution_time', 0);
ini_set('max_input_time', 0);

// ==================== FILE UPLOAD HANDLER ====================
$uploadDir = __DIR__ . "/uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $targetPath = $uploadDir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $uploadMsg = "✅ File uploaded successfully: " . htmlspecialchars($file['name']);
    } else {
        $uploadMsg = "❌ Error uploading file.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shared Files | SkillBuilder Academy</title>
  <link rel="icon" type="image/png" href="images/logo.png" />
  <style>
    body { margin: 0; font-family: 'Poppins', sans-serif; background: #f4f9ff; color: #333; }
    header, footer { background: #0077cc; color: white; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 1000; }
    .logo-container { display: flex; align-items: center; }
    .logo-container img { height: 40px; margin-right: 10px; }
    .logo-container span { font-size: 20px; font-weight: bold; }
    nav { display: flex; gap: 15px; }
    nav a { color: white; text-decoration: none; font-weight: 500; }
    nav a:hover { text-decoration: underline; }
    .menu-toggle { display: none; flex-direction: column; cursor: pointer; }
    .menu-toggle div { width: 25px; height: 3px; background: white; margin: 4px 0; }
    @media (max-width: 768px) {
      nav { display: none; flex-direction: column; background: #005fa3; position: absolute; top: 60px; right: 0; width: 200px; padding: 10px; }
      nav.show { display: flex; }
      .menu-toggle { display: flex; }
    }

    .page-title { text-align: center; padding: 60px 20px 20px; color: #0077cc; }
    .upload-container {
      margin: 20px auto;
      background: white;
      border-radius: 12px;
      padding: 30px;
      max-width: 800px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    input[type=file] {
      border: 1px solid #ccc;
      background: #fff;
      color: #333;
      padding: 10px;
      border-radius: 8px;
      width: 100%;
      margin-bottom: 15px;
    }
    button {
      background-color: #0077cc;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    button:hover { background-color: #005fa3; }
    .msg { margin-top: 10px; color: green; font-weight: 500; }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }
    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th { background-color: #0077cc; color: white; }
    a.download {
      color: #0077cc;
      text-decoration: none;
      font-weight: 500;
    }
    a.download:hover { text-decoration: underline; }
    footer { text-align: center; margin-top: 60px; }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header>
    <div class="logo-container">
      <img src="images/logo.png" alt="Logo">
      <span>SkillBuilder Academy</span>
    </div>
    <nav id="navbar">
      <a href="index.html">Home</a>
      <a href="about.html">About</a>
      <a href="projects.html">Projects</a>
      <a href="gallery.php">Gallery</a>
      <a href="contact.html">Contact</a>
      <a href="sharedfolder.php" style="text-decoration: underline;">Shared Files</a>
    </nav>
    <div class="menu-toggle" onclick="document.getElementById('navbar').classList.toggle('show')">
      <div></div><div></div><div></div>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <section class="page-title">
    <h1>📁 Shared Files</h1>
    <p>Upload and access files directly from your shared folder</p>
  </section>

  <div class="upload-container">
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="file" required>
      <button type="submit">Upload File</button>
    </form>
    <?php if (isset($uploadMsg)) echo "<div class='msg'>$uploadMsg</div>"; ?>

    <h2 style="margin-top:30px; color:#0077cc;">Uploaded Files</h2>
    <table>
      <tr>
        <th>File Name</th>
        <th>Size</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
      <?php
      $files = array_diff(scandir($uploadDir), ['.', '..']);
      usort($files, function($a, $b) use ($uploadDir) {
          return filemtime($uploadDir . $b) - filemtime($uploadDir . $a);
      });
      if (count($files) === 0) {
          echo "<tr><td colspan='4'>No files uploaded yet.</td></tr>";
      } else {
          foreach ($files as $file) {
              $filePath = $uploadDir . $file;
              $fileSize = round(filesize($filePath) / (1024 * 1024), 2) . " MB";
              $fileDate = date("d M Y H:i", filemtime($filePath));
              echo "<tr>
                      <td>" . htmlspecialchars($file) . "</td>
                      <td>$fileSize</td>
                      <td>$fileDate</td>
                      <td><a class='download' href='uploads/" . urlencode($file) . "' download>⬇ Download</a></td>
                    </tr>";
          }
      }
      ?>
    </table>
  </div>

  <!-- FOOTER -->
  <footer>
    <p>© 2025 Sundar Rajan | SkillBuilder Academy</p>
  </footer>
</body>
</html>
