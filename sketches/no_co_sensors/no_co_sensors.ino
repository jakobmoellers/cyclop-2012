double sensorValueNo;
double sensorValueCo;
double reading0;
double reading1;

void setup()
{
  Serial.begin(9600);
}

void loop()
{
  //NO2
  //Read values and calculate rs/ro
  reading0 = analogRead(0);
  sensorValueNo= map(reading0,0,1024,0,500);
  sensorValueNo= sensorValueNo/100;
  double rs_no=(22000/((5-sensorValueNo)*sensorValueNo));
  double rs_r0_no = rs_no / 1000;
  //Serial.print(" NO2 Rs/R0: ");
  //Serial.print(rs_r0_no);
  //Estimate ppm
  double no2_ppm=0.013043*rs_r0_no+0.0086956;
  Serial.print(" NO2 ppm: ");
  Serial.print(no2_ppm);

  //CO
  //Read values and calculate rs/ro
  reading1 = analogRead(1);
  sensorValueCo=map(reading1,0,1024,0,500);
  sensorValueCo=sensorValueCo/100;
  double rs_co=(100000/((5-sensorValueCo)))*sensorValueCo;
  double rs_r0_co = rs_co / 100000; 
  //Serial.print(" CO Rs/R0: ");
  //Serial.print(rs_r0_co);
  //Estimate ppm
  double co_ppm=-25*rs_r0_co+20.6;
  Serial.print(" CO ppm: ");
  Serial.println(co_ppm);

  delay(100);
}


