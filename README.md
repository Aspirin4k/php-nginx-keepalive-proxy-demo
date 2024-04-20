# PHP + Nginx proxy for keepalive connections

This project demonstrates one of the possible configurations of the Nginx sidecar proxy.
for handling keepalive connections for PHP applications.

That allows you to reduce the execution time of your requests by avoiding unnecessary
duplicate TCP and TLS handshakes with a remote API.

## Example of results

```
Running https://animechan.xyz/api/random
Executed request #0: tcp = 0.612415, tls = 1.1082, total = 1.819959
Executed request #1: tcp = 0.493603, tls = 0.876545, total = 1.517115
Executed request #2: tcp = 0.431641, tls = 0.837624, total = 1.714835
Executed request #3: tcp = 0.518705, tls = 1.061944, total = 1.674289
Executed request #4: tcp = 0.407415, tls = 0.93014, total = 1.636794
Executed request #5: tcp = 0.517502, tls = 0.907717, total = 1.51956
Executed request #6: tcp = 0.41931, tls = 0.839874, total = 1.56032
Executed request #7: tcp = 0.603984, tls = 1.014873, total = 1.655553
Executed request #8: tcp = 0.470796, tls = 0.89741, total = 1.767862
Executed request #9: tcp = 0.399318, tls = 0.849068, total = 1.465466
Finished all requests in 16.361854076385 seconds
========================
Running http://nginx:8083/animechan.xyz/api/random
Executed request #0: tcp = 0.002081, tls = 0, total = 1.42745
Executed request #1: tcp = 0.000805, tls = 0, total = 1.663629
Executed request #2: tcp = 0.002938, tls = 0, total = 1.715901
Executed request #3: tcp = 0.000904, tls = 0, total = 1.444526
Executed request #4: tcp = 0.00511, tls = 0, total = 0.714775
Executed request #5: tcp = 0.000577, tls = 0, total = 0.711171
Executed request #6: tcp = 0.002919, tls = 0, total = 0.608448
Executed request #7: tcp = 0.001353, tls = 0, total = 0.604887
Executed request #8: tcp = 0.000546, tls = 0, total = 0.727221
Executed request #9: tcp = 0.000991, tls = 0, total = 0.615444
Finished all requests in 10.239702939987 seconds
========================
Running https://whentheycry.ru/api/post/2
Executed request #0: tcp = 0.431877, tls = 0.755606, total = 1.429839
Executed request #1: tcp = 0.434394, tls = 0.803453, total = 1.506498
Executed request #2: tcp = 0.410752, tls = 0.741367, total = 1.34097
Executed request #3: tcp = 0.323339, tls = 0.628681, total = 1.276446
Executed request #4: tcp = 0.676683, tls = 1.098127, total = 1.968585
Executed request #5: tcp = 0.33116, tls = 0.665379, total = 1.286649
Executed request #6: tcp = 0.398673, tls = 0.715713, total = 1.360891
Executed request #7: tcp = 0.368654, tls = 0.789558, total = 1.415814
Executed request #8: tcp = 0.478557, tls = 0.794639, total = 1.451479
Executed request #9: tcp = 0.359838, tls = 0.763709, total = 1.376036
Finished all requests in 14.437201023102 seconds
========================
Running http://nginx:8083/whentheycry.ru/api/post/2
Executed request #0: tcp = 0.001341, tls = 0, total = 1.337778
Executed request #1: tcp = 0.001613, tls = 0, total = 0.472488
Executed request #2: tcp = 0.000648, tls = 0, total = 0.336202
Executed request #3: tcp = 0.001323, tls = 0, total = 0.321681
Executed request #4: tcp = 0.000472, tls = 0, total = 0.318372
Executed request #5: tcp = 0.001244, tls = 0, total = 0.312835
Executed request #6: tcp = 0.000435, tls = 0, total = 0.336835
Executed request #7: tcp = 0.002279, tls = 0, total = 0.351638
Executed request #8: tcp = 0.001315, tls = 0, total = 0.314239
Executed request #9: tcp = 0.001086, tls = 0, total = 0.32175
Finished all requests in 4.4288210868835 seconds
========================
```
PHP doesn't need to establish a TLS connection with a proxy; it uses simple HTTP.
In example, with whentheycry.ru, you can see that only the first request took
quite time, the following requests reused connection

