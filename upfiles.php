<?php
// Path to the uploads folder
$uploadDir = __DIR__ . '/uploads';
$files = [];

if (is_dir($uploadDir)) {
    $items = array_diff(scandir($uploadDir), ['.', '..']);
    foreach ($items as $item) {
        $filePath = $uploadDir . '/' . $item;
        if (is_file($filePath)) {
            $files[] = [
                'name' => $item,
                'size' => round(filesize($filePath) / 1024, 2) . ' KB',
                'date' => date("Y-m-d H:i:s", filemtime($filePath)),
                'link' => 'uploads/' . rawurlencode($item)
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shared Folder | Info.SundarRajan.org</title>
  <style>
    /* ====== GLOBAL STYLES ====== */
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #f5f8fd;
      margin: 0;
      padding: 0;
      color: #222;
    }

    header {
      background: linear-gradient(135deg, #1a73e8, #0057b7);
      color: #fff;
      padding: 20px 40px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    header h1 {
      margin: 0;
      font-size: 24px;
      letter-spacing: 1px;
    }

    main {
      max-width: 1000px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    h2 {
      color: #1a73e8;
      margin-bottom: 20px;
      font-size: 22px;
      border-bottom: 2px solid #1a73e8;
      display: inline-block;
      padding-bottom: 5px;
    }

    /* ====== TABLE STYLES ====== */
    .file-list-container {
      margin-top: 10px;
      overflow-x: auto;
    }

    table.file-table {
      width: 100%;
      border-collapse: collapse;
      min-width: 600px;
    }

    table.file-table th,
    table.file-table td {
      padding: 12px 14px;
      border-bottom: 1px solid #e9eef5;
      text-align: left;
      font-size: 15px;
    }

    table.file-table th {
      background: #f4f8ff;
      color: #1a1a1a;
      font-weight: 600;
    }

    table.file-table tr:hover {
      background-color: #f8fbff;
    }

    /* ====== DOWNLOAD BUTTON ====== */
    .download-btn {
      background: #1a73e8;
      color: #fff;
      text-decoration: none;
      padding: 6px 14px;
      border-radius: 6px;
      transition: 0.25s;
      font-size: 14px;
    }

    .download-btn:hover {
      background: #0c5dc0;
    }

    /* ====== FOOTER ====== */
    footer {
      text-align: center;
      padding: 20px;
      margin-top: 40px;
      color: #777;
      font-size: 14px;
    }

    /* ====== RESPONSIVE ====== */
    @media (max-width: 768px) {
      main {
        margin: 20px;
        padding: 20px;
      }
      table.file-table th, table.file-table td {
        font-size: 14px;
      }
      .download-btn {
        padding: 5px 10px;
        font-size: 13px;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>Shared Folder</h1>
  </header>

  <main>
    <h2>📂 Uploaded Files</h2>
    <div class="file-list-container">
      <table class="file-table">
        <thead>
          <tr>
            <th>File Name</th>
            <th>Size</th>
            <th>Last Modified</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($files)): ?>
            <tr><td colspan="4" style="text-align:center; color:#888;">No files uploaded yet.</td></tr>
          <?php else: ?>
            <?php foreach ($files as $f): ?>
              <tr>
                <td><?= htmlspecialchars($f['name']) ?></td>
                <td><?= htmlspecialchars($f['size']) ?></td>
                <td><?= htmlspecialchars($f['date']) ?></td>
                <td><a href="<?= htmlspecialchars($f['link']) ?>" download class="download-btn">Download</a></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

  <footer>
    © <?= date('Y') ?> SundarRajan.org — All Rights Reserved
  </footer>
</body>
</html>
