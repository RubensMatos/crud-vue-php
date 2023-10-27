# projeto-workana

## Database setup
```
createdb -U postgres workana
```

## Php Setup
```
Folder /php7.4.0
Run PHP on port 8000 so that the Vue project can make calls correctly.
/php7.4.0/php -S localhost:8000
```

## Php Api
```
A simple API was created in PHP without a framework.
To access the API, go to /php-7.4.0/api
```

## Project extra
```
Running the project will prompt you for a username and password. This feature was implemented as an extra step to enhance the project's structure. After importing the database contained in the root file, use the following credentials to access the system:

Username: master
Password: 123456
```

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).
