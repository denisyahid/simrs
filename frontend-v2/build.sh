
#!/bin/sh

#git pull origin master
yarn build

rm -rf /opt/www/transmedic-v3/**
cp -rf /opt/transmedic_web_v3/frontend-v2/dist/ /opt/www/transmedic-v3-old/

rm -rf /opt/www/transmedic-v3/**
cp -rf /opt/transmedic_web_v3/frontend-v2/dist/* /opt/www/transmedic-v3/
