<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery - SkillBuilder Academy</title>
  <link rel="icon" type="image/png" href="images/logo.png">
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
    @media (max-width: 768px) { nav { display: none; flex-direction: column; background: #005fa3; position: absolute; top: 60px; right: 0; width: 200px; padding: 10px; } nav.show { display: flex; } .menu-toggle { display: flex; } }

    .gallery { padding: 40px 20px; max-width: 1200px; margin: auto; }
    .gallery h1 { color: #0077cc; text-align: center; margin-bottom: 30px; }
    .gallery-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; }
    .gallery-grid img { width: 100%; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.3s; }
    .gallery-grid img:hover { transform: scale(1.05); }

    /* Lightbox effect */
    .lightbox { display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); align-items: center; justify-content: center; }
    .lightbox img { max-width: 90%; max-height: 90%; border-radius: 12px; }
    .lightbox:target { display: flex; }
    .close { position: absolute; top: 20px; right: 30px; color: white; font-size: 30px; text-decoration: none; }

    footer { text-align: center; margin-top: 40px; }
  </style>
</head>
<body>
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
      <a href="smartshare.php">Shared Files</a>
    </nav>
    <div class="menu-toggle" onclick="document.getElementById('navbar').classList.toggle('show')">
      <div></div><div></div><div></div>
    </div>
  </header>

  <section class="gallery">
    <h1>Gallery</h1>
    <div class="gallery-grid">
      <?php
        $folder = "gallery/"; // folder name
        $images = glob($folder . "*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}", GLOB_BRACE);
        $count = 1;
        foreach($images as $img){
          echo '<a href="#img'.$count.'"><img src="'.$img.'" alt=""></a>';
          $count++;
        }
      ?>
    </div>
  </section>

  <!-- Lightbox Popups -->
  <?php
    $count = 1;
    foreach($images as $img){
      echo '<div id="img'.$count.'" class="lightbox"><a href="#" class="close">&times;</a><img src="'.$img.'" alt=""></div>';
      $count++;
    }
  ?>

  <footer>
    <p>© 2025 Sundar Rajan | SkillBuilder Academy</p>
  </footer>
</body>
</html>
