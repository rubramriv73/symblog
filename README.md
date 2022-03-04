# Desarrollo Web Entorno Servidor
#### Rubén Ramírez Rivera
## SYMBLOG

Ejercicio en el que hemos generado un blog dinámico

## VirtualHost Usado

    <VirtualHost *> 
        ServerName symblog.local 
        DocumentRoot "/opt/lampp/htdocs/symblog/public" 
    
        <Directory "/opt/lampp/htdocs/symblog/public" > 
            Options All 
            AllowOverride All 
            Require all granted 
        </Directory> 
    </VirtualHost>
