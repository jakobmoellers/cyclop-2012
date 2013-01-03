

/*
1. Value sampling: 10sec (? maybe faster) -> check sensor constants
2. Averaging
3. Saving to SD
*/

#include <SD.h>
#include "DHT.h"
#include <Wire.h>
#include "RTClib.h"
#include <LSM303.h>

LSM303 compass;

#define DHTPIN 2     // what pin we're connected to
byte sensorPinLight = A5;

RTC_DS1307 RTC;
// Uncomment whatever type you're using!
//#define DHTTYPE DHT11   // DHT 11
#define DHTTYPE DHT22     // DHT 22
//#define DHTTYPE DHT21   // DHT 21 (AM2301)

// Connect pin 1 (on the left) of the sensor to +5V
// Connect pin 2 of the sensor to whatever your DHTPIN is
// Connect pin 4 (on the right) of the sensor to GROUND
// Connect a 10K resistor from pin 2 (data) to pin 1 (power) of the sensor

DHT dht(DHTPIN, DHTTYPE);

File dataLogFile;

// intervalls in seconds:
long samplingIntervall = 1;      // intervall for sampling raw data
long measurementIntervall = 5;   // intervall for averaging the raw data
long uploadIntervall = 60;       // intervall for uploading saved averged data and saved events

int time; //unixtime
DateTime timeOld = 0;     // last Sampling was made
DateTime lastMeasurement; // last Measurement was made
DateTime lastUpload;      // last Upload was made

//sensor sample variables:
float temperature = 0;    // current temperaure value
float humidity = 0;       // current humidity value
int light = 0;            // current light value
int cellid;
int accelerationData[3];
int compassData[3];

//sum variables for averaging function:
float sumTemp = 0;
float sumHumi = 0;
//avg variables for temperature and humidity
float avgTemp = 0;
float avgHum = 0;
int loopcounter = 0;    // number of measurements for computing the averages

//should acceleration and compass also be averaged? -> no
//or maybe different kind of sample handling for event sensors. -> yes

//Accelerometerstuff
double g;
double go=2000;

//String json = "{\"device_id\": 123456,\"measurements\": [{\"timestamp\": 1355230205,\"cellid\": 109128739432,\"mcc\": 123456,\"mnc\": 123456,\"temperature\": 29,\"humidity\": 80,\"light\": 1,\"acceleration\": {\"x\": 0,\"y\": 0,\"z\": 0},\"compass\": {\"x\": 0,\"y\": 0,\"z\": 0},\"battery\": 78}]}";

void setup()
{
  Serial.begin(9600);
  dht.begin();
  Wire.begin();
  RTC.begin();
  compass.init();
  compass.enableDefault();
  timeOld = RTC.now();
  lastMeasurement = RTC.now();
  lastUpload = RTC.now();
  if (! RTC.isrunning()) {
    Serial.println("RTC is NOT running!");
     // following line sets the RTC to the date & time this sketch was compiled
     RTC.adjust(DateTime(__DATE__, __TIME__));
  }
  Serial.print("Let's start");
  pinMode(10, OUTPUT);
  if (!SD.begin(4)) {
    Serial.println("initialization SD CARD failed!");
  }
  delay(500);
  Serial.print(" .");
  delay(500);
  Serial.print(" .");
  delay(500);
  Serial.println(" .");
}

boolean timeDifferenceBiggerOrEqualThan(DateTime a, DateTime b, long timeDiff){
  long c = a.unixtime() - b.unixtime();
  return (c >= timeDiff);
}

void loop()
{
  // reading event-triggering sensordata:
  light = analogRead(A3);
  /**
  compass.read();
  accelerationData[0] = (int)compass.a.x;
  accelerationData[1] = (int)compass.a.y;
  accelerationData[2] = (int)compass.a.z;
  */
  
  //now running on millis; should run with RTC time
  DateTime time = RTC.now();
  
  // Lightvalue Threshold reached? / parcel opened?
  if (light > 150) {
    Serial.println("EVENT OCCURED: Parcel opened!");
    // TODO: SD CARD Schreiben
    String blub = ""; // TODO: Change Device_ID   
  }
  
  // Accelerometer
   compass.read();
   double x = (double)compass.a.x;
   double y = (double)compass.a.y;
   double z = (double)compass.a.z;
   g=sqrt(x*x+y*y+z*z)/1000;
   if ((go!=g) &&(g>2.5)){
     go=g;
     Serial.println("EVENT OCCURED: Parcel crashed!");
     //TODO: Event auf SD Karte schreiben (Variablenname: "g")
     
   }
  
  if (timeDifferenceBiggerOrEqualThan(time,timeOld,samplingIntervall)) //sampling every 1 seconds (according to intervall variable)
  {
    Serial.println();
    Serial.print("Sampling at ");
    DateTime time = RTC.now();
    Serial.print(time.year(), DEC);
    Serial.print('/');
    Serial.print(time.month(), DEC);
    Serial.print('/');
    Serial.print(time.day(), DEC);
    Serial.print(' ');
    Serial.print(time.hour(), DEC);
    Serial.print(':');
    Serial.print(time.minute(), DEC);
    Serial.print(':');
    Serial.println(time.second(), DEC);
  
    //read the sensor values over here (change the following lines):
    humidity = dht.readHumidity();
    temperature = dht.readTemperature();
  
    // Output for testing issues:
    Serial.print("Temperature: ");
    Serial.println(temperature);
    Serial.print("Humidity: ");
    Serial.println(humidity);
    Serial.print("Lightvalue: ");
    Serial.println(light); /**
    Serial.println("Acceleration: ");
    Serial.println("        x: " + String(accelerationData[0]));
    Serial.println("        y: " + String(accelerationData[1]));
    Serial.println("        z: " + String(accelerationData[2]));*/ 
    
    String blub = "data=";
    blub += "1232234";  // TODO: Change device_id
    blub += ";";
    blub += time.unixtime();
    blub += ";";
    blub += "1232142"; // TODO: Change cell_id
    blub += ";";
    blub += "234"; // TODO: Change mcc
    blub += ";";
    blub += "23423";  // TODO: Change mnc
    blub += ";";
    blub += String((int)temperature);
    blub += ";";
    blub += String((int)humidity);
    blub += ";";
    blub += "75";  // TODO: Change Battery
    blub += ";";
    Serial.println(blub);
 
    // add measured values to the sum variables for computing the average
    sumTemp = sumTemp + temperature;
    sumHumi = sumHumi + humidity;
    loopcounter++;
  
    // time for new Measurement? (every 5seconds)
    if (timeDifferenceBiggerOrEqualThan(time, lastMeasurement, measurementIntervall))
    {
      //compute the average
      avgTemp = sumTemp / loopcounter;
      avgHum = sumHumi / loopcounter;
  
      lastMeasurement = time;    // update time for last measurement
      loopcounter = 0;           // setting number of measurements to zero
      // setting the sum's to zero
      sumTemp = 0;
      sumHumi = 0;
      if (timeDifferenceBiggerOrEqualThan(time, lastUpload, uploadIntervall))
      {
          // TODO: LOAD der Sachen von der SD Card und UPLOAD an den Server
          lastUpload = time;
      }
    }
    timeOld = time;              // update time for last sampling
  }
}
