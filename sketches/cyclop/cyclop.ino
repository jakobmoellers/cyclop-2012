/*
This is the main cyclop application
 */

//Libraries
#include <string.h>
#include <ctype.h>
#include "DHT.h"
#include <RTClib.h> 
#include <Wire.h>
#include <SeeedOLED.h>
#include <SD.h>
//#include <stdlib.h>
#include <floatToString.h>


//PINs
int ledPin = 13;
const int WaterPin=42; //Water sensor
int xPin = 25; //MMA7361L Three Axis Accelerometer
int yPin = 23;
int zPin = 22; 
int selPin = 27;
int sleepPin = 26;
int rxPin = 0; //GPS
int txPin = 1;  
#define DHTPIN 24 //DHT
int vibration1=46; //Vibration
int vibration2=47;
int LEDpin=38; //Button
int p51=39;
int p52=41;
int p53=40;
int no_pin=13;
int co_pin=14;
int alarmPin=49;
const byte speakerOut = 44; //Speaker
const char microphone = A10; //Microphone
char dustPin=A15; //Dust Sensor
int ledPowerPin=48;
int SDPin = 53;


//Variables
boolean alarm=false; //True if alarm is armed
const float scalePoints = 1023/5; //Accelerometer
float calValues[3] = {
  0,0,0};
int numReadings = 0;
int maxNumReadings = 20;
long dt = 250;
long lastTime = 0;
int byteGPS= -1; //GPS
char linea[300] = "";
char comandoGPR[7] = "$GPRMC";
int cont=0;
int bien=0;
int conta=0;
int indices[13]; 
#define DHTTYPE DHT11 // DHT 11
DHT dht(DHTPIN, DHTTYPE);
RTC_DS1307 RTC; //RTC
DateTime time;
int button = 0; //Button
int ledLevel = 255;
int onstatus = 0;
unsigned int timeUpDown[128]; //Speaker
const byte BPM = 200;
const char song[]={
  64,8,64,8,64,4};
/*
const char song[] = {	
 64,4,64,4,65,4,67,4,		67,4,65,4,64,4,62,4,
 60,4,60,4,62,4,64,4,		64,-4,62,8,62,2,
 64,4,64,4,65,4,67,4,		67,4,65,4,64,4,62,4,
 60,4,60,4,62,4,64,4,		62,-4,60,8,60,2,
 62,4,62,4,64,4,60,4,		62,4,64,8,65,8,64,4,60,4,
 62,4,64,8,65,8,64,4,62,4,	60,4,62,4,55,2,
 64,4,64,4,65,4,67,4,		67,4,65,4,64,4,62,4,
 60,4,60,4,62,4,64,4,		62,-4,60,8,60,2};*/
int period, j;
unsigned int timeUp, beat;
byte statePin = LOW;
const float TEMPO_SECONDS = 60.0 / BPM; 
const unsigned int MAXCOUNT = sizeof(song) / 2;
int delayTime=280;
int delayTime2=40;
float offTime=9680;
int oldTest;
int magnetSensor;
File dataFile; //SD

//Measurements
boolean rain=false;
double no2_ppm;
double co_ppm;
double gValue;
double lat;
double lon;
float temperature;
float humidity;
int secretKey=986743;
int dustVal;

/*
Main methods
 */

