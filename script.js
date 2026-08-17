document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("form").forEach(form => {
    form.addEventListener("submit", () => {
      const btn = form.querySelector("button[type=submit], button[name]");
      if (btn) btn.style.opacity = "0.75";
    });
  });
});