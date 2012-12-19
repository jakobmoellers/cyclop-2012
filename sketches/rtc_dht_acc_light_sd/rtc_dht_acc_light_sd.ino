// Prototype of POW
// TODO: correct temp values; float to string
//---------Libs to Include-----------
#include <SD.h>
#include <Wire.h> //I2C
#include <RTClib.h> 
#include <DHT.h>
#include <LSM303.h> //Accelerometer
//-------Compass/Accelerometer-------
LSM303 accelerometer;
int accArray[3];
int compArray[3];
//-------Photodiode--------
int lightPin = A3;
int lightValue;
//--------RTC Objects-----------
RTC_DS1307 RTC;
DateTime time;
//--------SD Values------------
const int chipSelect = 10;
File dataFile;
//---------DHT Variables and Object----------
#define DHTPIN 2
#define DHTTYPE DHT22   // DHT 22 (AM2302)
DHT dht(DHTPIN, DHTTYPE);
float temperature;
float humidity;
//--------String for Json----------
String json1 = "";
String json2 = "";
String acc = "";
String comp = "";
//--------Variables for avg Fuctionality---------
float tempSum = 0;
float humiSum = 0;
//-------------Timer Variables------
int samplingInterval = 2;
int loopCounter = 0;
long timeNew;
long timeOld = 0;
//-------------Device and Network Values----------------
int deviceid = 123;
int cid = 000;
int mcc = 456;
int mnc = 789;
//-------Setup the board and start sensors-------------
void setup () {
  Serial.begin(19200);
  Wire.begin();
  RTC.begin();
  dht.begin();
  Serial.println("Booting the system...");
  Serial.println("Initializing RTC...");  
  if (! RTC.isrunning()) {
    Serial.println("RTC is NOT running!");
    //set the RTC to the date & time this sketch was compiled
    RTC.adjust(DateTime(__DATE__, __TIME__));
  }
  time = RTC.now();
  Serial.print("Initializing SD card...");
  pinMode(10, OUTPUT);
  // see if the card is present and can be initialized:
  if (!SD.begin(chipSelect)) {
    Serial.println("Card failed, or not present");
    // don't do anything more
    return;
  }
  Serial.println("card initialized.");
}

void loop () {
    time = RTC.now();
    timeNew = time.unixtime();
    if ((timeNew - timeOld) >= samplingInterval){ //sampling every 2 seconds
      tempSum = tempSum + dht.readTemperature();
      humiSum = humiSum + dht.readHumidity();
      
      //TODO: Catch Events of Accelerometer and Photodiode
      Serial.print("The light value is: ");
      Serial.println(analogRead(3));
      
      accelerometer.read();
      
      Serial.print("Acceleration is: ");
      Serial.print("X: ");
      Serial.print((int)accelerometer.a.x);
      Serial.print(" Y: ");
      Serial.print((int)accelerometer.a.y);
      Serial.print(" Z: ");
      Serial.println((int)accelerometer.a.z);
      
      Serial.print("Compass is: ");
      Serial.print("X: ");
      Serial.print((int)accelerometer.m.x);
      Serial.print(" Y: ");
      Serial.print((int)accelerometer.m.y);
      Serial.print(" Z: ");
      Serial.println((int)accelerometer.m.z);      
      
      if (loopCounter == 4){ //Averaging after 10 seconds
        Serial.print("Calculating average values at ");
        Serial.println(millis());
        tempSum = tempSum / 5;
        humiSum = humiSum / 5;
        
        //TODO: Sting buffer problem causes crash of program!! Seperation of strings, change format to CSV, or try out Arduino Mega
        String json = "{\"device_id\": 123456,\"measurements\": [{\"timestamp\": 1355230205,\"cellid\": 109128739432,\"mcc\": 123456,\"mnc\": 123456}]}";
        //json1 = "{\"device_id\":" + String(deviceid) + ',' + "\"measurements\":[{\"timestamp\":" + String(timeNew) + ',' + "\"cellid\":" + String(cid) + ',' + "\"mcc\":" + String(mcc) + ',';
        //json2 = + "\"mnc\":" + String(mnc) + ',' + "\"temperature\":" + String((int)tempSum) + ',' + "\"humidity\":" + String((int)humiSum) + "}]}";
        
        //Serial.println(json1);
        //Serial.println(json2);
        
        dataFile = SD.open("datalog.txt", FILE_WRITE);

        // if the file is available, write to it:
        if (dataFile){
          dataFile.println(json);
          dataFile.close();
          // print to the serial port too:
          Serial.print(json);
          Serial.println(" saved to SD!");
        }  
        // if the file isn't open, pop up an error:
        else{
          Serial.println("Error opening datalog.txt");
        } 
        loopCounter = -1;
        tempSum = 0;
        humiSum = 0;
      }
      loopCounter++;
      timeOld = timeNew;  
    }
}
