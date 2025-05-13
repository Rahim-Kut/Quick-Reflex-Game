import explorerhat, time, requests, json, threading

PC_IP = "172.20.10.2"
BASE = f"http://{PC_IP}/quick-reflex-game/"
TOKEN_EP = BASE + "current_game.json"
POST_EP = BASE + "beam-broken.php"

token = None
go_at = None # epoch time when GO! appears
armed = False

GO_EP = BASE + "results/{token}.go"

def poll_token():
	global token, go_at, armed
	while True:
		try:
			if token is None:
				r = requests.get(TOKEN_EP, timeout=2)
				if r.ok  and r.text.strip():
					print("PI DEBUG: raw current_game.json:", r.text)
					j = r.json()
					token = j['token']
					print("Token", token, "armed; waiting for GO")
					
			# Wait until browser drops <token>.go in results dir
			if token and not armed:
				print("Token: ", token)
				r = requests.get(GO_EP.format(token=token), timeout=2)
				print("GO endpoint check", r.status_code)   # debug
				if r.status_code == 200:
					go_at = time.time()
					armed = True
					print("GO! timer started")
		except Exception as e:
			print("poll error:", e)
		time.sleep(0.2)

def beam_change(ch):
	global token, go_at, armed
	
	if not armed:
		return # ignoring until GO!
		
	if ch.read() == 0: # beam broken 
		reaction = int((time.time() - go_at) * 1000)
		print("Reaction", reaction, "ms")
		
		try:
			requests.post(POST_EP, data={'token': token, 'time_ms': reaction}, timeout=3)
		except Exception as e:
			print("HTTP post error:", e)
		
		explorerhat.output.one.on();
		time.sleep(0.2);
		explorerhat.output.one.off();
		
		# reset for the next game
		token = None
		go_at = None
		armed = False
		
explorerhat.input.one.changed(beam_change)
		
print("Beam-Pi running, polling token...")
threading.Thread(target=poll_token, daemon=True).start()

while True:
	 time.sleep(60)
