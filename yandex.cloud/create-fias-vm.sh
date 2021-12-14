#!/bin/sh

export $(grep -v '^#' .env | xargs -d '\n')

yc compute instance create-with-container avitoparser \
    --zone=ru-central1-b \
    --metadata-from-file user-data=metadata.yaml \
    --network-interface subnet-name=default-ru-central1-b,nat-ip-version=ipv4 \
    --service-account-name $SERVICE_ACCOUNT_NAME \
    --platform standard-v2 \
    --memory 8GB \
    --cores 4 \
    --create-boot-disk type=network-ssd,size=30GB \
    --docker-compose-file docker-compose-avitoparser.yml \
    --cloud-id $CLOUD_ID \
    --folder-id $FOLDER_ID
