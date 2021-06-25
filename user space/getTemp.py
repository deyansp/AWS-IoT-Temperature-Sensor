import glob
import time
import datetime
import getpass 
import requests
from urllib3.exceptions import InsecureRequestWarning

# verify set to false as server has self-signed TLS certificate
requests.packages.urllib3.disable_warnings(category=InsecureRequestWarning)

# getting folder with device id starting w/ 28
base_dir = '/sys/bus/w1/devices/'
device_folder = glob.glob(base_dir + '28*')[0]
dev_id = "28-021313a1caaa"

# the folder with the sensor readings file
device_file = device_folder + '/w1_slave'

url = 'https://3.222.193.155/uploadTemp.php'

def read_temp_raw():
    f = open(device_file, 'r')
    lines = f.readlines()
    f.close()
    return lines

def read_temp(interval):
    lines = read_temp_raw()
    # waiting for a sensor reading to become available
    while lines[0].strip()[-3:] != 'YES':
        time.sleep(0.2)
        lines = read_temp_raw()
    
    # finding the temperature value in text file
    equals_pos = lines[1].find('t=')
    
    # if the value is found
    if equals_pos != -1:
        date = datetime.datetime.now().strftime("%d-%m-%Y %H:%M:%S")
        temp_string = lines[1][equals_pos+2:]
        #temperature in Celcius
        temp = round(float(temp_string) / 1000.0, 1)
        unit = ""
        
        try:
            with open("/sys/class/gpio/gpio16/value") as pin:
                value = pin.read(1)
                # if the LED is off we return Farenheit
                if (value == '0'):
                    temp = temp * 9.0 / 5.0 + 32.0
                    unit = "F"
                #if the LED is on we return Celcius
                elif (value == '1'):
                    unit = "C"
                else:
                    return "error"
            
            # returning json string that will be sent to AWS
            return {"device": dev_id, "date": date, "temperature": temp, "unit": unit, "gap": interval}
        
        except:
            print ("Cannot access GPIO file. Remember to start the LKM first!")

def upload_temp(url, jsonData, user, password) :
    # verify set to false as server has self-signed TLS certificate
    req = requests.post(url, data = jsonData, auth = (user, password), verify = False)
    print("Upload request status: " + str(req.status_code) + " " + req.reason)

if __name__ == "__main__":
    interval = 0

    while True:
     user = input("Username: ")
     password = getpass.getpass() 
     interval = input("Measurement frequency (in seconds from 1 to 86400): ")
     
     if interval.isdigit() and 1 <= int(interval) <= 86400:
        interval = int(interval)
        break
     elif interval == 'q':
        exit
     else:
        print("Invalid input, please try again. Enter 'q' to quit")
    
    while True:
        temp = read_temp(interval)
        if temp != "error" :
            upload_temp(url, temp, user, password)
        else :
            print("Unable to determine LED state.")
        
        time.sleep(interval)