import explorerhat, time, requests, json, threading

PC_IP = "172.20.10.2"
BASE = f"http://{PC_IP}/quick-reflex-game/"
TOKEN_EP = BASE + "public/api/current_game.json"
POST_EP = BASE + "public/api/beam.php"

token = None
go_at = None # epoch time when GO! appears
armed = False

GO_EP = BASE + "public/results/{token}.go"

def is_buzzer_enabled():
	try:
		r = requests.get(BASE + 'public/buzzer_enabled.txt', timeout=1)
		return r.text.strip() == '1'
	except:
		return True

def poll_token():
	global token, go_at, armed
	while True:
			if token is None:
				r = requests.get(TOKEN_EP, timeout=2)
				if r.ok  and r.text.strip():
					print("PI DEBUG: raw current_game.json:", r.text)
					j = r.json()
					token = j['token']
					print("Token", token, "armed; waiting for GO")
					
			# Wait until browser drops <token>.go in results dir
			if token and not armed:
				r = requests.get(GO_EP.format(token=token), timeout=2)
				print("GO endpoint check", r.status_code)
				
				if r.status_code == 200:
					# parse the JSON that install puts into <token>.go
					#try:
					#	data = r.json()
					#	go_at = float(data.get('go_ts', time.time()))
					#except ValueError:
						# fallback if it isn't valid JSON
					#	go_at = time.time()
					go_at = time.time()
					armed = True
					print(f"GO! timer started at {go_at}")
			time.sleep(0.02)

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
		
		if is_buzzer_enabled():
			explorerhat.output.one.on();
			time.sleep(0.2);
			explorerhat.output.one.off();
		
		# reset for the next game
		token = None
		go_at = None
		armed = False

def send_heartbeat():
	while True:
		try:
			requests.post(BASE + 'public/api/heartbeat.php', timeout=2)
		except Exception:
			print('not found')
			pass
		time.sleep(10)
		
explorerhat.input.one.changed(beam_change)
		
print("Beam-Pi running, polling token...")
threading.Thread(target=send_heartbeat, daemon=True).start()
threading.Thread(target=poll_token, daemon=True).start()

while True:
	 time.sleep(60)
