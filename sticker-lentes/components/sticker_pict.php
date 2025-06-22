<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captura de Sticker de Lente</title>
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <?php
    if (!$_POST) {
        $p_id = $_POST['p_id'] ?? 'lenstick_';
    ?>
        <div class="container">
            <label for="capture">Presione espacio para capturar...</label>
            <br>
            <video id="video" autoplay playsinline></video>
            <div class="frame">
                <div class="corner"></div>
            </div>
            <img id="stickerFoto" onclick="location.href = '<?php echo $Sticker_Foto ?>'" src="<?php echo $Sticker_Foto ?>" alt="Foto del Sticker" />
            <br>
            <button type="button" id="capture">📷</button>
            <button style="margin-left: 80px;" type="button" id="selectFile" class="file-selector-button">📝</button>
            <br>

            <form id="WM2" name="WM2" method="post" enctype="multipart/form-data" action="sticker_pict.php">
                <input type="file" name="archivo1" id="fileInput" style="display: none;" />
                <input name="p_id" type="hidden" id="p_id" value="<?php echo $p_id ?>" />
                <?php
                if (isset($noRecargar)) {
                    echo '<input name="noRecargar" type="hidden" id="noRecargar" value="1" />';
                }
                ?>
                <input name="foto_old" type="hidden" id="foto_old" value="<?php echo $P_Foto['root'] ?>" />
                <button id="btn_aceptar" type="submit" name="Submit">Aceptar</button>
                <?php
                if (isset($F)) {
                    echo "<input name=\"F\" type=\"hidden\" value=\"1\" />";
                }
                ?>
                <a id="btn_cancelar" href="action.php" onclick="window.close()">Cancelar</a>
            </form>
        </div>

        <!-- <script lang="JavaScript" src="./pict.js"></script> -->

    <?php
    } else {
        if (isset($_FILES['archivo1']) && $_FILES['archivo1']['size'] > 0) {
            $pathImage = "../imagenes/Sticker/";
            $p_id = $_POST['p_id'] ?? 'lenstick_';
            $Pname = date("HisYmd");
            $ext1 = pathinfo($_FILES['archivo1']['name'], PATHINFO_EXTENSION);
            $ext1 = strtolower($ext1);
            $NewName = $p_id . $Pname . '.' . $ext1;
            $uploadPath = $pathImage . $NewName;

            // Valid extensions
            $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];

            if (in_array($ext1, $allowedExtensions)) {
                // Create folder if it doesn't exist
                if (!file_exists($pathImage)) {
                    mkdir($pathImage, 0777, true);
                }

                if (move_uploaded_file($_FILES['archivo1']['tmp_name'], $uploadPath)) {
                    // ✅ File uploaded successfully
                    // You can now do DB queries here if needed

                    // Optional: delete old photo
                    if (!empty($_POST['foto_old']) && file_exists($_POST['foto_old'])) {
                        unlink($_POST['foto_old']);
                    }

                    // Optional: DB insert/update (commented for now)
                    /*
                    $DF = "DELETE FROM paciente_foto WHERE Paciente_Id='$p_id'";
                    mysqli_query($_con, $DF) or die();

                    $PF = "UPDATE pacientes SET Foto=1 WHERE Id='$p_id'";
                    mysqli_query($_con, $PF) or die();

                    $PM = "INSERT INTO paciente_foto SET Paciente_Id='$p_id', root='$uploadPath'";
                    mysqli_query($_con, $PM) or die();
                    */

                    // Refresh the parent window and close this one
                    if (isset($_POST['noRecargar'])) {
                        echo "<script>window.opener.recargarFotoPaciente(); window.close();</script>";
                    } else {
                        echo "<script>window.opener.document.location.reload(); window.close();</script>";
                    }
                    exit;
                } else {
                    echo "❌ Error al mover la imagen. Verifica permisos.<br><a href=\"#\" onClick=\"history.back();\">Volver</a>";
                    exit;
                }
            } else {
                echo "❌ Imagen inválida (solo JPG, PNG, GIF)<br><a href=\"#\" onClick=\"history.back();\">Volver</a>";
                exit;
            }
        }
    }
    ?>

    <!-- <script lang="javaScript" src="../setup/actions.js"></script> -->
    <script lang="javaScript" src="../js/sticker_pict.js"></script>
</body>

</html>