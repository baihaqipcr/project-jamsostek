@workspace Act as an Expert UI/UX Engineer, Frontend Developer, and Motion Designer. I need to completely overhaul my Login and Register pages. 

The goal is to replace the current dark, flat, static design (as seen in image_7.png) with a modern, bright, animative, and premium "Bright Glassmorphism" aesthetic inspired by the second reference image (image_8.png).

Please help me implement this across the stack.

**1. STRICT COLOR PALETTE (Bright BPJS Branding):**
Instead of dark tones, use the bright, fresh, and flowing colors from the branding seen in image_8.png:
*   **Background (Light Gradient):** A bright, diffused, organically flowing background blending the 3 brand colors seamlessly:
    *   Primary (Dark Green): `#3AB44A`
    *   Secondary (Cyan Blue): `#05ADDC`
    *   Accent/Mid-tone (Lime Green): `#D6E65D`
*   **Primary Text (Title):** `#3AB44A` (Dark Green) for the main titles like "Masuk petugas".
*   **Glow & Accents:** `#05ADDC` (Cyan Blue) for Glowing effect, glowing input outlines, active states, and interactive links.

**2. DESIGN SYSTEM: BRIGHT GLASSMORPHISM:**
*   **The Container:**
    *   The static solid white box (from image_7.png) must be replaced with a highly transparent, frosted glass-like panel.
    *   *Tailwind reference:* `bg-white/10 backdrop-blur-2xl border border-white/20 shadow-2xl rounded-3xl`.
    *   Use highly rounded corners and a soft shadow to make it appear floating.
*   **Inputs:** 
    *   When focused, inputs must have a bright glowing cyan (`#05ADDC`) outline, with a soft blur effect, mimicking the glowing inputs in image_8.png.
*   **Typography:** Keep the Indonesian text, but make it clean, modern (e.g., using fonts like Inter or Plus Jakarta Sans). High legibility is required.

**3. INTERACTIVITY & ANIMATION (Human-Centric):**
*   **Glowing Cursor Trail:** This is a crucial element. Implement the bright cyan glowing cursor trail effect exactly as seen in image_8.png. The trail should follow the user's cursor across the entire page.
*   **Inputs Micro-interactions:** Input fields should have subtle scaling (`scale: 1.02` on focus) and magnetic hover effects.
*   **Form Fade-in:** When the page loads, use Framer Motion or smooth CSS transitions to fade in the entire glass panel and its elements with a staggered delay.
*   **Login <-> Register Transitions:** When the user switches between the Login and Register view, implement a smooth fade-and-slide page transition.

**4. FUNCTIONAL UPDATES (Add Register Button):**
*   **Add "Daftar" Link:** On the Login page (image_7.png), right below the "Masuk" button, add a new interactive link: "Belum punya akun? Daftar". This link should navigate the user to the Register page view.
*   **Create the Register Page View:** Build the Register page using the same aesthetic, with a similar glass panel.
    *   Title: "Daftar petugas" (using the `#3AB44A` green)
    *   Text support: "Buat akun bidang KSI untuk mencatat potensi."
    *   Fields: "Nama Lengkap", "Email (KSI)", "Kata Sandi", "Konfirmasi Kata Sandi".
    *   Main Button: "Daftar petugas" (same style as the updated "Masuk" button).
    *   Add link back: "Sudah punya akun? Masuk".

**Execution Plan for Copilot:**
1. I have attached my existing Login and Register component files.
2. Please rewrite these components with the Bright Glassmorphism look and the new "Daftar" link.
3. Then, provide the CSS and motion logic for the glowing cursor trail and page transitions.