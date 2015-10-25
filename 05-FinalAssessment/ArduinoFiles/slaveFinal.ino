
/*
 * eHealthFinal.ino
 * Slave File for A R D U I N O      U N O
 *
 * Created: 26/11/2014
 * Author: Martin Naismith - 1304494
 * Rev 0.6 - 01/12/2014 11:06
 *
 */ 

#include <Wire.h>
#include <Stream.h>
#include <iostream>
#include <string>
#include <string.h>
#include <WString.h>
#include <SoftwareSerial.h>
#include <arduino.h>
#include <typeinfo>


// variables -----------------------------------

SoftwareSerial cell(2,3);                 // We need to create a serial port on D2/D3 to talk to the GSM module

char mobilenumber[] = "07775609904"; // The text message recipient's mobile number
char dreish[] = "07786202877";  // Send data to dreish server
char data[20];		     // The data from the wire
boolean alert = false;       // if the message is to be send - raise the alert to true
boolean dataAvailable = false;
boolean dataSMS = false;
int i = 0;
int rcvtimes = 0;
char* patientmsg[] = {"Take a rest, keep warm and call for assistance please 07831433612 or 07768347597.",
                 "If you are not already doing so - stay calm and call for assistance please 07831433612 or 07768347597."};
char datamsg[6] = "data "; // Keyword for dreish
String smsmsg = "";

//------------------------------------------

void setup()
{
  Serial.begin(9600);  
  delay(500);
  pinMode(9, OUTPUT);                  // set the connection LED off, to let you see the connection delay is complete                                      
  pinMode(13, OUTPUT);
  digitalWrite(13, HIGH);
  Serial.println("Program Started....");
  
  // Start GSM shield
  digitalWrite(9, HIGH);        //POWER ON GSM MODULE
  cell.begin(9600);
  delay(35000);
  Serial.println("GSM initialized");
  
  Wire.begin(1);
  delay(500);
  Wire.onReceive(receiveEvent);
  digitalWrite(13, LOW);
  Serial.println("The program is waiting....");
}

//------------------------------------------------

void loop()
{
  
    if(dataAvailable)
    {
        // compile sms for datamsg and data
        char datamsg[] = "data ";
        char* smsmsg = new char[strlen(datamsg) + strlen(data) + 1];
        strcpy(smsmsg, datamsg);
        strcat(smsmsg, data);
        Serial.println(smsmsg);
        Serial.print("Length = ");
        Serial.println(strlen(smsmsg));
        
        // send the data to dreish
        dataSMS = true;
        sendSMS(smsmsg);
        
        alert = checkData();
        
        if(alert)
        {
            Serial.println("Going to send alert SMS");
            sendSMS(patientmsg[i]);
            alert = false;
            i++;
            if(i == 2)i = 1;
        } //end of if(alert)
        
        dataAvailable = false;
    }
  
} // end of loop

//------------------------------------------------

void receiveEvent(int byteCount)
{
    Serial.print("ByteCount = ");
    Serial.println(byteCount);
    char c;
    int i = 0;
    for(int j = 0; j < 19; j++)
    {
        data[j] = '\0';
    } // end of for - to clear data[]

    while (Wire.available() > 0)    // loop through all but the last
    {
        c = Wire.read();            // receive byte as a character
        //Serial.print(c);          // print the character
        if (c == '\n')
        {
            Serial.println("Wire being Flushed");
            Wire.flush();
        }
        if(c != ';')
        {
            data[i] = c;
            i++;
        }
    }
    
    dataAvailable = true;
    
} // END OF RECEIVE EVENT

//------------------------------------------

boolean checkData()
{
        
    Serial.println("In check data method");
    Serial.println(data);

    // break up the data char array to strings
    String temp100 = extract(data, 1, 4);
    String oxysat1 = extract(data, 7, 2);
    String bpm1 = extract(data, 11, sizeof(data)- 1 - 11);
    
    // reset alert
    alert = false;
    
    // check the temperature
    float temp = ((temp100.toInt()) / 100.0);
    Serial.print("Temp ");
    Serial.println(temp);
        
    if (temp > 38.2 || temp < 36.2)
    {
	alert = true;
    }
    Serial.print("Temp Alert = ");
    Serial.println(alert);

    // check Oxy Sats
    int oxysat = oxysat1.toInt();
    Serial.print("OxySat = ");
    Serial.println(oxysat);
    if (oxysat < 87)
    {
	alert = true;
    }
    Serial.print("OxySat Alert = ");
    Serial.println(alert);
	
    // check Heart Rate
    int bpm = bpm1.toInt();
    Serial.print("Bpm = ");
    Serial.println(bpm);
    if (bpm > 90 || bpm < 50)
    {
  	alert = true;
    }
    Serial.print("BMP Alert = ");
    Serial.println(alert);
    Serial.println();
        
    return alert;
        
} // end of checkdata method

//------------------------------------------

String extract(char *data, int from, int length) {
  
    char tmp[length];
    for(int i = from; i < from + length; i++)
    {
	tmp[i - from] = data[i];
    }
    String str = String(tmp);
    for(int i = 0; i < sizeof(tmp); i++)
    {
  	tmp[i] = '\0';
    }
    return str;
  
} // end of extract method

//------------------------------------------

void sendSMS(String txtmsg){      // function to send a text message

    digitalWrite(13, HIGH);
    Serial.println("Text Message Sender - Send this message");
    Serial.println(txtmsg);
    
    cell.println("AT+CMGF=1");                         // set SMS mode to text
    delay(500);   
    cell.println("AT+CMGD=1,4"); 
    delay(500);  
    cell.println("AT+CMGF=1"); 
    delay(500); 
    cell.print("AT+CMGW=");                  
    cell.write( 34 );                                  // ASCII equivalent of "
    
    if(dataSMS) 
    {
        cell.print(dreish);
        Serial.print("Sent to -> ");
        Serial.println(dreish);
        dataSMS = false;
    }   // data to dreish
    else 
    {
        cell.print(mobilenumber);
        Serial.print("Sent to -> ");
        Serial.println(mobilenumber);
    }   // sms to patient - Calm down dear
    
    cell.write( 34 );                                  // ASCII equivalent of "
    cell.println();                                    // return   
    delay(500);                                        // give the module some thinking time
  
    cell.print(txtmsg);
    delay(500);
    
    cell.print((char)26);                  // ASCII equivalent of Ctrl-Z, this is very important
    delay(500); 
    cell.println("AT+CMSS=1"); 
    
    Serial.println(" SENT ");
    Serial.println();
    Serial.println("----------------end of sms------------------");

    digitalWrite(13, LOW);                // set the LED 13 off 
    
    
} // END OF SENSMS METHOD

//---------------- E N D    O F    F I L E --------------------------
