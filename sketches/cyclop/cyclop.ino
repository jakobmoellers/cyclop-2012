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
#include <SoftwareSerial.h>


//PINs
#define DHTPIN 40 //DHT
#define DHTTYPE DHT11 // DHT 11
//int ledPin = 13;
const int WaterPin=45; //Water sensor
char xPin = A15; //MMA7361L Three Axis Accelerometer
char yPin = A14;
char zPin = A13;
int selPin = 12;
int sleepPin = 13;
int rxPin = 19; //GPS
int txPin = 18;
int vibration1 = 42; //Vibration
int vibration2 = 43;
int LEDpin = 48; //Button
int p51 = 49;
int p52 = 46;
int p53 = 47;
int no_pin = A0;
int co_pin = A1;
int alarmPin = 44;
const byte speakerOut = 39; //Speaker
const char microphone = A5; //Microphone
char dustPin = A4; //Dust Sensor
int ledPowerPin = 2;
int SDPin = 53; //SD
int GPRSPin1 = 10; //GPRS
int GPRSPin2 = 11;
int GPRSPinPower = 9;

//Variables
boolean alarm=false; //True if alarm is armed
const float scalePoints = 1023/5; //Accelerometer
float xValue, yValue, zValue;
float gxValue, gyValue, gzValue;
float degxValue, degyValue, degzValue;
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
DHT dht(DHTPIN, DHTTYPE);
RTC_DS1307 RTC; //RTC
DateTime currentTime;
DateTime lastUpload;
DateTime lastStore;
long uploadInterval = 40;
long storeInterval = 20;
int button = 0; //Button
int ledLevel = 255;
int onstatus = 0;
unsigned int timeUpDown[128]; //Speaker
const byte BPM = 200;
const char song[]={
  64,8,64,8,64,4};
/*
const char song[] = {
 64,4,64,4,65,4,67,4, 67,4,65,4,64,4,62,4,
 60,4,60,4,62,4,64,4, 64,-4,62,8,62,2,
 64,4,64,4,65,4,67,4, 67,4,65,4,64,4,62,4,
 60,4,60,4,62,4,64,4, 62,-4,60,8,60,2,
 62,4,62,4,64,4,60,4, 62,4,64,8,65,8,64,4,60,4,
 62,4,64,8,65,8,64,4,62,4, 60,4,62,4,55,2,
 64,4,64,4,65,4,67,4, 67,4,65,4,64,4,62,4,
 60,4,60,4,62,4,64,4, 62,-4,60,8,60,2};*/
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
SoftwareSerial mySerial(GPRSPin1, GPRSPin2); //GPRS
boolean connectOk;
boolean serverResponded;
int averageCounter=0;

//Measurements
boolean rain=false;
double no2_ppm;
double co_ppm;
double gValue;
String lat;
String lon;
float temperature;
float humidity;
int secretKey=1337;
int deviceId=5;
double dustVal;
float noise;

//Sum
double no2Sum;
double coSum;
double gValueSum;
float tempSum;
float humiSum;
double dustSum;
float noiseSum;

/*
Main methods
 */

