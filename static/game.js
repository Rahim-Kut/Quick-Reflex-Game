let token = null,
  startBtn,
  goDiv,
  resultDiv,
  formEl,
  timeIn,
  restartBtn;

document.addEventListener("DOMContentLoaded", () => {
  startBtn = document.getElementById("startBtn");
  goDiv = document.getElementById("go");
  resultDiv = document.getElementById("result");
  formEl = document.getElementById("saveForm");
  timeIn = document.getElementById("time_ms");
  restartBtn = document.getElementById("restartBtn");
  restartBtn.onclick = () => {
    window.location.reload();
  };

  startBtn.onclick = async () => {
    startBtn.disabled = true;
    const r = await fetch("api-start.php", { method: "POST" });
    const j = await r.json();
    token = j.token;
    setTimeout(() => {
      fetch("go-now.php", {
        method: "POST",
        body: new URLSearchParams({ token }),
      }).catch(console.error);
      goDiv.style.display = "block";
      pollBeam(); // start polling once GO! is visible
    }, j.delay * 1000);
  };
});

async function saveScore(e) {
  e.preventDefault();
  const data = new URLSearchParams(new FormData(formEl));
  const res = await fetch("record-game.php", { method: "POST", body: data });
  if (!res.ok) {
    alert("Save failed: " + (await res.text()));
    return;
  }
  // on success we reload
  location.reload();
}

// Called after GO! is displayed:
// keep trying to fetch results/<token>.json until it appears
async function pollBeam() {
  try {
    let res = await fetch(`results/${token}.json`, { cache: "no-store" });
    if (!res.ok) throw new Error("not ready");
    let j = await res.json();
    beamBroken(j.time_ms); // show result, hide GO!, show form
  } catch (e) {
    //  network error or 404 → not ready yet
    setTimeout(pollBeam, 200);
  }
}

function beamBroken(ms) {
  goDiv.style.display = "none";
  resultDiv.textContent = `Your time: ${ms} ms`;
  timeIn.value = ms;
  formEl.style.display = "block";
  token = null;
  restartBtn.style.display = "inline-block";
}
