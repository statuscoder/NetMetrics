# NetMetrics

## What is it
A simple tool to check the network status between the server and the client. Checks ping, download and upload data. All data is saved in CSV files, which are easy to work with.

## Requires
Websocket server https://github.com/walkor/workerman

### Sample output
```
[15:15:46] Trying to connect to the server
[15:15:46] Сonnection is open
[15:15:46] Key found id:135
[15:15:46] Pong 1752916546.747174
[15:15:47] Pong 1752916547.448089
[15:15:48] Pong 1752916548.149969
[15:15:48] Pong 1752916548.851507
[15:15:49] Pong 1752916549.553554
[15:15:50] Pong 1752916550.254897
[15:15:50] Pong 1752916550.955511
[15:15:51] Pong 1752916551.657162
[15:15:52] Average Ping 0.618ms
[15:15:52] Download test ..........................................................................................................50.293Mb in 0.609sec
[15:15:52] Download speed 660.663Mbps
[15:15:52] Upload test ...........................................................................................................50.293Mb in 0.503sec
[15:15:53] Upload speed 799.889Mbps
```

```
09:15:46;135;auth;127.0.0.1
09:15:46;135;ping;1;0.599;ms
09:15:47;135;ping;2;0.573;ms
09:15:48;135;ping;3;0.900;ms
09:15:48;135;ping;4;0.675;ms
09:15:49;135;ping;5;0.899;ms
09:15:50;135;ping;6;0.401;ms
09:15:50;135;ping;7;0.412;ms
09:15:51;135;ping;8;0.482;ms
09:15:52;135;download;50.293;0.609;660.663
09:15:53;135;upload;50.293;0.503;799.889
09:15:53;135;geo;127.0.0.1;0;0;0
```
