/*
should acceleration and accelerometer also be averaged? -> no

TODO: Sensing strategy?
TODO: Replace SoftwareSerial with hardware serial.
TODO: Set device id dynamicaly
*/
#include <SD.h>
#include <DHT.h>
#include <Wire.h>
#include <RTClib.h>
#include <LSM303.h>
#include <SoftwareSerial.h>

#define DHTTYPE DHT22 // Sensortype = DHT 22
#define DHTPIN 2 // DHT22 connected to digital 2

DHT dht(DHTPIN, DHTTYPE);
SoftwareSerial mySerial(10, 11); //Initialize GPRS-Shield
LSM303 accelerometer;
RTC_DS1307 RTC;
File dataFile;

int deviceId = 123;

// Network Variables needed for Position Information 
String mcc; //Mobile Country Code
String mnc; //Mobile Network Code
String lac; //Location Area Code
String cid; //Cell ID
String connectionStatus;

int batteryPin = A4;
int batteryValue = 0;
// intervalls in seconds:
long sampleInterval = 5; // intervall for sampling raw data
long measureInterval = 10; // intervall for averaging the raw data
long uploadInterval = 20; // intervall for uploading saved averged data and saved events

int time; //unixtime
DateTime timeOld = 0; // last Sampling was made
DateTime lastMeasurement; // last Measurement was made
DateTime lastUpload; // last Upload was made
DateTime crashTime; 
DateTime measureTime;
DateTime openTime;

//sensor sample variables:
float temperature = 0; // current temperaure value
float humidity = 0; // current humidity value
int light = 0; // current light value. Calculation to LUX is done on server.
double accData[3];
double g; //Acceleration
double go=2000; //Min value for executing event (2g)

boolean opened= true;
boolean crashed = false;
boolean connectOk;
boolean serverResponded;

//sum variables for averaging function:
float sumTemp = 0;
float sumHumi = 0;
//avg variables for temperature and humidity
int avgTemp = 0;
int avgHum = 0;
int loopCounter = 0; // number of measurements for computing the averages

void setup()
{
  mySerial.begin(19200);//Instanciate Software Serial communication.
  Serial.begin(19200);//Instanciate serial monitor. Just for debugging..
  delay(500);
  dht.begin();
  Wire.begin();
  RTC.begin();
  accelerometer.init();
  accelerometer.enableDefault();
  if (! RTC.isrunning()) {
    Serial.println("RTC is NOT running!");
     //RTC.adjust(DateTime(__DATE__, __TIME__)); //uncomment this line if RTC runs for first time
     Serial.println("RTC needs to be adjusted.");
  }
  pinMode(53, OUTPUT);
  if (!SD.begin(53)) {
    Serial.println("initialization SD CARD failed!");
  }
  delay(500);  
  Serial.println("Removing existing files.");
  if (SD.exists("logfile.txt"))
    SD.remove("logfile.txt");
  if (SD.exists("opened.txt"))
    SD.remove("opened.txt");
  if (SD.exists("crashed.txt")) 
    SD.remove("crashed.txt");
  Serial.println("Powering on GPRS Shield.");  
  RestartShield();
  delay(5000); // Waiting for GSM Signal
  Serial.println("Connecting to GSM Network."); 
  Reconnect();
  delay(5000); // Waiting for service
  timeOld = RTC.now();
  lastMeasurement = RTC.now();
  lastUpload = RTC.now();
}

