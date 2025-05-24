# Quick-Reflex-Game

_A tiny IoT web-game that measures your reaction speed._

> Project for **DT514G – Web-Systems** (Örebro University)  
> Raspberry Pi · PHP · MySQL · JS · CSS

---

## ✨ Features

| Area            | What you get                                                                                                                   |
| --------------- | ------------------------------------------------------------------------------------------------------------------------------ |
| **Hardware**    | • Raspberry Pi + Explorer HAT & IR break-beam<br>• 5 V buzzer that chirps when the beam is broken                              |
| **Frontend**    | • Responsive UI (mobile & desktop)<br>• “Get ready → GO!” flow with ms timer<br>• Live device-status banner                    |
| **Backend**     | • Secure sessions & bcrypt passwords<br>• `plays` (all runs) + `game_history` (Hall-of-Fame)<br>• Admin CRUD for users & plays |
| **Roles**       | `user`, `admin`                                                                                                                |
| **Reliability** | Heart-beat JSON every 10 s; **Start** auto-disabled when Pi drops                                                              |

---

## 🗂 Project structure

```text
quick-reflex-game/
 ├─ public/              ← web root
 │   ├─ index.php        ← main game
 │   ├─ api/             ← AJAX endpoints
 │   ├─ manage_users.php
 │   ├─ settings.php
 │   ├─ static/          ← style.css, game.js
 │   └─ results/         ← temp .go / .json
 ├─ includes/            ← db.php, auth.php
 └─ beam_pi.py           ← runs on the Pi
```

---

## 🚀 Installation

### 1 · Laptop / Desktop (PHP + MySQL)

```bash
git clone https://github.com/yourname/quick-reflex-game.git
cd quick-reflex-game

# import tables: users, plays, game_history
mysql -u root -p < install.sql
```

> **Admin login:** `admin / admin123`

### 2 · Raspberry Pi

```bash
sudo apt update
sudo apt install python3-requests explorerhat
git clone https://github.com/yourname/quick-reflex-game.git
cd quick-reflex-game
nano beam_pi.py          # set PC_IP to laptop’s address
python3 beam_pi.py
```

### 3 · Play

Open your browser to **`http://<laptop-ip>/quick-reflex-game/public/`**

1. Log in (or register)
2. Click **Start** → wait for **GO!** → break the beam
3. Click **Save** to enter Hall-of-Fame — or just hit **Play Again**

---

## ✍️ Credits

- **Author:** _Abdulrahim Kuteifan_ <[hilofer111@gmail.com](mailto:hilofer111@gmail.com)>

---

Made with ☕ and a lot of beam-breaking!
