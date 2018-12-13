# Configuració Laravel web sockets Proxy

```
sudo cat /etc/nginx/sites-available/00_socket.scool.cat
server {
  listen        443 ssl;
  listen        [::]:443 ssl;
  server_name   socket.scool.cat;

  # Start the SSL configurations
  ssl                  on;
  ssl_certificate      /etc/nginx/ssl/scool.cat/412609/server.crt;
  ssl_certificate_key  /etc/nginx/ssl/scool.cat/412609/server.key;

  location / {
    proxy_pass             http://127.0.0.1:6001;
    proxy_set_header Host  $host;
    proxy_read_timeout     60;
    proxy_connect_timeout  60;
    proxy_redirect         off;

    # Allow the use of websockets
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection 'upgrade';
    proxy_set_header Host $host;
    proxy_cache_bypass $http_upgrade;
  }
}
```

Activeu el site amb:

```
sudo ln -s /etc/nginx/sites-available/00_socket.scool.cat /etc/nginx/sites-enabled/
```

