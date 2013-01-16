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


//PINs
const int WaterPin=42; //Water sensor
int xPin = 2; //MMA7361L Three Axis Accelerometer
int yPin = 3;
int zPin = 4; 
int selPin = 6;
int sleepPin = 5;
int rxPin = 0; //GPS
int txPin = 1;  
#define DHTPIN 24 //DHT
int vibration1=46; //Vibration
int vibration2=47;
int LEDpin=38; //Button
int p51=39;
int p52=41;
int p53=40;
int no_pin=4;
int co_pin=5;
int alarmPin=48;

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


}

void loop(){

  //Reset variables
  rain=false;

  //TODO Download new hazards in a time interval of 1 minute

    //TODO What about the measurement process? Also every minute? Should be included here

    getPosition();

  //TODO Button does not work

  //TODO GPS does not work

  Serial.print("lat: ");
  Serial.print(lat);
  Serial.print(" lon: ");
  Serial.print(lon);

  //TODO Determine whether in alarm mode or standard mode
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

    //TODO Upload measurements //Remember to upload secret key

    //TODO Clean SD-Card

    //TODO Display hazards on display

    //TODO If hazard button is touched, create new harzard. Confirm with sound/blink.

    checkforHazardButtonPressed();


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

  //DHT
  humidity = dht.readHumidity();
  Serial.print(" hum: ");
  Serial.print(humidity);
  temperature = dht.readTemperature();
  Serial.print(" temp: ");
  Serial.print(temperature);

  Serial.println("");

}

void determineAlertMode(){

  //TODO Build working switch

  /*int alarmInput = digitalRead(alarmPin);
   if (alarmInput==0){
   alarm=true;
   Serial.println("Alarm activated");
   } 
   else {
   alarm=false;
   Serial.println("Alarm deactivated");
   }*/

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
  } 
  else 
  {
    linea[conta]=byteGPS;        // If there is serial port data, it is put in the buffer
    conta++;                      
    Serial.write(byteGPS); 
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
        /*Serial.println("");      // ... and write to the serial port
         Serial.println("");
         Serial.println("---------------");*/
        for (int i=0;i<12;i++)
        {
          switch(i)
          {
          case 0 :
            //Serial.print("Time in UTC (HhMmSs): ");
            break;
          case 1 :
            //Serial.print("Status (A=OK,V=KO): ");
            break;
          case 2 :
            Serial.print("Latitude: ");
            Serial.print(linea[2]);
            //TODO: Write lon to variable. Check first whether this works
            break;
          case 3 :
            //Serial.print("Direction (N/S): ");
            break;
          case 4 :
            Serial.print("Longitude: ");
            Serial.print(linea[4]);
            //TODO: Write lat to variable. Check first whether this works
            break;
          case 5 :
            //Serial.print("Direction (E/W): ");
            break;
          case 6 :
            //Serial.print("Velocity in knots: ");
            break;
          case 7 :
            //Serial.print("Heading in degrees: ");
            break;
          case 8 :
            //Serial.print("Date UTC (DdMmAa): ");
            break;
          case 9 :
            //Serial.print("Magnetic degrees: ");
            break;
          case 10 :
            //Serial.print("(E/W): ");
            break;
          case 11 :
            //Serial.print("Mode: ");
            break;
          case 12 :
            //Serial.print("Checksum: ");
            break;
          }
          /*for (int j=indices[i];j<(indices[i+1]-1);j++){
           Serial.print(linea[j+1]); 
           }*/
          Serial.println("");
        }
        //Serial.println("---------------");
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
  //Serial.print(" NO2 Rs/R0: ");
  //Serial.print(rs_r0_no);
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
  //Serial.print(" CO Rs/R0: ");
  //Serial.print(rs_r0_co);
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









