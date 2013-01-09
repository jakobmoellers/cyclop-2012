//MMA7361L Three Axis Accelerometer

int xPin = 3;
int yPin = 2;
int zPin = 1; 
int selPin = 6;
int sleepPin = 5;

float xValue, yValue, zValue;
float gxValue, gyValue, gzValue;
float degxValue, degyValue, degzValue;;
const float scalePoints = 1023/5;

float calValues[3] = {0,0,0};
int numReadings = 0;
int maxNumReadings = 20;
long dt = 250;
long lastTime = 0;


void setup() {
  Serial.begin(9600);
  digitalWrite(sleepPin,HIGH);
  digitalWrite(selPin,HIGH); 
  getCalValues();
}

void loop() {
  
  xValue = analogRead(xPin);
  yValue = analogRead(yPin);
  zValue = analogRead(zPin);
  
  gxValue = constrain((xValue/scalePoints-1.65-calValues[0])/0.8,-1,1);
  gyValue = constrain((yValue/scalePoints-1.65-calValues[1])/0.8,-1,1);
  gzValue = constrain((zValue/scalePoints-1.65-calValues[2])/0.8,-1,1);
    
  double gValue = sqrt(gxValue*gxValue+gyValue*gyValue+gzValue*gzValue);
  Serial.println(gValue);
 
  delay(100);
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

