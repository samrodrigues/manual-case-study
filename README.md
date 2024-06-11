# Project Overview

This project is set up with a SQLite database and includes an admin panel accessible at `/admin`. The project consists of two main API endpoints:

1. **Get Questionnaire**: Fetch a questionnaire with its questions and options.
2. **Submit Questionnaire**: Submit responses to a questionnaire and get recommended products.

## Prerequisites

- PHP 8.x
- Symfony 7.1 or later
- SQLite

## Installation

1. **Clone the Repository**

    ```bash
    git clone git@github.com:samrodrigues/manual-case-study.git
    cd manual-case-study
    ```

2. **Install Dependencies**

    ```bash
    composer install
    ```

3. **Copy `.env.example` to `.env` and generate a secret if needed**

    ```bash
    cp .env.example .env
    # Generate a new APP_SECRET if needed
    php bin/console secrets:generate-keys
    ```
   
4. **Database Setup**

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

5. **Start the Server**

    ```bash
    symfony server:start
    ```

## Admin Panel

Access the admin panel at [http://localhost:8000/admin](http://localhost:8000/admin).

## API Endpoints

### Get Questionnaire

**Endpoint**: `/api/questionnaire/{id}`

**Method**: `GET`

**Description**: Retrieves the questionnaire with the specified ID, including all questions and options.

**Response**:

```json
{
  "id": 1,
  "name": "Case Study Questionnaire",
  "questions": [
    {
      "id": 1,
      "text": "Do you have difficulty getting or maintaining an erection?",
      "options": [
        {
          "id": 1,
          "text": "Yes",
          "nextQuestionId": 2
        },
        {
          "id": 2,
          "text": "No",
          "nextQuestionId": 2
        }
      ]
    }
  ]
}
```

### Submit Questionnaire

**Endpoint**: /api/questionnaire-submissions/

**Method**: POST

**Description**: Submits the answers to a questionnaire and returns recommended products.

**Request Body**:

```json
{
    "questionnaire_id": 1,
    "respondent_id": 1,
    "answers": [
        {
          "question_id": 1,
          "option_id": 1
        },
        {
          "question_id": 2,
          "option_id": 4
        }
        // Other answers...
  ]
}
```

**Response**:

```json
[
    {
        "id": 1,
        "name": "Sildenafil 50mg"
    },
    {
        "id": 2,
        "name": "Tadalafil 10mg"
    }
    // Other recommended products...
]
```
## Development
**Adding New Questions or Options**

To add new questions or options, use the admin panel. You can also manage the relationships between options and products.