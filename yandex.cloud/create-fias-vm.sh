#!/bin/sh

export $(grep -v '^#' .env | xargs -d '\n')

yc compute instance create-with-container fias-gar \
    --zone=ru-central1-b \
    --metadata-from-file user-data=metadata.yaml \
    --network-interface subnet-name=default-ru-central1-b,nat-ip-version=ipv4 \
    --service-account-name $SERVICE_ACCOUNT_NAME \
    --hostname fias-gar \
    --platform standard-v2 \
    --memory 24GB \
    --cores 6 \
    --create-boot-disk type=network-hdd,size=200GB \
    --docker-compose-file docker-compose-fias-gar.yml \
    --cloud-id $CLOUD_ID \
    --folder-id $FOLDER_ID
