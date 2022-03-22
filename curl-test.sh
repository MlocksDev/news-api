#!/bin/bash
########## GLOBAL VARS #######
DOMAIN='http://localhost:8000/'
HEADER='Content-Type: application/json'
ID=$(($RANDOM%100))
EMAIL="marthalocks'$ID'@gmail.com"
PASSWORD="password'$ID'"

########## REGISTER USER #############
JSON_DATA='{"name": "Martha Locks", "email":"'$EMAIL'", "password": "'$PASSWORD'", "password_confirmation": "'$PASSWORD'"}'
ENDPOINT='api/register'
curl --data "$JSON_DATA" -H "$HEADER" -X POST "$DOMAIN/$ENDPOINT" > curl-output.html

########## USER LOGIN ###############
JSON_DATA='{"email":"'$EMAIL'", "password": "'$PASSWORD'"}'
ENDPOINT='api/login'
TOKEN=$(curl --data "$JSON_DATA" -H "$HEADER" -X POST "$DOMAIN/$ENDPOINT" | sed 's/\\\\\//\//g' | sed 's/[{}]//g' | awk -v k="text" '{n=split($0,a,","); for (i=1; i<=n; i++) print a[i]}' | sed 's/\"\:\"/\|/g' | sed 's/[\,]/ /g' | sed 's/\"//g' | grep -w token | cut -d":" -f2 | sed -e 's/^ *//g' -e 's/ *$//g')
TOKEN=${TOKEN##*|}

########## USER PROFILE #############
ENDPOINT='api/profile'
USER_ID=$(curl -H 'Accept: application/json' -H "Authorization: Bearer ${TOKEN}" -X GET "$DOMAIN/$ENDPOINT" | sed 's/\\\\\//\//g' | sed 's/[{}]//g' | awk -v k="text" '{n=split($0,a,","); for (i=1; i<=n; i++) print a[i]}' | sed 's/\"\:\"/\|/g' | sed 's/[\,]/ /g' | sed 's/\"//g' | grep -w id | cut -d":" -f2 | sed -e 's/^ *//g' -e 's/ *$//g')

########## INSERT AUTHORS ############
ENDPOINT='api/authors'
JSON_DATA='{"users_id": "'$USER_ID'","name":"Name New","lastname":"LastName New","gender":"M","active":"1"}'
curl --data "$JSON_DATA" -H "$HEADER" -H "Authorization: Bearer ${TOKEN}" -X POST "$DOMAIN/$ENDPOINT" > curl-output.html

######## REFRESH TOKEN ###############
ENDPOINT='api/refresh'
TOKEN=$(curl -H "$HEADER" -H "Authorization: Bearer ${TOKEN}" -X POST "$DOMAIN/$ENDPOINT" | sed 's/\\\\\//\//g' | sed 's/[{}]//g' | awk -v k="text" '{n=split($0,a,","); for (i=1; i<=n; i++) print a[i]}' | sed 's/\"\:\"/\|/g' | sed 's/[\,]/ /g' | sed 's/\"//g' | grep -w token | cut -d":" -f2 | sed -e 's/^ *//g' -e 's/ *$//g')
TOKEN=${TOKEN##*|}

############ LOGOUT #################
ENDPOINT='api/logout'
curl -H "$HEADER" -H "Authorization: Bearer ${TOKEN}" -X POST "$DOMAIN/$ENDPOINT" > curl-output.html