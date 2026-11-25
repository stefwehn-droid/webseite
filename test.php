<?php $file = '/mnt/fotoalbum/processed/testfile.txt'; if (is_writable($file)) { echo "Das Verzeichnis ist beschreibbar.";
} else {
    echo "Das Verzeichnis ist nicht beschreibbar.";
}
?>
