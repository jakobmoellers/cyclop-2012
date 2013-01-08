const int WaterPin=7;//The pin used to detect water
//const int buzzerPin=12;//The pin used to control the buzzer
 
void setup() {
    pinMode(WaterPin, INPUT);
    //pinMode(buzzerPin,OUTPUT);
    Serial.begin(9600);
}
 
void loop() {
    int sensorValue = digitalRead(WaterPin);
    if(sensorValue==0)
        Serial.println("Water");
    //else
        //digitalWrite(buzzerPin,LOW);
}
