# Proprli Technical Assessment

## Step by Step

Clone Repository

https://github.com/Mapexss/Proprli.git

git@github.com:Mapexss/Proprli.git

## Running development build (includes using laravel sail)
#### The following command will create a docker container called Proprli with 4 images:
 - (port 80) nginx-server
 - (por 5432) postgres with 2 databases (production and testing)
 - (port 81) laravel.test-1 (execute tests independently)
 - (no external port) app - Laravel production enviroment (accessible through nginx proxy)

#### Create Docker Container and run initial setup
```sh
sh setup.sh
```

#### Running tests in laravel.test container

```sh
./vendor/bin/sail test
```

## Project structure

### I created 5 entities based on application needs:

 - Buildings: Real estate properties with associated tasks
 - Teams: Groups containing multiple users
 - Users: System actors who can create/be assigned tasks and comments
 - Tasks: Work items linked to buildings and users
 - Comments: User feedback on tasks

### Design Decisions

 - Task assignment restricted to team members only
 - Comment permissions limited to same-team users
 - Tasks connected to buildings for multi-property management
 - Users belong to one team but can handle multiple buildings
 - Most relationships are one-to-many except Teams-Users connection


### Architecture
Implemented Clean Architecture separating:
Laravel components (API/database handling) and Business logic (independent from framework) making testing and framework migration easier

### Code Organization

#### Core/Domain:
 - Business entities, use cases, and builders
#### Data: 
 - Services and repository interfaces


### API Endpoints
#### Get Tasks: 
GET `/api/tasks?status=completed&assigned_user_id=1&start_date=2025-03-01&end_date=2025-03-02&building_id=1`

 - status: Narrows results to tasks with a specific workflow state (options: open, in-progress, completed, rejected)
 - assigned_user_id: Retrieves tasks allocated to a particular team member, identified by their unique identifier
 - building_id: Limits results to tasks associated with a specific property, using the property's database identifier
 - start_date: Shows tasks created on or following the specified date (inclusive lower boundary for creation timeframe)
 - end_date: Shows tasks created on or before the specified date (inclusive upper boundary for creation timeframe)

#### Create Task: 
POST `/api/tasks/{building_id}`
```json
{
 "name": "Any Name",
 "description": "Any Description",
 "status": "open",
 "assigned_user_id": 1,
 "creator_user_id": 2
}
```

#### Create Comment: 
POST `/api/comments/{task_id}`
```json
{
 "content": "Any text",
 "creator_user_id": 5
}
```