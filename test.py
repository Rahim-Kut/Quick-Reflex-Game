# Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

import explorerhat
import time

# Turn buzzer on (Output 1)
# print("Turning buzzer On...")
# explorerhat.output.one.on()
# time.sleep(1)

# Turn it off
# print("Turning buzzer off")
# explorerhat.output.one.off()

def beam_broken(channel):
	print("Beam broken!")
	explorerhat.output.one.on()
	time.sleep(0.2)
	explorerhat.output.one.off()
	
explorerhat.input.one.changed(beam_broken)

print("Ready. Break the beam to trigger the buzzer.")


#print("Reading input 1 every 0.5s...")

while True:
	#print("Input 1:", explorerhat.input.one.read())
	time.sleep(1)
