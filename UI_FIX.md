@workspace Act as an Expert UI/UX Engineer, Frontend Developer, and Motion Designer. I want to completely overhaul the user interface and user experience of my web application, "Pencatatan Potensi Jamsostek Bidang KSI".

The new design must be highly modern, interactive, "human-centric", and use a premium "Glassmorphism" aesthetic. 

**1. STRICT COLOR PALETTE (BPJS Branding):**
You must strictly use these exact colors as the foundation of the UI (backgrounds, gradients, text, accents):
*   **Primary (Dark Green):** `#3AB44A` - Use for main branding, primary buttons, and strong text.
*   **Secondary (Cyan Blue):** `#05ADDC` - Use for glowing effects, interactive accents, active states, and hover effects.
*   **Accent/Mid-tone (Lime Green):** `#D6E65D` - Use for subtle gradients, highlights, and warning/attention UI elements.
*   **Neutral/Glass:** Semi-transparent whites (`rgba(255, 255, 255, 0.1)` to `0.4`) and blurred backdrops for the glass effect.

**2. DESIGN SYSTEM: GLASSMORPHISM:**
*   **Background:** The main app background should be an elegant, subtle animated gradient blending the 3 brand colors seamlessly (mostly white/light gray or dark mode, with blurred color blobs in the background).
*   **Cards & Panels:** All containers (Dashboard cards, Forms, Sidebars, Modals) must use the Glassmorphism effect. 
    *   *Tailwind reference:* `bg-white/10 backdrop-blur-lg border border-white/20 shadow-xl rounded-2xl` (adjust for dark/light mode context).
*   **Typography:** Clean, modern sans-serif (e.g., Inter, Poppins, or Plus Jakarta Sans). High legibility is required.

**3. CORE FEATURES & ANIMATIONS TO IMPLEMENT:**
*   **Splash Screen / Welcome Greeting:** 
    *   Create a beautiful, smooth Splash Screen that appears when the user logs in. 
    *   It should feature the BPJS logo and a friendly, human-centric greeting (e.g., "Selamat Datang kembali, [Nama Pegawai]! Mari catat potensi hari ini.").
    *   Animate this with a soft fade-in, slight scale-up, and then slide out smoothly to reveal the dashboard.
*   **Human-Centric Micro-Interactions:**
    *   **Buttons & Inputs:** Add magnetic hover effects, subtle scaling (`scale: 0.98` on click), and glowing cyan (`#05ADDC`) focus rings on all form inputs.
    *   **Page Transitions:** Smooth fade and slide transitions when navigating between routes (e.g., from Dashboard to Data Table).
    *   **Staggered Loading:** When a data table or a list of dashboard cards loads, animate them appearing one by one with a staggered delay (fade up).

**4. EXECUTION PLAN FOR AI:**
(Assuming we are using React/Next.js with Tailwind CSS and Framer Motion. Adjust if using Vue/Svelte).
1.  First, generate the **CSS/Tailwind configuration** to include the new color palette and glassmorphism utilities.
2.  Create the **Splash Screen & Welcome Component** with Framer Motion animations.
3.  Refactor the **Main Layout (Sidebar & Topbar)** to use the frosted glass aesthetic over a gradient background.
4.  Refactor the **Form Inputs (Potensi)** to look modern, clean, with floating labels and glowing cyan focus states.

Please review my current frontend framework setup and provide the first step: the Global CSS/Tailwind config and the animated Splash Screen component.