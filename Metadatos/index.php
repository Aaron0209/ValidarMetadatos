<!DOCTYPE html>
<html>
<head>
    <title>Validar Metadatos</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <h1>Validar Metadatos</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $file = $_FILES["archivo"];

        if ($file["size"] > 5000000) {
            echo "<p>El archivo es demasiado grande. Por favor, sube un archivo de menos de 5 MB.</p>";
        } else {
            $nombre_archivo = $file["name"];
            $tipo_archivo = $file["type"];
            $tamano_archivo = $file["size"];
            $error_archivo = $file["error"];

            // Verificar si el archivo se subió correctamente
            if ($error_archivo > 0) {
                echo "<p>Se produjo un error al subir el archivo. Código de error: " . $error_archivo . "</p>";
            } else {
                echo "<h2>Metadatos del archivo:</h2>";
                echo "<ul>";
                echo "<li><strong>Nombre:</strong> " . $nombre_archivo . "</li>";
                echo "<li><strong>Tipo:</strong> " . $tipo_archivo . "</li>";
                echo "<li><strong>Tamaño:</strong> " . ($tamano_archivo / 1024) . " KB</li>";

                // Obtener los metadatos del archivo
                if (function_exists('exif_read_data')) {
                    $metadatos = exif_read_data($file["tmp_name"]);

                    if (isset($metadatos) && $metadatos !== false) {
                        $cantidad_metadatos = count($metadatos);
                        echo "<li><strong>Cantidad de metadatos:</strong> " . $cantidad_metadatos . "</li>";
                    } else {
                        echo "<li><strong>Cantidad de metadatos:</strong> No se encontraron metadatos.</li>";
                    }
                } else {
                    echo "<li><strong>Cantidad de metadatos:</strong> La función exif_read_data() no está disponible en este servidor.</li>";
                }

                echo "</ul>";

                // Verificar la extensión del archivo
                $extension_archivo = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
                if (!in_array($extension_archivo, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
                    echo "<p>El archivo debe ser un PDF, DOC, DOCX, XLS, XLSX, PPT o PPTX. Por favor, sube un archivo con una de las siguientes extensiones: .pdf, .doc, .docx, .xls, .xlsx, .ppt o .pptx.</p>";
                } else {
                    echo "<p>El archivo es un archivo válido.</p>";
                }
            }
        }
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="archivo">Selecciona un archivo:</label>
        <input type="file" name="archivo" id="archivo"><br>
        <input type="submit" value="Validar">
    </form>
</body>
</html>