void setup(){
  //Water sensor
  pinMode(WaterPin, INPUT);

  //Acc
  digitalWrite(sleepPin,HIGH);
  digitalWrite(selPin,HIGH); 
  getCalValues();

  //GPS
  pinMode(rxPin, INPUT);
  pinMode(txPin, OUTPUT);
  for (int i=0;i<300;i++)
  {       // Initialize a buffer for received data
    linea[i]=' ';
  }

  //DHT
  dht.begin();

  //RTC
  RTC.begin();
  Serial.println("Initializing RTC...");  
  if (! RTC.isrunning()) {
    Serial.println("RTC is NOT running!");
    //set the RTC to the date & time this sketch was compiled
    RTC.adjust(DateTime(__DATE__, __TIME__));
  }
  time = RTC.now();

  Serial.begin(9600);

  //Display
  Wire.begin();
  SeeedOled.init();  //initialze SEEED OLED display
  DDRB|=0x21;        //digital pin 8, LED glow indicates Film properly Connected .
  PORTB |= 0x21;
  SeeedOled.clearDisplay();          //clear the screen and set start position to top left corner
  SeeedOled.setNormalDisplay();      //Set display to normal mode (i.e non-inverse mode)
  SeeedOled.setHorizontalMode();  
  SeeedOled.putString("Welcome to Cyclop!"); //Print the String

    //Vibration
  pinMode(vibration1,OUTPUT);
  pinMode(vibration2,OUTPUT);
  vibrate(1);

  //Button
  pinMode(LEDpin, OUTPUT);
  pinMode(p51, INPUT);
  pinMode(p53, INPUT);
  pinMode(p52, INPUT);
  digitalWrite(p51, HIGH);
  digitalWrite(p52, HIGH);
  digitalWrite(p53, HIGH);
  analogWrite(LEDpin, 0);

  //Alarm
  pinMode(alarmPin,INPUT);

  //Speaker
  pinMode(ledPin, OUTPUT); 
  pinMode(speakerOut, OUTPUT);
  for (j = 128; j--;)
    timeUpDown[j] = 1000000 / (pow(2, (j - 69) / 12.0) * 880);
  playSong();

  //Microphone
  testMic();

  //Dust Sensor
  pinMode(ledPowerPin,OUTPUT);

  //Alarm
  pinMode(alarmPin,INPUT);
  oldTest = digitalRead(alarmPin);
  magnetSensor= oldTest;

  //SD

  pinMode(SDPin,OUTPUT);
  if (!SD.begin(SDPin)) {
    Serial.println("initialization SD CARD failed!");
  }
  delay(500);  
  Serial.println("Removing existing files.");
  if (SD.exists("measure.txt"))
    SD.remove("measure.txt");
  if (SD.exists("hazards.txt"))
    SD.remove("hazards.txt");

}

void loop(){

  //Reset variables
  rain=false;

  //TODO Download new hazards in a time interval of 1 minute

    //TODO What about the measurement process? Also every minute? Should be included here

    getPosition();

  //TODO GPS does not work

  Serial.print("lat: ");
  Serial.print(lat);
  Serial.print(" lon: ");
  Serial.print(lon);

  determineAlertMode();

  if(alarm==true){
    //Alarm mode

      getAcc();

    if (gValue!=1.0){

      //TODO Send theft alert to server
      Serial.println("Theft detected");     
    }



  } 
  else {
    //Standard mode

      takeMeasurements();

    //TODO Store measurements on SD Card

    storeMeasurement();

    checkforHazardButtonPressed();

    //TODO Upload measurements //Remember to upload secret key
    //TODO Clean SD-Card if post successful

    //TODO Upload Hazards //Remember to upload secret key
    //TODO Clean SD-Card if post successful


  }


}

/*
Help Methods
 */

void takeMeasurements(){

  //Watersensor  
  int waterSensorValue = digitalRead(WaterPin);
  if(waterSensorValue==0){
    rain=true;
    Serial.println(" Water");
  }

  //NO2
  getNO2();
  Serial.print(" NO2: ");
  Serial.print(no2_ppm);

  //CO
  getCO();
  Serial.print(" CO: ");
  Serial.print(co_ppm);

  //Acc
  getAcc();
  Serial.print(" g: ");
  Serial.print(gValue);

  //Dust;
  getDust();
  Serial.print(" du: ");
  Serial.print(dustVal);

  //DHT
  humidity = dht.readHumidity();
  Serial.print(" hum: ");
  Serial.print(humidity);
  temperature = dht.readTemperature();
  Serial.print(" temp: ");
  Serial.print(temperature);

  Serial.println("");

}