```
[20/Apr/2024:08:59:10 +0000] "GET /animechan.xyz/api/random HTTP/1.1" 13.235.114.104:443|keep-alive|0.792|1.424|1.424
[20/Apr/2024:08:59:11 +0000] "GET /animechan.xyz/api/random HTTP/1.1" 13.235.114.104:443|keep-alive|0.781|1.662|1.662
[20/Apr/2024:08:59:13 +0000] "GET /animechan.xyz/api/random HTTP/1.1" 13.235.114.104:443|keep-alive|0.995|1.711|1.711
[20/Apr/2024:08:59:14 +0000] "GET /animechan.xyz/api/random HTTP/1.1" 13.235.114.104:443|keep-alive|0.816|1.443|1.443
[20/Apr/2024:08:59:15 +0000] "GET /animechan.xyz/api/random HTTP/1.1" 13.235.114.104:443|keep-alive|0.000|0.709|0.709
[20/Apr/2024:08:59:16 +0000] "GET /animechan.xyz/api/random HTTP/1.1" 13.235.114.104:443|keep-alive|0.000|0.710|0.710
[20/Apr/2024:08:59:16 +0000] "GET /animechan.xyz/api/random HTTP/1.1" 13.235.114.104:443|keep-alive|0.000|0.605|0.605
[20/Apr/2024:08:59:17 +0000] "GET /animechan.xyz/api/random HTTP/1.1" 13.235.114.104:443|keep-alive|0.000|0.603|0.603
[20/Apr/2024:08:59:18 +0000] "GET /animechan.xyz/api/random HTTP/1.1" 13.235.114.104:443|keep-alive|0.000|0.726|0.726
[20/Apr/2024:08:59:18 +0000] "GET /animechan.xyz/api/random HTTP/1.1" 13.235.114.104:443|keep-alive|0.000|0.614|0.614
[20/Apr/2024:08:59:34 +0000] "GET /whentheycry.ru/api/post/2 HTTP/1.1" 65.21.251.49:443|keep-alive|0.716|1.167|1.324
[20/Apr/2024:08:59:35 +0000] "GET /whentheycry.ru/api/post/2 HTTP/1.1" 65.21.251.49:443|keep-alive|0.000|0.417|0.470
[20/Apr/2024:08:59:35 +0000] "GET /whentheycry.ru/api/post/2 HTTP/1.1" 65.21.251.49:443|keep-alive|0.000|0.317|0.335
[20/Apr/2024:08:59:35 +0000] "GET /whentheycry.ru/api/post/2 HTTP/1.1" 65.21.251.49:443|keep-alive|0.000|0.312|0.320
[20/Apr/2024:08:59:36 +0000] "GET /whentheycry.ru/api/post/2 HTTP/1.1" 65.21.251.49:443|keep-alive|0.000|0.310|0.317
[20/Apr/2024:08:59:36 +0000] "GET /whentheycry.ru/api/post/2 HTTP/1.1" 65.21.251.49:443|keep-alive|0.000|0.305|0.311
[20/Apr/2024:08:59:36 +0000] "GET /whentheycry.ru/api/post/2 HTTP/1.1" 65.21.251.49:443|keep-alive|0.000|0.319|0.335
[20/Apr/2024:08:59:37 +0000] "GET /whentheycry.ru/api/post/2 HTTP/1.1" 65.21.251.49:443|keep-alive|0.000|0.320|0.345
[20/Apr/2024:08:59:37 +0000] "GET /whentheycry.ru/api/post/2 HTTP/1.1" 65.21.251.49:443|keep-alive|0.000|0.306|0.312
[20/Apr/2024:08:59:37 +0000] "GET /whentheycry.ru/api/post/2 HTTP/1.1" 65.21.251.49:443|keep-alive|0.000|0.316|0.320
```
Three numbers: connect_time (tcp + tls), header_time, and total_time. As you can see,
Keep-alive connections depend on the configuration of the remote service. For whentheycry.ru
We have only one balancer; that's why a single connection is enough.

## Run locally
Run containers
```
docker-compose up
```
Execute request
```
http://127.0.0.1:8082
```
