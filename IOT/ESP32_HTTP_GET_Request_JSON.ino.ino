#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <Arduino_JSON.h>

const char* ssid = "Wokwi-GUEST";
const char* password = "";

//Your IP address or domain name with URL path
const String serverName = "https://www.XXX.com.br/IOT/view/esp-outputs-action.php?action=outputs_state&board=18&id_pessoa=70";

// Update interval time set to 5 seconds
const long interval = 5000;
unsigned long previousMillis = 0;

String outputsState;

void setup() {
  Serial.begin(115200);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) { 
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  unsigned long currentMillis = millis();
  
  if(currentMillis - previousMillis >= interval) {
     // Check WiFi connection status
    if(WiFi.status()== WL_CONNECTED ){ 
      Serial.println("iniciando recebimento de dados...");

      HTTPClient http;
      http.begin(serverName);

      int httpResponseCode = http.GET();

      if (httpResponseCode > 0) {

          Serial.print("HTTP ");
          Serial.println(httpResponseCode);
          String payload = http.getString();
          Serial.println();
          Serial.println(payload);
          JSONVar myObject = JSON.parse(payload);
  
          // JSON.typeof(jsonVar) can be used to get the type of the var
          if (JSON.typeof(myObject) == "undefined") {
            Serial.println("Parsing input failed!");
            return;
          }
    
        // Serial.print("JSON object = ");
        // Serial.println(myObject);
    
         // myObject.keys() can be used to get an array of all the keys in the object
          JSONVar keys = myObject.keys();
    
          for (int i = 0; i < keys.length(); i++) {
            JSONVar value = myObject[keys[i]];
            Serial.print("GPIO: ");
            Serial.print(keys[i]);
            Serial.print(" - SET to: ");
            Serial.println(value);
            pinMode(atoi(keys[i]), OUTPUT);
		        if(atoi(value)==1){
			        digitalWrite(atoi(keys[i]), HIGH);
		        }else{
		        	digitalWrite(atoi(keys[i]), LOW);
		        }
         }
         // save the last HTTP GET Request
          previousMillis = currentMillis;
      }else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
        Serial.println(":-(");
      }

    }else {
      Serial.println("WiFi Disconnected");
    }
  }
}

String httpGETRequest(const char* serverName) {
  WiFiClientSecure client;
  HTTPClient http;
    
  // Your IP address with path or Domain name with URL path 
  http.begin(client, serverName);
  
  // Send HTTP POST request
  int httpResponseCode = http.GET();
  
  String payload = "{}"; 
  
  if (httpResponseCode>0) {
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
    payload = http.getString();
  }
  else {
    Serial.print("Error code: ");
    Serial.println(httpResponseCode);
  }
  // Free resources
  http.end();

  return payload;
}