void setup(){

  Serial.begin(9600);
  mySerial.begin(19200);


  //Water sensor
  pinMode(WaterPin, INPUT);

  //Acc
  digitalWrite(sleepPin,HIGH);
  digitalWrite(selPin,HIGH);
  getCalValues();

  //GPS
  Serial1.begin(9600);
  pinMode(rxPin, INPUT);
  pinMode(txPin, OUTPUT);
  for (int i=0;i<300;i++)
  { // Initialize a buffer for received data
    linea[i]=' ';
  }

  //DHT
  dht.begin();

  //Display
  Wire.begin();
  SeeedOled.init(); //initialze SEEED OLED display
  DDRB|=0x21; //digital pin 8, LED glow indicates Film properly Connected .
  PORTB |= 0x21;
  SeeedOled.clearDisplay(); //clear the screen and set start position to top left corner
  SeeedOled.setNormalDisplay(); //Set display to normal mode (i.e non-inverse mode)
  SeeedOled.setHorizontalMode();
  SeeedOled.putString("Welcome to Cyclop!"); //Print the String

    //RTC
  RTC.begin();
  Serial.print("Initializing RTC...");
  if (! RTC.isrunning()) {
    Serial.println("RTC is NOT running!");
    //set the RTC to the date & time this sketch was compiled
    RTC.adjust(DateTime(__DATE__, __TIME__));
  }
  else Serial.println("done.");

  currentTime = RTC.now();
  lastUpload = RTC.now();
  lastStore = RTC.now();

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
  //pinMode(ledPin, OUTPUT);
  pinMode(speakerOut, OUTPUT);
  for (j = 128; j--;)
    timeUpDown[j] = 1000000 / (pow(2, (j - 69) / 12.0) * 880);
  playSong();

  //Mic
  //TODO do i have to declare the PIN mode here?
  pinMode(microphone,INPUT);

  //Dust Sensor
  pinMode(ledPowerPin,OUTPUT);

  //Alarm
  pinMode(alarmPin,INPUT);
  oldTest = digitalRead(alarmPin);
  magnetSensor= oldTest;

  //SD
  Serial.print("Initializing SD card...");
  pinMode(SDPin,OUTPUT);
  if (!SD.begin(SDPin)) {
    Serial.println("failed!");
  }
  else Serial.println("done.");

  delay(500);
  Serial.println("Removing existing files.");
  if (SD.exists("measure.txt"))
    SD.remove("measure.txt");
  if (SD.exists("hazards.txt"))
    SD.remove("hazards.txt");
  if (SD.exists("thefts.txt"))
    SD.remove("thefts.txt");

  //GPRS
  /*Serial.println("Powering on GPRS Shield.");
   RestartShield();
   delay(5000); // Waiting for GSM Signal
   Serial.println("Connecting to GSM Network.");
   Reconnect();
   delay(5000); // Waiting for service */

  resetVariablesForAveraging();

  Serial.println("initialization complete");

}

void loop(){

  currentTime=RTC.now();

  //TODO: Make Display announcements to ease debugging.

  //TODO Download new hazards in a time interval of 1 minute

  //TODO What about the measurement process? Also every minute?
  //Should be included here. I think this comment is obsolete because the m
  //asurements are averaged

  getPosition();
  Serial.println(lat);
  Serial.println(lon);

  determineAlertMode();

  if(alarm==true){
    //Alarm mode
    resetVariablesForAveraging();

    getAcc();

    //Serial.println(gValue);

    if (gValue>1.05||gValue<0.95){
      //Serial.println(gValue);
      Serial.println("Theft detected");
      storeTheft();
      Serial.println("Theft stored. Try upload");
      uploadTheft();
    }



  }
  else {

    //Standard mode

      takeMeasurements();

    //Serial.println(currentTime.unixtime());
    //Serial.println(lastStore.unixtime());

    if (DiffBiggerOrEqual(currentTime,lastStore,storeInterval)){
      storeMeasurement();
      resetVariablesForAveraging();
      Serial.println("Storing and averaging complete");
    }

    checkforHazardButtonPressed();

    if (DiffBiggerOrEqual(currentTime,lastUpload,uploadInterval)){
      uploadMeasurements();
    }

    uploadHazards();

  }
}

/*
Help Methods
 */

void resetVariablesForAveraging(){
  averageCounter=0;
  tempSum = 0;
  humiSum = 0;
  gValueSum = 0;
  no2Sum = 0;
  coSum = 0;
  noiseSum = 0;
  rain=false;
}


void takeMeasurements(){

  averageCounter=averageCounter+1;

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
  //
  getAcc();
  Serial.print(" g: ");
  Serial.print(gValue);

  //Dust;
  getDust();
  Serial.print(" du: ");
  Serial.print(dustVal);

  //DHT
  humidity = dht.readHumidity();
  humiSum += humidity;
  Serial.print(" hum: ");
  Serial.print(humidity);
  temperature = dht.readTemperature();
  tempSum += temperature;
  Serial.print(" temp: ");
  Serial.print(temperature);

  //Mic
  getNoise();
  Serial.print(" noise: ");
  Serial.print(noise);

  Serial.println("");

}

