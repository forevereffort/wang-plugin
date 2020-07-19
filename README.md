## Install
1. Clone this repo
2. Navigate to the folder and run the following command in your terminal:
```
composer install
composer dump-autoload -o

npm i
npm run lint:php:fix
npm run lint:scripts:fix
npm run lint:scss:fix
npm run build
npm run watch
```
3. please add this shortcode [wjc]
4. WP-CLI to bust cache
```
php wp-cli.phar wjc-bust-cache
```
