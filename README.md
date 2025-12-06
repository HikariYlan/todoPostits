# / / / / / TODOs Post-its! \ \ \ \ \ 
## Author: Hikari / Ylan

### How to install
#### Requirements

- PHP >= 8.3
- Symfony >= 7.4
- Composer >= 2.8
- Docker daemon (Docker Desktop for Windows users)

#### Installation

The following script will automatically fetch the project and install it for you!
Simply open a terminal at the desired location on you computer and paste the script.

```shell
git clone https://github.com/HikariYlan/todoPostits.git
cd todoPostits/
docker compose up -d
composer install
composer db
```

Explanations in order:
1. Fetch the project from GitHub
2. Place the terminal in the newly created repertory
3. Start the containers for the database and its administration panel 
4. Install all the required dependencies for the project
5. Create the database and populates it with fixtures (fake data)

### How to use

In a terminal placed in the project's repertory, type `composer start` to start the local server.
The application will be now accessible at this web address: http://localhost:8000.

The first thing you're going to see is the login page. From here you have 2 options:

- Use a fake account generated from the fixtures
- Register your own account by clicking "No account? Register". You will be then redirected to the registration page.

If you want to use a fake account, there is always the 2 same accounts when the fixtures are generated.

- Administration account:
    - Username: ylan
    - Password: admin
- Normal account:
    - Username: hikari
    - Password: user

After a successful authentication, you will be redirected to your "cork board". 
From there, you can see all of your Post-its!

## Out of context tip:

If you want to ignore you IDEs folder for every project, and you don't want to do it everytime you start a new one:

```shell
nano ~/.gitignore_global
```

Type any files/folders you want to ignore, then save and close.
After that, simply type (or paste), this command:

```shell
git config --global --add core.excludesfile ~/.gitignore_global
```

And now, for any of your projects, the unwanted files/folders are going to be ignored!
