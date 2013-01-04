#include <SoftwareSerial.h>
#include <SD.h>
 
SoftwareSerial mySerial(7, 8);
//device id: 
File dataFile;

void setup()
{
  mySerial.begin(19200);     // the GPRS baud rate   
  Serial.begin(19200);    // the GPRS baud rate 
  delay(500);
  
  Serial.print("Initializing SD card...");
  // On the Ethernet Shield, CS is pin 4. It's set as an output by default.
  // Note that even if it's not used as the CS pin, the hardware SS pin 
  // (10 on most Arduino boards, 53 on the Mega) must be left as an output 
  // or the SD library functions will not work. 
   pinMode(10, OUTPUT);
   
  if (!SD.begin(10)) {
    Serial.println("initialization failed!");
    return;
  }
  Serial.println("initialization done.");
}

void loop()
{  if (Serial.available())
    switch(Serial.read())
   {
     case 'p':
       PowerOnOff();
       break;
     case 'g':
       GSMStatus();
       break;
     case 'c':
       ConnectAndPost();
       break;     
//     case 's':
//       Serial.print("data=");
//      // Serial.println(json);
//       Serial.println("Print Test: " + String("12341"));
//       break;
     case 'l':
     // Serial.println(String(sizeof(json)));
       Serial.println("Stuff\x1A");
       break;  
     case 's':
     // Serial.println(String(sizeof(json)));
   //    sendData();
       break;          
       
   } 
  if (mySerial.available())
    Serial.write(mySerial.read());
  //delay(100);
}

//Turn on or off 
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

//Query GSM network registration status
void GSMStatus(){

  mySerial.println("AT+CPIN?");
  delay(1000); 
  ShowSerialData();
  
  mySerial.println("AT+CSQ");
  delay(1000);
  ShowSerialData();
 
  mySerial.println("CREG=2");
  delay(4000);
  ShowSerialData();

//  mySerial.println("CREG?");
//  delay(4000);
//  ShowSerialData();

}

//----------Start Task and Activate Wireless Connection--------------
void ConnectAndPost(){
//  mySerial.println("AT+CGATT?");
//  //Query network Status
//  delay(1000);
//  ShowSerialData();

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

//  mySerial.println("AT+CIPSTATUS");
//  //Query current connection status
//  delay(1000);
//  ShowSerialData();
//
//  mySerial.println("AT+CIPHEAD=1");
//  //to add an 'IP Header' to receive data
//  delay(1000);
//  ShowSerialData();

//  mySerial.println("AT+CIPCSGP=1");
//  //query the IP address of the given domain name
//  delay(1000);
//  ShowSerialData();

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

 dataFile = SD.open("logfile.txt"); 
 
//   mySerial.println("POST /rest/index.php/post2 HTTP/1.1 ");
   
//   mySerial.print("data={\"temperature\":66}");
//   delay(100);
//   ShowSerialData();
   

           if (dataFile) {
            Serial.println("Reading logfile.txt...");
            int i = 0;
            // read from the file until there's nothing else in it:
            while (dataFile.available()) {
              //Serial.write(dataFile.read());  
              if(i%42 == 0){
              
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
              
                 mySerial.println("Content-Length: 46");
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
              }
              mySerial.write(dataFile.read());
              i++;
              
            }
            // close the file:
            
            dataFile.close();
            Serial.println(" finished!");
          } else {
            // if the file didn't open, print an error:
            Serial.println("error opening test.txt");
          }
   
  mySerial.println((char)26);//sending
  delay(1000);//waitting for reply, important! the time is base on the condition of internet 
  mySerial.println();
  ShowSerialData(); 
 
//  //PRINT DATA TO mySerial
//  mySerial.println((char)26);//sending
//  delay(5000);//waitting for reply, important! the time is base on the condition of internet 
//  mySerial.println();
//  ShowSerialData();

  mySerial.println("AT+CIPCLOSE");//close the TCP connection
  delay(100);
  ShowSerialData();  

//  mySerial.println("AT+CIPSHUT");//close the GPRS connection
//  delay(100);
//  ShowSerialData();
}

void ShowSerialData()
{
  while(mySerial.available()!=0)
    Serial.write(mySerial.read());
    delay(500);
    Serial.println();
    Serial.println("------");
}
