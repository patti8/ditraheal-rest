#!/bin/sh

#######################
# Kirim data RS Onlie #
#######################

# 1. Pasien
curl -sS http://localhost/webservice/kemkes/pasien?kirim=1 > /dev/null

# 2. Diagnosa Pasien
curl -sS http://localhost/webservice/kemkes/diagnosa-pasien?kirim=1 > /dev/null

# 3. Data Kebutuhan APD
curl -sS http://localhost/webservice/kemkes/data-kebutuhan-apd?kirim=1 > /dev/null

# 4. Data Kebutuhan SDM
curl -sS http://localhost/webservice/kemkes/data-kebutuhan-sdm?kirim=1 > /dev/null

# 5. Data Tempat Tidur Isolasi
curl -sS http://localhost/webservice/kemkes/data-tempat-tidur?kirim=1 > /dev/null