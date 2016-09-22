
server {
    listen 80 default_server;
    server_name Slim-Skeleton.huntrealestate.com ~^.+-Slim-Skeleton\.huntcorp\.co _;
    access_log /var/log/nginx/access_Slim-Skeleton.log;
    error_log /var/log/nginx/error_Slim-Skeleton.log;
    client_max_body_size 100m;

    gzip on;
    gzip_types
        text/plain
        text/css
        application/javascript
        application/json
        application/xml
    ;

    if ($http_x_forwarded_proto = "http") {
        return       301 https://$host$request_uri;
    }

    location /nginx_status {
        stub_status on;
        access_log off;
        allow 127.0.0.1;
        deny all;
    }

    location ~* ^.+\.(jpg|jpeg|gif|png|ico|css|zip|tgz|gz|rar|bz2|pdf|txt|tar|wav|bmp|rtf|js|flv|swf|html|htm|eot|ttf|woff|otf)$
    {
        root /home/ec2-user/Slim-Skeleton/public;
        expires 1h;
    }

    location / {
        root   /home/ec2-user/Slim-Skeleton/public;
        index  index.php index.html index.htm;

        try_files $uri $uri/ @rewrites;
    }

    location @rewrites {
        rewrite ^ /index.php last;
    }

    location ~ \.php$ {
        fastcgi_pass   unix:/var/run/php-fpm/php-fpm.sock;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  /home/ec2-user/Slim-Skeleton/public$fastcgi_script_name;
        include        fastcgi_params;
    }

    location ~ ^/php-(status|ping)$ {
        fastcgi_pass   unix:/var/run/php-fpm/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include        fastcgi_params;
        access_log off;
        allow 127.0.0.1;
        deny all;
    }
}