void storeMeasurement(){

  /*boolean rain=false;
   TIME
   double no2_ppm;
   double co_ppm;
   double gValue;
   double lat;
   double lon;
   float temperature;
   float humidity;
   int secretKey=986743;
   int dustVal;
   */

  //TODO SD not tested yet

    String measurement = String(rain);
  measurement +=";";
  measurement +=time.unixtime();
  measurement +=";";
  measurement +=doubleToString(no2_ppm,2);
  measurement +=";";
  measurement +=doubleToString(co_ppm,2);
  measurement +=";";
  measurement +=doubleToString(gValue,2);
  measurement +=";";
  measurement +=doubleToString(lat,8);
  measurement +=";";
  measurement +=doubleToString(lon,8);
  measurement +=";";
  char tmpBuffer1[10];
  measurement += floatToString(tmpBuffer1,temperature,2);
  measurement +=";";
  char tmpBuffer2[10];
  measurement += floatToString(tmpBuffer2,humidity,2);
  measurement +=";";
  measurement += String(secretKey);
  measurement +=";";
  measurement +=String(dustVal);
  Serial.println(measurement);
  printlnSD(measurement,1);

}

void storeHazard(){
  //TODO 
}

void printlnSD(String str, int fileName){
  if (fileName == 1){
    dataFile = SD.open("measure.txt", FILE_WRITE);
  }
  else if (fileName == 2){
    dataFile = SD.open("hazards.txt", FILE_WRITE);
  }
  // if the file is available, write to it:
  if (dataFile) {
    dataFile.print(str);
    dataFile.close();
  }
  // if the file isn't open, pop up an error:
  else {
    Serial.println("error opening datalog.txt");
  }
}

void cleanSD(int fileName){
  if(fileName==1){
    SD.remove("measure.txt");
    Serial.println("measure.txt removed!");
  }
  else if(fileName==2){
    SD.remove("hazards.txt");
    Serial.println("hazards.txt removed!");
  }
}

void determineAlertMode(){

  int magnetSensor = digitalRead(alarmPin);
  if (magnetSensor!=oldTest){
    Serial.println("Alarm mode changed");
    oldTest=magnetSensor;
    alarm = !alarm;
    if (alarm==true){
      SeeedOled.clearDisplay();
      SeeedOled.putString("Alarm ON");
    } 
    else {
      SeeedOled.clearDisplay();
      SeeedOled.putString("Alarm OFF");
    }


  }
  delay(200);

}

//Accelerometer
void getAcc(){
  float xValue = analogRead(xPin);
  float yValue = analogRead(yPin);
  float zValue = analogRead(zPin);
  float gxValue = constrain((xValue/scalePoints-1.65-calValues[0])/0.8,-1,1);
  float gyValue = constrain((yValue/scalePoints-1.65-calValues[1])/0.8,-1,1);
  float gzValue = constrain((zValue/scalePoints-1.65-calValues[2])/0.8,-1,1);
  gValue = sqrt(gxValue*gxValue+gyValue*gyValue+gzValue*gzValue);
}

void getCalValues() {
  while (numReadings < maxNumReadings)
    if (millis() - lastTime > dt) {
      calValues[0] = calValues[0] + analogRead(xPin);
      calValues[1] = calValues[1] + analogRead(yPin);
      calValues[2] = calValues[2] + analogRead(zPin);
      lastTime = millis();
      numReadings++;
    }
  calValues[0] = (calValues[0] / maxNumReadings)/scalePoints-1.65;
  calValues[1] = (calValues[1] / maxNumReadings)/scalePoints-1.65;
  calValues[2] = (calValues[2] / maxNumReadings)/scalePoints-0.85;
}

//GPS
String return_gps_pos(){
  String gpspos = String(linea);
  return gpspos;
}