void loop()
{
  // reading event-triggering sensordata
  light = analogRead(A3);
  
  accelerometer.read();
  accData[0] = (double) accelerometer.a.x;
  accData[1] = (double) accelerometer.a.y;
  accData[2] = (double) accelerometer.a.z;
  
  //sampling time based on millis; RTC time should be used instead
  DateTime time = RTC.now();
  //Event-trigger: Lightvalue Threshold reached? / parcel opened?
  if ((light > 200) && (!opened)) {
    openTime = RTC.now();
    opened = true;
    Serial.println("EVENT OCCURED: Parcel opened!");
    String blub = String(deviceId);
    blub += ";";
    blub += openTime.unixtime();
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
    blub += ";";
    printlnSD(blub, 2);
    //Save light event string to SD
    File tempFile = SD.open("logtemp.txt", FILE_WRITE);
    if(tempFile){
      tempFile.println(blub);
      tempFile.close();
    }
  }
  if ((opened) && (light<160))
    opened = false;
  // Acceleration Threshold reached? / parcel crashed?
  g = sqrt(accData[0]*accData[0]+accData[1]*accData[1]+accData[2]*accData[2])/1000;
  if ((go!=g) && (g>2.5) && (!crashed))
  {
    crashTime = RTC.now();
    crashed = true;
    go = g;
    g *= 10;
    Serial.println("EVENT OCCURED: Parcel crashed!");
    String blub = String(deviceId);
    blub += ";";
    blub += crashTime.unixtime();
    blub += ";";
    blub += cid;
    blub += ";";
    blub += mcc; 
    blub += ";";
    blub += mnc; 
    blub += ";";
    blub += lac; 
    blub += ";";
    blub += String((int)g);
    blub += ";";
    printlnSD(blub, 3);
    File tempFile = SD.open("crashtemp.txt", FILE_WRITE);
    if(tempFile){
      tempFile.println(blub);
      tempFile.close();
    }
  }
  
  if (DiffBiggerOrEqual(time,timeOld,sampleInterval)) //Sampling intervall (according to intervall variable)
  {
    loopCounter++;
    crashed = false; // is that correct ?
    Serial.println("S A M P L E  N R. " + String(loopCounter) + ",  T I M E: " + String(millis()/1000) + " S E C");
    //time = RTC.now();
  
    //read the sensor values over here (change the following lines):
    humidity = dht.readHumidity();
    temperature = dht.readTemperature();
 
    // add measured values to the sum variables for computing the average
    sumTemp += temperature;
    sumHumi += humidity;
    
    // time for new Measurement? (every 10seconds)
    if (DiffBiggerOrEqual(time, lastMeasurement, measureInterval))
    {
      GetMccMncCid();
      GetLac();
      Serial.println("C O M P U T I N G  A V E R A G E S ");
      //compute the average
      avgTemp = (int)(sumTemp / loopCounter);
      avgHum = (int)(sumHumi / loopCounter);
  
      lastMeasurement = time; // update time for last measurement
      loopCounter = 0; // setting number of measurements to zero
      // setting the sum's to zero
      sumTemp = 0;
      sumHumi = 0;
      
      batteryValue=analogRead(batteryPin);
      int mapVal = map(batteryValue, 0, 1023, 1, 500);//Convert to Voltage
      int batVal = map(mapVal, 300, 400, 1, 100)-2;//assuming that full charged battery is at 4V and minimum 3V
      if(batVal<1)batVal = 0;
      else if(batVal>100)batVal = 100;
      
      measureTime = RTC.now();
      
      String blub = String(deviceId);
      blub += ";";
      blub += measureTime.unixtime();
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
      blub += batVal;
      blub += ";";
      Serial.println(blub);
      printlnSD(blub, 1);
      
      File tempFile = SD.open("logtemp.txt", FILE_WRITE);
      if(tempFile){
        tempFile.println(blub);
        tempFile.close();
      }
      
      if (DiffBiggerOrEqual(time, lastUpload, uploadInterval))
      {
        Serial.println("U P L O A D  D A T A");
        if(getSignalStatus()=="attached" && IsIpAvailable()){
            Serial.println("Attached and connected.");
            if (SD.exists("logfile.txt")){
              delay(2000);
              Serial.println("Trying to send measurments.");
              TcpPost(1);
            }
            if (SD.exists("opened.txt")){
              delay(2000);  
              Serial.println("Trying to send open events.");
              TcpPost(2);
            }
            if (SD.exists("crashed.txt")){
              delay(2000);
              Serial.println("Trying to send crash events.");
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
        lastUpload = RTC.now(); // update time for last Upload
        lastMeasurement = RTC.now();
     }
    }
    timeOld = time; // update time for last sampling
  }
}
boolean DiffBiggerOrEqual(DateTime a, DateTime b, long timeDiff){
  long c = a.unixtime() - b.unixtime();
  return (c >= timeDiff);
}

void printlnSD(String str, int fileName)
{
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
    dataFile.print(str);
    dataFile.close();
  }
  // if the file isn't open, pop up an error:
  else {
    Serial.println("error opening datalog.txt");
  }
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
  GetMccMncCid();
  delay(1000);
  GetLac();
}

void GetMccMncCid()
{
  int kommaZaehler = 0;
  mcc = "";
  mnc = "";
  cid = "";

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
  if(mcc.length()==0) mcc = "0";
  if(mnc.length()==0) mnc = "0";
  if(cid.length()==0) cid = "0";
  Serial.println("mcc:" + mcc + " mnc:"+ mnc + " cell-ID:" + cid);
  delay(500);
}

void GetLac(){
  int kommaZaehler = 0;
  lac = "";
  
  mySerial.println("AT+CREG?");
  delay(100);
  
  while(mySerial.available()!=0){
    byte in = mySerial.read();
    Serial.print(char(in));
    //if(char(in) != '\n')
    //input += char(in);
    if ((kommaZaehler==2) && (char(in)!='"') && (char(in)!=','))
      lac += char(in);
    if (char(in)==','){
      kommaZaehler++;
    }
  }
  Serial.println("LAC: " + lac);
  if(lac.length()==0 || lac==" ") lac = "0";
  delay(500);
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

void TcpPost(int postOption){

  String serverResponse = "";
  unsigned long retry = 0;
  connectOk = false;
  serverResponded = false;

  delay(500);
  
  mySerial.println("AT+CIPSTART=\"TCP\",\"128.176.146.214\",\"80\"");//start up the connection
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
 
  //set http headder
  if(postOption==1)
    mySerial.println("POST /rest/index.php/postMeasurement HTTP/1.1 ");
  else if(postOption==2)
    mySerial.println("POST /rest/index.php/postLight HTTP/1.1 ");
  else if(postOption==3)
    mySerial.println("POST /rest/index.php/postShock HTTP/1.1 ");
  delay(100);
  ShowSerialData();
  
  mySerial.println("Host: potwech.uni-muenster.de");
  delay(100);
  ShowSerialData();
   
  mySerial.println("Accept: application/json");
  delay(100);
  ShowSerialData();
   
  //open file and check length
  if(postOption == 1)
    dataFile = SD.open("logfile.txt");
  else if(postOption==2)
    dataFile = SD.open("opened.txt");
  else if(postOption==3)
    dataFile = SD.open("crashed.txt");
   
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
  } else {
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
    SD.remove("logfile.txt");
    Serial.println("logfile.txt removed!");
  }
  else if(postOption==2){
    SD.remove("opened.txt");
    Serial.println("opened.txt removed!");
  }
  else if(postOption==3){
    SD.remove("crashed.txt");
    Serial.println("crashed.txt removed!");
  }  

  delay(1000);
  
  mySerial.println("AT+CIPCLOSE");//close the TCP connection
  delay(2000);
  ShowSerialData();
  delay(500); 
  
  Serial.println("Upload Complete!");
}

//indicates state of GPRS connection
//returns "attached", "detached" or "deactivated";
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
