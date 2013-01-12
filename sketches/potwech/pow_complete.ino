/*
1. Value sampling: 10sec (? maybe faster) -> check sensor constants
2. Averaging
3. Saving to SD

TODO: LOGFILE.TXT, CRASHED.TXT, OPENED.TXT bei Start löschen falls vorhanden
*/

#include <SD.h>
#include "DHT.h"
#include <Wire.h>
#include "RTClib.h"
#include <LSM303.h>
#include <SoftwareSerial.h>

SoftwareSerial mySerial(10, 11);

String mcc;
String mnc;
String lac;
String cid;

LSM303 accelerometer;

#define DHTPIN 2 // what pin we're connected to

RTC_DS1307 RTC;
// Uncomment whatever type you're using!
//#define DHTTYPE DHT11 // DHT 11
#define DHTTYPE DHT22 // DHT 22
//#define DHTTYPE DHT21 // DHT 21 (AM2301)

// Connect pin 1 (on the left) of the sensor to +5V
// Connect pin 2 of the sensor to whatever your DHTPIN is
// Connect pin 4 (on the right) of the sensor to GROUND
// Connect a 10K resistor from pin 2 (data) to pin 1 (power) of the sensor

DHT dht(DHTPIN, DHTTYPE);

File dataFile;

// intervalls in seconds:
long sampleInterval = 1; // intervall for sampling raw data
long measureInterval = 5; // intervall for averaging the raw data
long uploadInterval = 60; // intervall for uploading saved averged data and saved events

int time; //unixtime
DateTime timeOld = 0; // last Sampling was made
DateTime lastMeasurement; // last Measurement was made
DateTime lastUpload; // last Upload was made

//sensor sample variables:
float temperature = 0; // current temperaure value
float humidity = 0; // current humidity value
int light = 0; // current light value
int cellid;
double accData[3];
boolean opened= false;
boolean crashed = false;

//sum variables for averaging function:
float sumTemp = 0;
float sumHumi = 0;
//avg variables for temperature and humidity
int avgTemp = 0;
int avgHum = 0;
int loopCounter = 0; // number of measurements for computing the averages

//should acceleration and accelerometer also be averaged? -> no
//or maybe different kind of sample handling for event sensors. -> yes

//Accelerometerstuff
double g;
double go=2000;

void setup()
{
  mySerial.begin(19200);
  Serial.begin(19200);
  delay(500);
  dht.begin();
  Wire.begin();
  RTC.begin();
  accelerometer.init();
  accelerometer.enableDefault();
  timeOld = RTC.now();
  lastMeasurement = RTC.now();
  lastUpload = RTC.now();
  if (! RTC.isrunning()) {
    Serial.println("RTC is NOT running!");
     // following line sets the RTC to the date & time this sketch was compiled
     RTC.adjust(DateTime(__DATE__, __TIME__));
  }
  Serial.print("Let's start");
  pinMode(53, OUTPUT);
  if (!SD.begin(53)) {
    Serial.println("initialization SD CARD failed!");
  }
  delay(500);
  Serial.print(" .");
  delay(500);
  Serial.print(" .");
  delay(500);
  Serial.println(" .");
  PowerOnOff();
  delay(8000);
  ConnectToGSM();
}

boolean DiffBiggerOrEqual(DateTime a, DateTime b, long timeDiff){
  long c = a.unixtime() - b.unixtime();
  return (c >= timeDiff);
}

void printlnSD(String str, int fileName){
  
  if (fileName == 1){
    dataFile = SD.open("logfile.txt", FILE_WRITE);
  }
  else if (fileName == 2){
    dataFile = SD.open("opened.txt", FILE_WRITE);
  }
  else if (fileName == 3){
    dataFile = SD.open("crashed.txt", FILE_WRITE);
  }
  // if the file is available, write to it:
  if (dataFile) {
    dataFile.println(str);
    dataFile.close();
  }
  // if the file isn't open, pop up an error:
  else {
    Serial.println("error opening datalog.txt");
  }
}