void getPosition(){
  byteGPS=Serial.read();         // Read a byte of the serial port
  if (byteGPS == -1) 
  {           // See if the port is empty yet
    delay(100);
    //Serial.println("here"); 
  } 
  else 
  {
    //delay(1000);
    linea[conta]=byteGPS;        // If there is serial port data, it is put in the buffer
    conta++;                      
    //Serial.write(byteGPS); 
    if (byteGPS==13)
    {            // If the received byte is = to 13, end of transmission
      //digitalWrite(ledPin, LOW); 
      cont=0;
      bien=0;
      for (int i=1;i<7;i++)
      {     // Verifies if the received command starts with $GPR
        if (linea[i]==comandoGPR[i-1]){
          bien++;
        }
      }
      if(bien==6)
      {               // If yes, continue and process the data
        for (int i=0;i<300;i++)
        {
          if (linea[i]==',')
          {    // check for the position of the  "," separator
            indices[cont]=i;
            cont++;
          }
          if (linea[i]=='*')
          {    // ... and the "*"
            indices[12]=i;
            cont++;
          }
        }
        Serial.println("");      // ... and write to the serial port
        Serial.println("");
        Serial.println("---------------");
        for (int i=0;i<12;i++)
        {
          switch(i)
          {
          case 0 :
            Serial.print("Time in UTC (HhMmSs): ");
            break;
          case 1 :
            Serial.print("Status (A=OK,V=KO): ");
            break;
          case 2 :
            Serial.print("Latitude: ");
            break;
          case 3 :
            Serial.print("Direction (N/S): ");
            break;
          case 4 :
            Serial.print("Longitude: ");
            break;
          case 5 :
            Serial.print("Direction (E/W): ");
            break;
          case 6 :
            Serial.print("Velocity in knots: ");
            break;
          case 7 :
            Serial.print("Heading in degrees: ");
            break;
          case 8 :
            Serial.print("Date UTC (DdMmAa): ");
            break;
          case 9 :
            Serial.print("Magnetic degrees: ");
            break;
          case 10 :
            Serial.print("(E/W): ");
            break;
          case 11 :
            Serial.print("Mode: ");
            break;
          case 12 :
            Serial.print("Checksum: ");
            break;
          }
          for (int j=indices[i];j<(indices[i+1]-1);j++)
          {
            Serial.print(linea[j+1]); 
          }
          Serial.println("");
        }
        Serial.println("---------------");
      }
      conta=0;                    // Reset the buffer
      for (int i=0;i<300;i++)
      {    //  
        linea[i]=' ';             
      }                 
    }
  }
}

//NO2

void getNO2(){
  //Read values and calculate rs/ro
  double reading0 = analogRead(no_pin);
  double sensorValueNo= map(reading0,0,1024,0,500);
  sensorValueNo= sensorValueNo/100;
  double rs_no=(22000/((5-sensorValueNo)*sensorValueNo));
  double rs_r0_no = rs_no / 1000;
  //Estimate ppm
  no2_ppm=0.013043*rs_r0_no+0.0086956;
}

//CO
void getCO(){
  //Read values and calculate rs/ro
  double reading1 = analogRead(co_pin);
  double sensorValueCo=map(reading1,0,1024,0,500);
  sensorValueCo=sensorValueCo/100;
  double rs_co=(100000/((5-sensorValueCo)))*sensorValueCo;
  double rs_r0_co = rs_co / 100000; 
  //Estimate ppm
  co_ppm=-25*rs_r0_co+20.6;
}

//Vibration
void vibrate(int secs){
  digitalWrite(vibration1,HIGH);
  digitalWrite(vibration2,HIGH);
  delay(secs*1000);
  digitalWrite(vibration1,LOW);
  digitalWrite(vibration2,LOW);

}

//Button

