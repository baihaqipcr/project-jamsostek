@workspace Act as a Senior Full-Stack Engineer and Security Expert. I need to implement "Data Ownership / Data Isolation" for my application ("Pencatatan Potensi Jamsostek Bidang KSI"). 

The goal is: Employee A can only see, edit, and manage their own data. When Employee A logs out and Employee B logs in, the dashboard and data tables must ONLY show Employee B's data. 

Please help me implement this across the stack.

**1. Database Schema Update:**
* Look at my database schema. Ensure the `Potensi` table has a relation to the `Users` table (e.g., `user_id` or `pegawai_id`).
* How should I update the schema or migration file to reflect this? 
* The system must automatically assign the logged-in user's ID when a new record is created.

**2. Backend API Refactoring:**
* Update the `GET` endpoints (Dashboard statistics, List Data) to strictly filter records by the authenticated user's ID (`WHERE user_id = current_user.id`).
* Update `PUT/PATCH` and `DELETE` endpoints to verify that the item belongs to the user requesting the change.
* (Optional) If we have roles, allow `ADMIN` to see all data, but `PEGAWAI` to see only their own.

**3. Frontend State & Cache Management:**
* When a user logs out, we must explicitly clear all local state, caching mechanisms, and session data so Employee B does not see Employee A's cached data.

**Next Steps for Copilot:**
1. I have attached the relevant files using `#file`.
2. Please provide the exact code changes needed for the Schema first.
3. Then, provide the updated Backend query logic.
4. Finally, show me how to properly clear the cache/state on the Frontend logout function.