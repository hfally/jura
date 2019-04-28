#JURA ONLINE - <small>CODING CHALLENGE</small>

This is a course outline API. It has different endpoints aimed at providing an hierarchical, extremely flexible and detailed course outline tree with an infinite depth. Also, it has endpoints for creating, updating and deleting.

## Development Stack
This project was built on **LAMP** (Linux + Apache + MYSQL + PHP). Lumen; a slim laravel deviation was the framework adopted.

Dependencies were managed with Composer as recommended by the Lumen documentation. For further information on Lumen, visit https://lumen.laravel.com/5.8/docs

## Basic Local Setup
Follow the procedures listed below to setup the project locally:

**NB:  Apache, Mysql, PHP7.2+ and Composer all have to be installed**

* Clone the project
* Run ``composer install`` to install all dependencies
* Make a copy of .env.example to .env and set your environment variables
* Create a database and connect the application to it via the .env file
* Run ``php artisan migrate`` to create all needed tables
* Run ``php artisan db:seed`` to populate the tables with dummy data
* You can create a virtualhost using tools like EKNOR (https://github.com/hfally/eknor) or just run ``php artisan serve`` to make the API accessible.

## Endpoints
A basic security layer has been set on all available enpoints.
**Authorization** header must be added to all requests.

###Available Methods
* GET - Fetch records
* POST - Create records
* PUT - Update specified records
* DELETE - Delete specified records

###Custom Status Codes
* 200 - Action carried out successfully
* 400 - Bad request (An error caused by you)
* 401 - Unauthorized actions (Authorization key wasnt provided or invalid)
* 500 - You did everything right, but an error occurred (contact support)

###Default Status Code
* 404 - Link not found (wrong url)
* 500 - Something went wrong server-side (contact web master)

#


####COURSES
Course is regarded as the peak of the tree with topics being each's children. Below are all course related endpoints.

#
_**GET all courses**_
```
jura.staging/courses
```

Fetches all courses with their topics and sub-topics.

**Headers**
<pre>
<b>AUTHORIZATION</b>               SAMPLE_AUTHORIZATION_KEY
</pre>

**Response**
````json
{
      "status": 200,
      "count": 6,
      "data": [
          {
              "id": 1,
              "title": "Eos debitis.",
              "course_code": "217",
              "description": "Necessitatibus maxime veniam sed deleniti placeat nulla. Fugit nemo deleniti est commodi. Dolores autem voluptatibus neque culpa repudiandae dolores accusantium.",
              "created_at": "2019-04-27 16:34:47",
              "updated_at": "2019-04-27 16:34:47",
              "topics": [
                  {
                      "id": 2,
                      "topic_id": null,
                      "course_id": 1,
                      "name": "Earum et veniam laborum nam eaque.",
                      "created_at": "2019-04-27 16:34:47",
                      "updated_at": "2019-04-27 16:34:47",
                      "sub_topics": [
                          {
                              "id": 8,
                              "topic_id": 2,
                              "course_id": 1,
                              "name": "Repellendus accusamus sunt expedita.",
                              "created_at": "2019-04-27 16:34:47",
                              "updated_at": "2019-04-27 16:34:47",
                              "sub_topics": [
                                  {
                                      "id": 10,
                                      "topic_id": 8,
                                      "course_id": 1,
                                      "name": "In fuga officiis.",
                                      "created_at": "2019-04-27 16:34:47",
                                      "updated_at": "2019-04-27 16:34:47",
                                      "sub_topics": []
                                  }
                              ]
                          }
                      ]
                  }
              ]
          },
          {
              "id": 2,
              "title": "Molestias et.",
              "course_code": "333",
              "description": "Alias dolores dolore sint unde et. Natus saepe aut qui excepturi fugiat amet. Dolores suscipit porro consectetur eos voluptas ut omnis dolores. Delectus similique quo ut.",
              "created_at": "2019-04-27 16:34:47",
              "updated_at": "2019-04-27 16:34:47",
              "topics": [
                  {
                      "id": 31,
                      "topic_id": null,
                      "course_id": 2,
                      "name": "Qui facere aperiam et nobis.",
                      "created_at": "2019-04-27 16:34:48",
                      "updated_at": "2019-04-27 16:34:48",
                      "sub_topics": []
                  }
              ]
          }
      ]
}
````
**<small>NB: what signifies the end of the depth is an empty sub_topics array</small>**

#

_**GET specified course**_

```
jura.staging/course/{COURSE_ID}
```

Fetches details of a specified course.

**Headers**
<pre>
<b>AUTHORIZATION</b>               SAMPLE_AUTHORIZATION_KEY
</pre>

**Response**

```json
{
    "status": 200,
    "data": {
        "id": 7,
        "title": "Introduction to English Stylistics",
        "course_code": "EGL 401",
        "description": "This is an introductory class to english stylistics",
        "created_at": "2019-04-27 17:47:15",
        "updated_at": "2019-04-27 17:47:25",
        "topics": [
            {
                "id": 76,
                "topic_id": null,
                "course_id": 7,
                "name": "Aim and Objectives",
                "created_at": "2019-04-27 19:02:46",
                "updated_at": "2019-04-28 06:10:55",
                "sub_topics": [
                    {
                        "id": 77,
                        "topic_id": 76,
                        "course_id": 7,
                        "name": "Aim",
                        "created_at": "2019-04-27 19:04:43",
                        "updated_at": "2019-04-28 06:10:55",
                        "sub_topics": [
                            {
                                "id": 79,
                                "topic_id": 77,
                                "course_id": 7,
                                "name": "What is the meaning of gold?",
                                "created_at": "2019-04-27 19:06:17",
                                "updated_at": "2019-04-27 19:06:17",
                                "sub_topics": [
                                    {
                                        "id": 80,
                                        "topic_id": 79,
                                        "course_id": 7,
                                        "name": "Definition",
                                        "created_at": "2019-04-27 19:06:40",
                                        "updated_at": "2019-04-27 19:06:40",
                                        "sub_topics": []
                                    }
                                ]
                            }
                        ]
                     }
                 ]
             }
         ]
     }
 }
```

#

_**POST create course**_

```
jura.staging/courses/create
```
Create a new course. 

**Request**
```json
{
  "title" : "Stylistics",
  "course_code" : "EGL 401",
  "description" : "This is an introductory class to english stylistics"
}
```
<small><b>Course title and course code must be unique.</b></small>

**Headers**
<pre>
<b>AUTHORIZATION</b>               SAMPLE_AUTHORIZATION_KEY
</pre>

**Response**
```
{
    "status": 200,
    "course": {
        "title": "Stylistics",
        "course_code": "EGL 401",
        "description": "This is an introductory class to english stylistics",
        "updated_at": "2019-04-28 18:12:57",
        "created_at": "2019-04-28 18:12:57",
        "id": 8
    }
}
```

#

_**PUT update course**_

```
jura.staging/courses/update/{COURSE_ID}
```

Update details of a specified course.

**Request**
```
{
    "title" : "English Stylistics",
    "course_code" : "EGL 401",
    "description" : "This is an introductory class to english stylistics"
}
```
<small><b>NB: At least one of the fields must be provided for update.</b></small>

**Headers**
<pre>
<b>AUTHORIZATION</b>               SAMPLE_AUTHORIZATION_KEY
</pre>

**Response**

```json
{
    "status": 200,
    "course": {
        "id": 7,
        "title": "English Stylistics",
        "course_code": "EGL 401",
        "description": "This is an introductory class to english stylistics",
        "created_at": "2019-04-27 17:47:15",
        "updated_at": "2019-04-27 17:47:25"
    }
}
```

#

_**DELETE delete course**_

```
jura.staging/courses/delete/{COURSE_ID}
```
Be very sure of what you are doing while hitting this endpoint; not only does it delete the course, it also deletes all topics and subtopics related to it. This is done to avoid littering the database.

**Headers**
<pre>
<b>AUTHORIZATION</b>               SAMPLE_AUTHORIZATION_KEY
</pre>

**Response**
```json
{
    "status": 200,
    "message": "Course and its related topics deleted!"
}
```

#

####TOPICS

Topics can be direct children of a course or children of another topic. Below are all topic related endpoints.

#

_**GET specified topic**_

```
jura.staging/topic/{TOPIC_ID}
```
Fetches details and sub topics of a specified topic.

**Headers**
<pre>
<b>AUTHORIZATION</b>               SAMPLE_AUTHORIZATION_KEY
</pre>

**Response**

```json
{
    "status": 200,
    "data": {
        "id": 76,
        "topic_id": null,
        "course_id": 7,
        "name": "Aim and Objectives",
        "created_at": "2019-04-27 19:02:46",
        "updated_at": "2019-04-28 06:10:55",
        "course_details": {
            "title": "Introduction to English Stylistics",
            "description": "This is an introductory class to english stylistics"
        },
        "sub_topics": [
            {
                "id": 77,
                "topic_id": 76,
                "course_id": 7,
                "name": "Aim",
                "created_at": "2019-04-27 19:04:43",
                "updated_at": "2019-04-28 06:10:55",
                "sub_topics": [
                    {
                        "id": 79,
                        "topic_id": 77,
                        "course_id": 7,
                        "name": "What is the meaning of gold?",
                        "created_at": "2019-04-27 19:06:17",
                        "updated_at": "2019-04-27 19:06:17",
                        "sub_topics": [
                            {
                                "id": 80,
                                "topic_id": 79,
                                "course_id": 7,
                                "name": "Definition",
                                "created_at": "2019-04-27 19:06:40",
                                "updated_at": "2019-04-27 19:06:40",
                                "sub_topics": []
                            }
                        ]
                    }
                ]
            }
        ]
    }
}
```

#

_**POST create topic**_
```
jura.staging/topics/create/
```
Create a topic or a sub topic. This is determined by the type of parent provided: topic or course.

**Request**

```json
{
  "parent" : "course",
  "parent_id" : 7,
  "name" : "Aims and Objectives"
}
```
**NOTE:**
* `parent` can either be `course` or `topic`
* `parent_id` is the id of the parent course/topic
* if `parent` is `course` then the topic is a direct child of the specified course else, you are creating a sub-topic.
* `name` is unique within the group it is placed "sub-topics of a topic" or "direct topics of a course"

**Headers**
<pre>
<b>AUTHORIZATION</b>               SAMPLE_AUTHORIZATION_KEY
</pre>

**Response**

```json
{
    "status": 200,
    "topic": {
        "name": "Aims and Objectives",
        "course_id": 7,
        "topic_id": null,
        "updated_at": "2019-04-28 19:00:18",
        "created_at": "2019-04-28 19:00:18",
        "id": 82
    }
}
```
#
_**PUT update topic**_
```
jura.staging/topics/update/{TOPIC_ID}
```
Update details of a specified topic.

**Request**
```json
{
	"parent" : "topic",
	"parent_id" : 2,
	"name" : "Aim and Objectives"
}
```

**Headers**
<pre>
<b>AUTHORIZATION</b>               SAMPLE_AUTHORIZATION_KEY
</pre>

**Response**

```json
{
    "status": 200,
    "topic": {
        "id": 82,
        "topic_id": 2,
        "course_id": 1,
        "name": "Aim and Objectives",
        "created_at": "2019-04-28 19:00:18",
        "updated_at": "2019-04-28 19:30:36",
        "sub_topics": []
    }
}
```

#

_**DELETE delete topic**_

```
jura.staging/topics/delete/{TOPIC_ID}
```
Delete a specified topic and consequentially delete all its sub-topics to avoid database littering.

**Headers**
<pre>
<b>AUTHORIZATION</b>               SAMPLE_AUTHORIZATION_KEY
</pre>

**Response**

```json
{
    "status": 200,
    "message": "Topic and its related topics deleted!"
}
```

#
## STAGING BASE URL
https://jura-api.herokuapp.com

## Author
Henry Falade

[Github](https://github.com/hfally)

[Medium Posts](https://medium.com/@hfally)

[hello@henryfalade.me](mailto:hello@henryfalade.me) | [+234 810 261 0381](tel:+2348102610381)