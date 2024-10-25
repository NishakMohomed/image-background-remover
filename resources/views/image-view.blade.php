<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Background remover</title>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Styles / Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Webcamjs for allowing sites to access device webcam -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
    integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
  <section class="bg-white">
    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6 ">
      <div class="mx-auto max-w-screen-sm text-center mb-8 lg:mb-16">
        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900">Background remover</h2>
        <p class="font-light text-gray-500 lg:mb-16 sm:text-xl">Erase image backgrounds for free</p>
      </div>
      <div class="grid lg:grid-cols-3 md:grid-cols-3 grid-cols-1 place-items-center">

        <!-- --------------------------------------webcam section----------------------------------- -->
        <div class="text-center">
          <p class="font-semibold mb-2">Take a picture</p>
          <div id="webcam" class="webcam-container h-auto max-w-full rounded-lg">
            <img id="cameraThumbnail" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png"
              alt="Jese Avatar">
          </div>

          <button type="button" onclick="takeSnapshot()" id="captureButton"
            class="hidden py-2.5 px-5 me-2 my-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Capture</button>

          <button type="button" id="activateCameraButton"
            class="py-2.5 px-5 me-2 my-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Switch
            on camera</button>

          <!-- ------------------------------------Submit button--------------------------------- -->
          <form id="captureForm" method="POST" action="">
            @csrf
            <input type="hidden" name="image" id="captured_image">
            <button type="submit" id="removeBackgroundButton"
              class="hidden focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 my-3">Remove
              bg</button>
          </form>

        </div>

        <!-- --------------------------------------Center text----------------------------------- -->

        <div class="text-center font-semibold text-2xl text-gray-500 py-5">OR</div>

        <!-- --------------------------------------Upload section----------------------------------- -->
        <div class="text-center">
          <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <p class="font-semibold mb-2">Upload a file</p>
            <div class="flex items-center justify-center w-full">
              <label for="dropzone-file"
                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                  <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                  </svg>
                  <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to
                      upload</span> or drag and drop</p>
                  <p class="text-xs text-gray-500">PNG or JPG</p>
                </div>
                <input id="dropzone-file" type="file" id="imageUpload" name="image" accept="image/*" class="hidden" />
              </label>
            </div>

            <button type="button"
              class="py-2.5 px-5 me-2 my-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Upload</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <script>
    const cameraThumbnail = document.getElementById('cameraThumbnail');
    const activateCameraButton = document.getElementById('activateCameraButton');
    const captureButton = document.getElementById('captureButton');
    const removeBackgroundButton = document.getElementById('removeBackgroundButton');

    // Webcam config
    Webcam.set({
      width: 320,
      height: 240,
      image_format: 'jpeg',
      jpeg_quality: 100
    });

    // Function to activate the camera on button click
    activateCameraButton.addEventListener('click', function () {
      // Hide the activate button and show the capture button
      activateCameraButton.style.display = 'none';
      removeBackgroundButton.style.display = 'none';
      captureButton.style.display = 'inline-flex';

      // Hide img and activate webcam
      cameraThumbnail.style.display = 'none';
      Webcam.attach('#webcam');
    });

    // Turn off the camera without removing the DOM element
    function turnOffCamera() {
      if (Webcam.stream) {
        Webcam.stream.getTracks().forEach(track => track.stop());
      }
    }

    function takeSnapshot() {
      Webcam.snap(function (data_uri) {
        document.getElementById('webcam').innerHTML =
          '<img src="' + data_uri + '"/>';
        turnOffCamera();

        let raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
        document.getElementById('captured_image').value = raw_image_data;
      });

      activateCameraButton.style.display = 'inline-flex';
      captureButton.style.display = 'none';
      removeBackgroundButton.style.display = 'inline-flex';

      activateCameraButton.innerHTML = "Retake"
    }
  </script>
</body>

</html>