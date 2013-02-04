#include <stdlib.h>



// the setup routine runs once when you press reset:
void setup() {
  // initialize serial communication at 9600 bits per second:
  Serial.begin(9600);
}

// the loop routine runs over and over again forever:
void loop() {
  
  
  getHazard();
  delay(1000);        // delay in between reads for stability
}



boolean getHazard(){
  String lat = "5158.36879";
  String lon = "00735.75679";
   
  double latMin = stringToDouble(lat.substring(2,4)+lat.substring(5));
  double latDeg = 52 + (latMin/60)/100000;
  double lonMin = stringToDouble(lon.substring(3,5)+lon.substring(6));
  double lonDeg = 7 + (lonMin/60)/100000;
  
  int numberOfHazards = 3;
  //51.941266,
  double hazardsLat[3] = {51.941266,51.942403,51.942944};
  double hazardsLon[3] = {7.61356,7.614486,7.614268};
  
  //double latitude = 52 + (1/(latMin/60));
  Serial.print("Lat: ");
  Serial.println(latDeg,10);
  Serial.print("Lon: ");
  Serial.println(lonDeg,10);
  
  for(int i=0; i < numberOfHazards; i++){
    float distance = distance_between(latDeg, lonDeg, hazardsLat[i], hazardsLon[i]);
    Serial.print("Distance: ");
    Serial.println(distance);
    if(distance < 100){
      Serial.println("Hazard!!");
    }
  }

}

double stringToDouble(String str){
  char bufChar[str.length()+1];
  str.toCharArray(bufChar, str.length()+1);
  double d = strtod(bufChar, NULL);  
  return d;
}



/* static */
float distance_between (float lat1, float long1, float lat2, float long2){
    // returns distance in meters between two positions, both specified
    // as signed decimal-degrees latitude and longitude. Uses great-circle
    // distance computation for hypothetical sphere of radius 6372795 meters.
    // Because Earth is no exact sphere, rounding errors may be up to  0.5%.
    // Courtesy of Maarten Lamers
    float delta = radians(long1-long2);
    float sdlong = sin(delta);
    float cdlong = cos(delta);
    lat1 = radians(lat1);
    lat2 = radians(lat2);
    float slat1 = sin(lat1);
    float clat1 = cos(lat1);
    float slat2 = sin(lat2);
    float clat2 = cos(lat2);
    delta = (clat1 * slat2) - (slat1 * clat2 * cdlong);
    delta = sq(delta);
    delta += sq(clat2 * sdlong);
    delta = sqrt(delta);
    float denom = (slat1 * slat2) + (clat1 * clat2 * cdlong);
    delta = atan2(delta, denom);
    return delta * 6372795;
}
