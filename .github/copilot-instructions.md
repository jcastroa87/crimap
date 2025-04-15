# Copilot Instructions

## Technologies Used
- **Laravel:**  
  - A powerful PHP framework that provides a robust foundation for modern web applications.
  - **Documentation:** [Laravel Docs](https://laravel.com/docs/12.x)
  - **Best Practices:**  
    - Follow PSR-12 coding standards and Laravel’s conventions.
    - Maintain clear separation between models, views, and controllers (MVC).
    - Utilize Eloquent ORM for database interactions.
    - Secure the application with proper middleware, input validation, and CSRF protection.
    - Manage environment-specific configurations using the `.env` file.

- **Backpack for Laravel:**  
  - A ready-made admin interface package that significantly speeds up the development of CRUD operations.
  - **Documentation:** [Backpack for Laravel Docs](https://backpackforlaravel.com/docs/6.x)
  - **Best Practices:**  
    - Leverage Backpack’s built-in operations for common tasks.
    - Customize fields, validation, and views as per the official documentation.
    - Keep custom modifications modular and consistent with Backpack’s extension patterns.
    - Maintain up-to-date documentation for any custom changes to make onboarding easier.

- **Tabler Admin Template:**  
  - A modern and responsive admin template that enhances the user interface with a clean design.
  - **Documentation:** [Tabler Admin Docs](https://tabler.io/docs)
  - **Best Practices:**  
    - Integrate the template with Laravel’s Blade templating for seamless UI development.
    - Use Laravel Mix (or your preferred asset bundler) to compile and optimize Tabler’s assets.
    - Ensure responsiveness and accessibility by adhering to Tabler’s design guidelines.
    - Customize the theme as necessary while keeping updates manageable with a clear asset management workflow.

## Best Practices

Below is a comprehensive guide outlining best practices when coding in Laravel. These practices help you write clean, maintainable, and efficient code while taking full advantage of Laravel’s powerful features. Incorporate these guidelines into your workflow whether you’re starting a new project or refining an existing one.

## Development Guidelines

1. **Follow the Laravel Conventions and MVC Architecture**
    - **Embrace the MVC Pattern:**  
        - Separate concerns by using models for data, controllers for business logic, and views for presentation.
        - Keep controllers lean; delegate complex logic to dedicated service classes or repositories.
    - **Directory Structure:**  
        - Use Laravel’s default directory structure to maintain consistency.
        - Consider additional folders like `Services`, `Repositories`, or `Jobs` for extra logic.

2. **Adopt Consistent Coding Standards**
    - **PSR-12 Compliance:**  
        - Follow the PSR-12 coding standard for PHP.
        - Employ tools such as PHP CS Fixer or PHP_CodeSniffer to automate enforcement.
    - **Naming Conventions:**  
        - Use clear and descriptive names for classes, methods, and variables (camelCase for variables/methods, PascalCase for classes).
    - **Commenting and Documentation:**  
        - Write meaningful inline comments and use PHPDoc for thorough documentation.

3. **Manage Environment Configuration Effectively**
    - **Use the `.env` File:**  
        - Store configuration settings, API keys, and secrets in the `.env` file.
        - Do not commit this file; use `.env.example` as a template.
    - **Configuration Caching:**  
        - Cache configuration using `php artisan config:cache` and refresh upon changes.

4. **Leverage Eloquent ORM Wisely**
    - **Relationship Management:**  
        - Utilize Eloquent relationships to simplify data interactions and use eager loading (`with()`) to optimize performance.
    - **Query Building:**  
        - Use the query builder for complex queries, ensuring input validation and sanitization.
    - **Mass Assignment:**  
        - Protect models using `$fillable` or `$guarded` properties.

5. **Emphasize Security Best Practices**
    - **Input Validation and Sanitization:**  
        - Use Laravel’s request validation and CSRF protection.
    - **Authentication and Authorization:**  
        - Implement Laravel’s authentication scaffolding or packages like Laravel Fortify; use policies/gates for permissions.
    - **Error Handling:**  
        - Centralize error handling via Laravel’s exception handler and provide user-friendly error messages.

6. **Write Tests to Ensure Code Reliability**
    - **Automated Testing:**  
        - Use PHPUnit and Laravel’s testing utilities for unit and feature tests to ensure high coverage.
    - **Test-Driven Development (TDD):**  
        - Consider TDD to guide design and maintain overall code quality.

7. **Optimize Application Performance**
    - **Caching Strategies:**  
        - Utilize Laravel’s caching mechanisms (file, Redis, Memcached) to store heavy computations or queries.
    - **Queueing and Background Jobs:**  
        - Offload heavy tasks using Laravel Queues and monitor for failures.
    - **Optimized Query Performance:**  
        - Profile and optimize queries with proper indexing and batching where applicable.

8. **Utilize Dependency Injection and Service Providers**
    - **Dependency Injection:**  
        - Favor constructor injection for dependencies to improve testability and decouple components.
    - **Service Providers:**  
        - Register custom services, event listeners, or bindings within service providers, keeping them lean.

9. **Organize Routes and API Endpoints**
    - **Route Organization:**  
        - Use Laravel’s resourceful routing for web and API endpoints; group routes by feature and cache with `php artisan route:cache`.
    - **RESTful API Design:**  
        - Follow REST conventions with proper HTTP verbs, resource-based endpoints, clear status codes, and versioning.

10. **Embrace Laravel’s Ecosystem and Tools**
    - **Artisan CLI:**  
        - Leverage Artisan for tasks such as migrations, testing, and creating boilerplate code.
    - **Laravel Mix:**  
        - Manage and compile front-end assets efficiently with Laravel Mix.
    - **Regular Dependency Updates:**  
        - Keep Laravel and packages up-to-date, reviewing release notes and update guidelines regularly.

- **Code Structure & Standards**  
    - **Laravel:** Adhere to the conventional MVC structure; keep business logic in controllers and models, and maintain focused views.
    - **Backpack:** Use Backpack’s CRUD controllers and operations, extending only when necessary per documentation.
    - **General:** Follow PSR-12 coding standards and ensure code is well-commented and modular.

- **API & Routing**  
    - **Routing:**  
        - Use Laravel’s resourceful routing for both web and API endpoints; secure API routes in `routes/api.php` with middleware.
    - **API Design:**  
        - Adopt RESTful API principles, document endpoints and version them, and utilize Laravel API resources for JSON responses.

- **Testing & Quality Assurance**  
    - **Testing:** Write comprehensive tests using Laravel’s PHPUnit integration—focus on both unit and feature tests.
    - **Code Reviews:** Conduct peer reviews and employ continuous integration to automatically run tests on new commits.

- **Deployment & Maintenance**  
    - Keep dependencies updated; document and monitor changes to routing, database schema, or UI components.
    - Employ appropriate logging and error handling to monitor production performance.

- **Project Documentation and Memory Files:**  
    - Always update the `/docs` directory with new or revised documentation to ensure all information about the project is available and current.
    - **Documentation Directory:**  
        - All project documentation—including memory files, user guides, architecture documents, and design decisions—must be stored in the `/docs` directory.
        - Update or create documentation files in `/docs` as part of every significant change to the project.
        - Maintain a clear README.md in `/docs` as the entry point for project documentation.
    - **Memory Files:**  
        - Write and update memory files that capture key decisions, configuration changes, and critical system behaviors.
        - Keep memory files synchronized with project changes to provide a historical context and guide future maintenance.

- **Final Notes**  
    - **Security:** Validate inputs, gracefully handle exceptions, and protect against common vulnerabilities.
    - **Consistency:** Maintain consistency across all modules (Laravel, Backpack and Tabler) to ensure a cohesive user experience and maintainable codebase.

## MCP Server
You have available this servers to use:

- **Memory MCP Server:**  
    - A server that implements the Model Context Protocol (MCP) for managing ephemeral data, caching, and context management.
    - This server is used to manage session data, cache temporary states, or store context information across requests.
    - **Documentation:** [Memory MCP Server](https://github.com/modelcontextprotocol/servers/tree/main/src/memory)

- **Brave-Search MCP Server:**
    - A server that implements the Model Context Protocol (MCP) for search functionality.
    - This server is used to power internal search or external query capabilities.
    - **Documentation:** [Brave-Search MCP Server](https://github.com/docker/mcp-servers/tree/main/src/brave-search)

- **Fetch MCP Server:**
    - A server that implements the Model Context Protocol (MCP) for data retrieval.
    - This server is used to robustly fetch daily exchange rates or related data, especially as a fallback when other APIs fail.
    - **Documentation:** [Fetch MCP Server](https://github.com/modelcontextprotocol/servers/tree/main/src/fetch)

## Follow these steps for each interaction:

1. **Memory Retrieval**
    - Always begin your chat by saying only "Remembering..." and retrieve all relevant information from your knowledge graph
    - Always refer to your knowledge graph as your "memory"

2. **Memory**
    - While conversing with the user, be attentive to any new information that falls into these categories:
        - New module to develop
        - Fix an error
        - Update module

3. **Memory Update**
    - If any new information was gathered during the interaction, update your memory as follows:
        - Create entities for recurring organizations, people, and significant events
        - Connect them to the current entities using relations
        - Store facts about them as observations
