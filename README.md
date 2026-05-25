# Well-Meadows-Hospital-Registry
Registry Website as a Requirement for our Web Systems and Information Management courses

# System Requirements
**PHP Version:** PHP 8.2.12
**Laravel Version:** 12.57.0
**Database Requirement:** PostgreSQL (PgAdmin)
**Required Tools:** Visual Studio Code, Git


# Instructions for the members

## First set up 

Follow these commands when first setting up the project, if you've already done this step and you already have a local repository, proceed to <a href="#branch">Branch Instructions</a> after setting up you can then follow the <a href="#setup">Postgresql setup instructions</a>

### Installation & Setup

**1. Change directory**

Open your terminal and run the following command to change the directory to your desired location

```bash
cd (full_directory_here)

```

**2. Clone the repository**

After changing directory, run the following command to clone the project to your local machine:

```bash
git clone https://github.com/vincebalaman/Well-Meadows-Hospital-Registry.git

```
---

## Setting up your local repository or branch <p id="branch"></p>

### UPDATE LOCAL REPOSITORY 
**1. Pull**
 
```bash
git pull origin main
```

---

### CREATE YOUR OWN BRANCH
**1. Create a branch for a specific feature**
create a isolated branch for the a specific feature to be added 
Create and switch to the new branch by running this command:

```bash
git checkout -b branch_name
```

**2. Push the branch**
After you've made changes to your own branch, run these commands
to push your branch and then merge with the main branch
```bash
git add .
git commit -m "Description of your commit"
git push origin branch_name
```

---

## SETTING UP POSTGRESQL AS DATABASE <p id="setup"></p>
for easier set up watch this <a href="https://www.youtube.com/watch?v=vrJUMNXgppw">video</a> and follow its instructions

## SET UP MIGRATIONS AND SEEDS

**1. Migrate**
After setting up the database, migrate all the current migrations:
```bash
php artisan migrate
```

**2. Seeding**
After after migrating you need to run the seeding to populate the tables with constant datas:
```bash
php artisan db:seed
```

## RUN INSTRUCTIONS

**1. NPM**
After after migrating and seeding, install compiled assets to make sure all utility classes are loaded
```bash
npm install

npm run build
```

**1. Serve**
After after migrating and seeding, install compiled assets to make sure all utility classes are loaded
```bash
php artisan serve
```

## Test Accounts
**Roles**

**Admin:**
email: admin@test.com
password: 12345678

**Staff:**
email: staff@test.com
password: 12345678

**Patient:**
email: patient@test.com
password: 12345678

## Known Limitations

**Patient Billing**

Calculable amounts must be set for the automatic solving of the patient billing.
