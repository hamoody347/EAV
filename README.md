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


### Supported Operators
#### Here are the available operators:

* `>`: Greater than

* `<`: Less than
* `gt`: Greater than (alternative)
* `lt`: Less than (alternative)
* `LIKE`: SQL LIKE operator (case-insensitive)
* `like`: SQL LIKE operator (case-sensitive)

#### Example Usage:

* `GET projects?filters[name]=Project1&filters[name_operator]=LIKE`

* `GET /api/projects?filters[created_at]=2025-03-06&filters[created_at_operator]=>`

 ## Sample Request / Response

 Following are some requests and the expected responses.

`POST /api/projects`

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

### Authentication

To access any of the protected API routes, you must authenticate using Laravel Passport. The `/login` route will return a personal access token.

Use the following request to get a token:

`POST /login`

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
    "token": "your_personal_access_token"
}
```

Use the token for subsequent API requests by including it in the Authorization header as a Bearer token:

`Authorization: Bearer your_personal_access_token`
