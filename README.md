# Perky-Rabbit-Corp. Recruitment Task

## Flow

User can signup and login to enter the dashboard, which gives a list of employees(prepopulated). Now user can create new employee, edit employee data, add their achievements and so on. Employees can be filtered based on department and achievement. After logout user will be redirected to signin page again. If user data and auth token is stored in local storage then user will be redirected to dashboard.

## Setup

**Backend/Server**
- Install composer [composer install]
- Copy .env file [cp .env.example .env]
- Set application key [php artisan key:generate]
- Setup the local database; Enter database credentials in the .env
- Migrate tables and seeds [ php artisan migrate:fresh --seed ]
- Run the server [php artisan serve]

**Frontend/Client**
- Install npm [npm i]
- Run the server [npm run dev]



## Issues 
- Deleting achievements in random order creates issues as vue dont support mutating elements using index value {could be solved but left unfinished because of timebound}
- Logout button still shows after logging out, without refresh [same, could be solved, scheduled for last minute but left unfinished]
