//51.9,7.96;51.9,7.96;51.9,7.96;51.9,7.96;51.9,7.96


// the setup routine runs once when you press reset:
void setup() {
  // initialize serial communication at 9600 bits per second:
  Serial.begin(9600);
  delay(200);
}

// the loop routine runs over and over again forever:
void loop() {
  
  String returnCoordinates = "51.19,7.196;52.9,7.96;50.9,7.96;51.87,7.96;51.8,7.96;51.65,5;";
  float lat = 51.9;
  float lon = 7.959;
  
  // print out the value you read:
  Serial.println("Input String");
  Serial.println(returnCoordinates);
  //hazardDetection(returnCoordinates, lat, lon);
  
  getCoordinates(returnCoordinates);
  
  delay(5000);        // delay in between reads for stability
}

void getCoordinates(String returnCoordinates){
  int countCoordinates =  countSplitCharacters(returnCoordinates, ';');
  String x[countCoordinates];
  String y[countCoordinates];
  
  for(int j = 0; j < countCoordinates-1; j++){
    returnCoordinates = splitStrings(returnCoordinates, j, x,y);
    Serial.println(x[j]);
    //Serial.println(y[j]);
  }

  Serial.println("danach");

  for(int i = 0; i < countCoordinates; i++){
    Serial.println(x[i]);
  }


}


String splitStrings(String text, int coordinateIndex, String *x, String *y){
    int commaIndex = text.indexOf(';');
    //  Search for the next comma just after the first
    //int secondCommaIndex = text.indexOf(';', commaIndex+1);

    String pair = text.substring(0, commaIndex);
    text = text.substring(commaIndex+1);
    
    commaIndex = pair.indexOf(',');
    x[coordinateIndex] = pair.substring(0,commaIndex);
    y[coordinateIndex] = pair.substring(commaIndex+1);
    
    return text;
}


 int countSplitCharacters(String text, char splitChar) {
     int returnValue = 1;
     int index = 0;
 
     while (index > -1) {
       index = text.indexOf(splitChar, index + 1);
       if(index > -1) returnValue+=1;
     }
     return returnValue;
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
