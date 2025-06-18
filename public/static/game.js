//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

let token = null,
  startBtn,
  goDiv,
  resultDiv,
  formEl,
  timeIn,
  restartBtn,
  statusMessage;

document.addEventListener("DOMContentLoaded", () => {
  startBtn = document.getElementById("startBtn");
  goDiv = document.getElementById("go");
  resultDiv = document.getElementById("result");
  formEl = document.getElementById("saveForm");
  timeIn = document.getElementById("time_ms");
  restartBtn = document.getElementById("restartBtn");
  statusMessage = document.getElementById("statusMessage");

  if (restartBtn) {
    restartBtn.onclick = () => window.location.reload();
  }

  if (startBtn) {
    startBtn.onclick = async () => {
      startBtn.disabled = true;
      if (statusMessage) {
        statusMessage.textContent = "Get ready...";
        statusMessage.classList.remove("hidden");
      }

      const r = await fetch("api/start.php", { method: "POST" });
      const j = await r.json();
      token = j.token;

      setTimeout(async () => {
        fetch("api/go.php", {
          method: "POST",
          body: new URLSearchParams({ token }),
        }).catch(console.error);

        goDiv.style.display = "block";
        pollBeam(); // start polling once GO! is visible
        if (statusMessage) {
          statusMessage.classList.add("hidden");
          statusMessage.textContent = "";
        }
      }, j.delay * 1000);
    };
  }

  async function checkDevice() {
    console.log("checkDevice() running");
    try {
      let res = await fetch("heartbeat.json", { cache: "no-store" });
      if (!res.ok) throw new Error("bad status" + res.status);
      let { ts } = await res.json();
      let age = Date.now() / 1000 - ts;

      console.log("Heartbeat ts=", ts, "age=", age); // DEBUG

      let el = document.getElementById("ds-text");

      if (age < 15) {
        el.textContent = "🟢 Online";
        if (startBtn) {
        startBtn.disabled = false;
        startBtn.classList.remove("disabled");
        }
      } else {
        if (startBtn) {
          startBtn.disabled = true;
          startBtn.classList.add("disabled");
        }
        el.textContent = "🔴 Offline";
      }
    } catch {
      document.getElementById("ds-text").textContent = "⚠️ Error";
    }
  }
  // check loop
  setInterval(checkDevice, 5000);
  checkDevice();
});

async function saveScore(e) {
  e.preventDefault();
  const data = new URLSearchParams(new FormData(formEl));
  const res = await fetch("api/record.php", { method: "POST", body: data });
  if (!res.ok) {
    alert("Save failed: " + (await res.text()));
    return;
  }
  location.reload();
}

// Called after GO! is displayed, keep trying to fetch results/<token>.json until it appears
async function pollBeam() {
  try {
    let res = await fetch(`results/${token}.json`, { cache: "no-store" });
    if (!res.ok) throw new Error("not ready");
    let j = await res.json();
    beamBroken(j.time_ms);
  } catch (e) {
    setTimeout(pollBeam, 200);
  }
}

function beamBroken(ms) {
  if (ms < 100) {
    alert("Oops-your time was inhumanly fast! (" + ms + "ms). Try again!");
    window.location.reload();
    return;
  }

  if (statusMessage) {
    statusMessage.classList.add("hidden");
    statusMessage.textContent = "";
  }

  fetch("api/record-play.php", {
    method: "POST",
    body: new URLSearchParams({ time_ms: ms }),
  }).catch(() => {
    // ignore
  });

  goDiv.style.display = "none";
  resultDiv.textContent = `Your time: ${ms} ms`;
  timeIn.value = ms;
  formEl.style.display = "block"; // Save button shown

  if (startBtn) startBtn.style.display = "none";
  if (restartBtn) restartBtn.style.display = "inline-block";

  token = null;
}
