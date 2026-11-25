<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Botanical Garden Galerie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #0d0d0d;
      color: #e0e0e0;
    }

    h1 {
      text-align: center;
      margin: 1rem;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 1rem;
      color: #fff;
      text-decoration: none;
      font-size: 1.1rem;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    .gallery {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
      padding: 2rem;
    }

    .gallery-item img {
      width: 100%;
      height: auto;
      border-radius: 8px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
      cursor: pointer;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .gallery-item img:hover {
      transform: scale(1.05);
      box-shadow: 0 12px 30px rgba(255, 255, 255, 0.2);
    }

    .lightbox {
      display: none;
      position: fixed;
      z-index: 1000;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.85);
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    .lightbox-img {
      max-width: 90%;
      max-height: 80%;
      border-radius: 8px;
      box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
    }

    .lightbox .close {
      position: absolute;
      top: 30px;
      right: 40px;
      font-size: 3rem;
      color: white;
      cursor: pointer;
      font-weight: bold;
    }

    .lightbox-nav {
          display: flex;
	        justify-content: space-between;
	        width: 90%;
		      max-width: 1000px;
		      margin-top: 1rem;
		          }

    .lightbox-nav button {
      background: none;
      border: 2px solid #fff;
      color: #fff;
      padding: 0.5rem 1rem;
      font-size: 1rem;
      cursor: pointer;
      border-radius: 5px;
      transition: background 0.2s ease;
    }

        .lightbox-nav button:hover {
	      background: #fff;
	            color: #000;
	          }
      </style>
	      </head>
	      <body>

	        <h1>Botanical Garden</h1>
		  <a class="back-link" href="/index.php">← Zurück zur Startseite</a>

		    <div class="gallery">
		        <?php
    $dir = '/mnt/fotoalbum/botanicalgarden';
        $webPath = '/fotos/botanicalgarden';
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
	    $images = [];

	    foreach (scandir($dir) as $file) {
		          $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
			        if (in_array($ext, $allowed)) {
					        $images[] = $webPath . '/' . htmlspecialchars($file);
						      }
			      }

	        foreach ($images as $index => $src) {
			      echo '<div class="gallery-item">';
			            echo '<img src="' . $src . '" alt="" onclick="openLightbox(' . $index . ')">';
			            echo '</div>';
				        }

	        echo '<script>const imageList = ' . json_encode($images) . ';</script>';
	        ?>
  </div>

	    <!-- Lightbox -->
	      <div id="lightbox" class="lightbox">
	          <span class="close" onclick="closeLightbox()">&times;</span>
		      <img class="lightbox-img" src="" alt="" id="lightboxImage">
		          <div class="lightbox-nav">
			        <button onclick="prevImage()">← Zurück</button>
				      <button onclick="nextImage()">Weiter →</button>
				          </div>
					    </div>

					      <script>
	        let currentIndex = 0;

		    function openLightbox(index) {
			          currentIndex = index;
				        document.getElementById("lightboxImage").src = imageList[currentIndex];
				        document.getElementById("lightbox").style.display = "flex";
					    }

		    function closeLightbox() {
			          document.getElementById("lightbox").style.display = "none";
				      }

		    function nextImage() {
			          currentIndex = (currentIndex + 1) % imageList.length;
				        document.getElementById("lightboxImage").src = imageList[currentIndex];
				      }

		    function prevImage() {
			          currentIndex = (currentIndex - 1 + imageList.length) % imageList.length;
				        document.getElementById("lightboxImage").src = imageList[currentIndex];
				      }

		    document.getElementById("lightbox").addEventListener("click", function(e) {
			          if (e.target === this) closeLightbox();
				      });
		  </script>

</body>
</html>

