import serial
import json
from datetime import datetime

# Connect to Arduino Serial port
ser = serial.Serial('COM3', 9600)  # Change 'COM3' to your port (e.g., '/dev/ttyUSB0' for Linux)

while True:
    try:
        data = ser.readline().decode('utf-8').strip()
        temp, hum = data.split(',')

        # Prepare data
        sensor_data = {
            "temperature": temp,
            "humidity": hum,
            "time": datetime.now().strftime("%H:%M:%S")
        }

        # Save to JSON
        with open('data.json', 'w') as file:
            json.dump(sensor_data, file)

        print("Data updated:", sensor_data)

    except Exception as e:
        print("Error:", e)
