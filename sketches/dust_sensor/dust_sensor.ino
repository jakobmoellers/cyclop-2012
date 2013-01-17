char dustPin=A15;
int ledPowerPin=48;

int dustVal;

int delayTime=280;
int delayTime2=40;
float offTime=9680;

void setup(){
  Serial.begin(9600);
  pinMode(ledPowerPin,OUTPUT);
  pinMode(4, OUTPUT);
}

void loop(){
  // ledPower is any digital pin on the arduino connected to Pin 3 on the sensor
  digitalWrite(ledPowerPin,LOW); // power on the LED
  delayMicroseconds(delayTime);
  dustVal=analogRead(dustPin); // read the dust value via pin 5 on the sensor
  delayMicroseconds(delayTime2);
  digitalWrite(ledPowerPin,HIGH); // turn the LED off
  delayMicroseconds(offTime);

  delay(50);
  Serial.println(dustVal);
}

