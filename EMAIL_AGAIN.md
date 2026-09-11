@workspace Act as a Senior Full-Stack Developer. I have decided to revert my authentication system. The previous "User Code" and "Auto-generated password sent via email" flow is too complex for the users. 

I want to return to a standard, traditional Auth flow: Login using Email and a Password created by the user themselves.

Please refactor the stack to implement these changes:

**1. Database Schema Reversion:**
* Remove the `user_code` column from the Users/Pegawai table.
* Ensure the `email` column is set to UNIQUE and is required.

**2. Registration Flow (Frontend & Backend):**
* **Frontend:** Update the registration form to accept `full_name`, `email`, `password`, and `password_confirmation`.
* **Backend:** Remove the logic that auto-generates the `user_code` and random password. 
* **Backend:** Remove the email sending service completely from the registration process. 
* **Backend:** Simply validate the input, hash the user-provided `password`, and save the record to the database.

**3. Login Flow:**
* Refactor the Login controller to accept `{ email, password }` instead of `{ user_code, password }`.
* Update the database query to authenticate the user by checking their `email`.

**Execution Plan:**
1. I have tagged my Auth Controller, User Model/Schema, and the Frontend Registration View.
2. First, provide the schema updates to remove `user_code`.
3. Second, rewrite the Registration controller to accept a user-defined password and remove the mailer logic.
4. Third, rewrite the Login controller to use email again.
5. Finally, update the frontend Registration form fields.