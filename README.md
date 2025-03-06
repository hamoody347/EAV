## Installation

Install dependancies
```
composer install
```

Run migrations
```
php artisan migrate --seed
```

Create passport client
```
php artisan passport:client --personal
```

Run server
```
php artisan serve
```

## Usage

### Routes

Project, Timesheet & User models support CRUD operations.

```
GET /api/{model}

GET /api/{model}/{id}

POST /api/{model}

PUT /api/{model}/{id}

DELETE /api/{model}/{id}
```

### Filtering

The ``` GET /api/{model} ``` route supports filtering for all 3 models.

 By default all filters will use the `=` operator.

 The syntax to pass a filter will be `projects?filters[name]=Project1`.

 This should have the exact field name.

 For the use of different operators, pass the name of the field followed by `_operator` and then the operator itself

 `projects?filters[name]=Project1&filters[name_operator]=LIKE`

### Operators
 The possible operators are `>, <, gt, lt, LIKE and like`

 ## Sample Request / Response

 Following are some requests and the expected responses.

`POST 127.0.0.1:8000/api/projects`

 ### Request
 ``` JSON
{
    "name": "Sample Project",
    "status": "active",
    "attrs": [
        {
            "attribute_id": 1,
            "value": "Quality Assurance"
        },
        {
            "attribute_id": 2,
            "value": 3000
        }
    ]
}
 ```

### Response

``` JSON
{
    "name": "Sample Project",
    "status": "active",
    "updated_at": "2025-03-06T22:56:07.000000Z",
    "created_at": "2025-03-06T22:56:07.000000Z",
    "id": 17,
    "attribute_values": [
        {
            "id": 15,
            "value": "Quality Assurance",
            "attribute_id": 1,
            "entity_type": "App\\Models\\Project",
            "entity_id": 17,
            "created_at": "2025-03-06T22:56:07.000000Z",
            "updated_at": "2025-03-06T22:56:07.000000Z",
            "attribute": {
                "id": 1,
                "name": "department",
                "type": "select",
                "created_at": "2025-03-06T21:16:59.000000Z",
                "updated_at": "2025-03-06T21:16:59.000000Z"
            }
        },
        {
            "id": 16,
            "value": "3000",
            "attribute_id": 2,
            "entity_type": "App\\Models\\Project",
            "entity_id": 17,
            "created_at": "2025-03-06T22:56:07.000000Z",
            "updated_at": "2025-03-06T22:56:07.000000Z",
            "attribute": {
                "id": 2,
                "name": "budget",
                "type": "number",
                "created_at": "2025-03-06T21:16:59.000000Z",
                "updated_at": "2025-03-06T21:16:59.000000Z"
            }
        }
    ]
}
```

`POST 127.0.0.1:8000/login`

### Request
``` JSON
{
    "email": "admin@example.com",
    "password": "password"
}
```
### Response
``` JSON
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5ZTVlYjU3MS02OGRhLTRjNjItYjM5ZS1kMDMzN2I5ZjkyNzIiLCJqdGkiOiJiZjQwNjY3MWNlZTA3MTk4MTBkMzZhZjcwN2NhM2Y0MTg4ZTZjNDI2NmU1OGJmNjI5ZDM2MmQyZTViY2IwMGM2NTg4Y2E1NjI3OGRhMzEzZiIsImlhdCI6MTc0MTMwMjE3OC41OTEwMDQsIm5iZiI6MTc0MTMwMjE3OC41OTEwMDksImV4cCI6MTc3MjgzODE3OC41Mjg1MjcsInN1YiI6IjIiLCJzY29wZXMiOltdfQ.enW7hFSLe39rCdvc-3UyXH14C4aI0WoOAKKgrGF7mHIHMKcNzlXQEUmshbxSR2NdqWZcouSJna5Y0vbhM8Ik3BDxoTXby65HlGfNfsycjFnsVjTd0JoUsxN4QWuZOViwwPiIls2osTUZt1Bhx2UNuD5XsManhxbWFcet6qoN4Lz78dj1WSB4bt_YGjldYG9yXwpFOsdyHU3-A8b1ISaJdR5wQY42HxY2FN1NHfzaSBHTPDulQtOeBJFUndBWrnsitAsF4sRz5TbHlqbqUTHO8fdoQft7nPj7KO8zXrBrcKeCHNCckrRFh1rqnYQlM01At6iUQBS50VGQDlWjdb3BjmdBgshyBJoimPUJvQVJW1gJkjxcJ7oAzmbxQ0ND8zp7eemkliyrJdGjzwBnLv0urTZ_hKuI-uIdoZkG7huNlDQl2hEZ09YletPEnL0Bc0uqbQeC50u76Q4wAhRI7Zsh9f-O72652PLSjcWyLWwFvgCHgsHyyuHyP5X7VhRz6ADDN7f_wJmNwn8flrLyz7EUwOezecGpkxCGTx9iwQ2ZX2hevwyTvYrP22lqbkr5VpxbmZPvccPztMxh8t1knvqDqJMV4jIb-9Ox4vI0gF1Y06T0ftaEvLX-cYVjOJMpOoaOr2pdf4J5pQkBll9bYXMY9d-8Yye1_u_Ok7JI-8fIfpI"
}
```

## Testing Credentials

### email: `admin@example.com`
### password: `password`
