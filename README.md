Welcome to Betsson API!

To run the project, you will need to follow these next steps:
- Clone the project into your local machine
- Run docker-compose up -d (both database and application containers will be started)
- Run docker-compose exec api composer install

- There are a few registers already at the database.
- I have not addressed the ports of db container to host so that one could open the database at DBeaver or MySql Workbench for example, but if you need to see the content of the database, it's possible to run 'docker exec -it betsson-db bash' and inside bash, run 'mysql -uroot -proot'.
- One important point is that I left the env.php so that it will be easier to run the application than if you would have to copy a env.example to a new file, etc.

Here goes the link to the API documentation. It is quite simple, but do the work!
https://documenter.getpostman.com/view/8417668/2s8Z6u5uwj
