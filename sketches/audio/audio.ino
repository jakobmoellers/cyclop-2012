/*
 * Square wave tune with an Arduino and a PC speaker.
 * The calculation of the tones is made following the mathematical
 * operation:
 *
 *	timeUpDown = 1/(2 * toneFrequency) = period / 2
 * )c( Copyleft 2009 Daniel Gimpelevich
 * Inspired from AlexandreQuessy's http://playground.arduino.cc/Code/MusicalAlgoFun
 */

const byte ledPin = 13;
const byte speakerOut = 8; /* This makes a standard old PC speaker connector fit nicely over the pins. */

/* 10.5 octaves :: semitones. 60 = do, 62 = re, etc. */
/* MIDI notes from 0, or C(-1), to 127, or G9. */
/* Rests are note number -1. */

unsigned int timeUpDown[128];

/* our song. Each number pair is a MIDI note and a note symbol. */
/* Symbols are 1 for whole, -1 for dotted whole, 2 for half, */
/* -2 for dotted half, 4 for quarter, -4 for dotted quarter, etc. */

const byte BPM = 120;
const char song[] = {	
  64,4,64,4,65,4,67,4,		67,4,65,4,64,4,62,4,
  60,4,60,4,62,4,64,4,		64,-4,62,8,62,2,
  64,4,64,4,65,4,67,4,		67,4,65,4,64,4,62,4,
  60,4,60,4,62,4,64,4,		62,-4,60,8,60,2,
  62,4,62,4,64,4,60,4,		62,4,64,8,65,8,64,4,60,4,
  62,4,64,8,65,8,64,4,62,4,	60,4,62,4,55,2,
  64,4,64,4,65,4,67,4,		67,4,65,4,64,4,62,4,
  60,4,60,4,62,4,64,4,		62,-4,60,8,60,2};

int period, i;
unsigned int timeUp, beat;
byte statePin = LOW;
const float TEMPO_SECONDS = 60.0 / BPM; 
const unsigned int MAXCOUNT = sizeof(song) / 2;

void setup() {
  pinMode(ledPin, OUTPUT); 
  pinMode(speakerOut, OUTPUT);
  for (i = 128; i--;)
    timeUpDown[i] = 1000000 / (pow(2, (i - 69) / 12.0) * 880);
}

void loop() {
  digitalWrite(speakerOut, LOW);     
  for (beat = 0; beat < MAXCOUNT; beat++) {
    statePin = !statePin;
    digitalWrite(ledPin, statePin);

    i = song[beat * 2];
    timeUp = (i < 0) ? 0 : timeUpDown[i];

    period = (timeUp ? (1000000 / timeUp) / 2 : 250) * TEMPO_SECONDS
      * 4 / song[beat * 2 + 1];
    if (period < 0)
      period = period * -3 / 2;
    for (i = 0; i < period; i++) {
      digitalWrite(speakerOut, timeUp ? HIGH : LOW);
      delayMicroseconds(timeUp ? timeUp : 2000);
      digitalWrite(speakerOut, LOW);
      delayMicroseconds(timeUp ? timeUp : 2000);
    }
    delay(50);
  }
  digitalWrite(speakerOut, LOW);
  delay(1000);
}
