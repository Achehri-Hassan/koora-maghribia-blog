function updateLiveScores() {
  const now = new Date();

  document.querySelectorAll(".class-match-live").forEach((container) => {
    const startTime = new Date(container.getAttribute("data-start"));
    const endTime = new Date(container.getAttribute("data-end"));
    const statusBadge = container.querySelector(".live-status");
    const matchUrl = container.getAttribute("data-url");

    if (now < startTime) {
      statusBadge.textContent = "لم تبدأ بعد";
      statusBadge.style.backgroundColor = "#1e293b";
      statusBadge.style.animation = "none";
      container.classList.remove("clickable");
      container.onclick = null;

    } else if (now >= startTime && now <= endTime) {
      const diffInSeconds = Math.floor((now - startTime) / 1000);
      let currentMinute = Math.floor(diffInSeconds / 60);
      if (currentMinute < 1) currentMinute = 1;
      if (currentMinute > 90) currentMinute = 90;

      statusBadge.textContent = `مباشر 🔴 د ${currentMinute}`;
      statusBadge.style.backgroundColor = "#ef4444";
      statusBadge.style.animation = "pulse 1.5s infinite";

      container.classList.add("clickable");
      container.onclick = function () {
        location.href = matchUrl;
      };
      
    } else {
      statusBadge.textContent = "انتهت";
      statusBadge.style.backgroundColor = "#22c55e";
      statusBadge.style.animation = "none";

      container.classList.remove("clickable");
      container.onclick = null;
    }

  });
}
updateLiveScores();
setInterval(updateLiveScores, 1000);
