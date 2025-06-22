function reproducirSonido(rutaSonido) {
  var audio = new Audio(rutaSonido);
  audio.play();
}
document.addEventListener("DOMContentLoaded", function () {
  const video = document.getElementById("video");
  const canvas = document.createElement("canvas");
  const captureButton = document.getElementById("capture");
  const fileInput = document.getElementById("fileInput");
  const stickerFoto = document.getElementById("stickerFoto");
  const selectFileButton = document.getElementById("selectFile");

  // Acceder a la cámara
  navigator.mediaDevices
    .getUserMedia({
      video: true,
    })
    .then((stream) => {
      video.srcObject = stream;
    })
    .catch((error) => {
      console.error("Error al acceder a la cámara:", error);
      alert("No se pudo acceder a la cámara o intento subir un archivo vacío.");
      window.location.replace("sticker_pict.php");
    });

  // Función para capturar imagen
  function captureImage() {
    //reproducirSonido("sounds/shutter.mp3");
    if (video.srcObject) {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const context = canvas.getContext("2d");
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      // Convertir canvas a Blob y asignar al input file
      canvas.toBlob((blob) => {
        if (blob) {
          const file = new File([blob], "webcam_photo.png", {
            type: "image/png",
          });
          const dataTransfer = new DataTransfer();
          dataTransfer.items.add(file);
          fileInput.files = dataTransfer.files;
          const newImageURL = URL.createObjectURL(file);
          stickerFoto.src = newImageURL;
        } else {
          alert("Error al capturar la imagen.");
        }
      }, "image/png");
    } else {
      alert("No hay un stream de video disponible.");
    }
  }

  // Evento click en botón capturar
  captureButton.addEventListener("click", captureImage);

  // Captura con la barra espaciadora
  document.addEventListener("keydown", (event) => {
    if (event.code === "Space") {
      event.preventDefault();
      captureImage();
    }
  });

  // Evento click en botón seleccionar archivo
  selectFileButton.addEventListener("click", () => {
    fileInput.click();
  });

  // Evento cambio en el input file
  fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
      const file = fileInput.files[0];
      const newImageURL = URL.createObjectURL(file);
      stickerFoto.src = newImageURL;
    }
  });
});
