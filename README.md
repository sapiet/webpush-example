# Installation

- Clone this project

`git clone git@github.com:sapiet/webpush-example.git && cd webpush-example`

- Install the dependencies

`composer install`

- Create a .env.local file and define the DATABASE_URL variable

- Create the database and the associated schema by running:

`bin/console doctrine:database:create && bin/console doctrine:schema:update -f`

- Run the data fixtures to create a test user:

`bin/console doctrine:fixtures:load -n`

- Generate your VAPID keys and follow the instructions:

`bin/console webpush:generate:keys`

- Start the web server by running

`bin/console server:run`

- Open your browser and go to http://localhost:8000
- Log in with john@user / 0000
- In your terminal, run the following to send a push notification to user with id 1 
`bin/console app:send 1`
