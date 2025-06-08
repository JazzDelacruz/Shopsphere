let captchaText = "";

function generateCaptcha() {
  const canvas = document.getElementById("captchaCanvas");
  const ctx = canvas.getContext("2d");
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  captchaText = "";

  for (let i = 0; i < 5; i++) {
    captchaText += chars.charAt(Math.floor(Math.random() * chars.length));
  }

  ctx.fillStyle = "#f2f2f2";
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  for (let i = 0; i < captchaText.length; i++) {
    ctx.font = `${Math.floor(Math.random() * 10 + 25)}px Arial`;
    ctx.fillStyle = `rgb(${Math.floor(Math.random() * 100)}, ${Math.floor(Math.random() * 100)}, ${Math.floor(Math.random() * 100)})`;
    const x = 20 + i * 25;
    const y = 30 + Math.random() * 10;
    const angle = (Math.random() - 0.5) * 0.5;
    ctx.save();
    ctx.translate(x, y);
    ctx.rotate(angle);
    ctx.fillText(captchaText[i], 0, 0);
    ctx.restore();
  }

  for (let i = 0; i < 30; i++) {
    ctx.beginPath();
    ctx.arc(Math.random() * canvas.width, Math.random() * canvas.height, 1.5, 0, 2 * Math.PI);
    ctx.fillStyle = "#aaa";
    ctx.fill();
  }
}

document.getElementById("signup").addEventListener("submit", function (event) {
  event.preventDefault(); // prevent actual submission

  const userInput = document.getElementById("captchaInput").value.trim();

  if (userInput === captchaText) {
    document.getElementById("key").style.color = "green";
    document.getElementById("key").textContent = "CAPTCHA matched. Submitting...";

    // ✅ Manually submit the form to start.php
    setTimeout(() => {
      document.getElementById("signup").submit(); // <-- only submit if CAPTCHA is correct
    }, 1000);

  } else {
    document.getElementById("key").style.color = "red";
    document.getElementById("key").textContent = "CAPTCHA did not match. Please try again.";
    generateCaptcha(); // Refresh CAPTCHA
  }
});

// Generate CAPTCHA on page load
window.onload = generateCaptcha;