void loop()
{
  // reading event-triggering sensordata:
  light = analogRead(A3);
  accelerometer.read();
  accData[0] = (double) accelerometer.a.x;
  accData[1] = (double) accelerometer.a.y;
  accData[2] = (double) accelerometer.a.z;
  
  //now running on millis; should run with RTC time
  DateTime time = RTC.now();
  // Lightvalue Threshold reached? / parcel opened?
  if ((light > 200) && (!opened)) {
    opened = true;
    Serial.println("EVENT OCCURED: Parcel opened!");
    // TODO: SD CARD Schreiben
    String blub = "123"; // TODO: Change device_id
    blub += ";";
    blub += time.unixtime();
    blub += ";";
    blub += cid; 
    blub += ";";
    blub += mcc; 
    blub += ";";
    blub += mnc; 
    blub += ";";
    blub += lac; 
    blub += ";";
    blub += String(light);
    printlnSD(blub, 2);
  }
  if ((opened) && (light<160))
  {
    opened = false;
  }
  // Acceleration Threshold reached? / parcel crashed?
  g=sqrt(accData[0]*accData[0]+accData[1]*accData[1]+accData[2]*accData[2])/1000;
  if ((go!=g) &&(g>2.5) && (!crashed))
  {
    crashed = true;
    go=g;
    Serial.println("EVENT OCCURED: Parcel crashed!");
    // TODO: SD CARD Schreiben
    String blub = "123"; // TODO: Change device_id
    blub += ";";
    blub += time.unixtime();
    blub += ";";
    blub += cid; 
    blub += ";";
    blub += mcc; 
    blub += ";";
    blub += mnc; 
    blub += ";";
    blub += lac; 
    blub += ";";
    g = g * 10;
    blub += String((int)g);
    printlnSD(blub, 3);
  }
  
  if (DiffBiggerOrEqual(time,timeOld,sampleInterval)) //sampling every 1 seconds (according to intervall variable)
  {
    crashed = false;
    Serial.println("S A M P L I N G");
    DateTime time = RTC.now();
  
    //read the sensor values over here (change the following lines):
    humidity = dht.readHumidity();
    temperature = dht.readTemperature();
 
    // add measured values to the sum variables for computing the average
    sumTemp = sumTemp + temperature;
    sumHumi = sumHumi + humidity;
    loopCounter++;
    
    // time for new Measurement? (every 5seconds)
    if (DiffBiggerOrEqual(time, lastMeasurement, measureInterval))
    {
      Serial.println("C O M P U T I N G  A V E R A G E S ");
      //compute the average
      avgTemp = (int)(sumTemp / loopCounter);
      avgHum = (int)(sumHumi / loopCounter);
  
      lastMeasurement = time; // update time for last measurement
      loopCounter = 0; // setting number of measurements to zero
      // setting the sum's to zero
      sumTemp = 0;
      sumHumi = 0;
      
      GetLacCid();
      GetMccMnc();
      
      String blub = "123"; // TODO: Change device_id
      blub += ";";
      blub += time.unixtime();
      blub += ";";
      blub += cid; 
      blub += ";";
      blub += mcc; 
      blub += ";";
      blub += mnc; 
      blub += ";";
      blub += lac; 
      blub += ";";
      blub += avgTemp;
      blub += ";";
      blub += avgHum;
      blub += ";";
      blub += "75;"; // TODO: Change Battery
      Serial.print(blub);
      printlnSD(blub, 1);
      
      if (DiffBiggerOrEqual(time, lastUpload, uploadInterval))
      {
          Serial.println("U P L O A D  S H I T  Y O O ");
          TcpPost();
          // TODO: LOAD der Sachen von der SD Card und UPLOAD an den Server
          lastUpload = time; // update time for last Upload
      }
    }
    timeOld = time; // update time for last sampling
  }
}

void PowerOnOff()
{
  pinMode(9, OUTPUT); 
  digitalWrite(9,LOW);
  delay(1000);
  digitalWrite(9,HIGH);
  delay(2000);
  digitalWrite(9,LOW);
  delay(3000);
}

