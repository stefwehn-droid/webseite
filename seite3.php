<?php function generateResponsiveImage($basename, $alt) {
  // URL-Pfad, der auf den Symlink verweist
  $urlPath = "/fotos/other";
  
  // Bildpfad für das Bild mit .jpg-Endung
  $imageUrl = "{$urlPath}/{$basename}.jpg";
  
  // Bild mit Vergrößerungsfunktion
  echo "<div class='image-container'> <img src=\"{$imageUrl}\" alt=\"{$alt}\" class=\"gallery-image\" data-fullsize=\"{$imageUrl}\" onclick=\"openLightbox(this)\" /> </div>";
}
?> <!DOCTYPE html> <html lang="de"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Bildergalerie – Stefan Wehner</title> <style> 
    body {
      font-family: system-ui, sans-serif; background-color: #111; color: #eee; padding: 2rem; margin: 0;
    }
    h1 { text-align: center; margin-bottom: 2rem;
    }
    .gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; padding: 0 1rem;
    }
    img { width: 100%; height: auto; border-radius: 0.5rem; box-shadow: 0 0 10px rgba(0,0,0,0.5); transition: transform 0.3s ease; cursor: pointer;
    }
    img:hover { transform: scale(1.03);
    }
    .format-buttons { text-align: center; margin-bottom: 20px;
    }
    .format-buttons button { background-color: #444; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 0 10px; cursor: pointer;
    }
    .format-buttons button:hover { background-color: #666;
    }
    .back-button { background-color: #444; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;
    }
    .back-button:hover { background-color: #666;
    }
    .button-container { text-align: center; margin-bottom: 20px;
    }
    .button-container button { margin: 5px;
    }
    /* Lightbox Styles */ .lightbox { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); justify-content: center; align-items: center; 
      z-index: 999;
    }
    .lightbox img { max-width: 90%; max-height: 90%; object-fit: contain;
    }
    .lightbox .close-btn, .lightbox .prev-btn, .lightbox .next-btn { position: absolute; top: 20px; color: white; font-size: 30px; cursor: pointer; z-index: 1000;
    }
    .lightbox .close-btn { top: 10px; right: 20px;
    }
    .lightbox .prev-btn { left: 20px; top: 50%; transform: translateY(-50%);
    }
    .lightbox .next-btn { right: 20px; top: 50%; transform: translateY(-50%);
    }
    /* Pfeile und Escape */ .lightbox .prev-btn:hover, .lightbox .next-btn:hover, .lightbox .close-btn:hover { color: #ddd;
    }
  </style> </head> <body> <!-- Container für die Buttons --> <div class="button-container"> <button class="back-button" onclick="window.location.href='/index.php';">Zurück zur 
    Startseite</button> <button id="btn-all">Alle Bilder</button> <button id="btn-portrait">Hochformat</button> <button id="btn-landscape">Querformat</button>
  </div> <hr><br>
  
  <div class="gallery" id="image-gallery"> <?php
      // Server-Pfad zu den Bildern
      $serverPath = "/mnt/fotoalbum/other";
      // Überprüfen, ob das Verzeichnis existiert
      if (is_dir($serverPath)) {
        // Durchsuchen des Verzeichnisses nach allen .jpg-Bildern
        $files = glob("{$serverPath}/*.jpg");
        
        // Überprüfen, ob glob() Bilder findet
        if ($files === false || empty($files)) { echo "<p>Keine Bilder gefunden. Überprüfe den Pfad und die Bildformate.</p>";
        } else {
          foreach ($files as $fullpath) { $filename = basename($fullpath);
            // Entfernen des .jpg Suffixes, um den Basename zu extrahieren
            $basename = pathinfo($filename, PATHINFO_FILENAME); generateResponsiveImage($basename, ucfirst($basename));
          }
        }
      } else {
        echo "<p>Fehler: Das Verzeichnis {$serverPath} wurde nicht gefunden.</p>";
      }
    ?> </div> <!-- Lightbox --> <div class="lightbox" id="lightbox"> <span class="close-btn" onclick="closeLightbox()">X</span> <span class="prev-btn" onclick="prevImage()">&#10094;</span> 
    <span class="next-btn" onclick="nextImage()">&#10095;</span> <img id="lightbox-img" src="" alt="">
  </div> <script>
    // Filter-Funktion für alle Bilder, Hochformat und Querformat
    const allButton = document.getElementById('btn-all'); const portraitButton = document.getElementById('btn-portrait'); const landscapeButton = document.getElementById('btn-landscape'); 
    const gallery = document.getElementById('image-gallery'); allButton.addEventListener('click', () => {
      filterImages('all');
    });
    portraitButton.addEventListener('click', () => { filterImages('portrait');
    });
    landscapeButton.addEventListener('click', () => { filterImages('landscape');
    });
    function filterImages(format) { const allImages = document.querySelectorAll('.gallery img'); allImages.forEach(img => { const width = img.naturalWidth; const height = img.naturalHeight;
        
        if (format === 'portrait') {
          // Nur Hochformat-Bilder anzeigen
          if (height > width) { img.style.display = 'block';
          } else {
            img.style.display = 'none';
          }
        } else if (format === 'landscape') {
          // Nur Querformat-Bilder anzeigen
          if (width > height) { img.style.display = 'block';
          } else {
            img.style.display = 'none';
          }
        } else {
          // Alle Bilder anzeigen
          img.style.display = 'block';
        }
      });
    }
    // Lightbox Funktionen
    let currentImageIndex = -1; let images = []; function openLightbox(image) { images = Array.from(document.querySelectorAll('.gallery-image')); currentImageIndex = images.indexOf(image); 
      const lightbox = document.getElementById('lightbox'); const lightboxImg = document.getElementById('lightbox-img'); lightboxImg.src = image.dataset.fullsize; lightbox.style.display = 
      'flex';
    }
    function closeLightbox() { document.getElementById('lightbox').style.display = 'none';
    }
    function nextImage() { currentImageIndex = (currentImageIndex + 1) % images.length; const nextImage = images[currentImageIndex]; document.getElementById('lightbox-img').src = 
      nextImage.dataset.fullsize;
    }
    function prevImage() { currentImageIndex = (currentImageIndex - 1 + images.length) % images.length; const prevImage = images[currentImageIndex]; 
      document.getElementById('lightbox-img').src = prevImage.dataset.fullsize;
    }
    // Tastaturnavigation
    document.addEventListener('keydown', (e) => { if (document.getElementById('lightbox').style.display === 'flex') { if (e.key === 'ArrowRight') { nextImage();
        } else if (e.key === 'ArrowLeft') {
          prevImage();
        } else if (e.key === 'Escape') {
          closeLightbox();
        }
      }
    });
  </script> </body> </html>
