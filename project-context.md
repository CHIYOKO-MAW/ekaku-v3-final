# Project Context

## Project Overview

This project is a Laravel-based web application used to manage public service workflows.

The system contains:

* User-facing features
* Administrative features
* Multi-role access control
* Document management
* Verification workflows
* Reporting and dashboard features

---

## Technology Stack

Backend:

* Laravel
* PHP
* Eloquent ORM

Frontend:

* Blade
* Alpine.js
* Tailwind CSS

Database:

* Relational database (SQLite/MySQL)

---

## Development Philosophy

Prefer framework conventions over custom implementations.

Favor maintainability, readability, and consistency.

Avoid unnecessary complexity.

---

## Existing Codebase Expectations

Before introducing new patterns:

* Inspect existing implementations.
* Reuse existing solutions when appropriate.
* Keep changes consistent with surrounding code.

Consistency is generally preferred over introducing new architectural styles.

---

## Frontend Expectations

User interfaces should:

* Remain responsive
* Remain accessible
* Be easy to maintain
* Follow consistent interaction patterns

When redesigning or improving UI:

* Consider existing user flows.
* Preserve functionality unless explicitly changed.
* Avoid breaking established workflows.

---

## Backend Expectations

Business logic should be understandable and maintainable.

When making changes:

* Prefer Laravel conventions.
* Keep responsibilities clear.
* Avoid unnecessary abstraction.

---

## Database Expectations

Schema changes should be deliberate.

Before modifying database structures:

* Understand existing relationships.
* Consider migration impact.
* Consider existing data.

---

## Debugging Expectations

Focus on root causes.

Avoid symptom-based fixes.

Verify only the functionality relevant to the requested task.

---

## Documentation Expectations

Project documentation should describe the project.

Agent behavior should be defined in AGENTS.md.

Do not mix project documentation and agent instructions.

---

## Important Principle

This file provides context about the project.

It does not override explicit user requests.

If the user requests a redesign, refactor, architectural change, or alternative implementation, follow the user's request.