void storeMeasurement(){

  String measurement = String(rain);
  measurement +=";";
  measurement +=currentTime.unixtime();
  measurement +=";";
  measurement +=doubleToString((no2Sum/averageCounter),2);
  measurement +=";";
  measurement +=doubleToString((coSum/averageCounter),2);
  measurement +=";";
  measurement +=doubleToString((gValueSum/averageCounter),2);
  measurement +=";";
  //measurement +=doubleToString(lat,8);
  measurement+=lat;
  measurement +=";";
  //measurement +=doubleToString(lon,8);
  measurement+=lon;
  measurement +=";";
  char tmpBuffer1[10];
  measurement += floatToString(tmpBuffer1,(tempSum/averageCounter),2);
  measurement +=";";
  char tmpBuffer2[10];
  measurement += floatToString(tmpBuffer2,(humiSum/averageCounter),2);
  measurement +=";";
  measurement += String(secretKey);
  measurement +=";";
  measurement +=doubleToString((dustSum/averageCounter),2);
  measurement +=";";
  char tmpBuffer3[10];
  measurement +=floatToString(tmpBuffer3,(noiseSum/averageCounter),2);
  measurement +=";";
  measurement +=String(deviceId);
  measurement +=";";
  Serial.println(measurement);
  printlnSD(measurement,1);

  //Backup
  File tempFile = SD.open("logtempm.txt", FILE_WRITE);
  if(tempFile){
    tempFile.println(measurement);
    tempFile.close();
    Serial.println("Measurement saved to logtempm.txt");
  }
  else Serial.println("Error opening logtempm.txt.");

  lastStore=RTC.now();

}

void storeHazard(){

  String hazard = "Hazard";
  hazard+=";";
  hazard+=currentTime.unixtime();
  hazard+=";";
  //hazard+=doubleToString(lat,8);
  hazard+=lat;
  hazard+=";";
  //hazard+=doubleToString(lon,8);
  hazard+=lon;
  hazard+=";";
  hazard+= String(secretKey);
  hazard+=";";
  hazard+=String(deviceId);
  hazard+=";";

  Serial.println(hazard);
  printlnSD(hazard,2);

  //Backup
  File tempFile = SD.open("logtemph.txt", FILE_WRITE);
  if(tempFile){
    tempFile.println(hazard);
    tempFile.close();
    Serial.println("Hazard saved to logtemph.txt.");
  }

}

void storeTheft(){

  String theft = "Theft";
  theft+=";";
  theft+=currentTime.unixtime();
  theft+=";";
  //  theft+=doubleToString(lat,8);
  theft+=lat;
  theft+=";";
  //  theft+=doubleToString(lon,8);
  theft+=lon;
  theft+=";";
  theft+= String(secretKey);
  theft+=";";
  theft+=String(deviceId);
  theft+=";";

  Serial.println(theft);
  printlnSD(theft,3);

  File tempFile = SD.open("logtempt.txt", FILE_WRITE);
  if(tempFile){
    tempFile.println(theft);
    tempFile.close();
    Serial.println("Theft saved to logtempt.txt.");
  }

}

void printlnSD(String str, int fileName){
  if (fileName == 1){
    dataFile = SD.open("measure.txt", FILE_WRITE);
  }
  else if (fileName == 2){
    dataFile = SD.open("hazards.txt", FILE_WRITE);
  }
  else if (fileName == 3){
    dataFile = SD.open("thefts.txt", FILE_WRITE);
  }
  // if the file is available, write to it:
  if (dataFile) {
    dataFile.print(str);
    dataFile.close();
    Serial.println("Saved to SD!");
  }
  // if the file isn't open, pop up an error:
  else {
    Serial.println("error opening datalog.txt");
  }
}

