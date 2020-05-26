# Sky Shield Security

---

#### About
This is a 2-in-1 application that both serves as punch system for employees working at [Sky Shield Security](http://www.sonnyhernandez.com/sky-shield-security.html) as well as an expenses managing system for the administrator of said company. It's intended for use by a single administrator (and the company's employees). Built (albeit reluctantly) with **PHP**, **MySQL**, **Bootstrap** and other technologies as a part of my _Database Design & Management_ course ([CCOM 4027](https://natsci.uprrp.edu/ccom/courselist/)) at the [University of Puerto Rico, Río Piedras Campus](https://www.uprrp.edu/).

#### Preview
```
TODO Video/GIF/Images
```

#### Features
General:
- Object Oriented Design
- Login/logout functionality
- Strong Password Encryption
- Fast and Easy Configuration
- Responsive (mobile and laptop views)
- Clean, Minimal, and Intuitive Interface

Employee Side:
- Clocking in/out at different times
	- for a given employee shift, the system forbids clocking in until the employee is within a configurable amount of minutes from the expected start time
	- when clocking out, the employee can include any comments about his/her shift
	- clocking in is disabled for employees without a position
- View the most recent (or all) punches (_"sessions"_, as we call it)
- View account details, including:
	- Full Name
	- Email
	- Disabilities (if any)
	- Phone Number
- Change password

Administrator Side:
- Employees
	- Track employee attendance
	- Add/edit employees (remove only when employee has never clocked in)
	- Manage exceptions when a given employee forgets to clock out
	- Manage an employee's position (add, remove or change **up to 2** positions)
	- View how many hours each employee has completed (daily, weekly and cumulatively)
	- Download Excel Spreadsheet that contains quarterly information about each employee
		- Tracks tax breaks due to disabilities
		- Track employee's overtime pay (if applicable)
		- Calculates gross pay, overtime pay, taxes and net pay on a quarterly basis
	- Allow one employee to clock in for another (absent) one, if no shift conflicts occur and the administrator acknowledges it
	- Approve/disapprove of "unexpected" shifts from your employees (overtime hours, irregular hours, clocking in for someone else, etc.)

- Clients
	- Add new clients
	- Track and charge overtime hours
	- Create and manage positions for a client
	- Automatically manages billing amounts to clients
	- Download commercial invoices for any given client

#### Future Features
- Track GPS data when clocking in/out to prevent faking by comparing the actual location with the expected location
- Store pdfs and spreadsheets in some sort of cloud (AWS or GCP) to later reference them (storing links in database)

#### Usage
Make sure you have the database created and set up. Please use the provided `tables.sql` file for this (keep in mind it was done with MySQL Version 5.5.60). First create and configure the environment variables in `.env` using `.env.example` as a reference. Then, install `Composer` (using [these instructions](https://getcomposer.org/download/)). Finally, install all libraries specified in `composer.json` and `composer.lock` with the command:
```
composer install
# or 
php composer.phar install
# depending on if you have the alias for composer setup or not
```
Remember `.env` contains passwords, emails and other sensitive data, so make sure you don't make it available through URL access by adding the following lines to your root `.htaccess` file:
```
# Disable index view
options -Indexes

# Hide .env file
<Files .env>
order allow,deny
Deny from all
</Files>
```
Also, make sure your database server is at the same timezone and hour as you, to avoid conflicts with dates and times.

#### Entity-Relationship Diagram
```
TODO
```

#### Near-Exhaustive List of Technologies Used
Requirements:
- PHP ≥ 5.4.16
- MariaDB ≥ 5.5.60
- vlucas/phpdotenv ≥ 3.6
- ircmaxell/password-compat ≥ 1.0 (for forwards-compatibility with `password_hash` and `password_verify` functions from PHP ≥ 5.5.0)
- ircmaxell/random-lib ≥ 1.2

Implicit:
- HTML 5
- CSS 3
- Javascript ES5
- Bootstrap 4.4.1
- jQuery 3.4.1
- PopperJS 1.16.0
- Feather Icons 4.9.0
- ChartJS 2.7.3
- Fetch API (for JS)

#### License
This software is under the license XXXXXXXXXX.

#### Bugs & Inquiries
This software is done without XXXXXXX. Please contact me at [my email](vhernandezcastro@gmail.com) if you find any bugs or have any inquiries. As mentioned in the About section, its intended for use by a single administrator (and the company's employees). It was built with a relatively small-sized company in mind (around 30 to 50 employees).

---

#### To Do
1. Update `.env.example`, which will serve as a model for `.env`
2. Only permit clocking in when employee is within 15 min of the start of his/her/their shift.
3. Create remaining tables
	- shift
	- pays
	- factura
4. Do Administrative Section
	- add bucket client (Sky Shield Itself, id === 0) and bucket position (pid === 0) to the tables.sql and readme
	- display/edit/add clients
	- check which employees have forgotten to check out (need Shift table first)
	- generate Excel spreadsheet
	- generate facturas to a client for a given quincena
	- "unexpected" shift approval/disapproval or even deletion
5. Decide if hid (hasPosition ID) should be a thing, which would mean changing many tables from using (eid, pid) tuples to hid, plus implementing a "bucket" position for "freelancer-type" employees
6. Implement hourlyWage changes (there should be a log of all the wages every employee had to accurately calculate the payments/facturas)
7. Implement qualifiesOvertime changes (there should be a log of all times the employee changed from overtime to no overtime and back, to accurately calculate the payments/facturas)
8. Implement `select` menu for the employee to choose between multiple positions to clock in (remembering to display what position the employee is currently clocked in for)