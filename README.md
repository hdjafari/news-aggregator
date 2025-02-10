## Installation

1. Clone the repository:

```bash
$ git clone https://github.com/hdjafari/news-aggregator.git
$ cd news-aggregator
```

2. Install dependencies:

```bash
$ composer install
```

3. Copy the `.env.example` file to `.env` and configure your environment variables:

```bash
$ cp .env.example .env
```

4. Generate an application key:

```bash
$ php artisan key:generate
```

5. Run the database migrations:

```bash
$ php artisan migrate
```

6. Start the development server:

```bash
$ php artisan serve
```

The application will be available at `http://127.0.0.1:8000`.

## API Endpoints

### Get Articles

Retrieve articles based on search queries, filtering criteria, and user preferences.

- **URL:** `/api/articles`
- **Method:** `GET`
- **Query Parameters:**
  - `search` (optional): Search query for article titles and content.
  - `category` (optional): Filter articles by category.
  - `source` (optional): Filter articles by source.
  - `author` (optional): Filter articles by author.

### Get Categories

Retrieve all categories.

- **URL:** `/api/categories`
- **Method:** `GET`

## Scheduling Jobs

The application schedules jobs to fetch articles from various sources every minute. The categories to fetch can be configured in the `.env` file using the `NEWSCATEGORIES` variable.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