void checkforHazardButtonPressed(){
  button = getButtonState();  // Get button status
  if (button == 0x04)  // FLAME
  {
    onstatus = onstatus ^ 1;  // flip on/off status of LED
    analogWrite(LEDpin, ledLevel);
    Serial.println("Hazard");
    SeeedOled.clearDisplay();
    SeeedOled.putString("Hazard saved and uploaded!");
    storeHazard(); //TODO do this function
    delay(2000);
    SeeedOled.clearDisplay();
    SeeedOled.putString("Welcome to Cyclop!");
    digitalWrite(LEDpin, 0);   
  }
}

uint8_t getButtonState()
{
  // Initially set all buttons as inputs, and pull them up
  pinMode(p52, INPUT);
  digitalWrite(p52, HIGH);
  pinMode(p51, INPUT);
  digitalWrite(p51, HIGH);
  pinMode(p53, INPUT);
  digitalWrite(p53, HIGH);

  // Read the d/u/flame buttons
  if (!digitalRead(p53))
    return 0x01;  // Down
  if (!digitalRead(p52))
    return 0x02;  // Up
  if (!digitalRead(p51))
    return 0x04;  // Flame

  // Read right button
  pinMode(p52, OUTPUT);  // set p52 to output, set low
  digitalWrite(p52, LOW);
  if (!digitalRead(p53))
    return 0x08;  // Right
  pinMode(p52, INPUT);  // set p52 back to input and pull-up
  digitalWrite(p52, HIGH);

  // Read left button
  pinMode(p51, OUTPUT);  // Set p51 to output and low
  digitalWrite(p51, LOW);
  if (!digitalRead(p53))
    return 0x10;  // Left
  pinMode(p51, INPUT);  // Set p51 back to input and pull-up
  pinMode(p51, HIGH);

  return 0;
}


void playSong(){
  digitalWrite(speakerOut, LOW);     
  for (beat = 0; beat < MAXCOUNT; beat++) {
    statePin = !statePin;
    digitalWrite(ledPin, statePin);

    j = song[beat * 2];
    timeUp = (j < 0) ? 0 : timeUpDown[j];

    period = (timeUp ? (1000000 / timeUp) / 2 : 250) * TEMPO_SECONDS
      * 4 / song[beat * 2 + 1];
    if (period < 0)
      period = period * -3 / 2;
    for (j = 0; j < period; j++) {
      digitalWrite(speakerOut, timeUp ? HIGH : LOW);
      delayMicroseconds(timeUp ? timeUp : 2000);
      digitalWrite(speakerOut, LOW);
      delayMicroseconds(timeUp ? timeUp : 2000);
    }
    delay(50);
  }
  digitalWrite(speakerOut, LOW);
  //delay(1000);

}

void testMic(){

  // read the input on analog pin 0:
  int micro = analogRead(microphone);
  // Convert the analog reading (which goes from 0 - 1023) to a voltage (0 - 5V):
  float voltage = micro * (5.0 / 1023.0);
  // print out the value you read:
  Serial.println(voltage);

}

void getDust(){
  digitalWrite(ledPowerPin,LOW); // power on the LED
  delayMicroseconds(delayTime);
  dustVal=analogRead(dustPin); // read the dust value via pin 5 on the sensor
  delayMicroseconds(delayTime2);
  digitalWrite(ledPowerPin,HIGH); // turn the LED off
  delayMicroseconds(offTime);
}

//Rounds down (via intermediary integer conversion truncation)
String doubleToString(double input,int decimalPlaces){
  String string;
  if(decimalPlaces!=0){
    string = String((int)(input*pow(10,decimalPlaces)));
    if(abs(input)<1){
      if(input>0)
        string = "0"+string;
      else if(input<0)
        string = string.substring(0,1)+"0"+string.substring(1);
    }
    return string.substring(0,string.length()-decimalPlaces)+"."+string.substring(string.length()-decimalPlaces);
  }
  else {
    return String((int)input);
  }
}







