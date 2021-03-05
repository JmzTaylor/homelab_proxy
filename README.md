# Jmz Homelab "Proxy"

Proxy is in quotes because I couldn't think of a better name.   There is nothing overly special about this other than using it as a simple and easy to use dashboard for all your self-hosted services.  

Only caveat to using this, is that if you host this on public facing server and use https then the services you are adding to it must be https also due to iframe restrictions.

![Screenshot1](https://github.com/JmzTaylor/homelab_proxy/blob/master/screenshots/screen1.PNG)
![Screenshot2](https://github.com/JmzTaylor/homelab_proxy/blob/master/screenshots/screen2.PNG)
![Screenshot3](https://github.com/JmzTaylor/homelab_proxy/blob/master/screenshots/screen3.PNG)
![Screenshot4](https://github.com/JmzTaylor/homelab_proxy/blob/master/screenshots/screen3.PNG)

# Usage:

```
docker run -p 80:8080 jmzsoftware/jmzhomelabproxy:latest
```

Docker image based off https://github.com/TrafeX/docker-php-nginx

###### XMR donations: 84RhRfh4oDxSJTChJfjt7GXvB2tKYxKWp9rNgJyg3oDo5ZPCiDcPzvued3JRJ9HB11BR4jc5pUkni47MdNYciLAnTnQY4KV

###### [](https://github.com/JmzTaylor/JmzXMR-web#btc-donations-33ivzrufsm9hnc3t7zb87zsuls9w5cf3ux)BTC donations: 33ivZruFsm9hNC3T7zB87zsULs9w5cF3UX

###### [](https://github.com/JmzTaylor/JmzXMR-web#eth-donations-0x415ab1735b5af36b90ec052ed9a4b8035a95b57e)ETH donations: 0x415Ab1735B5AF36B90ec052Ed9a4B8035A95b57E
