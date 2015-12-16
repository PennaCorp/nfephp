openssl pkcs12 -in mc.pfx -nocerts -out 09306720000154_priKEY.pem -nodes
openssl pkcs12 -in mc.pfx -nokeys -out 09306720000154_certKEY.pem -nodes
openssl pkcs12 -in mc.pfx -clcerts -nokeys -out 09306720000154_pubKEY.pem