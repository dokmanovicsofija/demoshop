# DemoShop

## Overview

DemoShop je jednostavna aplikacija za upravljanje proizvodima u online katalogu. Ova aplikacija omogućava administratorima da dodaju, uređuju, brišu, omoguće i onemoguće proizvode. Posetioci mogu pregledati i pretraživati proizvode u katalogu.

## Getting Started

```bash
# 1. Clone the Repository:
git clone https://github.com/dokmanovicsofija/demoshop.git
cd demoshop

# 2. Install Dependencies:
composer install

# 3. Set Up Environment Variables:
cp .env.example .env

# 4. Edit the `.env` file to match your database configuration:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=demoshop
DB_USERNAME=root
DB_PASSWORD=root

# 5. Set Up the Database:
php migration.php

# 6. Seed the Database (Optional):
php seedData.php