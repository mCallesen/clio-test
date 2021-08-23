Taxonomy exercise 
====

## Endpoints
#### Add a new node to the tree.
```
POST /api/employees/
```

Accepts JSON sent in a post requests. Example:
```
curl -X POST http://localhost:8000/api/employees/ \
     -H 'Content-Type: application/json' \
     -d '{"id": 1, "name": "Morten", "parent_id": 1, "job_title": "developer", "additional_info": {"type": "language", "info": "PHP"}}'
```

#### Get all child nodes of a given node from the tree. (Just 1 layer of children)
```
GET /api/employees/get_children/<id>
```
A GET request to this endpoint will return a JSON representation of all children of the node with the given ID
```
curl http://localhost:8000/api/employees/get_children/1
```

#### Change the parent node of a given node.
```
POST /api/employees/update_parent/<id>
```
Accepts JSON sent in a POST request. The JSON must contain the id of the new parent node:
```
curl -X POST http://localhost:8000/api/employees/update_parent/2 \
     -H 'Content-Type: application/json' \
     -d '{"new_id": 1}'
```

## The models
Most of the program revolves around the Employee model and its controller. This class contains the requested information.
In addition, there are two subclasses of Employee, Developer and Manager which mostly differs from Employee by having different validation rules.

I chose to implement the extra fields for developer/manager as a model related to Employee. This model is called AdditionalInfo and contains two fields, type and info, which are both strings.
The AdditionalInfo is required when adding a developer or manager.  
I did it this way because I wanted to avoid adding fields to the Employee table that might be null most of the time, depending on how many different types of employees you have. In addition, it makes it pretty easy to add special fields for other types of employees.


## How to run it
### Requirements
The program was created with php 7.4.3, Laravel 8.55.0 and sqlite3 3.31.1.
In addition, I needed to install the following programs:
```
sudo apt install php-xml php-mbstring php7.4-sqlite3
```

### Setup
To setup the database, go to the app/database folder and write 
```
touch database.sqlite
```
Now open the .env file in the project root and replace the path in the line: 
```
DB_DATABASE=/home/morten/code_stuff/lv_training/clio-test/database/database.sqlite
```
with the path to you newly created database.sqlite file.
Let's just mention for good measure that it's usually a bad idea to leave .env files out in the open, but since this doesn't contain anything dangerous and the project is only for running locally, it should be okay.

Now, in the project root, run the command to create the necessary tables and create a root node:
```
php artisan migrate
php artisan db:seed
```

If everything's gone well so far, you should be able to run the program by running the command:
```
php artisan serve
```


## Improvements 
I imagine there are quite a few since this is my first Laravel project, but here are some things I would like to have done if I had more time:
- Add tests.
- Make a docker image to run the application, the setup is a bit too complicated.
- Add proper validation.

I also had some problems with handling the related AdditionalInfo class when parsing the JSON in the add_node function into an Employee object.  
I ended up making a not very pretty workaround (which can be seen in the add_node function in app\Http\Controllers\EmployeeController.php) that manually sets the additional_info property on the Employee and does a lot of stuff manually that I'm sure Laravel can handle for me. Would like to fix it.