void uploadMeasurements(){
  Serial.println("U P L O A D M E A S U R E");
  if(getSignalStatus()=="attached" && IsIpAvailable()){
    Serial.println("Attached and connected.");
    if (SD.exists("measure.txt")){
      delay(2000);
      Serial.println("Trying to send measurments.");
      TcpPost(1);
      lastUpload=RTC.now();
    }
  }
  else if(getSignalStatus()=="deactivated"){
    Serial.println("GPRS Shield is offline...restarting.");
    RestartShield();
  }
  else if(!IsIpAvailable() || getSignalStatus()=="detached"){
    Serial.println("Detached from GPRS or not connected...reconnecting.");
    Reconnect();
  }

}

void uploadHazards(){
  if (SD.exists("hazards.txt")){
    Serial.println("U P L O A D  H A Z A R D S");
    if(getSignalStatus()=="attached" && IsIpAvailable()){
      Serial.println("Attached and connected.");
      if (SD.exists("hazards.txt")){
        delay(2000);
        Serial.println("Trying to send open events.");
        TcpPost(2);
      }
    }
    else if(getSignalStatus()=="deactivated"){
      Serial.println("GPRS Shield is offline...restarting.");
      RestartShield();
    }
    else if(!IsIpAvailable() || getSignalStatus()=="detached"){
      Serial.println("Detached from GPRS or not connected...reconnecting.");
      Reconnect();
    }
  }
}

void uploadTheft(){
  if (SD.exists("thefts.txt")){
    Serial.println("U P L O A D  T H E F T S");
    if(getSignalStatus()=="attached" && IsIpAvailable()){
      Serial.println("Attached and connected.");
      if (SD.exists("thefts.txt")){
        delay(2000);
        Serial.println("Trying to send open events.");
        TcpPost(3);
      }
    }
    else if(getSignalStatus()=="deactivated"){
      Serial.println("GPRS Shield is offline...restarting.");
      RestartShield();
    }
    else if(!IsIpAvailable() || getSignalStatus()=="detached"){
      Serial.println("Detached from GPRS or not connected...reconnecting.");
      Reconnect();
    }
  }
}

