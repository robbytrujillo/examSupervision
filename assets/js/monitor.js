document.addEventListener("visibilitychange", function () {
  if (document.hidden) {
    logViolation("tab_switch");
  }
});

document.addEventListener("fullscreenchange", function () {
  if (!document.fullscreenElement) {
    logViolation("exit_fullscreen");
  }
});

document.addEventListener("contextmenu", function (e) {
  e.preventDefault();
});

document.onkeydown = function (e) {
  if (e.keyCode == 123) return false;
};

function logViolation(type) {
  fetch("log_violation.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "type=" + type,
  });
}
