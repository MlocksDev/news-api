#!/bin/bash
ID=$(($RANDOM%100))
curl --data '{"name": "Martha Locks", "email":"marthalocks'$ID'@gmail.com", "password": "password", "password_confirmation": "password"}' -H 'Content-Type: application/json' -X POST 'http://localhost:8000/api/register' > curl-output.html