void TcpPost(int postOption){

  String serverResponse = "";
  unsigned long retry = 0;
  connectOk = false;
  serverResponded = false;

  delay(500);

  mySerial.println("AT+CIPSTART=\"TCP\",\"128.176.147.30\",\"80\"");//start up the connection
  //wait for the correct response "CONNECT OK"; return after 20 retries
  while (!connectOk){
    delay(100);
    if(retry==200){
      Serial.println("Upload canceled after 20 seconds.");
      mySerial.println("AT+CIPCLOSE");//close the TCP connection
      delay(2000);
      ShowSerialData();
      return;
    }
    WaitForConnectOk();
    retry++;
  }

  //Serial.println("Correct answer after retry number " + String(retry));
  delay(2000);

  mySerial.println("AT+CIPSEND");//wait for '>'
  delay(4000);
  ShowSerialData();
  delay(2000);

  //set http header
  if(postOption==1)
    mySerial.println("POST /rest/index.php/postMeasurement HTTP/1.1 ");
  else if(postOption==2)
    mySerial.println("POST /rest/index.php/postHazard HTTP/1.1 ");
  else if(postOption==3)
    mySerial.println("POST /rest/index.php/postTheft HTTP/1.1 ");
  delay(100);
  ShowSerialData();

  mySerial.println("Host: giv-cyclop.uni-muenster.de");
  delay(100);
  ShowSerialData();

  mySerial.println("Accept: application/json");
  delay(100);
  ShowSerialData();

  //open file and check length
  if(postOption == 1)
    dataFile = SD.open("measure.txt");
  else if(postOption==2)
    dataFile = SD.open("hazards.txt");
  else if(postOption==3)
    dataFile = SD.open("thefts.txt");

  mySerial.println("Content-Length:" + String(dataFile.size()+4)); // Size of SD file + 'data=' - ';'
  delay(100);
  ShowSerialData();

  mySerial.println("Content-Type: application/x-www-form-urlencoded");
  delay(100);
  ShowSerialData();

  mySerial.println();//do not delete. is part of the headder!
  delay(100);
  ShowSerialData();

  mySerial.print("data=");
  delay(100);
  ShowSerialData();

  if (dataFile) {
    int i = 1;
    char in;
    //Read SD File and send Data to Serial Port
    while (dataFile.available() && i<dataFile.size()) {
      in = dataFile.read();
      mySerial.write(in);
      i++;
    }
    delay(500);
    ShowSerialData();

    dataFile.close();
    Serial.println("Ready.");
    //Serial.println(" finished!");
  }
  else {
    // if the file didn't open, print an error:
    Serial.println("error opening ");
  }

  delay(500);
  mySerial.println((char)26);//sending
  delay(5000);//waitting for reply, important! the time is base on the condition of internet
  retry = 0;
  while (!serverResponded){
    delay(100);
    if(retry==300){
      Serial.println("Server did not respond after 30 seconds.");
      mySerial.println("AT+CIPCLOSE");//close the TCP connection
      delay(2000);
      ShowSerialData();
      return;
    }
    WaitForServerResponse();
    retry++;
  }
  Serial.println("Correct answer after retry number " + String(retry));

  if(postOption==1){
    SD.remove("measure.txt");
    Serial.println("measure.txt removed!");
  }
  else if(postOption==2){
    SD.remove("hazards.txt");
    Serial.println("hazards.txt removed!");
  }
  else if(postOption==3){
    SD.remove("thefts.txt");
    Serial.println("thefts.txt removed!");
  }

  delay(1000);

  mySerial.println("AT+CIPCLOSE");//close the TCP connection
  delay(2000);
  ShowSerialData();
  delay(500);

  Serial.println("Upload Complete!");
}

void WaitForConnectOk(){
  String conTest = "";
  while (mySerial.available()!=0){
    conTest += char(mySerial.read());
    if(conTest.endsWith("CONNECT OK")){
      connectOk = true;
      Serial.println(conTest);
      return;
    }
  }
}

void WaitForServerResponse(){
  String response = "";
  while (mySerial.available()!=0){
    response += char(mySerial.read());
    if(response.endsWith("200 OK")){
      serverResponded = true;
      Serial.println(response);
      return;
    }
  }
}

boolean IsIpAvailable(){
  int pointCounter = 0;
  mySerial.println("AT+CIFSR");
  //get local IP adress (as response)
  delay(1000);
  while (mySerial.available()!=0){
    if(char(mySerial.read())=='.')pointCounter++;
    if(pointCounter==3) return true; //IP: XXX.XXX.XXX.XXX
  }
  return false;
}

void Reconnect(){
  int retries = 0;
  String sStatus = getSignalStatus();
  while(sStatus=="detached"){
    Serial.println("No Signal. Retrying..");
    if(retries>20)return;
    retries++;
    delay(1000);
    sStatus = getSignalStatus();
  }
  mySerial.println("AT+CIPSHUT");//close the connection
  delay(200);
  ShowSerialData();
  delay(3000);
  ConnectToGSM();
  Serial.println("..done.");
}

String getSignalStatus(){
  String signalStatus = "";
  mySerial.println("AT+CGATT?");
  delay(100);
  while(mySerial.available()!=0){
    if(char(mySerial.read())==':'){
      mySerial.read();
      signalStatus += char(mySerial.read());
    }
  }
  if(signalStatus=="1")signalStatus = "attached"; //ready to connect to APN
  else if(signalStatus=="0")signalStatus = "detached";//no signal
  else signalStatus = "deactivated";//shield deactivated
  return signalStatus;
}

void ShowSerialData()
{
  while(mySerial.available()!=0)
    Serial.write(mySerial.read());
  delay(500);
  Serial.println("------");
}

