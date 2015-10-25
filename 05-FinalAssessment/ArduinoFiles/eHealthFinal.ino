/*
 * eHealthFinal1.ino
 * Master File for   A R D U I N O    U N O
 *
 * Created: 11/16/2014 2:07:24 PM
 * Author: Martin Naismith - 1304494
 * Rev 0.6 01/12/14 11:51
 */ 

#include <eHealth.h>
#include <PinChangeInt.h>
#include <Wire.h>
#include <Stream.h>
#include <string.h>
#include <math.h>

// variables -----------------------------------
int cont = 0;

void setup()
{
	  /* add setup code here, setup code runs once when the processor starts */
	  Serial.begin(9600);
	  Wire.begin();
	  eHealth.initPulsioximeter();
	  	  
	  //Attach the interruptions for using the pulsioximeter.
	  PCintPort::attachInterrupt(6, readPulsioximeter, RISING);

          Serial.println("Program Strated........");
	  
} // end of setup ------------------------------

void loop()
{
	  /* add main program code here, this code starts again each time it ends */
	  
	  // 45 sec delay
	  delay(45000);
	  
	  // get the current temperature and send to serial
	  int temp = ceil((eHealth.getTemperature() - 2.80 )* 100); // -2.8 error correction

	  // get the current oxy-sats and send to serial
	  int oxysat = (eHealth.getOxygenSaturation());
	  
	  // get the current heart rate and send to serial
	  int bpm = eHealth.getBPM();
	  
	  while ( bpm > 0 && temp > 0)
	  {
		  Wire.beginTransmission(1);
		  Wire.write("t");
                  Serial.print("t");
                  Wire.print(temp); // Wire.print(temp); 
                  Serial.print(temp);
                  Wire.write(",s");
                  Serial.print(",s");
                  Wire.print(oxysat); // Wire.print(oxysat);
                  Serial.print(oxysat);
                  Wire.write(",b");
                  Serial.print(",b");
                  Wire.print(bpm); // Wire.print(bpm);
                  Serial.print(bpm);
                  Wire.write(";");
                  Serial.println(";");
		  Wire.endTransmission();
                  
                  bpm = temp = 0;
		  
	  } // end while on wire transmission


} // end of loop --------------------------------

//Include always this code when using the pulsioximeter sensor
//------------------------------------------------------------
void readPulsioximeter(){

	cont ++;

	if (cont == 10)
	{ //Get only of one 10th measures to reduce the latency
		eHealth.readPulsioximeter();
		cont = 0;
	} // end of if
	
} // end readPulsioximeter method
