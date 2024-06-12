<div class="modal fade" 
        data-bs-backdrop="static" 
        data-bs-keyboard="false" 
        id="imageCropperModal" 
        tabindex="-1" 
        role="dialog" 
        aria-labelledby="imageCropperModalTitle" 
        aria-hidden="true"
    >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageCropperModalTitle">Image Cropper</h5>
        <button type="button" id="imageCropperClose" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
          <div id="cropContainer">
              <img id="imageToCrop" style="max-width: 100%;">
          </div>
          <div class="row p-0 mx-0 mt-2 mb-0">
            <canvas id="canvas" class="w-100" style="display: none;"></canvas>
          </div>
      </div>
      <div class="modal-footer" id="imageCropFooter">
        <button type="button" class="btn btn-success" style="display: none;" id="recropButton">Re Crop</button>
        <button type="button" class="btn btn-primary" id="cropButton">Crop</button>
        <button type="button" class="btn btn-primary" style="display: none;"  id="finishButton">Done</button>
      </div>
    </div>
  </div>
</div>
<script>
  let cropper;
  const image = $("#imageToCrop")
  const cropperModal = $('#imageCropperModal');
  const canvas = document.getElementById('canvas')
  const recropButton = document.getElementById("recropButton");
  const cropButton = document.getElementById("cropButton");
  var previewImageUrl = "";
  var onModalClose = () => {}
  let imgBlob = null;
  var imageWidth = 512;
  var imageHeight = 512;
  var cropAspectRatio = 1;

  function showModal(imageUrl) {
    image.attr('src', imageUrl)
    cropperModal.modal('show')
    // cropperModal.modal({
    //   backdrop: 'static',
    //   keyboard: false
    // })
  }

  function setCroppedImageWidth(width) {
    imageWidth = width
  }

  function setCroppedImageHeight(height) {
    imageHeight = height
  }

  function closeModal() {
    cropperModal.modal('hide')
  }

  function reset() {
    $("#cropContainer").css('display', 'block')
    $("#cropButton").css('display', 'block')
    $("#recropButton").css('display', 'none')
    $("#finishButton").css('display', 'none')
    canvas.style.display = 'none';
    imgBlob = null;
  }

  function getCroppedImageBlob() {
    return imgBlob;
  }

  function setOnModalClose(fn) {
    onModalClose = fn
  }

  function getPreviewImageUrl() {
    return previewImageUrl
  }

  function setCropAspectRatio(value) {
    cropAspectRatio = value
  }

  $(document).ready(function() {
    $("#imageCropperClose").on('click', function() {
        closeModal()
    })

    cropperModal.on('shown.bs.modal', function () {
      reset()
      cropper = new Cropper(document.getElementById('imageToCrop'), {
        aspectRatio: cropAspectRatio,
        viewMode: 1,
      });
    }).on('hidden.bs.modal', function () {
      cropper.destroy();
      cropper = null;
    });

    $("#recropButton").on("click", function() {
      reset()
    })

    $("#cropButton").on('click', function() {
      const croppedCanvas = cropper.getCroppedCanvas();
      canvas.style.display = 'block';
      const context = canvas.getContext('2d');
      canvas.width = croppedCanvas.width;
      canvas.height = croppedCanvas.height;
      context.clearRect(0, 0, canvas.width, canvas.height);
      context.drawImage(croppedCanvas, 0, 0);
      $("#cropContainer").css('display', 'none')
      const webpImage = canvas.toDataURL('image/webp')
      const ctx2 = canvas.getContext('2d')
      ctx2.clearRect(0, 0, canvas.width, canvas.height)
      canvas.width = parseInt(imageWidth);
      canvas.height = parseInt(imageHeight);
      var newImage = new Image();
      newImage.onload = () => {
        ctx2.drawImage(newImage, 0, 0, parseInt(imageWidth), parseInt(imageHeight))
      };
      newImage.src = webpImage;
      recropButton.style.display = 'block'
      cropButton.style.display = 'none'
      $("#finishButton").css('display', 'block')
    })
    $("#finishButton").on('click', function() {
      canvas.toBlob((blob) => {
        imgBlob = blob
      })
      previewImageUrl = canvas.toDataURL("image/webp")
      onModalClose()
      cropperModal.modal('hide')
    })
  });

</script>