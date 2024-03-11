#include <Wire.h>
#include <WiFi.h>
#include <HTTPClient.h>

// Set our wifi name and password
const char* ssid = "Hotspot-3";
const char* password = "password";

// Change url with your website path upto insert.php
String serverName = "http://192.168.1.63/ycp/rfid-staff-attandance/insert.php?";

int led1 = 27;   //
int led2 = 26;   //

char input[12]="";        // A variable to store the Tag ID being presented
String s="";

void setup()
{  
  pinMode(led1, OUTPUT);
  pinMode(led2, OUTPUT);
 
  Serial.begin(9600);

  WiFi.begin(ssid, password); // Attempt to connect to wifi with our password
  Serial.println("Connecting"); // Print our status to the serial monitor
  // Wait for wifi to connect
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  digitalWrite(led1,HIGH);
  digitalWrite(led2,LOW);  
  delay(5000);
}

void loop()
{  
  //Read Sensor values
  if(Serial.available())// check serial data ( RFID reader)
	{
		int count = 0; // Reset the counter to zero
		/* Keep reading Byte by Byte from the Buffer till the RFID Reader Buffer is	empty 
		   or till 12 Bytes (the ID size of our Tag) is read */
		while(Serial.available() && count < 12) 
		{
			input[count] = Serial.read(); // Read 1 Byte of data and store it in the input[] variable
			count++; // increment counter
			delay(5);     
		}    
    
    s = input;
    s = s.substring(0,12);
    //Display RFID values to serial monitor
    Serial.println("Tag  : " + String(s) + "");  
    sendData();       
    delay(200); 
  }  
}

//Function to send data to server
void sendData()
{
  HTTPClient http; // Initialize our HTTP client

  String url = serverName + "rfid=" + String(s);
  //Send request
  Serial.println(url);
  http.begin(url.c_str()); // Initialize our HTTP request
  int httpResponseCode = http.GET(); // Send HTTP request
  String payload = http.getString();
  Serial.println("payload: "+payload);
  if(payload=="Success"){
    digitalWrite(led1,LOW);
    digitalWrite(led2,HIGH);
    delay(2000);
    digitalWrite(led1,HIGH);
  digitalWrite(led2,LOW);
  }
  if (httpResponseCode > 0)
  {
    // Check for good HTTP status code
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
  }
  else
  {
    Serial.print("Error code: ");
    Serial.println(httpResponseCode);
  }
  http.end();
}