void ConnectToGSM(){
//  mySerial.println("AT+CGATT?");
//  //Query network Status
//  delay(1000);
//  ShowSerialData();
  Serial.println();

  mySerial.println("AT+CSTT=\"data.access.de\"");
  //setting the SAPBR, the connection type is using gprs
  delay(500);
  ShowSerialData();

  mySerial.println("AT+CIICR");
  //bring up wireless connection to GPRS (or CSD)
  delay(2000);
  ShowSerialData(); 
 
  mySerial.println("AT+CIFSR");
  //get local IP adress (as response)
  delay(5000);
  ShowSerialData();
}
void GetMccMnc()
{
  int kommaZaehler = 0;
  mcc = "";
  mnc = "";
  
  mySerial.println("AT+CENG=1");
  delay(1000);
  ShowSerialData();

  //Informationen für Cell ID etc abrufen
  mySerial.println("AT+CENG?");
  delay(1000);
  
  while(mySerial.available()!=0){
    byte in = mySerial.read();
    //Serial.print(char(in));
    if ((kommaZaehler==5) && (char(in)!=','))
      mcc += char(in);
    if ((kommaZaehler==6) && (char(in)!=','))
      mnc += char(in);
    if ((kommaZaehler==8) && (char(in)!=','))
      cid += char(in);
    if (char(in)==','){
      kommaZaehler++;
    }
  }
  Serial.println("mcc:" + mcc + " mnc:"+ mnc + " cell-ID:" + cid);
  delay(500);
}
void GetLacCid(){
  int kommaZaehler = 0;
  lac = "";
  cid = "";
  
  mySerial.println("AT+CREG?");
  delay(100);
  
  while(mySerial.available()!=0){
    byte in = mySerial.read();
    Serial.print(char(in));
    //if(char(in) != '\n')
    //input += char(in);
    if ((kommaZaehler==2) && (char(in)!='"') && (char(in)!=','))
      lac += char(in);
    if ((kommaZaehler==3) && (char(in)!='"'))
      cid += char(in);
    if (char(in)==','){
      kommaZaehler++;
    }
  }
  Serial.println("LAC: " + lac + ", CID: " + cid);
  delay(500);
}
void TcpPost(){
              
        //mySerial.println("AT+CIPSTART=\"TCP\",\"http://potwech.uni-muenster.de/rest/index.php/post/\",\"80\"");
        mySerial.println("AT+CIPSTART=\"TCP\",\"128.176.146.214\",\"80\"");//start up the connection
        //start up connection
        delay(6000);
        ShowSerialData(); 

        mySerial.println("AT+CIPSEND");
        //send data to remote server, CTRL+Z (0x1a) to send 
        //  >hello TCP server.
        //  SEND OK
        //  hello sim900
        delay(4000);
        ShowSerialData();
       
       mySerial.println("POST /rest/index.php/postMeasurement HTTP/1.1 ");
       delay(100);
       ShowSerialData();
    
      //PRINT DATA TO mySerial
       mySerial.println("Host: potwech.uni-muenster.de");
       delay(100);
       ShowSerialData();
       
       mySerial.println("Accept: application/json");
       delay(100);
       ShowSerialData();

       dataFile = SD.open("logfile.txt"); //open file
       
       mySerial.println("Content-Length:" + String(dataFile.size()+4));
       delay(100);
       ShowSerialData();   
       
       mySerial.println("Content-Type: application/x-www-form-urlencoded");
       delay(100);
       ShowSerialData();
       
       mySerial.println();
       delay(100);
       ShowSerialData();
       
       mySerial.print("data=");
       delay(100);
       ShowSerialData();

       if (dataFile) {
          int i = 1;  
          while (dataFile.available() && i<dataFile.size()) { //send complete logfile  
              mySerial.write(dataFile.read());
              i++;
            }
            delay(500);
            ShowSerialData();
            
            dataFile.close();
            SD.remove("logfile.txt");
            Serial.println("Ready.");
            //Serial.println(" finished!");
          } else {
            // if the file didn't open, print an error:
            Serial.println("error opening test.txt");
          }
 
  //PRINT DATA TO mySerial
  mySerial.println((char)26);//sending ich bin ein homo, gez.: Jan Wirwahn
  delay(2000);//waitting for reply, important! the time is base on the condition of internet 
  mySerial.println();
  ShowSerialData();
  
  mySerial.println("AT+CIPCLOSE");//close the TCP connection
  delay(100);
  ShowSerialData();  
}
