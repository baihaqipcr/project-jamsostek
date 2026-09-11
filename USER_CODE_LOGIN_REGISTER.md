@workspace Act as a Senior Backend Developer. I want to change my authentication system from "Email-based login" to a "Unique User Code login". 

Email will now ONLY be used during registration (to send the generated credentials) and for "Forgot Password". 

**1. Database Schema Update:**
* Add a `user_code` column (String, Unique) to the Users/Pegawai table.

**2. Auto-Generate Credentials Logic (Registration):**
* When registering a new user, take their `full_name`.
* Extract the first letter of the first word, and the first letter of the second word (e.g., "Azril Ramadhan" -> "AR"). If only one word, use the first two letters.
* Append 5 random digits to create the `user_code` (e.g., "AR96283").
* Check the database to ensure this exact `user_code` is unique. If it exists, regenerate the 5 digits.
* Generate a random secure password (8 characters, alphanumeric).
* Hash the password and save the new user record.

**3. Email Notification (Nodemailer/Mailer):**
* Create a service that automatically sends an email to the user's provided email address upon successful registration.
* The email template should include: "Selamat datang! Berikut adalah detail login Anda: Kode User: [user_code], Password: [random_password]".

**4. Login Refactoring:**
* Refactor the Login controller. The payload should now accept `{ user_code, password }` instead of `{ email, password }`.
* Update the database query to find the user by `user_code`.

**Execution Plan:**
1. I have tagged my Auth Controller, User Model/Schema, and Registration service.
2. First, provide the schema updates.
3. Second, provide the helper functions to generate the `user_code` and `random_password`.
4. Third, update the Registration and Login endpoints.