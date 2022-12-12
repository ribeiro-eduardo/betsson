Welcome to Betsson API!

To run the project, you will need to follow these next steps:
- Run the SQL Dump attached to the email I sent you.
- Open the project in your IDE
- On the command line of the project, run "composer install" to install all the dependencies.
- Create a new file in the root of the project called 'env.php'
- Copy the content of the file called 'env.example.php' located in the root as well
- Paste it on the new file you created (env.php) and set the user, password and port to your MySQL server
- On the command line of the project, run "cd public"
- Once in public directory, run "php -S localhost:8080"

- The available routes are:
  - Add new customer: 
    Method: POST
    Address: http://localhost:8080/customers
    Body example: 
    {
    	"first_name": "Gabriela",
    	"last_name": "Ribeiro",
    	"gender": "female",
    	"country": "BR",
	    "email": "gabriela@betsson.com"
    }
    
  - Edit customer details:
    Method: PUT
    Address: http://localhost:8080/customers/{customer_id}
    Body example:
    {
      "first_name": "María",
      "last_name": "Cruzes",
      "gender": "female",
      "country": "BR",
      "email": "maria@betsson.com.br"
    }
    
  - Deposit
    Method: PUT
    Address: http://localhost:8080/deposit/{account_id}
    Body example:
    {
      "amount": 100
    }
  
  - Withdrawal
    Method: PUT
    Address: http://localhost:8080/withdrawal/{account_id}
    Body example:
    {
      "amount": 200
    }
    
  - Operations report
    Method: GET
    Address: http://localhost:8080/operations_report/{interval}
    PS: parameter interval is optional. If not passed, the API will assume 7 days
    
 - Extra Endpoint: Customer(s) data
    Method: GET
    Address: http://localhost:8080/customers/{customer_id}
    PS: parameter customer_id is optional. If not passed, the API will return a list of all customers registered.
    