void ConnectToGSM(){
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

  mySerial.println("AT+CENG=1");
  delay(1000);
  ShowSerialData();

  delay(1000);
  /*GetMccMncCid();
   delay(1000);
   GetLac();*/
}

void RestartShield(){
  if(getConnectionStatus()=="error" && getSignalStatus()=="deactivated"){
    PowerOnOff();
  }
  else{
    PowerOnOff();
    delay(1000);
    PowerOnOff();
  }
}

void PowerOnOff()
{
  pinMode(GPRSPinPower, OUTPUT);
  digitalWrite(GPRSPinPower,LOW);
  delay(1000);
  digitalWrite(GPRSPinPower,HIGH);
  delay(2000);
  digitalWrite(GPRSPinPower,LOW);
  delay(3000);
}

String getConnectionStatus(){
  String cs = "error";
  byte in;
  mySerial.println("AT+CIPSTATUS");
  delay(500);
  while(mySerial.available()!=0){
    if(char(mySerial.read())==':'){
      mySerial.read();
      while(mySerial.available()!=0){
        in = mySerial.read();
        if(char(in)!='\n')
          cs += char(in);
      }
      return cs;
    }
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
  if (magnetSensor != oldTest){
    Serial.println("Alarm mode changed");
    oldTest = magnetSensor;
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
  xValue = analogRead(xPin);
  //Serial.print(xValue);
  yValue = analogRead(yPin);
  //Serial.print(" ");
  //Serial.print(yValue);
  zValue = analogRead(zPin);
  //Serial.print(" ");
  //Serial.println(zValue);
  gxValue = constrain((xValue/scalePoints-1.65-calValues[0])/0.8,-1,1);
  gyValue = constrain((yValue/scalePoints-1.65-calValues[1])/0.8,-1,1);
  gzValue = constrain((zValue/scalePoints-1.65-calValues[2])/0.8,-1,1);
  double tmpValue = sqrt(gxValue*gxValue+gyValue*gyValue+gzValue*gzValue);
  //Serial.print(" Acc: ");
  //Serial.println(tmpValue);
  gValue = tmpValue;
  gValueSum += gValue; 
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
  lat="";
  lon="";

  while(true){
    //if(Serial.available()){
    //digitalWrite(ledPin, HIGH);
    byteGPS=Serial1.read();         // Read a byte of the serial port
    //char test = (char) byteGPS;
    //Serial.println(test);
    //delay(100);
    //Serial.write(byteGPS);
    /*}else{
     Serial.println("not available");
     }*/
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
          
          for (int i=0;i<12;i++)
          {
            switch(i)
            {
            case 5 :
              //Serial.print("Longitude: ");
              for (int i=0;i<300;i++)
              {    //  
                linea[i]=' ';             
              }  
              //Serial.println("");
              return;

              /*case 0 :
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
               for (int i=0;i<300;i++)
               {    //  
               linea[i]=' ';             
               }  
               Serial.println("");
               return;
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
               break;*/
            }
            for (int j=indices[i];j<(indices[i+1]-1);j++)
            {
              if(i==2){
                //Serial.print(linea[j+1]); 
                lat+=linea[j+1];
              } 
              if(i==4){
                //Serial.print(linea[j+1]);
                lon+=linea[j+1];
              }
            }
            //Serial.println("");
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

}


//NO2

void getNO2(){
  //Read values and calculate rs/ro
  double reading0 = analogRead(no_pin);
  double sensorValueNo = map(reading0,0,1024,0,500);
  sensorValueNo = sensorValueNo / 100;
  double rs_no = (22000 / ((5-sensorValueNo) * sensorValueNo));
  double rs_r0_no = rs_no / 1000;
  //Estimate ppm
  double tmpNO2 = 0.013043 * rs_r0_no + 0.0086956;
  no2_ppm = tmpNO2;
  no2Sum += no2_ppm;
}

//CO
void getCO(){
  //Read values and calculate rs/ro
  double reading1 = analogRead(co_pin);
  double sensorValueCo = map(reading1,0,1024,0,500);
  sensorValueCo = sensorValueCo/100;
  double rs_co = (100000 / ((5-sensorValueCo))) * sensorValueCo;
  double rs_r0_co = rs_co / 100000;
  //Estimate ppm
  double tmpCO = -25 * rs_r0_co + 20.6;
  co_ppm = abs(tmpCO);
  coSum += co_ppm;
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
  button = getButtonState(); // Get button status
  if (button == 0x04) // FLAME
  {
    onstatus = onstatus ^ 1; // flip on/off status of LED
    analogWrite(LEDpin, ledLevel);
    Serial.println("Hazard");
    SeeedOled.clearDisplay();
    SeeedOled.putString("Hazard saved and uploaded!");
    storeHazard();
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
    return 0x01; // Down
  if (!digitalRead(p52))
    return 0x02; // Up
  if (!digitalRead(p51))
    return 0x04; // Flame

  // Read right button
  pinMode(p52, OUTPUT); // set p52 to output, set low
  digitalWrite(p52, LOW);
  if (!digitalRead(p53))
    return 0x08; // Right
  pinMode(p52, INPUT); // set p52 back to input and pull-up
  digitalWrite(p52, HIGH);

  // Read left button
  pinMode(p51, OUTPUT); // Set p51 to output and low
  digitalWrite(p51, LOW);
  if (!digitalRead(p53))
    return 0x10; // Left
  pinMode(p51, INPUT); // Set p51 back to input and pull-up
  pinMode(p51, HIGH);

  return 0;
}


void playSong(){
  digitalWrite(speakerOut, LOW);
  for (beat = 0; beat < MAXCOUNT; beat++) {
    statePin = !statePin;
    //digitalWrite(ledPin, statePin);

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

void getNoise(){
  // read the input on analog pin 0:
  int micro = analogRead(microphone);
  // Convert the analog reading (which goes from 0 - 1023) to a voltage (0 - 5V):
  float voltage = micro * (5.0 / 1023.0);
  noise = voltage;
  noiseSum += noise;
}

void getDust(){
  digitalWrite(ledPowerPin,LOW); // power on the LED
  delayMicroseconds(delayTime);
  double tmpDustVal = double(analogRead(dustPin)); // read the dust value via pin 5 on the sensor
  dustVal = abs(tmpDustVal);
  dustSum += dustVal;
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


boolean DiffBiggerOrEqual(DateTime a, DateTime b, long timeDiff){
  long c = a.unixtime() - b.unixtime();
  return (c >= timeDiff);
}

void GetRequest()
{
  //TODO: Check if attached to GPRS

  //Bearer settings (used for http as IP based application)
  mySerial.println("AT+SAPBR=3,1,\"CONTYPE\",\"GPRS\"");//the connection type: GPRS
  delay(1000);
  ShowSerialData();

  mySerial.println("AT+SAPBR=3,1,\"APN\",\"data.access.de\"");//Set access point name
  delay(4000);
  ShowSerialData();

  mySerial.println("AT+SAPBR=1,1");//open bearer for IP connection
  delay(2000);
  ShowSerialData();

  mySerial.println("AT+HTTPINIT"); //initialize HTTP request
  delay(2000);
  ShowSerialData();

  //set the website over HTTPPARA
  mySerial.println("AT+HTTPPARA=\"URL\",\"giv-cyclop.uni-muenster.de/rest/index.php/hazards_csv\"");
  delay(1000);
  ShowSerialData();

  mySerial.println("AT+HTTPACTION=0");//submit request
  delay(10000);
  //if returned data is very large, more time required
  //TODO: dynamicaly change delay time
  ShowSerialData();

  mySerial.println("AT+HTTPREAD");// read data from accessed website
  delay(300);
  //returned data stored into string.
  //TODO: check length of returned data and compare to string length
  String dat ="";
  byte in;
  while(mySerial.available()!=0){
    in=mySerial.read();
    dat += char(in);
  }

  mySerial.println(dat);
  delay(100);

  //TODO: check if HTTP service has to be terminated
}






